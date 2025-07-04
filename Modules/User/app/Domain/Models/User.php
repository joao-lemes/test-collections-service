<?php

declare(strict_types=1);

namespace Modules\User\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JsonSerializable;
use Modules\BillingCollection\Domain\Models\BillingCollection;

class User extends Model implements JsonSerializable
{    
    /** @var array<string> $fillable */
    protected $fillable = [
        'name', 
        'email',
        'inscription',
    ];
    
    /** @return array<int,string> */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'inscription' => $this->inscription,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function billingCollections(): HasMany
    {
        return $this->hasMany(BillingCollection::class, 'customer_id');
    }
}
