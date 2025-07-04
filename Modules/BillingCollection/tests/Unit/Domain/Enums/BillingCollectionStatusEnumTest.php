<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Tests\Unit\Domain\Enums;

use Modules\BillingCollection\Domain\Enums\BillingCollectionStatusEnum;
use Tests\TestCase;

class BillingCollectionStatusEnumTest extends TestCase
{
    public function testEnumCases(): void
    {
        $this->assertEquals('created', BillingCollectionStatusEnum::CREATED->value);
        $this->assertEquals('notification_sent', BillingCollectionStatusEnum::NOTIFICATION_SENT->value);
    }

    public function testFromValue(): void
    {
        $this->assertSame(BillingCollectionStatusEnum::CREATED, BillingCollectionStatusEnum::from('created'));
        $this->assertSame(BillingCollectionStatusEnum::NOTIFICATION_SENT, BillingCollectionStatusEnum::from('notification_sent'));
    }

    public function testValues(): void
    {
        $values = array_map(fn($case) => $case->value, BillingCollectionStatusEnum::cases());

        $this->assertContains('created', $values);
        $this->assertContains('notification_sent', $values);
        $this->assertCount(2, $values);
    }
}
