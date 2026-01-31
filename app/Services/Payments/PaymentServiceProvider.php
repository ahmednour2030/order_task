<?php

namespace App\Services\Payments;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('payment', function ($app) {
            return new PaymentManager($app);
        });

       $this->app->alias(PaymentManager::class, 'payment');
    }
}
