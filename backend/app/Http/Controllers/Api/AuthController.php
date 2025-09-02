<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Company;
use App\Services\AuthService;
use App\Services\CompanyService;
use Exception;

class AuthController extends Controller
{
    protected $authService;
    protected $companyService;

    public function __construct(AuthService $authService, CompanyService $companyService)
    {
        $this->authService = $authService;
        $this->companyService = $companyService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->register($request->validated());

            return response()->json([
                'message' => $result['message'],
                'user' => $result['user'],
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->login($request->only('email', 'password'));

            return response()->json([
                'message' => 'Login realizado com sucesso',
                'user' => $result['user'],
                'token' => $result['token'],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function logout()
    {
        try {
            $this->authService->logout();

            return response()->json(['message' => 'Logout realizado com sucesso']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function me()
    {
        try {
            $user = $this->authService->getAuthenticatedUser();

            return response()->json([
                'user' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function refresh()
    {
        try {
            $token = $this->authService->refreshToken();

            return response()->json([
                'token' => $token,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function getCompanies()
    {
        try {
            $companies = $this->companyService->getCompanies();

            return response()->json([
                'companies' => $companies,
            ]);
        } catch (Exception $e) {
            $statusCode = $e->getCode();
            if ($statusCode < 100 || $statusCode > 599) {
                $statusCode = 500;
            }
            return response()->json([
                'error' => $e->getMessage()
            ], $statusCode);
        }
    }
}
