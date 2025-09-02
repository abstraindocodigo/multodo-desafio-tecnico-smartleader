<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AddUserToCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add-to-company {company_identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adicionar um novo usuário a uma empresa existente';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $companyIdentifier = $this->argument('company_identifier');

        $company = Company::where('identifier', $companyIdentifier)->first();

        if (!$company) {
            $this->error("Empresa com identificador '{$companyIdentifier}' não encontrada.");
            $this->info('Empresas disponíveis:');
            $companies = Company::all(['identifier', 'name']);
            foreach ($companies as $comp) {
                $this->line("- {$comp->identifier}: {$comp->name}");
            }
            return 1;
        }

        $this->info("Adicionando usuário à empresa: {$company->name} ({$company->identifier})");
        $this->newLine();

        $userName = $this->ask('Nome do usuário');
        $userEmail = $this->ask('Email do usuário');
        $userPassword = $this->secret('Senha do usuário (mínimo 6 caracteres)');
        $userPasswordConfirmation = $this->secret('Confirmar senha');

        if ($userPassword !== $userPasswordConfirmation) {
            $this->error('As senhas não coincidem.');
            return 1;
        }

        $validator = Validator::make([
            'name' => $userName,
            'email' => $userEmail,
            'password' => $userPassword,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            $this->error('Dados inválidos:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('- ' . $error);
            }
            return 1;
        }

        try {
            $this->info('Criando usuário...');
            $user = User::create([
                'name' => $userName,
                'email' => $userEmail,
                'password' => Hash::make($userPassword),
                'company_id' => $company->id,
                'email_verified_at' => null,
            ]);

            $this->info('Enviando email de verificação...');
            $user->notify(new VerifyEmailNotification());

            $this->info("✅ Usuário '{$user->name}' criado com sucesso!");
            $this->info("📧 Email de verificação enviado para: {$user->email}");
            $this->newLine();
            $this->info('=== Resumo ===');
            $this->table(
                ['Tipo', 'ID', 'Nome', 'Email', 'Empresa'],
                [
                    ['Usuário', $user->id, $user->name, $user->email, $company->name],
                ]
            );

            $this->newLine();
            $this->info('🎉 Usuário adicionado à empresa com sucesso!');
            $this->info('O usuário deve verificar o email antes de fazer login no sistema.');
        } catch (\Exception $e) {
            $this->error('Erro ao criar usuário: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
