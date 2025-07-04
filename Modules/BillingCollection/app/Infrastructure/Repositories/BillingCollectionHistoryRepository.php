<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Infrastructure\Repositories;

use Modules\BillingCollection\Domain\Models\BillingCollectionHistory;
use Modules\BillingCollection\Domain\Repositories\BillingCollectionHistoryRepository as IBillingCollectionHistoryRepository;

class BillingCollectionHistoryRepository implements IBillingCollectionHistoryRepository
{
    public function __construct(private readonly BillingCollectionHistory $model)
    {
    }

    public function create(array $attributes): BillingCollectionHistory
    {
        return $this->model->create($attributes);
    }
}
