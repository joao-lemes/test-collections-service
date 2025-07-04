<?php

declare(strict_types=1);

namespace Modules\BillingCollection\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\BillingCollection\Application\Services\BillingCollectionService;
use Modules\BillingCollection\Http\Requests\StoreBillingCollectionRequest;

class BillingCollectionController extends Controller
{
    public function __construct(private readonly BillingCollectionService $billingCollectionService)
    {
    }

    public function storeAction(StoreBillingCollectionRequest $request): JsonResponse
    {
        $output = $this->billingCollectionService->store(
            $request->get('customer_id'),
            $request->get('amount'),
            $request->get('due_date'),
            $request->get('description')
        );

        return $output->response()->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
