<?php

namespace App\Services\Payments;

use App\Services\Payments\Contracts\PaymentGateway;

class PaymentRepository
{
    /**
     * @param PaymentGateway $gateway
     */
    public function __construct(
        protected PaymentGateway $gateway
    ) {}

    /**
     * @param int $amount
     * @return array
     */
    public function charge(int $amount): array
    {
        return $this->gateway->charge($amount);
    }
}
