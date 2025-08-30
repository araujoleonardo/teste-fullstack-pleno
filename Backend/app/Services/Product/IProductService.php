<?php

namespace App\Services\Product;

use App\DTO\FormDTO\ProductFormDTO;
use App\DTO\PaginationFilter\BaseFilterDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface IProductService
{
    public function getAll(BaseFilterDTO $dto, int $id): LengthAwarePaginator;

    public function store(ProductFormDTO $dto, int $id): bool;

    public function update(ProductFormDTO $dto): bool;

    public function delete(string $id): bool;
}
