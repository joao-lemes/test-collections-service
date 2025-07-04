<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Infrastructure\Repositories;

use Modules\BillingCollection\Domain\Models\BillingCollection;
use Modules\BillingCollection\Domain\Repositories\BillingCollectionRepository as IBillingCollectionRepository;

class BillingCollectionRepository implements IBillingCollectionRepository
{
    public function __construct(private readonly BillingCollection $model)
    {
    }

    public function create(array $attributes): BillingCollection
    {
        return $this->model->create($attributes);
    }

    public function findOrFail(int $id): BillingCollection
    {
        return $this->model->findOrFail($id);
    }
}
