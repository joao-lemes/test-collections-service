<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Domain\Enums;

enum BillingCollectionStatusEnum: string
{
    case CREATED = 'created';
    case NOTIFICATION_SENT = 'notification_sent';
}
