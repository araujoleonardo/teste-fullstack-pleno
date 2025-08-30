<?php

namespace App\Http\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfValidate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/[^0-9]/', '', $value); // remove caractere especial

        if (strlen($cpf) != 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            $fail('O campo CPF está inválido.'); // 11 dígitos não iguais
        }
        $sum = 0;
        for ($i = 1; $i <= 9; $i++) {
            $sum += (int)substr($cpf, $i - 1, 1) * (11 - $i);
        }
        $remainder = ($sum * 10) % 11;
        if ($remainder == 10 || $remainder == 11) {
            $remainder = 0;
        }
        if ($remainder != (int)substr($cpf, 9, 1)) {
            $fail('O campo CPF está inválido.'); // primeiro dígito
        }
        $sum = 0;
        for ($i = 1; $i <= 10; $i++) {
            $sum += (int)substr($cpf, $i - 1, 1) * (12 - $i);
        }
        $remainder = ($sum * 10) % 11;
        if ($remainder == 10 || $remainder == 11) {
            $remainder = 0;
        }
        if ($remainder != (int)substr($cpf, 10, 1)) {
            $fail('O campo CPF está inválido.'); // segundo dígito
        }
    }
}
