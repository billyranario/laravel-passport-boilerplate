<?php

namespace App\Http\Requests\auth;

use App\Traits\Requests\AuthTrait;
use Billyranario\ProstarterKit\App\Http\Requests\BaseRequest;

class ForgotPasswordRequest extends BaseRequest
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
            'email' => ['required', 'email', 'exists:users,email']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email is invalid.',
            'email.exists' => 'Email does not exist.'
        ];
    }
}
