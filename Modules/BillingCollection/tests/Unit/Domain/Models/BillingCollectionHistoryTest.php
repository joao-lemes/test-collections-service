<?php

namespace Modules\BillingCollection\Tests\Unit\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\BillingCollection\Domain\Models\BillingCollection;
use Modules\BillingCollection\Domain\Models\BillingCollectionHistory;
use Tests\TestCase;
use Faker\Factory;
use Faker\Generator;

class BillingCollectionHistoryTest extends TestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testJsonSerialize(): void
    {
        $now = Carbon::now();

        $history = new BillingCollectionHistory([
            'collection_id' => $this->faker->randomNumber(),
            'type' => $this->faker->word(),
            'status' => $this->faker->word(),
            'payload' => json_encode([
                'customer_id' => $this->faker->randomNumber(),
                'amount' => $this->faker->randomFloat(2, 10, 1000),
            ]),
        ]);

        $history->id = $this->faker->randomNumber();
        $history->created_at = $now;
        $history->updated_at = $now;

        $serialized = $history->jsonSerialize();

        $this->assertIsArray($serialized);
        $this->assertEquals($history->id, $serialized['id']);
        $this->assertEquals($history->collection_id, $serialized['collection_id']);
        $this->assertEquals($history->type, $serialized['type']);
        $this->assertEquals($history->status, $serialized['status']);
        $this->assertEquals($history->payload, $serialized['payload']);
        $this->assertEquals($now->toDateTimeString(), Carbon::parse($serialized['created_at'])->toDateTimeString());
        $this->assertEquals($now->toDateTimeString(), Carbon::parse($serialized['updated_at'])->toDateTimeString());
    }

    public function testCollectionRelationship(): void
    {
        $history = new BillingCollectionHistory();
        $relation = $history->collection();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('collection_id', $relation->getForeignKeyName());
        $this->assertEquals(BillingCollection::class, $relation->getRelated()::class);
    }
}
