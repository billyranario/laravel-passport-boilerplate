<?php

namespace App\Http\Requests\user;

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

        return [
            'firstname'  => ['required', 'string', 'max:128'],
            'lastname'  => ['required', 'string', 'max:128'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
        ];
    }
}
