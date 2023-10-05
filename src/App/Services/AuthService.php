<?php

namespace App\Services;

use App\Constants\ActivityLogConstant;
use App\Constants\ServiceResponseMessages;
use App\Dtos\UserDto;
use App\Repositories\Eloquent\{
    ActivityLogRepository,
    AuthRepository,
    UserRepository
};
use Billyranario\ProstarterKit\App\Core\ServiceResponse;
use Billyranario\ProstarterKit\App\Helpers\LoggerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthService
{
    /**
     * @var UserRepository $userRepository
     * @var AuthRepository $authRepository
     * @var ActivityLogRepository $activityLogRepository
     */
    private UserRepository $userRepository;
    private AuthRepository $authRepository;
    private ActivityLogRepository $activityLogRepository; 

    /**
     * @param UserRepository $userRepository
     * @param AuthRepository $authRepository
     * @param ActivityLogRepository $activityLogRepository
     */
    public function __construct(
        UserRepository $userRepository,
        AuthRepository $authRepository,
        ActivityLogRepository $activityLogRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->authRepository = $authRepository;
        $this->activityLogRepository = $activityLogRepository;
    }

    /**
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function register(UserDto $userDto): ServiceResponse
    {
        $data = [
            'firstname' => $userDto->getFirstname(),
            'lastname' => $userDto->getLastname(),
            'email' => $userDto->getEmail(),
            'password' => Hash::make($userDto->getPassword()),
            'role_id' => $userDto->getRoleId()
        ];

        if ($user = $this->userRepository->create($data)) {
            $this->activityLogRepository->create([
                'author_id' => null,
                'type' => ActivityLogConstant::REGISTRATION,
                'activity' => ActivityLogConstant::LOG_REGISTER($user)
            ]);
            return ServiceResponse::success(ServiceResponseMessages::REGISTER_SUCCESS, $user);
        }

        return ServiceResponse::error(ServiceResponseMessages::REGISTER_ERROR);
    }

    /**
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function login(UserDto $userDto): ServiceResponse
    {
        $credentials = [
            'email' => $userDto->getEmail(),
            'password' => $userDto->getPassword()
        ];

        if ($user = $this->userRepository->findByEmail($userDto->getEmail())) {
            if (!is_null($user->blocked_at)) {
                return ServiceResponse::error(ServiceResponseMessages::BLOCKED_ACCOUNT, [
                    'errors' => ['email' => [ServiceResponseMessages::BLOCKED_ACCOUNT]]
                ]);
            }
        }

        if ($token = $this->authRepository->authenticate($credentials, $userDto->getRemember())) {
            $this->activityLogRepository->create([
                'author_id' => null,
                'type' => ActivityLogConstant::LOGIN,
                'activity' => ActivityLogConstant::LOG_SIGNIN($user)
            ]);
            return ServiceResponse::success(
                ServiceResponseMessages::LOGIN_SUCCESS,
                ['token' => $token]
            );
        }

        return ServiceResponse::error(ServiceResponseMessages::INVALID_CREDENTIALS, [
            'errors' => ['password' => [ServiceResponseMessages::INVALID_CREDENTIALS]]
        ]);
    }

    /**
     * @param Request $request
     * @return ServiceResponse
     */
    public function refreshToken(Request $request): ServiceResponse
    {
        if ($token = $this->authRepository->refreshToken($request)) {
            return ServiceResponse::success(
                ServiceResponseMessages::REFRESH_TOKEN_SUCCESS,
                ['token' => $token]
            );
        }

        return ServiceResponse::error(ServiceResponseMessages::REFRESH_TOKEN_ERROR);
    }

    /**
     * @return ServiceResponse
     */
    public function authenticatedUser(): ServiceResponse
    {
        if ($user = $this->userRepository->findById(auth()->id(), ['preference'])) {
            return ServiceResponse::success(ServiceResponseMessages::SUCCESS, $user);
        }

        return ServiceResponse::error(ServiceResponseMessages::ERROR);
    }

    /**
     * @param bool $logoutFromAllDevices
     * @return ServiceResponse
     */
    public function logout(bool $logoutFromAllDevices = false): ServiceResponse
    {
        if ($this->authRepository->logout($logoutFromAllDevices)) {
            return ServiceResponse::success(ServiceResponseMessages::LOGOUT_SUCCESS, true);
        }

        return ServiceResponse::error(ServiceResponseMessages::LOGOUT_ERROR);
    }

    /**
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function forgotPassword(UserDto $userDto): ServiceResponse
    {
        if ($status = $this->authRepository->sendResetLinkViaEmail(['email' => $userDto->getEmail()])) {
            return $status === Password::RESET_LINK_SENT
                ? ServiceResponse::success(ServiceResponseMessages::PASSWORD_RESET_LINK_SUCCESS($userDto->getEmail()), ['status' => $status])
                : ServiceResponse::error(ServiceResponseMessages::PASSWORD_RESET_LINK_ERROR($userDto->getEmail()), ['status' => $status]);
        }

        return ServiceResponse::error(ServiceResponseMessages::ERROR);
    }

    /**
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function resetPassword(UserDto $userDto): ServiceResponse
    {
        $data = [
            'email' => $userDto->getEmail(),
            'password' => $userDto->getPassword(),
            'token' => $userDto->getResetToken()
        ];

        if ($status = $this->authRepository->resetPassword($data)) {
            LoggerHelper::logInfo('Password reset successfully.', ['status' => $status]);
            return $status === Password::PASSWORD_RESET
                ? ServiceResponse::success(ServiceResponseMessages::PASSWORD_RESET_SUCCESS($userDto->getEmail()), ['status' => $status])
                : ServiceResponse::error(ServiceResponseMessages::PASSWORD_RESET_ERROR($userDto->getEmail()), ['status' => $status]);
        }

        return ServiceResponse::error(ServiceResponseMessages::ERROR);
    }
}
