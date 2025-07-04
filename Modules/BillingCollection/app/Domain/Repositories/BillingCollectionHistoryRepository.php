<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Domain\Repositories;

use Modules\BillingCollection\Domain\Models\BillingCollectionHistory;

interface BillingCollectionHistoryRepository
{
    public function create(array $attributes): BillingCollectionHistory;
}
