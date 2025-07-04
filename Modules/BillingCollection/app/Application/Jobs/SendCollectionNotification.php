<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\BillingCollection\Domain\Enums\BillingCollectionStatusEnum;
use Modules\BillingCollection\Domain\Enums\BillingCollectionTypeEnum;
use Modules\BillingCollection\Domain\Repositories\BillingCollectionHistoryRepository;
use Modules\BillingCollection\Domain\Repositories\BillingCollectionRepository;
use Modules\User\Domain\Repositories\UserRepository;

class SendCollectionNotification implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(private int $collectionId) {}

    public function handle(
    BillingCollectionRepository $collectionRepository,
    BillingCollectionHistoryRepository $collectionHistoryRepository,
    UserRepository $userRepository
    ): void {
        $collection = $collectionRepository->findOrFail($this->collectionId);
        $user = $userRepository->findOrFail($collection->customer_id);

        Log::info("Cobrança {$this->collectionId}: E-mail enviado com sucesso",
        [
                'user_email' => $user->email,
                'customer_id' => $collection->customer_id,
                'amount' => $collection->amount,
                'due_date' => $collection->due_date,
                'description' => $collection->description,
            ]
        );

        $collectionHistoryRepository->create([
            'collection_id' => $collection->id,
            'type' => BillingCollectionTypeEnum::CREATE->value,
            'status' => BillingCollectionStatusEnum::NOTIFICATION_SENT->value,
            'payload' => json_encode([
                'customer_id' => $collection->customer_id,
                'amount' => $collection->amount,
                'due_date' => $collection->due_date,
                'description' => $collection->description,
            ]),
        ]);
    }
}
