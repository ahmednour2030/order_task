<?php
namespace Tests\Unit\Payments;

use App\Services\Payments\Contracts\PaymentGateway;
use App\Services\Payments\PaymentManager;
use App\Services\Payments\PaymentRepository;
use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PaymentManagerTest extends TestCase
{
    private Application $app;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock Application (Laravel Container)
        $this->app = $this->createMock(Application::class);
    }

    #[Test]
    public function it_creates_stripe_gateway_repository()
    {
        $manager = new PaymentManager($this->app);

        $gateway = $this->invokeMethod($manager, 'build', [[
            'driver' => 'stripe'
        ]]);

        $this->assertInstanceOf(PaymentRepository::class, $gateway);
    }

    #[Test]
    public function it_creates_paypal_gateway_repository()
    {
        $manager = new PaymentManager($this->app);

        $gateway = $this->invokeMethod($manager, 'build', [[
            'driver' => 'paypal'
        ]]);

        $this->assertInstanceOf(PaymentRepository::class, $gateway);
    }

    #[Test]
    public function it_throws_exception_when_driver_is_missing()
    {
        $this->expectException(InvalidArgumentException::class);

        $manager = new PaymentManager($this->app);
        $this->invokeMethod($manager, 'build', [[]]);
    }

    #[Test]
    public function it_supports_custom_gateway_extension()
    {
        $manager = new PaymentManager($this->app);

        $manager->extend('fake', function () use ($manager) {
            return $manager->repository(
                new class implements PaymentGateway {
                    public function charge(int $amount): array
                    {
                        return [
                            'method' => 'fake',
                            'amount' => $amount,
                            'status' => 'successful',
                        ];
                    }
                }
            );
        });

        $gateway = $this->invokeMethod($manager, 'build', [[
            'driver' => 'fake'
        ]]);

        $result = $gateway->charge(100);

        $this->assertSame('fake', $result['method']);
        $this->assertSame(100, $result['amount']);
    }


    /**
     * Helper to call protected methods
     * @throws \ReflectionException
     */
    protected function invokeMethod(object $object, string $method, array $parameters = [])
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($method);

        return $method->invokeArgs($object, $parameters);
    }
}
