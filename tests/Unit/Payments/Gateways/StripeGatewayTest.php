<?php

namespace Tests\Unit\Payments\Gateways;

use App\Services\Payments\Gateways\StripeGateway;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class StripeGatewayTest extends TestCase
{
    #[Test]
    public function stripe_gateway_returns_expected_response()
    {
        $gateway = new StripeGateway();

        $response = $gateway->charge(200);

        $this->assertSame('stripe', $response['method']);
        $this->assertSame(200, $response['amount']);
        $this->assertSame('successful', $response['status']);
    }
}
