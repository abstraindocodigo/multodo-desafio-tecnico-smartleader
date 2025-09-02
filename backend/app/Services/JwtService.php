<?php

namespace App\Services;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class JwtService
{

    public function generateToken(User $user): string
    {
        return JWTAuth::fromUser($user);
    }


    public function attempt(array $credentials): ?string
    {
        return JWTAuth::attempt($credentials);
    }


    public function getAuthenticatedUser(): ?User
    {
        /** @var User|null $user */
        $user = auth()->user();
        return $user;
    }


    public function invalidateToken(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }


    public function refreshToken(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }


    public function getCurrentToken(): ?string
    {
        return JWTAuth::getToken();
    }


    public function isTokenValid(): bool
    {
        try {
            JWTAuth::parseToken()->authenticate();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public function getTokenPayload(): array
    {
        return JWTAuth::parseToken()->getPayload()->toArray();
    }
}
