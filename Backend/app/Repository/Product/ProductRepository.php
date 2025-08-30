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
        $query = Product::where('user_id', '=', $id)->query();

        if ($dto->search) {
            $query->where('name', 'LIKE', '%' . $dto->search . '%')
                ->orWhere('price', 'LIKE', '%' . $dto->search . '%');
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
            $product = new Product();
            $product->name = Str::upper($dto->name);
            $product->price = $dto->price;
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
            $product = Product::findOrFail($dto->id);
            $product->name = Str::upper($dto->name);
            $product->price = $dto->price;
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
