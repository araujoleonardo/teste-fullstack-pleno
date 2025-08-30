<?php

namespace App\DTO\FormDTO;

class UserFormDTO
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?string $email,
        public ?string $cpf,
    ) {}
}
