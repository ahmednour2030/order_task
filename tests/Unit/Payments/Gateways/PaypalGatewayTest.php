<?php

namespace Tests\Unit\Payments\Gateways;

use App\Services\Payments\Gateways\PaypalGateway;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PaypalGatewayTest extends TestCase
{
    #[Test]
    public function stripe_gateway_returns_expected_response()
    {
        $gateway = new PaypalGateway();

        $response = $gateway->charge(200);

        $this->assertSame('paypal', $response['method']);
        $this->assertSame(200, $response['amount']);
        $this->assertSame('successful', $response['status']);
    }
}
