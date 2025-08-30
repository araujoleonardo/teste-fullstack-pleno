<?php

namespace App\Http\Requests;

use App\Http\Rules\CpfValidate;
use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
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
            'name'          => 'required|string|min:5',
            'price'         => 'required|string|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => 'O campo nome é obrigatório.',
            'name.string'       => 'O campo nome deve ser texto.',
            'name.min'          => 'O nome deve conter pelo menos :min caracteres.',

            'price.required'    => 'O campo preco é obrigatório.',
            'price.string'      => 'O campo preco nao é valido.',
            'price.min'         => 'O campo preco não é válido.',
        ];
    }
}
