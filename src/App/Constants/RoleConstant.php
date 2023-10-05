<?php

namespace App\Constants;

class RoleConstant
{
    /**
     * Roles
     */
    const ADMIN = 1;
    const CLIENT = 2;
    const SUPERADMIN = 9;

    const AUTHORIZED_ROLES = [
        self::ADMIN,
        self::SUPERADMIN,
    ];
}
