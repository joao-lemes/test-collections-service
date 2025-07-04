<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Application\Services;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\Application\Services\UserService;
use Modules\User\Domain\Repositories\UserRepository;
use Modules\User\Http\Resources\OutputUser;
use Modules\User\Http\Resources\OutputUserCollection;
use Modules\User\Http\Resources\OutputUserWithCollectionValue;
use Tests\TestCase;
use Mockery;
use Faker\Factory;
use Faker\Generator;
use Modules\User\Domain\Models\User;

/** @internal */
class UserServiceTest extends TestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testStoreCreatesNewUser(): void
    {
        $repository = Mockery::mock(UserRepository::class);
        $service = new UserService($repository);

        $name = $this->faker->name;
        $email = $this->faker->email;
        $inscription = $this->faker->numerify('######');

        $fakeUser = new User();
        $fakeUser->forceFill([
            'id' => 1,
            'name' => $name,
            'email' => $email,
            'inscription' => $inscription,
        ]);

        $repository->shouldReceive('create')
            ->once()
            ->with([
                'name' => $name,
                'email' => $email,
                'inscription' => $inscription,
            ])
            ->andReturn($fakeUser);

        $result = $service->store($name, $email, $inscription);

        $this->assertInstanceOf(OutputUser::class, $result);
        $this->assertEquals($name, $result->resource->name);
        $this->assertEquals($email, $result->resource->email);
    }

    public function testListReturnsUserCollection(): void
    {
        $repository = Mockery::mock(UserRepository::class);
        $service = new UserService($repository);

        $fakeUsers = collect([
            new class($this->faker) {
                public $id;
                public $name;
                public function __construct($faker)
                {
                    $this->id = $faker->randomNumber();
                    $this->name = $faker->name;
                }
            },
            new class($this->faker) {
                public $id;
                public $name;
                public function __construct($faker)
                {
                    $this->id = $faker->randomNumber();
                    $this->name = $faker->name;
                }
            },
        ]);

        $repository->shouldReceive('list')
            ->once()
            ->andReturn($fakeUsers);

        $result = $service->list();

        $this->assertInstanceOf(OutputUserCollection::class, $result);
        $this->assertCount(2, $result->collection);
    }

    public function testGetWithCollectionValueCalculatesSum(): void
    {
        $repository = Mockery::mock(UserRepository::class);
        $service = new UserService($repository);

        $id = $this->faker->randomNumber();
        $year = $this->faker->year;
        $month = str_pad((string) $this->faker->numberBetween(1, 12), 2, '0', STR_PAD_LEFT);
        $date = "{$year}-{$month}";
        $expectedSum = $this->faker->randomFloat(2, 100, 10000);

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = $id;

        $hasManyMock = Mockery::mock(HasMany::class);
        $hasManyMock->shouldReceive('whereMonth')
            ->with('due_date', $month)
            ->andReturnSelf();
        $hasManyMock->shouldReceive('whereYear')
            ->with('due_date', $year)
            ->andReturnSelf();
        $hasManyMock->shouldReceive('sum')
            ->with('amount')
            ->andReturn($expectedSum);

        $user->shouldReceive('billingCollections')
            ->once()
            ->andReturn($hasManyMock);

        $repository->shouldReceive('findOrFail')
            ->once()
            ->with($id)
            ->andReturn($user);

        $result = $service->getWithCollectionValue($id, $date);

        $this->assertInstanceOf(OutputUserWithCollectionValue::class, $result);
        $this->assertEquals($expectedSum, $result->resource->collectionValue);
    }
}
