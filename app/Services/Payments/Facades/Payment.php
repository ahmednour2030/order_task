<?php

namespace App\Services\Payments\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;

/**
 *  @method static string charge(int $amount)
 *  @method static string gateway(string $name = null)
 *  @method static extend(string $driver, Closure $callback)
 */
class Payment extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'payment';
    }
}
