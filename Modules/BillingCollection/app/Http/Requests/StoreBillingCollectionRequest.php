<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Http\Requests;

use App\Http\Requests\BaseRequest;

class StoreBillingCollectionRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    /** @return array<string> */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }
}
