<?php

namespace App\Services\Payments\Gateways;

use App\Services\Payments\Contracts\PaymentGateway;

class CashGateway implements PaymentGateway
{
    public function charge(int $amount): array
    {
        // Simulate a Cash payment processing
        return [
            'payment_id' => 'CASH-' . uniqid(),
            'amount' => $amount,
            'status' => 'successful',
            'method' => 'cash'
        ];
    }
}
