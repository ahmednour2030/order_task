<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @author Ahmed Mohamed
 */
trait ApiResponse
{

    /**
     * @param $message
     * @param $data
     * @param $errors
     * @param  int  $status
     * @return JsonResponse
     */
    public function apiResponse(
        $message = null,
        $data = null,
        $errors = null,
        int $status = ResponseAlias::HTTP_OK,
    ): JsonResponse {
        return response()->json(compact('message', 'errors', 'data'), $status);
    }

    /**
     * This function apiResponseValidation for Validation Request
     * @param $validator
     */
    public function apiResponseValidation($validator)
    {
        $errors = $validator->errors();
        $response = $this->apiResponse('Invalid data send', null, $errors->first(),
            ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        throw new HttpResponseException($response);
    }

    /**
     * Check if the request is an API request.
     */
    private static function isApiRequest($request): bool
    {
        return $request->is('api') || $request->is('api/*') || $request->wantsJson();
    }
}

