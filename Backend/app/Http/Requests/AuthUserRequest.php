<?php

namespace App\Http\Requests;

use App\Http\Rules\CpfValidate;
use Illuminate\Foundation\Http\FormRequest;

class AuthUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'      => 'required|string|min:10',
            'email'     => 'required|string|email|min:10|unique:users',
            'cpf'       => ['required', 'unique:users', new CpfValidate()],
            'password'  => 'required|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => 'O campo nome é obrigatório.',
            'name.string'       => 'O campo nome deve ser texto.',
            'name.min'          => 'O nome deve ser completo.',

            'email.required'    => 'O campo email é obrigatório.',
            'email.email'       => 'O campo email não é válido.',
            'email.min'         => 'O campo email não é válido.',
            'email.unique'      => 'Este email já está cadastrado.',

            'cpf.required'    => 'O campo cpf é obrigatório.',
            'cpf.unique'      => 'Este cpf já está cadastrado.',

            'password.required'     => 'O campo senha é obrigatório.',
            'password.string'       => 'O campo senha deve ser alfanumérico.',
            'password.confirmed'    => 'O campo confirmação de senha está inválido.',
            'password.min'          => 'O campo senha precisa ter no mínimo :min caracteres.',
        ];
    }
}
