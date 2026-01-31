<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'error' => 'Unauthenticated.'
                ], 401);
            }

            return redirect()->guest($exception->redirectTo() ?? route('login'));
        }

        return parent::render($request, $exception);
    }
}
