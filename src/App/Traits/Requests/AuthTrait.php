<?php

namespace App\Traits\Requests;

use App\Dtos\UserDto;

trait AuthTrait
{
    /**
     * Transform request to data transfer object.
     * @return UserDto
     */
    public function toDto(): UserDto
    {
        $userDto = new UserDto();

        $userDto->setEmail($this->getInputAsString('email'));
        $userDto->setPassword($this->getInputAsString('password'));
        $userDto->setFirstname($this->getInputAsString('firstname'));
        $userDto->setLastname($this->getInputAsString('lastname'));
        $userDto->setRemember($this->getInputAsBoolean('remember'));
        $userDto->setResetToken($this->getInputAsString('token'));

        return $userDto;
    }
}
