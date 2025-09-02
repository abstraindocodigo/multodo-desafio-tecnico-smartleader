<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Exception;

class PasswordResetService
{

    public function sendResetLink(string $email): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new Exception('Email não encontrado em nosso sistema', 404);
        }

        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            return [
                'message' => 'Link de recuperação de senha enviado para seu email',
                'status' => 'success'
            ];
        }

        if ($status === Password::RESET_THROTTLED) {
            throw new Exception('Muitas tentativas. Tente novamente em alguns minutos', 429);
        }

        throw new Exception('Erro ao enviar link de recuperação', 500);
    }


    public function resetPassword(array $data): array
    {
        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return [
                'message' => 'Senha redefinida com sucesso',
                'status' => 'success'
            ];
        }

        if ($status === Password::INVALID_TOKEN) {
            throw new Exception('Token inválido ou expirado', 400);
        }

        if ($status === Password::INVALID_USER) {
            throw new Exception('Usuário não encontrado', 404);
        }

        throw new Exception('Erro ao redefinir senha', 500);
    }


    public function validateResetToken(string $email, string $token): bool
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return false;
        }

        return Password::getRepository()->exists($user, $token);
    }
}
