<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OutputUserCollection extends ResourceCollection
{
    public $collects = OutputUser::class;
}
