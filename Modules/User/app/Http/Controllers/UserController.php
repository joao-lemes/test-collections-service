<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\User\Application\Services\UserService;
use Modules\User\Http\Requests\GetUserWithCollectionValueRequest;
use Modules\User\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function storeAction(StoreUserRequest $request): JsonResponse
    {
        $output = $this->userService->store(
            $request->get('name'),
            $request->get('email'),
            $request->get('inscription')
        );

        return $output->response()->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    public function list(): JsonResponse
    {
        $output = $this->userService->list();

        return $output->response();
    }

    public function getWithCollectionValue(GetUserWithCollectionValueRequest $request): JsonResponse
    {
        $output = $this->userService->getWithCollectionValue(
            (int) $request->route('id'),
            $request->input('date')
        );

        return $output->response();
    }
}
