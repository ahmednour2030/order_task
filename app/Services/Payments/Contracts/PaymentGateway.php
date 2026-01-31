<?php

namespace App\Services\Payments\Contracts;

interface PaymentGateway
{
    /**
     * @param int $amount
     * @return array
     */
    public function charge(int $amount): array;
}
