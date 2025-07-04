<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Tests\Unit\Application\Jobs;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Facades\Log;
use Modules\BillingCollection\Application\Jobs\SendCollectionNotification;
use Modules\BillingCollection\Domain\Enums\BillingCollectionStatusEnum;
use Modules\BillingCollection\Domain\Enums\BillingCollectionTypeEnum;
use Modules\BillingCollection\Domain\Models\BillingCollection;
use Modules\BillingCollection\Domain\Repositories\BillingCollectionHistoryRepository;
use Modules\BillingCollection\Domain\Repositories\BillingCollectionRepository;
use Modules\User\Domain\Models\User;
use Modules\User\Domain\Repositories\UserRepository;
use Tests\TestCase;
use Mockery;

/** @internal */
class SendCollectionNotificationTest extends TestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testHandleLogsAndCreatesHistory(): void
    {
        $collectionId = $this->faker->randomNumber();
        $customerId = $this->faker->randomNumber();
        $amount = $this->faker->randomFloat(2, 10, 1000);
        $dueDate = \Carbon\Carbon::instance($this->faker->dateTimeBetween('now', '+1 month'))->setTimezone('UTC');
        $description = $this->faker->sentence;
        $email = $this->faker->email;

        $collection = new BillingCollection();
        $collection->id = $collectionId;
        $collection->customer_id = $customerId;
        $collection->amount = $amount;
        $collection->due_date = $dueDate;
        $collection->description = $description;

        $user = new User(['email' => $email]);

        $collectionRepository = Mockery::mock(BillingCollectionRepository::class);
        $collectionRepository->shouldReceive('findOrFail')
            ->once()
            ->with($collectionId)
            ->andReturn($collection);

        $userRepository = Mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('findOrFail')
            ->once()
            ->with($customerId)
            ->andReturn($user);

        $collectionHistoryRepository = Mockery::mock(BillingCollectionHistoryRepository::class);
        $collectionHistoryRepository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) use ($collection) {
                if (!isset($arg['collection_id'], $arg['type'], $arg['status'], $arg['payload'])) {
                    return false;
                }

                $payload = json_decode($arg['payload'], true);

                return $arg['collection_id'] === $collection->id
                    && $arg['type'] === BillingCollectionTypeEnum::CREATE->value
                    && $arg['status'] === BillingCollectionStatusEnum::NOTIFICATION_SENT->value
                    && is_array($payload)
                    && isset($payload['customer_id'], $payload['amount'], $payload['description'], $payload['due_date'])
                    && $payload['customer_id'] === $collection->customer_id
                    && $payload['amount'] === $collection->amount
                    && $payload['description'] === $collection->description
                    && Carbon::parse($payload['due_date'])->equalTo($collection->due_date);
            }));

        Log::shouldReceive('info')
            ->once()
            ->with(
                "Cobrança {$collectionId}: E-mail enviado com sucesso",
                Mockery::on(function ($context) use ($user, $collection) {
                    return $context['user_email'] === $user->email
                        && $context['customer_id'] === $collection->customer_id
                        && $context['amount'] === $collection->amount
                        && $context['description'] === $collection->description
                        && Carbon::parse($context['due_date'])->equalTo($collection->due_date);
                })
            );

        $job = new SendCollectionNotification($collectionId);
        $job->handle($collectionRepository, $collectionHistoryRepository, $userRepository);
    }
}
