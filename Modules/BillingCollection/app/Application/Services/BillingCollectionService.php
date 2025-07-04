<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Application\Services;

use Modules\BillingCollection\Application\Jobs\SendCollectionNotification;
use Modules\BillingCollection\Domain\Enums\BillingCollectionStatusEnum;
use Modules\BillingCollection\Domain\Enums\BillingCollectionTypeEnum;
use Modules\BillingCollection\Domain\Repositories\BillingCollectionHistoryRepository;
use Modules\BillingCollection\Domain\Repositories\BillingCollectionRepository;
use Modules\BillingCollection\Http\Resources\OutputBillingCollection;

class BillingCollectionService
{
    public function __construct(
        private readonly BillingCollectionRepository $billingCollectionRepository,
        private readonly BillingCollectionHistoryRepository $billingCollectionHistoryRepository,
    ) {
    }

    public function store(
        int $customerId,
        float $amount,
        string $dueDate,
        string $description
    ): OutputBillingCollection {
        $billingCollection = $this->billingCollectionRepository->create([
            'customer_id' => $customerId,
            'amount' => $amount,
            'due_date' => $dueDate,
            'description' => $description,
        ]);

        $this->billingCollectionHistoryRepository->create([
            'collection_id' => $billingCollection->id,
            'type' => BillingCollectionTypeEnum::CREATE->value,
            'status' => BillingCollectionStatusEnum::CREATED->value,
            'payload' => json_encode([
                'customer_id' => $customerId,
                'amount' => $amount,
                'due_date' => $dueDate,
                'description' => $description,
            ]),
        ]);
        SendCollectionNotification::dispatch($billingCollection->id);

        return new OutputBillingCollection($billingCollection);
    }
}
