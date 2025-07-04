<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Tests\Unit\Domain\Models;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\BillingCollection\Domain\Models\BillingCollection;
use Modules\BillingCollection\Domain\Models\BillingCollectionHistory;
use Modules\User\Domain\Models\User;
use Tests\TestCase;

class BillingCollectionTest extends TestCase
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

        $collection = new BillingCollection([
            'customer_id' => $this->faker->randomNumber(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'due_date' => $now->toDateString(),
            'description' => $this->faker->sentence,
        ]);

        $collection->id = $this->faker->randomNumber();
        $collection->created_at = $now;
        $collection->updated_at = $now;

        $serialized = $collection->jsonSerialize();

        $this->assertIsArray($serialized);
        $this->assertEquals($collection->id, $serialized['id']);
        $this->assertEquals($collection->customer_id, $serialized['customer_id']);
        $this->assertEquals($collection->amount, $serialized['amount']);
        $this->assertEquals($collection->due_date, $serialized['due_date']);
        $this->assertEquals($collection->description, $serialized['description']);
        $this->assertEquals($now->toDateTimeString(), Carbon::parse($serialized['created_at'])->toDateTimeString());
        $this->assertEquals($now->toDateTimeString(), Carbon::parse($serialized['updated_at'])->toDateTimeString());
    }

    public function testCustomerRelationship(): void
    {
        $collection = new BillingCollection();
        $this->assertInstanceOf(BelongsTo::class, $collection->customer());
        $this->assertEquals('customer_id', $collection->customer()->getForeignKeyName());
        $this->assertEquals(User::class, $collection->customer()->getRelated()::class);
    }

    public function testHistoriesRelationship(): void
    {
        $collection = new BillingCollection();
        $this->assertInstanceOf(HasMany::class, $collection->histories());
        $this->assertEquals('collection_id', $collection->histories()->getForeignKeyName());
        $this->assertEquals(BillingCollectionHistory::class, $collection->histories()->getRelated()::class);
    }
}
