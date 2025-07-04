<?php

declare(strict_types=1);

namespace Modules\User\Application\Services;

use Modules\User\Domain\Repositories\UserRepository;
use Modules\User\Http\Resources\OutputUser;
use Modules\User\Http\Resources\OutputUserCollection;
use Modules\User\Http\Resources\OutputUserWithCollectionValue;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function store(
        string $name,
        string $email,
        string $inscription
    ): OutputUser {
        $user = $this->userRepository->create([
            'name' => $name,
            'email' => $email,
            'inscription' => $inscription,
        ]);

        return new OutputUser($user);
    }

    public function list(): OutputUserCollection
    {
        $users = $this->userRepository->list();

        return new OutputUserCollection($users);
    }

    public function getWithCollectionValue(
        int $id,
        string $date
    ): OutputUserWithCollectionValue {
        $user = $this->userRepository->findOrFail($id);

        [$year, $month] = explode('-', $date);
        $user->collectionValue = $user->billingCollections()
            ->whereMonth('due_date', $month)
            ->whereYear('due_date', $year)
            ->sum('amount');

        return new OutputUserWithCollectionValue($user);
    }
}
