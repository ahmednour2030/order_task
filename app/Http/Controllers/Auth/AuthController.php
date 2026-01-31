<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Register
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);

        $token = JWTAuth::fromUser($user);

        return $this->apiResponse('User registered successfully', [
            'user' => $user,
            'token' => $token
        ], null, 201);
    }

    // Login
    public function login(LoginRequest $request)
    {
        $token = $request->authenticate();

        if (! $token) {
            return $this->apiResponse(
                'خطأ في البريد الإلكتروني أو كلمة المرور',
                null,
                'Unauthorized',
                422
            );
        }

        $data = [
            'token'       => $token,
            'token_type'  => 'bearer',
            'expires_in'  => auth('api')->factory()->getTTL() * 60,
        ];

        return $this->apiResponse('Login successful', $data);
    }

    // Get Authenticated User
    public function me()
    {
        return $this->apiResponse('User retrieved successfully', auth('api')->user());
    }

    // Logout
    public function logout()
    {
        auth()->logout();
        return $this->apiResponse('Successfully logged out');
    }

    // Refresh Token
    public function refresh()
    {
        return $this->apiResponse('Token refreshed successfully', [
            'token' => auth('api')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
