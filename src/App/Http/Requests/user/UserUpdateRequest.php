<?php

namespace App\Http\Requests\user;

use App\Constants\RoleConstant;
use App\Traits\Requests\UserTrait;
use Billyranario\ProstarterKit\App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends BaseRequest
{
    use UserTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route('user');

        $rules = [
            'firstname'  => ['required', 'string', 'max:128'],
            'lastname'  => ['required', 'string', 'max:128'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
        ];

        if (auth()->user()->role_id === RoleConstant::ADMIN) {
            if (!empty($this->input('role_id'))) {
                $rules['role_id'] = ['required', 'integer'];
            }
            if (!empty($this->input('password'))) {
                $rules['password'] = [
                    'required',
                    'min:8',
                    'max:16',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/'
                ];
            }
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'firstname.required' => 'First name is required.',
            'firstname.string' => 'First name must be a string.',
            'firstname.max' => 'First name must be less than :max characters long.',
            'lastname.required' => 'Last name is required.',
            'lastname.string' => 'Last name must be a string.',
            'lastname.max' => 'Last name must be less than :max characters long.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email is invalid.',
            'email.unique' => 'Email already exists.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least :min characters long.',
            'password.max' => 'Password must be less than :max characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit and one special character.'
        ];
    }
}
