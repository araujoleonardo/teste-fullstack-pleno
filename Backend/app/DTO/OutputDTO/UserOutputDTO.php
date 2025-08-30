<?php

namespace App\DTO\OutputDTO;

class UserOutputDTO
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?string $email,
        public ?string $cpf,
        public ?string $createdAt,
    ) {}
}
