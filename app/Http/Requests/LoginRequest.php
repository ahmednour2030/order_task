<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    use ApiResponse;

    private const MAX_ATTEMPTS = 5; // Maximum login attempts

    private const DECAY_SECONDS = 60; // Decay time in seconds

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        if (!$token = $this->guard()->attempt($this->credentials())) {
            $this->handleFailedLogin();

            return false;
        }

        $this->handleSuccessfulLogin();
        RateLimiter::clear($this->throttleKey());

        return $token;
    }

    /**
     * Handle a failed login attempt.
     */
    protected function handleFailedLogin(): void
    {
        RateLimiter::hit($this->throttleKey(), self::DECAY_SECONDS);

        // Fire failed login event
        event(new Failed($this->guardName(), null, $this->credentials()));
    }

    /**
     * Handle a successful login attempt.
     */
    protected function handleSuccessfulLogin(): void
    {
        // Fire successful login event
        event(new Login($this->guardName(), Auth::user(), false));
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        // Return an API response instead of throwing an exception
        $data = [
            'message' => trans('auth.throttle'),
            'seconds' => $seconds,
        ];

        $this->apiResponse('error', null, $data, 429)->send();
        exit; // Stop further execution
    }

    /**
     * Get the credentials for the authentication attempt.
     */
    protected function credentials(): array
    {
        return $this->only('email', 'password');
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')));
    }

    /**
     * Get the authentication guard to be used during authentication.
     */
    protected function guard(): Guard|StatefulGuard
    {
        return Auth::guard($this->guardName());
    }

    /**
     * @return string
     */
    protected function guardName(): string
    {
        return 'api';
    }

    /**
     * @param  Validator  $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        return $this->apiResponseValidation($validator);
    }
}

