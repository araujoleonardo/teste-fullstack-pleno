<?php

namespace App\DTO\FormDTO;

class ProductFormDTO
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?string $price,
        public ?string $description,
    ) {}
}
