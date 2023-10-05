<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\UserCreateRequest;
use App\Http\Requests\user\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Billyranario\ProstarterKit\App\Core\ResponseHelper;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @var UserService $userService
     */
    private UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * Get paginated users.
     * @param UserRequest $request
     * @return JsonResponse|UserResource
     */
    public function index(UserRequest $request): mixed
    {
        $userDto = $request->toDto();
        $serviceResponse = $this->userService->getPaginatedUsers($userDto);

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::resource(UserResource::class, $serviceResponse->getData());
    }

    /**
     * Get user by ID.
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse|UserResource
     */
    public function show(UserRequest $request, int $id): mixed
    {
        // $userDto = $request->toDto();
        // $serviceResponse = $this->userService->getUserById($id, $userDto->getRelations());

        // if ($serviceResponse->isError()) {
        //     return ResponseHelper::error($serviceResponse->getMessage());
        // }

        // return ResponseHelper::resource(UserResource::class, $serviceResponse->getData());
    }

    /**
     * Create user.
     * @param UserCreateRequest $request
     * @return JsonResponse|UserResource
     */
    public function store(UserCreateRequest $request): JsonResponse|UserResource
    {
        $userDto = $request->toDto();
        $serviceResponse = $this->userService->create($userDto);

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::resource(UserResource::class, $serviceResponse->getData());
    }

    /**
     * Block/Unblock user by ID.
     * @param UserRequest $request
     * @return JsonResponse|UserResource
     */
    public function setBlockStatus(UserRequest $request): JsonResponse|UserResource
    {
        $userDto = $request->toDto();
        $serviceResponse = $this->userService->setBlockStatus($userDto);

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::resource(UserResource::class, $serviceResponse->getData());
    }

    /**
     * Restore users.
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function restoreUsers(UserRequest $request): JsonResponse
    {
        $userDto = $request->toDto();
        $serviceResponse = $this->userService->bulkRestore($userDto);

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::json($serviceResponse->getMessage());
    }

    /**
     * Bulk Delete users.
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function deleteUsers(UserRequest $request): JsonResponse
    {
        $userDto = $request->toDto();
        $serviceResponse = $this->userService->bulkDelete($userDto);

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::json($serviceResponse->getMessage());
    }
}
