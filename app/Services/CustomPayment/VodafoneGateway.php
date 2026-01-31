<?php

namespace App\Services\CustomPayment;

use App\Services\Payments\Contracts\PaymentGateway;

class VodafoneGateway implements PaymentGateway
{
    public function charge(int $amount): array
    {
        // Simulate a Vodafone payment processing || custom implementation payment gateway
        return [
            'payment_id' => 'VODAFONE-' . uniqid(),
            'amount' => $amount,
            'status' => 'successful',
            'method' => 'vodafone'
        ];
    }
}
