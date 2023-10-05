<?php

namespace App\Http\Requests\user;

use App\Dtos\UserDto;
use App\Traits\Requests\UserTrait;
use Billyranario\ProstarterKit\App\Http\Requests\BaseRequest;

class UserChangePasswordRequest extends BaseRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'current_password'],
            'new_password' => [
                'required',
                'min:8',
                'max:16',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/'
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'password.required' => 'Password is required.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least :min characters long.',
            'new_password.max' => 'New password must be less than :max characters long.',
            'new_password.regex' => 'New password must contain at least one uppercase letter, one lowercase letter, one digit and one special character.'
        ];
    }

    /**
     * Transform request to data transfer object.
     * @return UserDto
     */
    public function toDto(): UserDto
    {
        $userDto = new UserDto();
        $userDto->setId(auth()->id());
        $userDto->setNewPassword($this->getInputAsString('new_password'));

        return $userDto;
    }
}
