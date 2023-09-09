<?php

namespace App\Http\Requests\auth;

use App\Traits\Requests\AuthTrait;
use Billyranario\ProstarterKit\App\Http\Requests\BaseRequest;

class ResetPasswordRequest extends BaseRequest
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
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required'],
            'password' => [
                'required',
                'min:8',
                'max:16',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/'
            ],
        ];
    }
}
