<?php

namespace App\Services\Payments\Gateways;

use App\Services\Payments\Contracts\PaymentGateway;

class PaypalGateway implements PaymentGateway
{
    public function charge(int $amount): array
    {
        // Simulate a PayPal payment processing
        return [
            'payment_id' => 'PP-' . uniqid(),
            'amount' => $amount,
            'status' => 'successful',
            'method' => 'paypal'
        ];
    }
}
