<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

// служба для мок-тестирования HTTP-запросов
class HttpFakerService
{
    public static function setup()
    {
        if (app()->environment('local')) {
            Http::fake([
                'https://api.site.com/book' => fn() => randomBool()
                    ? Http::response(['message' => 'Order successfully booked'], 201)
                    : Http::response(['error' => 'Barcode already exists'], 409),

                'https://api.site.com/approve' => fn() => randomBool()
                    ? Http::response(['message' => 'Order successfully approved'], 200)
                    : Http::response(['error' => self::getRandomErrorMessage()], 500),
            ]);
        }
    }

    private static function getRandomErrorMessage(): string
    {
        $errorMessages = [
            'Event cancelled',
            'No tickets',
            'No seats',
            'Fan removed',
        ];

        return $errorMessages[array_rand($errorMessages)];
    }
}
