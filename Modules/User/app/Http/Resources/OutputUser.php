<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutputUser extends JsonResource
{
    public function toArray($request)
    {
        return $this->resource->jsonSerialize();
    }
}
