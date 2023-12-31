<?php

namespace App\Http\Requests\auth;

use App\Traits\Requests\AuthTrait;
use Billyranario\ProstarterKit\App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    use AuthTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'firstname'  => ['required', 'string', 'max:128'],
            'lastname'  => ['required', 'string', 'max:128'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
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
            'firstname.required' => 'First name is required.',
            'firstname.string' => 'First name must be a string.',
            'firstname.max' => 'First name must be less than :max characters long.',
            'lastname.required' => 'Last name is required.',
            'lastname.string' => 'Last name must be a string.',
            'lastname.max' => 'Last name must be less than :max characters long.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email is invalid.',
            'email.unique' => 'Email already exists.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least :min characters long.',
            'password.max' => 'Password must be less than :max characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit and one special character.'
        ];
    }
}
