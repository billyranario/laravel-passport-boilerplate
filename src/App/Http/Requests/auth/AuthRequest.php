<?php

namespace App\Http\Requests\auth;

use App\Traits\Requests\AuthTrait;
use Billyranario\ProstarterKit\App\Http\Requests\BaseRequest;

class AuthRequest extends BaseRequest
{
    use AuthTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
