<?php

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    public function getCompanies()
    {
        return Company::select('id', 'name', 'identifier', 'email')
            ->orderBy('name')
            ->get();
    }
}
