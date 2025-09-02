<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateFirstUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {--company= : Identificador da empresa existente (opcional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criar usuário (nova empresa ou empresa existente)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $companyIdentifier = $this->option('company');
        $company = null;

        if ($companyIdentifier) {
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

            $this->info("=== Adicionando Usuário à Empresa: {$company->name} ===");
        } else {
            $this->info('=== Criação de Nova Empresa e Usuário ===');

            if (Company::count() > 0) {
                $this->warn('Já existem empresas cadastradas no sistema.');
                if (!$this->confirm('Deseja criar uma nova empresa mesmo assim?')) {
                    return 0;
                }
            }

            $this->info('Dados da Empresa:');
            $companyName = $this->ask('Nome da empresa');
            $companyIdentifier = $this->ask('Identificador da empresa (único)', Str::slug($companyName));
            $companyEmail = $this->ask('Email da empresa (opcional)');
            $companyPhone = $this->ask('Telefone da empresa (opcional)');
            $companyAddress = $this->ask('Endereço da empresa (opcional)');
        }

        $this->newLine();
        $this->info('Dados do Usuário:');
        $userName = $this->ask('Nome do usuário');
        $userEmail = $this->ask('Email do usuário');
        $userPassword = $this->secret('Senha do usuário (mínimo 6 caracteres)');
        $userPasswordConfirmation = $this->secret('Confirmar senha');

        if ($userPassword !== $userPasswordConfirmation) {
            $this->error('As senhas não coincidem!');
            return 1;
        }

        if (strlen($userPassword) < 6) {
            $this->error('A senha deve ter pelo menos 6 caracteres!');
            return 1;
        }

        $validationData = [
            'user_name' => $userName,
            'user_email' => $userEmail,
            'user_password' => $userPassword,
        ];

        $validationRules = [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_password' => 'required|string|min:6',
        ];

        if (!$company) {
            $validationData['company_name'] = $companyName;
            $validationData['company_identifier'] = $companyIdentifier;
            $validationRules['company_name'] = 'required|string|max:255';
            $validationRules['company_identifier'] = 'required|string|max:255|unique:companies,identifier';
        }

        $validator = Validator::make($validationData, $validationRules);

        if ($validator->fails()) {
            $this->error('Dados inválidos:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('- ' . $error);
            }
            return 1;
        }

        try {
            if (!$company) {
                $this->info('Criando empresa...');
                $company = Company::create([
                    'name' => $companyName,
                    'identifier' => $companyIdentifier,
                    'email' => $companyEmail ?: null,
                    'phone' => $companyPhone ?: null,
                    'address' => $companyAddress ?: null,
                ]);

                $this->info("✅ Empresa '{$company->name}' criada com sucesso!");
            } else {
                $this->info("✅ Usando empresa existente: '{$company->name}'");
            }

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
                ['Tipo', 'ID', 'Nome', 'Email'],
                [
                    ['Empresa', $company->id, $company->name, $company->email ?: 'N/A'],
                    ['Usuário', $user->id, $user->name, $user->email],
                ]
            );

            $this->newLine();
            if (!$companyIdentifier) {
                $this->info('🎉 Nova empresa e usuário criados com sucesso!');
            } else {
                $this->info('🎉 Usuário adicionado à empresa com sucesso!');
            }
            $this->info('O usuário deve verificar o email antes de fazer login no sistema.');
        } catch (\Exception $e) {
            $this->error('Erro ao criar empresa/usuário: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
