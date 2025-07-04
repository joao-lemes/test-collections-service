<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Tests\Unit\Application\Services;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Facades\Bus;
use Mockery;
use Modules\BillingCollection\Application\Jobs\SendCollectionNotification;
use Modules\BillingCollection\Application\Services\BillingCollectionService;
use Modules\BillingCollection\Domain\Enums\BillingCollectionStatusEnum;
use Modules\BillingCollection\Domain\Enums\BillingCollectionTypeEnum;
use Modules\BillingCollection\Domain\Models\BillingCollection;
use Modules\BillingCollection\Domain\Models\BillingCollectionHistory;
use Modules\BillingCollection\Domain\Repositories\BillingCollectionHistoryRepository;
use Modules\BillingCollection\Domain\Repositories\BillingCollectionRepository;
use Modules\BillingCollection\Http\Resources\OutputBillingCollection;
use Tests\TestCase;

/** @internal */
class BillingCollectionServiceTest extends TestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        Bus::fake();
    }

    public function testStoreCreatesCollectionHistoryAndDispatchesJob(): void
    {
        $customerId  = $this->faker->randomNumber();
        $amount      = $this->faker->randomFloat(2, 10, 5000);
        $dueDate     = $this->faker->date('Y-m-d');
        $description = $this->faker->sentence;

        $collection           = new BillingCollection();
        $collection->id       = $this->faker->randomNumber();
        $collection->customer_id = $customerId;
        $collection->amount   = $amount;
        $collection->due_date = $dueDate;
        $collection->description = $description;

        $collectionRepo = Mockery::mock(BillingCollectionRepository::class);
        $collectionRepo->shouldReceive('create')
            ->once()
            ->with([
                'customer_id' => $customerId,
                'amount'      => $amount,
                'due_date'    => $dueDate,
                'description' => $description,
            ])
            ->andReturn($collection);

        $historyRepo = Mockery::mock(BillingCollectionHistoryRepository::class);
        $historyRepo->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) use ($collection, $customerId, $amount, $dueDate, $description) {
                $payload = json_decode($data['payload'], true);

                return $data['collection_id'] === $collection->id
                    && $data['type']   === BillingCollectionTypeEnum::CREATE->value
                    && $data['status'] === BillingCollectionStatusEnum::CREATED->value
                    && $payload['customer_id'] === $customerId
                    && $payload['amount']      === $amount
                    && $payload['due_date']    === $dueDate
                    && $payload['description'] === $description;
            }))
            ->andReturn(new BillingCollectionHistory());

        $service = new BillingCollectionService($collectionRepo, $historyRepo);

        $result = $service->store($customerId, $amount, $dueDate, $description);

        $this->assertInstanceOf(OutputBillingCollection::class, $result);
        $this->assertEquals($collection->id, $result->resource->id);

        Bus::assertDispatched(
            SendCollectionNotification::class,
            fn(SendCollectionNotification $job) => (function () use ($job, $collection) {
                $reflection = new \ReflectionClass($job);
                $property = $reflection->getProperty('collectionId');
                $property->setAccessible(true);
                return $property->getValue($job) === $collection->id;
            })()
        );
    }
}
