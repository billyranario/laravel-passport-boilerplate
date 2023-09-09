<?php

namespace App\Services;

use App\Constants\ServiceResponseMessages;
use App\Core\ServiceResponse;
use App\Repositories\Eloquent\UserRepository;

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
     * @return ServiceResponse
     */
    public function getAuthUser(): ServiceResponse
    {
        if ($user = $this->userRepository->findById(auth()->id())) {
            return ServiceResponse::success('', $user);
        }

        return ServiceResponse::error(ServiceResponseMessages::UNAUTHENTICATED);
    }
}