<?php

namespace App\Services;

// сервис подтверждения заказа
use Illuminate\Support\Facades\Http;

class ApprovalService
{
    private string $apiUrl = 'https://api.site.com/approve';

    private $errorMessages = [
        'Event cancelled',
        'No tickets',
        'No seats',
        'Fan removed'
    ];

    public function approveOrder(string $barcode)
    {
        try {
            return Http::post($this->apiUrl, [$barcode]);
        } catch (\Exception $e) {
            logger()->error('Failed to approve the order', ['error' => $e->getMessage()]);

            return Http::response(['error' => 'Internal server error'], 500);
        }
    }

}
