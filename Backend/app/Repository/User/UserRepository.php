<?php

namespace App\Repository\User;

use App\DTO\FormDTO\UserFormDTO;
use App\DTO\PaginationFilter\BaseFilterDTO;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserRepository implements IUserRepository
{
    public function getUsers():Collection
    {
        return User::get();
    }

    public function getAll(BaseFilterDTO $dto): LengthAwarePaginator
    {
        $query = User::with('products');

        if ($dto->search) {
            $query->where('name', 'LIKE', '%' . $dto->search . '%')
                ->orWhere('email', 'LIKE', '%' . $dto->search . '%')
                ->orWhere('cpf', 'LIKE', '%' . $dto->search . '%');
        }

        if ($dto->field && $dto->direction) {
            $query->orderBy($dto->field, $dto->direction);
        }

        return $query->paginate($dto->perPage);
    }

    public function findById(string $id): User
    {
        return User::where('id', $id)->with('products')->first();
    }

    public function store(UserFormDTO $dto): bool
    {
        try {
            $user = new User();
            $user->name = Str::upper($dto->name);
            $user->email = $dto->email;
            $user->cpf = $dto->cpf;
            $user->password = Hash::make('password');
            $user->save();

            return true;
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return false;
        }
    }

    public function update(UserFormDTO $dto): bool
    {
        try {
            $user = User::findOrFail($dto->id);
            $user->name = Str::upper($dto->name);
            $user->email = $dto->email;
            $user->cpf = $dto->cpf;
            $user->password = Hash::make('password');
            $user->save();

            return true;
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return false;
        }
    }

    public function delete(string $id): bool
    {
        try{
            $user = User::with('products')->findOrFail($id);
            $user->products()->delete();
            return $user->delete();
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return false;
        }
    }

}
