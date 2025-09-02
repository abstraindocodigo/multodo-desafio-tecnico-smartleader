<?php

namespace App\Http\Requests\Export;

use Illuminate\Foundation\Http\FormRequest;

class ExportTasksRequest extends FormRequest
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
            'format' => 'nullable|in:csv,xlsx',
        ];
    }


    public function messages()
    {
        return [
            'status.in' => 'O status deve ser: pendente, em_andamento ou concluida.',
            'priority.in' => 'A prioridade deve ser: baixa, media ou alta.',
            'user_id.exists' => 'O usuário selecionado não existe.',
            'format.in' => 'O formato deve ser: csv ou xlsx.',
        ];
    }


    public function attributes()
    {
        return [
            'status' => 'status',
            'priority' => 'prioridade',
            'user_id' => 'usuário',
            'format' => 'formato',
        ];
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'format' => $this->format ?? 'csv',
        ]);
    }
}
