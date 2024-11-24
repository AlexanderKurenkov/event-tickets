<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ApprovalService;
use App\Services\BookingService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected BookingService $bookingService;
    protected ApprovalService $approvalService;

    public function __construct(BookingService $bookingService, ApprovalService $approvalService)
    {
        $this->bookingService = $bookingService;
        $this->approvalService = $approvalService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // проверка правильности входящих данных
        $validatedData = $request->validate([
            'event_id' => 'required|integer',
            'event_date' => 'required|string|max:10',
            'ticket_adult_price' => 'required|integer',
            'ticket_adult_quantity' => 'required|integer',
            'ticket_kid_price' => 'required|integer',
            'ticket_kid_quantity' => 'required|integer',
        ]);

        // рассчитать общую цену
        $equalPrice = $validatedData['ticket_adult_price'] * $validatedData['ticket_adult_quantity'] + $validatedData['ticket_kid_price'] * $validatedData['ticket_kid_quantity'];

        $maxAttempts = 10;
        $attempts = 0;

        while ($attempts < $maxAttempts) {
            $attempts++;

            // сгенерировать уникальный штрих-код
            $barcode = generateBarcode();

            $orderData = array_merge($validatedData, [
                'barcode' => $barcode,
                'equal_price' => $equalPrice,
                'created' => now(),
            ]);

            // создать экземпляр модели Order без сохранения
            try {
                $order = new Order($orderData);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            // запрос к стороннему API-сервису для бронирования заказа
            $bookingResponse = $this->bookingService->bookOrder($order);

            if ($bookingResponse->status() >= 200 && $bookingResponse->status() < 300) {
                // запрос к стороннему API-сервису для подтверждения заказа
                $approvalResponse = $this->approvalService->approveOrder($barcode);

                if ($approvalResponse->status() >= 200 && $approvalResponse->status() < 300) {
                    // в случае успеха сохраняем заказ в БД
                    $order->save();

                    // ответ при успешном запросе
                    logger()->info($approvalResponse['message']);
                    return response()->json(['message' => $approvalResponse['message'], 'order' => $order], 201);
                } else {
                    // ответ в случае ошибки
                    logger()->error('Error approving order: ' . $approvalResponse['error']);
                    return response()->json(['error' => $approvalResponse['error']], $approvalResponse->status());
                }

            } else {
                logger()->error('Error approving order: need to regenerate barcode');
            }
        }

        // ответ, когда превышено допустимое число попыток для подтверждения заказа
        logger()->error('Error approving order: ' . $approvalResponse['error']);
        return response()->json(['error' => "Booking failed after {$maxAttempts} attempts"], 409);
    }
}
