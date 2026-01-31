<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Services\Payments\PaymentServiceProvider::class,  // order is important
    App\Providers\PaymentServiceProvider::class,
];
