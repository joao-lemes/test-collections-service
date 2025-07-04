<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Tests\Unit\Domain\Enums;

use Modules\BillingCollection\Domain\Enums\BillingCollectionTypeEnum;
use Tests\TestCase;

class BillingCollectionTypeEnumTest extends TestCase
{
    public function testEnumCase(): void
    {
        $this->assertEquals('create', BillingCollectionTypeEnum::CREATE->value);
    }

    public function testFromValue(): void
    {
        $this->assertSame(BillingCollectionTypeEnum::CREATE, BillingCollectionTypeEnum::from('create'));
    }

    public function testCases(): void
    {
        $cases = BillingCollectionTypeEnum::cases();

        $this->assertCount(1, $cases);
        $this->assertSame('create', $cases[0]->value);
        $this->assertSame(BillingCollectionTypeEnum::CREATE, $cases[0]);
    }
}
