<?php

namespace App\Providers;

use App\Services\CustomPayment\VodafoneGateway;
use App\Services\Payments\Facades\Payment;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Custom gateway
        Payment::extend('vodafone', function ($app, $config) {
            return Payment::repository(
                new VodafoneGateway(/* config */)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
