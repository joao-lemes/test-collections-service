<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use Modules\User\Domain\Models\User;
use Modules\User\Domain\Repositories\UserRepository as IUserRepository;

class UserRepository implements IUserRepository
{
    public function __construct(private readonly User $model)
    {
    }

    /** @param array<string> $attributes */
    public function create(array $attributes): User
    {
        return $this->model->create($attributes);
    }

    public function list(): Collection
    {
        return $this->model->all();
    }

    public function findOrFail(int $id): User
    {
        return $this->model->findOrFail($id);
    }
}
