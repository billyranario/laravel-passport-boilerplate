<?php

namespace App\Services;

use App\Constants\ServiceResponseMessages;
use App\Dtos\UserDto;
use App\Repositories\Eloquent\UserRepository;
use Billyranario\ProstarterKit\App\Core\ServiceResponse;

class UserService
{
    /**
     * @var UserRepository $userRepository
     */
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
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
            'password' => $userDto->getPassword(),
            'role_id' => $userDto->getRoleId()
        ];

        if ($user = $this->userRepository->create($data)) {
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
}
