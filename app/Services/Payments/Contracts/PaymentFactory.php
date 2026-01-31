<?php

namespace App\Services\Payments\Contracts;

interface PaymentFactory
{
    /**
     * @param string|null $name
     * @return
     */
    public function gateway(?string $name = null);
}
