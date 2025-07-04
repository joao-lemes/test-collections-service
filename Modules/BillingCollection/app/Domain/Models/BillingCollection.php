<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JsonSerializable;
use Modules\User\Domain\Models\User;

class BillingCollection extends Model implements JsonSerializable
{
    /** @var string $table */
    protected $table = 'collections';

    /** @var array<string> $fillable */
    protected $fillable = [
        'customer_id', 
        'amount',
        'due_date',
        'description',
    ];
    
    /** @return array<int,float,string> */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'amount' => $this->amount,
            'due_date' => $this->due_date,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(BillingCollectionHistory::class, 'collection_id');
    }
}
