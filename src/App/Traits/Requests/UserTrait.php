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
        $userDto->setRoleId($this->getInputAsInt('role_id'));
        $userDto->setPassword($this->getInputAsString('password'));
        $userDto->setCreatedBy(auth()->id());

        if (!empty($this->getInputAsString('sortBy'))) {
            $userDto->setOrderBy($this->getInputAsString('sortBy'));
        }

        if (!empty($this->getInputAsString('sortDirection'))) {
            $userDto->setOrderDirection($this->getInputAsString('sortDirection'));
        }

        if (!empty($this->getInputAsString('searchKeyword'))) {
            $userDto->setSearchKeyword($this->getInputAsString('searchKeyword'));
        }

        if (!empty($this->getInputAsInt('perPage'))) {
            $userDto->setPerPage($this->getInputAsInt('perPage'));
        }

        if (!empty($this->getInputAsArrayFromCommaSeparatedString('relations'))) {
            $userDto->setRelations($this->getInputAsArrayFromCommaSeparatedString('relations'));
        }

        if (!empty($this->getInputAsString('theme'))) {
            $userDto->setTheme($this->getInputAsString('theme'));
        }

        if (!empty($this->getInputAsBoolean('blockStatus'))) {
            $userDto->setIsBlocked($this->getInputAsBoolean('blockStatus'));
        }

        if (!empty($this->getInputAsBoolean('deleteStatus'))) {
            $userDto->setIsDeleted($this->getInputAsBoolean('deleteStatus'));
        }

        if (!empty($this->getInputAsBoolean('forceDelete'))) {
            $userDto->setForceDelete($this->getInputAsBoolean('forceDelete'));
        }

        if (!empty($this->getInputAsArray('ids'))) {
            $userDto->setSelectedItems($this->getInputAsArray('ids'));
        }

        if (!empty($this->getInputAsBoolean('archive'))) {
            $userDto->setIsArchive($this->getInputAsBoolean('archive'));
        }

        return $userDto;
    }
}
