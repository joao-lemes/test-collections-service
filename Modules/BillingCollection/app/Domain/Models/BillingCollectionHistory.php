<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JsonSerializable;

class BillingCollectionHistory extends Model implements JsonSerializable
{
    /** @var string $table */
    protected $table = 'collection_histories';

    /** @var array<string> $fillable */
    protected $fillable = [
        'collection_id', 
        'type',
        'status',
        'payload',
    ];

    /** @return array<int,string> */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'collection_id' => $this->collection_id,
            'type' => $this->type,
            'status' => $this->status,
            'payload' => $this->payload,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(BillingCollection::class, 'collection_id');
    }
}
