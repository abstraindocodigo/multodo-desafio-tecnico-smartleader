<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pendente,em_andamento,concluida',
            'priority' => 'in:baixa,media,alta',
            'due_date' => 'nullable|date',
        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'status.in' => 'O status deve ser: pendente, em_andamento ou concluida.',
            'priority.in' => 'A prioridade deve ser: baixa, media ou alta.',
            'due_date.date' => 'A data de vencimento deve ser uma data válida.',
        ];
    }


    public function attributes()
    {
        return [
            'title' => 'título',
            'description' => 'descrição',
            'status' => 'status',
            'priority' => 'prioridade',
            'due_date' => 'data de vencimento',
        ];
    }
}
