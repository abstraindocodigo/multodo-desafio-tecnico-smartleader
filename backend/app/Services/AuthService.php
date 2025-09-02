<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class AuthService
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $company = null;

            if ($data['registration_type'] === 'new_company') {
                $company = Company::create([
                    'name' => $data['company_name'],
                    'identifier' => $data['company_identifier'],
                    'email' => $data['company_email'] ?? null,
                    'phone' => $data['company_phone'] ?? null,
                    'address' => $data['company_address'] ?? null,
                ]);
            } elseif ($data['registration_type'] === 'existing_company') {
                $company = Company::where('identifier', $data['existing_company_identifier'])->first();

                if (!$company) {
                    throw new Exception('Empresa não encontrada.', 404);
                }
            } else {
                throw new Exception('Tipo de registro inválido.', 400);
            }

            // Check if this is the first user in the company
            $isFirstUser = $company->users()->count() === 0;

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'company_id' => $company->id,
                'is_company_admin' => $isFirstUser,
            ]);

            $user->sendEmailVerificationNotification();

            $message = $data['registration_type'] === 'new_company'
                ? 'Usuário e empresa criados com sucesso. Verifique seu email para confirmar a conta.'
                : 'Usuário criado com sucesso e associado à empresa. Verifique seu email para confirmar a conta.';

            return [
                'user' => $user->load('company'),
                'message' => $message,
            ];
        });
    }


    public function login(array $credentials): array
    {
        $token = $this->jwtService->attempt($credentials);

        if (!$token) {
            throw new Exception('Credenciais inválidas', 401);
        }

        $user = $this->jwtService->getAuthenticatedUser();

        if (!$user->hasVerifiedEmail()) {
            throw new Exception('Por favor, verifique seu email antes de fazer login. Verifique sua caixa de entrada e spam.', 403);
        }

        return [
            'user' => $user->load('company'),
            'token' => $token,
        ];
    }


    public function logout(): void
    {
        $this->jwtService->invalidateToken();
    }


    public function getAuthenticatedUser(): User
    {
        $user = $this->jwtService->getAuthenticatedUser();
        return $user->load('company');
    }


    public function refreshToken(): string
    {
        return $this->jwtService->refreshToken();
    }
}
