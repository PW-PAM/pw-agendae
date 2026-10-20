<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'txNome'  => ['required', 'string', 'min:3', 'max:255'],
            'txEmail' => ['required', 'email', 'max:255', 'unique:users,email'],
            'txSenha' => ['required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'txNome.required'  => 'O campo nome é obrigatório.',
            'txNome.min'       => 'O nome deve ter no mínimo :min caracteres.',
            'txNome.max'       => 'O nome deve ter no máximo :max caracteres.',

            'txEmail.required' => 'O campo e-mail é obrigatório.',
            'txEmail.email'    => 'Digite um e-mail válido.',
            'txEmail.unique'   => 'Este e-mail já está cadastrado.',

            'txSenha.required' => 'O campo senha é obrigatório.',
            'txSenha.min'      => 'A senha deve ter no mínimo :min caracteres.',
        ];
    }
}