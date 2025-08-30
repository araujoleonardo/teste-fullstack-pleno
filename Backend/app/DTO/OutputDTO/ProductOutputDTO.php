<?php

namespace App\DTO\OutputDTO;

class ProductOutputDTO
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?string $price,
        public ?string $description,
    ) {}
}
