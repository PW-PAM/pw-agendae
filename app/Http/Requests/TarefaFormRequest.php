<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TarefaFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'txNome'    => ['required', 'string', 'min:3', 'max:255'],
            'txDesc'    => ['required', 'string', 'max:200'],
            'txProjeto' => ['required', 'exists:projeto,id'],
            'txData'    => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'txNome.required'    => 'O campo título é obrigatório.',
            'txNome.min'         => 'O título deve ter no mínimo :min caracteres.',

            'txDesc.required'    => 'O campo descrição é obrigatório.',
            'txDesc.max'         => 'A descrição deve ter no máximo :max caracteres.',

            'txProjeto.required' => 'Selecione um planejamento.',
            'txProjeto.exists'   => 'O planejamento selecionado não existe.',

            'txData.required'    => 'O campo prazo é obrigatório.',
            'txData.date'        => 'Informe uma data válida.',
            'txData.after_or_equal' => 'O prazo não pode ser uma data que já passou.',
        ];
    }
}