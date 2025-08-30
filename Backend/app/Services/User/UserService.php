<?php

namespace App\Services\User;

use App\DTO\FormDTO\UserFormDTO;
use App\DTO\OutputDTO\UserOutputDTO;
use App\DTO\PaginationFilter\BaseFilterDTO;
use App\Models\User;
use App\Repository\User\IUserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserService implements IUserService
{
    protected IUserRepository $repository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function getUsers(): Collection
    {
        return $this->repository->getUsers();
    }

    public function getAll(BaseFilterDTO $dto): LengthAwarePaginator
    {
        $paginator = $this->repository->getAll($dto);

        $paginator->getCollection()->transform(function (User $user) {
            return new UserOutputDTO(
                id: $user->id,
                name: $user->name,
                email: $user->email,
                cpf: $user->cpf,
                createdAt: $user->created_at->format('d/m/Y'),
            );
        });

        return $paginator;
    }

    public function findById(string $id): UserOutputDTO
    {
        $userData = $this->repository->findById($id);

        return new UserOutputDTO(
            id: $userData->id,
            name: $userData->name,
            email: $userData->email,
            cpf: $userData->cpf,
            createdAt: $userData->created_at->format('d/m/Y'),
        );
    }

    public function store(UserFormDTO $dto): bool
    {
        return $this->repository->store($dto);
    }

    public function update(UserFormDTO $dto): bool
    {
        return $this->repository->update($dto);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }
}
