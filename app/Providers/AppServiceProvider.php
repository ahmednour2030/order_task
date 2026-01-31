<?php

namespace App\Providers;

use App\Traits\ApiResponse;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use ApiResponse;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Override redirect for all auth middleware
        Authenticate::redirectUsing(function ($request) {
            if ($this->isApiRequest($request)) {
                return null; // null يمنع redirect و AuthenticationException ترجع JSON
            }
        });
    }
}
