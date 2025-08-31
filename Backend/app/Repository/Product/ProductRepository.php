<?php

namespace App\Repository\Product;

use App\DTO\FormDTO\ProductFormDTO;
use App\DTO\PaginationFilter\BaseFilterDTO;
use App\Models\Product;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductRepository implements IProductRepository
{
    public function getAll(BaseFilterDTO $dto, int $id): LengthAwarePaginator
    {
        $query = Product::where('user_id', '=', $id);

        if ($dto->search) {
            $query->where('name', 'ILIKE', '%' . $dto->search . '%')
                ->orWhere('price', 'ILIKE', '%' . $dto->search . '%');
        }

        if ($dto->field && $dto->direction) {
            $query->orderBy($dto->field, $dto->direction);
        }

        return $query->paginate($dto->perPage);
    }

    public function store(ProductFormDTO $dto, int $id): bool
    {
        if(!User::find($id)) {
            Log::error('Usuario não encontrado!');
           return false;
        }

        try {
            $price = preg_replace('/[^0-9]/', '', $dto->price);
            $product = new Product();
            $product->name = Str::upper($dto->name);
            $product->price = $price;
            $product->description = $dto->description;
            $product->user_id = $id;
            $product->save();

            return true;
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return false;
        }
    }

    public function update(ProductFormDTO $dto): bool
    {
        try {
            $price = preg_replace('/[^0-9]/', '', $dto->price);
            $product = Product::findOrFail($dto->id);
            $product->name = Str::upper($dto->name);
            $product->price = $price;
            $product->description = $dto->description;
            $product->save();

            return true;
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return false;
        }
    }

    public function delete(string $id): bool
    {
        try{
            return Product::findOrFail($id)->delete();
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return false;
        }
    }

}
