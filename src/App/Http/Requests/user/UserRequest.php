<?php

namespace App\Http\Requests\user;

use App\Traits\Requests\UserTrait;
use Billyranario\ProstarterKit\App\Http\Requests\BaseRequest;

class UserRequest extends BaseRequest
{
    use UserTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
