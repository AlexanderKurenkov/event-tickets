<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

// сервис бронирования заказа
class BookingService
{
    private string $apiUrl = 'https://api.site.com/approve';

    public function bookOrder(Order $order)
    {
        // использовать только необходимые поля экземпляра модели
        $data = $order->only([
            'event_id',
            'event_date',
            'ticket_adult_price',
            'ticket_adult_quantity',
            'ticket_kid_price',
            'ticket_kid_quantity',
            'barcode',
        ]);

        try {
            return Http::post($this->apiUrl, $data);
        } catch (\Exception $e) {
            logger()->error('Failed to book the order', ['error' => $e->getMessage()]);

            return Http::response(['error' => 'Internal server error'], 500);
        }
    }
}
