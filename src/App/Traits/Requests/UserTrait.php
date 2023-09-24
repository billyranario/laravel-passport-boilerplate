<?php

namespace App\Traits\Requests;

use App\Dtos\UserDto;

trait UserTrait
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Transform request to data transfer object.
     * @return UserDto
     */
    public function toDto(): UserDto
    {
        $userDto = new UserDto();

        $userDto->setFirstname($this->getInputAsString('firstname'));
        $userDto->setLastname($this->getInputAsString('lastname'));
        $userDto->setEmail($this->getInputAsString('email'));

        if (!empty($this->getInputAsArrayFromCommaSeparatedString('relations'))) {
            $userDto->setRelations($this->getInputAsArrayFromCommaSeparatedString('relations'));
        }
        if (!empty($this->getInputAsString('theme'))) {
            $userDto->setTheme($this->getInputAsString('theme'));
        }

        return $userDto;
    }
}
