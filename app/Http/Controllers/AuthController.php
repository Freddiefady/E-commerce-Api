<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\InvalidTokenException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use Throwable;

final class AuthController extends BaseController
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());

        /** @var JWTGuard $guard */
        $guard = auth('api');
        $token = $guard->login($user);

        return parent::createdResponse(
            data: [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => (int) $guard->factory()->getTTL() * 60,
            ],
            message: 'User registered successfully'
        );
    }

    /**
     * @throws InvalidTokenException
     * @throws Throwable
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        /** @var JWTGuard $guard */
        $guard = auth('api');
        $token = $guard->attempt($credentials);

        throw_unless($token, InvalidTokenException::class);

        return parent::successResponse(
            data: [
                'user' => new UserResource($guard->user()),
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => (int) $guard->factory()->getTTL() * 60,
            ],
            message: 'Login successful'
        );
    }

    public function logout(): JsonResponse
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');
        $guard->logout();

        return parent::successResponse(
            message: 'Successfully logged out'
        );
    }

    public function me(): JsonResponse
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        return parent::successResponse(
            data: ['user' => new UserResource($guard->user())],
            message: 'User retrieved successfully'
        );
    }

    public function refresh(): JsonResponse
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        return parent::successResponse(
            data: [
                'token' => $guard->refresh(),
                'token_type' => 'bearer',
                'expires_in' => (int) $guard->factory()->getTTL() * 60,
            ],
            message: 'Token refreshed successfully'
        );
    }
}
