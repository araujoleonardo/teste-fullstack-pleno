<?php

namespace App\DTO\PaginationFilter;

use Illuminate\Http\Request;

class BaseFilterDTO
{
    public function __construct(
        public ?string $search = null,
        public ?string $field = null,
        public ?string $direction = 'asc',
        public ?int $perPage = 10
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->input('search'),
            field: $request->input('field'),
            direction: $request->input('direction'),
            perPage: $request->input('perPage'),
        );
    }
}
