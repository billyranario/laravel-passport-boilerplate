<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\ {
    UserChangePasswordRequest,
    UserRequest,
    UserUpdateRequest
};
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
     * Get current authenticated user.
     * Can send relations (optional)
     * 
     * @param UserRequest $request
     * @return JsonResponse|UserResource
     */
    public function getCurrentUser(UserRequest $request): JsonResponse|UserResource
    {
        $userDto = $request->toDto();
        $serviceResponse = $this->userService->getAuthUser($userDto->getRelations());

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::resource(UserResource::class, $serviceResponse->getData());
    }

    /**
     * Update user by ID.
     * 
     * @param UserUpdateRequest $request
     * @param int $id
     * @return JsonResponse|UserResource
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse|UserResource
    {
        $userDto = $request->toDto();
        $userDto->setId($id);
        $serviceResponse = $this->userService->update($userDto);

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::resource(UserResource::class, $serviceResponse->getData());
    }

    /**
     * Change user password.
     * @param UserChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(UserChangePasswordRequest $request): JsonResponse
    {
        $serviceResponse = $this->userService->changePassword($request->toDto());

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::json($serviceResponse->getMessage());
    }

    /**
     * Set user preferences
     * @param UserRequest $request
     * @return JsonResponse|UserResource
     */
    public function setPreferences(UserRequest $request): JsonResponse|UserResource
    {
        $userDto = $request->toDto();
        $serviceResponse = $this->userService->setPreferences($userDto);

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::resource(UserResource::class, $serviceResponse->getData());
    }

}
