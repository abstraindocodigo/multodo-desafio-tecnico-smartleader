<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Company;
use Illuminate\Console\Command;

class UpdateCompanyAdmins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:update-admins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza usuários existentes marcando o primeiro usuário de cada empresa como admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Atualizando administradores das empresas...');

        $companies = Company::with('users')->get();

        foreach ($companies as $company) {
            $users = $company->users()->orderBy('created_at')->get();

            if ($users->count() > 0) {
                $firstUser = $users->first();

                if (!$firstUser->is_company_admin) {
                    $firstUser->update(['is_company_admin' => true]);
                    $this->line("✓ {$firstUser->name} marcado como admin da empresa {$company->name}");
                } else {
                    $this->line("- {$firstUser->name} já é admin da empresa {$company->name}");
                }
            }
        }

        $this->info('Atualização concluída!');
    }
}
