<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutputBillingCollection extends JsonResource
{
    public function toArray($request)
    {
        return $this->resource->jsonSerialize();
    }
}
