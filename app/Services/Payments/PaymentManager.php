<?php

namespace App\Services\Payments;

use App\Services\Payments\Contracts\PaymentFactory;
use App\Services\Payments\Gateways\PaypalGateway;
use App\Services\Payments\Gateways\StripeGateway;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;

class PaymentManager implements PaymentFactory
{
    /**
     * @var array
     */
    protected array $gateways = [];

    /**
     * @var array
     */
    protected array $customCreators = [];

    /**
     * @param Application $app
     */
    public function __construct(
        protected Application $app
    ){}

    /**
     * @param string|null $name
     * @return mixed
     */
    public function gateway(string $name = null)
    {
        $name ??= config('payment.default');

        return $this->gateways[$name]
            ??= $this->resolve($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function resolve(string $name)
    {
        $config = config("payment.gateways.$name");

        if (! is_array($config)) {
            throw new \InvalidArgumentException("Gateway [$name] not configured.");
        }

        return $this->build($config);
    }

    /**
     * @param array $config
     * @return mixed
     */
    protected function build(array $config)
    {
        if (! isset($config['driver'])) {
            throw new InvalidArgumentException('Payment driver not defined.');
        }

        $driver = $config['driver'];

        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($config);
        }

        $method = 'create' . ucfirst($driver) . 'Gateway';

        if (method_exists($this, $method)) {
            return $this->{$method}($config);
        }

        throw new InvalidArgumentException("Gateway [$driver] not supported.");
    }

    /**
     * // ===== Custom Gateway Registration =====
     *
     * @param string $driver
     * @param Closure $callback
     * @return $this
     */
    public function extend(string $driver, Closure $callback): static
    {
        $this->customCreators[$driver] = $callback->bindTo($this, $this);

        return $this;
    }

    /**
     * @param array $config
     * @return mixed
     */
    protected function callCustomCreator(array $config)
    {
        return $this->customCreators[$config['driver']]($this->app, $config);
    }

    // ===== PaymentFactory Methods =====

    /**
     * @param array $config
     * @return PaymentRepository
     */
    protected function createStripeGateway(array $config)
    {
        return $this->repository(new StripeGateway(/* config */));
    }

    /**
     * @param array $config
     * @return PaymentRepository
     */
    protected function createPaypalGateway(array $config)
    {
        return $this->repository(new PaypalGateway(/* config */));
    }

    /**
     * Create a new repository instance.
     */
    public function repository($gateway): PaymentRepository
    {
        return new PaymentRepository($gateway);
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->gateway()->$method(...$arguments);
    }
}
