<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Domain\Models;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\Domain\Models\User;
use Modules\BillingCollection\Domain\Models\BillingCollection;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected Generator $faker;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testFillableFields(): void
    {
        $user = new User();

        $this->assertEquals(
            ['name', 'email', 'inscription'],
            $user->getFillable()
        );
    }

    public function testBillingCollectionsRelationship(): void
    {
        $user = new User();

        $relation = $user->billingCollections();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('customer_id', $relation->getForeignKeyName());
        $this->assertEquals(BillingCollection::class, $relation->getRelated()::class);
    }

    public function testJsonSerialize(): void
    {
        $now = Carbon::now();

        $user = new User([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'inscription' => $this->faker->numerify('######'),
        ]);

        $user->id = $this->faker->randomNumber();
        $user->created_at = $now;
        $user->updated_at = $now;

        $serialized = $user->jsonSerialize();

        $this->assertIsArray($serialized);
        $this->assertEquals($user->id, $serialized['id']);
        $this->assertEquals($user->name, $serialized['name']);
        $this->assertEquals($user->email, $serialized['email']);
        $this->assertEquals($user->inscription, $serialized['inscription']);
        $this->assertEquals($now->toDateTimeString(), Carbon::parse($serialized['created_at'])->toDateTimeString());
        $this->assertEquals($now->toDateTimeString(), Carbon::parse($serialized['updated_at'])->toDateTimeString());
    }
}
