<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\ {
    AuthRequest,
    ForgotPasswordRequest,
    LoginRequest,
    RegisterRequest,
    ResetPasswordRequest
};
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Billyranario\ProstarterKit\App\Core\ResponseHelper;
use Billyranario\ProstarterKit\App\Http\Requests\BaseRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @var AuthService $authService
     */
    private AuthService $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct(
        AuthService $authService
    ) {
        $this->authService = $authService;
    }

    /**
     * Login user.
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $serviceResponse = $this->authService->login($request->toDto());

        if ($serviceResponse->isError()) {
            return ResponseHelper::json($serviceResponse->getData(), Response::HTTP_BAD_REQUEST);
        }

        return ResponseHelper::json($serviceResponse->getData());
    }

    /**
     * Register user.
     * @param RegisterRequest $request
     * @return JsonResponse|UserResource
     */
    public function register(RegisterRequest $request): JsonResponse|UserResource
    {
        $serviceResponse = $this->authService->register($request->toDto());

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::resource(UserResource::class, $serviceResponse->getData());
    }

    /**
     * Refresh token.
     * @param BaseRequest $request
     * @return JsonResponse
     */
    public function refresh(BaseRequest $request): JsonResponse
    {
        $serviceResponse = $this->authService->refreshToken($request);

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::json($serviceResponse->getData());
    }

    /**
     * Get authenticated user.
     * @return JsonResponse|UserResource
     */
    public function authenticatedUser(): JsonResponse|UserResource
    {
        $serviceResponse = $this->authService->authenticatedUser();

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::resource(UserResource::class, $serviceResponse->getData());
    }

    /**
     * Logout user.
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $serviceResponse = $this->authService->logout();

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::json($serviceResponse->getMessage());
    }

    /**
     * Forgot password.
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $serviceResponse = $this->authService->forgotPassword($request->toDto());

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::json($serviceResponse->getMessage());
    }

    /**
     * Reset password.
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $serviceResponse = $this->authService->resetPassword($request->toDto());

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::json($serviceResponse->getMessage());
    }
}
