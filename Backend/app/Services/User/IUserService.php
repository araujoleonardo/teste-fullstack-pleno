<?php

namespace App\Services\User;

use App\DTO\FormDTO\UserFormDTO;
use App\DTO\OutputDTO\UserOutputDTO;
use App\DTO\PaginationFilter\BaseFilterDTO;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IUserService
{
    public function getUsers(): Collection;

    public function getAll(BaseFilterDTO $dto): LengthAwarePaginator;

    public function findById(string $id): UserOutputDTO;

    public function store(UserFormDTO $dto): bool;

    public function update(UserFormDTO $dto): bool;

    public function delete(string $id): bool;
}
