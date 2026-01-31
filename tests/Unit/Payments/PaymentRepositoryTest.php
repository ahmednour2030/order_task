<?php

namespace Tests\Unit\Payments;

use App\Services\Payments\PaymentRepository;
use App\Services\Payments\Contracts\PaymentGateway;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PaymentRepositoryTest extends TestCase
{
    #[Test]
    public function it_calls_gateway_charge_method()
    {
        $gateway = $this->createMock(PaymentGateway::class);

        $gateway->expects($this->once())
            ->method('charge')
            ->with(500)
            ->willReturn([
                'status' => 'successful'
            ]);

        $repository = new PaymentRepository($gateway);

        $result = $repository->charge(500);

        $this->assertEquals('successful', $result['status']);
    }
}
