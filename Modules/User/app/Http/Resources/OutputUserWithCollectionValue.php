<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutputUserWithCollectionValue extends JsonResource
{
    public function toArray($request)
    {
        return array_merge($this->resource->jsonSerialize(), [
            'collection_value' => $this->resource->collectionValue,
        ]);
    }
}
