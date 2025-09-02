<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Criando dados de teste...');

        // Criar 2 empresas (usando firstOrCreate para evitar duplicatas)
        $companies = [];
        for ($i = 1; $i <= 2; $i++) {
            $companies[] = Company::firstOrCreate(
                ['identifier' => "TEST{$i}"],
                [
                    'name' => "Empresa Teste {$i}",
                    'identifier' => "TEST{$i}",
                    'email' => "contato@empresa{$i}.com",
                    'phone' => "(11) 9999-000{$i}",
                    'address' => "Rua Teste {$i}, 123 - São Paulo/SP",
                ]
            );
        }

        $this->command->info('Empresas criadas: ' . count($companies));

        // Criar 3 usuários para cada empresa (usando firstOrCreate para evitar duplicatas)
        $users = [];
        foreach ($companies as $companyIndex => $company) {
            for ($j = 1; $j <= 3; $j++) {
                $userNumber = ($companyIndex * 3) + $j;
                $companyNumber = $companyIndex + 1;
                $users[] = User::firstOrCreate(
                    ['email' => "usuario{$userNumber}@empresa{$companyNumber}.com"],
                    [
                        'name' => "Usuário {$userNumber}",
                        'email' => "usuario{$userNumber}@empresa{$companyNumber}.com",
                        'email_verified_at' => now(),
                        'password' => Hash::make('password'),
                        'company_id' => $company->id,
                    ]
                );
            }
        }

        $this->command->info('Usuários criados: ' . count($users));

        // Criar 5 tarefas para cada usuário (usando firstOrCreate para evitar duplicatas)
        $taskCount = 0;
        foreach ($users as $userIndex => $user) {
            $statuses = ['pendente', 'em_andamento', 'concluida'];
            $priorities = ['baixa', 'media', 'alta'];

            for ($k = 1; $k <= 5; $k++) {
                $taskNumber = ($userIndex * 5) + $k;
                $taskTitle = "Tarefa {$taskNumber} - {$user->name}";

                Task::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'title' => $taskTitle,
                    ],
                    [
                        'company_id' => $user->company_id,
                        'user_id' => $user->id,
                        'title' => $taskTitle,
                        'description' => "Descrição da tarefa {$taskNumber} do usuário {$user->name} da empresa {$user->company->name}.",
                        'status' => $statuses[array_rand($statuses)],
                        'priority' => $priorities[array_rand($priorities)],
                        'due_date' => now()->addDays(rand(1, 30)),
                    ]
                );

                $taskCount++;
            }
        }

        $this->command->info('Tarefas criadas: ' . $taskCount);
        $this->command->info('Dados de teste criados com sucesso!');

        // Mostrar resumo
        $this->command->info('=== RESUMO DOS DADOS DE TESTE ===');
        $this->command->info('Empresas: ' . Company::count());
        $this->command->info('Usuários: ' . User::count());
        $this->command->info('Tarefas: ' . Task::count());

        $this->command->info('=== CREDENCIAIS DE TESTE ===');
        foreach ($users as $user) {
            $this->command->info("Email: {$user->email} | Senha: password | Empresa: {$user->company->name}");
        }
    }
}
