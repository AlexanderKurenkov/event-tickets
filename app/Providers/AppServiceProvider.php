<?php

namespace App\Providers;

use App\Services\HttpFakerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // подключение службы для мок-тестирования HTTP-запросов
        HttpFakerService::setup();
    }
}
