<?php

namespace App\Services\Payments\Gateways;

use App\Services\Payments\Contracts\PaymentGateway;

class StripeGateway implements PaymentGateway
{
    public function charge(int $amount): array
    {
        // Simulate a Stripe payment processing
        return [
            'payment_id' => 'STRIPE-' . uniqid(),
            'amount' => $amount,
            'status' => 'successful',
            'method' => 'stripe'
        ];
    }
}
