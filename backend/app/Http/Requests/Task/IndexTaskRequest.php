<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class IndexTaskRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'status' => 'nullable|in:pendente,em_andamento,concluida',
            'priority' => 'nullable|in:baixa,media,alta',
            'user_id' => 'nullable|integer|exists:users,id',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }


    public function messages()
    {
        return [
            'status.in' => 'O status deve ser: pendente, em_andamento ou concluida.',
            'priority.in' => 'A prioridade deve ser: baixa, media ou alta.',
            'user_id.exists' => 'O usuário selecionado não existe.',
            'page.min' => 'A página deve ser maior que 0.',
            'per_page.min' => 'O número de itens por página deve ser maior que 0.',
            'per_page.max' => 'O número de itens por página não pode ser maior que 100.',
        ];
    }


    public function attributes()
    {
        return [
            'status' => 'status',
            'priority' => 'prioridade',
            'user_id' => 'usuário',
            'page' => 'página',
            'per_page' => 'itens por página',
        ];
    }
}
