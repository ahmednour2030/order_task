<?php

namespace App\Traits;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * @author Ahmed Mohamed
 */
trait ApiExceptionHandler
{
    use ApiResponse;

    // Using another trait inside this trait

    /**
     * Register API-specific exception handlers.
     *
     * @param  Exceptions  $exceptions
     * @return void
     */
    public function registerApiExceptionHandlers(Exceptions $exceptions): void
    {
        if (!$this->isApiRequest(request())) {
            return;
        }

        $handlers = [
            ValidationException::class => [422, 'Validation error', fn($e) => ['errors' => $e->errors()]],
            NotFoundHttpException::class => [404, 'Resource not found'],
            ModelNotFoundException::class => [404, 'Model not found'],
            AuthenticationException::class => [401, 'Unauthorized'],
            HttpResponseException::class => fn($e) => [
                $e->getResponse()->getStatusCode(), 'Http Response Exception',
                config('app.debug') ? ['exception' => $e->getMessage()] : []
            ],
            QueryException::class => fn($e) => [
                500, 'Database query error',
                ['exception' => config('app.debug') ? ['exception' => $e->getMessage()] : []]
            ],
            MethodNotAllowedHttpException::class => [405, 'Method not allowed'],
        ];

        foreach ($handlers as $exceptionClass => $handler) {
            $exceptions->render(function (Throwable $exception) use ($exceptionClass, $handler) {
                if (!$exception instanceof $exceptionClass) {
                    return null;
                }

                [$status, $message, $extra] = is_callable($handler)
                    ? $handler($exception)
                    : array_pad((array) $handler, 3, []);

                return $this->apiResponse($message, $extra ?? [], null, $status);
            });
        }
    }
}
