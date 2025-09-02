<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'registration_type' => 'required|in:new_company,existing_company',

            // Campos obrigatórios apenas para nova empresa
            'company_name' => 'required_if:registration_type,new_company|string|max:255',
            'company_identifier' => 'required_if:registration_type,new_company|string|max:255|unique:companies,identifier',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string|max:500',

            // Campo obrigatório apenas para empresa existente
            'existing_company_identifier' => 'required_if:registration_type,existing_company|string|max:255|exists:companies,identifier',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.unique' => 'Este email já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'registration_type.required' => 'O tipo de registro é obrigatório.',
            'registration_type.in' => 'O tipo de registro deve ser "new_company" ou "existing_company".',

            // Mensagens para nova empresa
            'company_name.required_if' => 'O nome da empresa é obrigatório ao criar uma nova empresa.',
            'company_identifier.required_if' => 'O identificador da empresa é obrigatório ao criar uma nova empresa.',
            'company_identifier.unique' => 'Este identificador de empresa já está em uso.',
            'company_email.email' => 'O email da empresa deve ter um formato válido.',
            'company_phone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'company_address.max' => 'O endereço não pode ter mais de 500 caracteres.',

            // Mensagens para empresa existente
            'existing_company_identifier.required_if' => 'O identificador da empresa é obrigatório ao se associar a uma empresa existente.',
            'existing_company_identifier.exists' => 'A empresa com este identificador não foi encontrada.',
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'nome',
            'email' => 'email',
            'password' => 'senha',
            'registration_type' => 'tipo de registro',
            'company_name' => 'nome da empresa',
            'company_identifier' => 'identificador da empresa',
            'company_email' => 'email da empresa',
            'company_phone' => 'telefone da empresa',
            'company_address' => 'endereço da empresa',
            'existing_company_identifier' => 'identificador da empresa existente',
        ];
    }
}
