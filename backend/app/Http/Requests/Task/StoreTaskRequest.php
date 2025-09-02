<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pendente,em_andamento,concluida',
            'priority' => 'in:baixa,media,alta',
            'due_date' => 'nullable|date|after:now',
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
            'due_date.after' => 'A data de vencimento deve ser posterior à data atual.',
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


    protected function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ?? 'pendente',
            'priority' => $this->priority ?? 'media',
        ]);
    }
}
