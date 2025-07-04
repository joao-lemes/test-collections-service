<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Domain\Repositories;

use Modules\BillingCollection\Domain\Models\BillingCollection;

interface BillingCollectionRepository
{
    public function create(array $attributes): BillingCollection;
    public function findOrFail(int $id): BillingCollection;
}
