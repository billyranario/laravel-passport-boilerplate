<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
    /**
     * @param array $credentials
     * @param bool $remember
     * @return string|null
     */
    public function authenticate(array $credentials, bool $remember = false): ?string;

    /**
     * @param bool $logoutFromAllDevices
     * @return bool
     */
    public function logout(bool $logoutFromAllDevices): bool;
}