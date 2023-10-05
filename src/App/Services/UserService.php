<?php

namespace App\Services;

use App\Constants\ActivityLogConstant;
use App\Constants\RoleConstant;
use App\Constants\ServiceResponseMessages;
use App\Dtos\UserDto;
use App\Repositories\Eloquent\ActivityLogRepository;
use App\Repositories\Eloquent\UserRepository;
use Billyranario\ProstarterKit\App\Core\ServiceResponse;
use Billyranario\ProstarterKit\App\Helpers\LoggerHelper;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @var UserRepository $userRepository
     * @var ActivityLogRepository $activityLogRepository
     */
    private UserRepository $userRepository;
    private ActivityLogRepository $activityLogRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
        ActivityLogRepository $activityLogRepository
    ) {
        $this->userRepository = $userRepository;
        $this->activityLogRepository = $activityLogRepository;
    }

    /**
     * Get paginated users.
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function getPaginatedUsers(UserDto $userDto): ServiceResponse
    {
        if ($users = $this->userRepository->paginate($userDto)) {
            return ServiceResponse::success(ServiceResponseMessages::USER_LIST_SUCCESS, $users);
        }
        return ServiceResponse::error(ServiceResponseMessages::USER_LIST_ERROR);
    }

    /**
     * Get authenticated user.
     * @param array $relations
     * @return ServiceResponse
     */
    public function getAuthUser($relations = []): ServiceResponse
    {
        if ($user = $this->userRepository->findById(auth()->id(), $relations)) {
            return ServiceResponse::success('', $user);
        }

        return ServiceResponse::error(ServiceResponseMessages::UNAUTHENTICATED);
    }

    /**
     * Create User.
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function create(UserDto $userDto): ServiceResponse
    {
        $data = [
            'firstname' => $userDto->getFirstname(),
            'lastname' => $userDto->getLastname(),
            'email' => $userDto->getEmail(),
            'password' => Hash::make($userDto->getPassword()),
            'role_id' => $userDto->getRoleId(),
        ];

        if ($user = $this->userRepository->create($data)) {
            $this->activityLogRepository->create([
                'author_id' => auth()->id(),
                'type' => ActivityLogConstant::ACCOUNT_CREATE,
                'activity' => ActivityLogConstant::LOG_ACCOUNT_CREATE($user)
            ]);
            LoggerHelper::logInfo('User', [
                'author_id' => auth()->id() || null,
                'type' => ActivityLogConstant::LOGIN,
                'activity' => ActivityLogConstant::LOG_SIGNIN($user)
            ]);
            return ServiceResponse::success(ServiceResponseMessages::USER_CREATED, $user);
        }

        return ServiceResponse::error(ServiceResponseMessages::CREATE_FAILED);
    }

    /**
     * Update user by ID.
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function update(UserDto $userDto): ServiceResponse
    {
        $data = [
            'firstname' => $userDto->getFirstname(),
            'lastname' => $userDto->getLastname(),
            'email' => $userDto->getEmail(),
        ];

        if (auth()->user()->role_id === RoleConstant::ADMIN) {
            $data['role_id'] = $userDto->getRoleId();
            $data['password'] = Hash::make($userDto->getPassword());
        }

        if ($user = $this->userRepository->update($data, $userDto->getId())) {
            return ServiceResponse::success(ServiceResponseMessages::USER_UPDATED, $user);
        }

        return ServiceResponse::error(ServiceResponseMessages::USER_NOT_FOUND);
    }

    /**
     * Change user password.
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function changePassword(UserDto $userDto): ServiceResponse
    {
        if ($user = $this->userRepository->changePassword($userDto->getNewPassword(), $userDto->getId())) {
            return ServiceResponse::success(ServiceResponseMessages::USER_PASSWORD_CHANGED, $user);
        }

        return ServiceResponse::error(ServiceResponseMessages::ERROR);
    }

    /**
     * Set user preferences
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function setPreferences(UserDto $userDto): ServiceResponse
    {
        $data = [
            'theme' => $userDto->getTheme()
        ];

        if ($user = $this->userRepository->setPreferences($data, auth()->id())) {
            return ServiceResponse::success(ServiceResponseMessages::SUCCESS, $user);
        }

        return ServiceResponse::error(ServiceResponseMessages::USER_NOT_FOUND);
    }

    /**
     * Block/Unblock user account.
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function setBlockStatus(UserDto $userDto): ServiceResponse
    {
        $data = [
            'blocked_at' => $userDto->getIsBlocked() ? now() : null
        ];

        if ($user = $this->userRepository->bulkUpdate($data, $userDto->getSelectedItems())) {
            $this->activityLogRepository->create([
                'author_id' => auth()->id(),
                'type' => ActivityLogConstant::ACCOUNT_BLOCK,
                'activity' => ActivityLogConstant::LOG_ACCOUNT_BLOCK(count($userDto->getSelectedItems()), $userDto->getIsBlocked() ? 'blocked' : 'unblocked')
            ]);
            return ServiceResponse::success(ServiceResponseMessages::USER_BLOCKED, $user);
        }

        return ServiceResponse::error(ServiceResponseMessages::ERROR);
    }

    /**
     * Delete user acount
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function bulkDelete(UserDto $userDto): ServiceResponse
    {
        if ($this->userRepository->bulkDelete($userDto->getSelectedItems(), $userDto->getForceDelete())) {
            $this->activityLogRepository->create([
                'author_id' => auth()->id(),
                'type' => ActivityLogConstant::ACCOUNT_BLOCK,
                'activity' => ActivityLogConstant::LOG_ACCOUNT_BLOCK(count($userDto->getSelectedItems()), $userDto->getForceDelete() ? 'deleted permanently' : 'archived')
            ]);
            return ServiceResponse::success(ServiceResponseMessages::USER_DELETED);
        }

        return ServiceResponse::error(ServiceResponseMessages::USER_NOT_FOUND);
    }

    /**
     * Restore user account
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function restore(UserDto $userDto): ServiceResponse
    {
        if ($this->userRepository->restore($userDto->getId())) {
            return ServiceResponse::success(ServiceResponseMessages::USER_RESTORED);
        }

        return ServiceResponse::error(ServiceResponseMessages::USER_NOT_FOUND);
    }

    /**
     * Restore users.
     * @param UserDto $userDto
     * @return ServiceResponse
     */
    public function bulkRestore(UserDto $userDto): ServiceResponse
    {
        if ($this->userRepository->bulkRestore($userDto->getSelectedItems())) {
            $this->activityLogRepository->create([
                'author_id' => auth()->id(),
                'type' => ActivityLogConstant::ACCOUNT_BLOCK,
                'activity' => ActivityLogConstant::LOG_ACCOUNT_RESTORE(count($userDto->getSelectedItems()))
            ]);
            return ServiceResponse::success(ServiceResponseMessages::USER_RESTORED);
        }

        return ServiceResponse::error(ServiceResponseMessages::USER_NOT_FOUND);
    }
}
