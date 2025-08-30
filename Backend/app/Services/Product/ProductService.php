<?php

namespace App\Services\Product;

use App\DTO\FormDTO\ProductFormDTO;
use App\DTO\OutputDTO\ProductOutputDTO;
use App\DTO\PaginationFilter\BaseFilterDTO;
use App\Models\Product;
use App\Repository\Product\IProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService implements IProductService
{
    protected IProductRepository $repository;

    public function __construct(IProductRepository $productRepository)
    {
        $this->repository = $productRepository;
    }

    public function getAll(BaseFilterDTO $dto, int $id): LengthAwarePaginator
    {
        $paginator = $this->repository->getAll($dto, $id);

        $paginator->getCollection()->transform(function (Product $product) {
            return new ProductOutputDTO(
                id: $product->id,
                name: $product->name,
                price: $product->price,
                description: $product->description,
            );
        });

        return $paginator;
    }

    public function store(ProductFormDTO $dto, int $id): bool
    {
        return $this->repository->store($dto, $id);
    }

    public function update(ProductFormDTO $dto): bool
    {
        return $this->repository->update($dto);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }
}
