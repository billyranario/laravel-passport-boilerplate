<?php

namespace App\Constants;

class ActivityLogConstant
{
    /**
     * Types
     */
    const REGISTRATION = 1;
    const LOGIN = 2;
    const ACCOUNT_CREATE = 3;
    const ACCOUNT_BLOCK = 4;
    const ACCOUNT_ARCHIVE = 5;
    const ACCOUNT_DELETE = 6;

    /**
     * Log user registration
     */
    public static function LOG_REGISTER(mixed $row): string
    {
        return "{$row->firstname} {$row->lastname} has registered.";
    }

    /**
     * Log user login
     */
    public static function LOG_SIGNIN(mixed $row): string
    {
        return "{$row->firstname} {$row->lastname} has logged in.";
    }

    /**
     * Log creation of user account.
     */
    public static function LOG_ACCOUNT_CREATE(mixed $row): string
    {
        return "Created account for {$row->firstname} {$row->lastname}.";
    }

    /**
     * Log blocking of user account.
     */
    public static function LOG_ACCOUNT_BLOCK(int $count, string $action): string
    {
        return "{$count} users has been {$action}.";
    }

    /**
     * Log archiving of user account.
     */
    public static function LOG_ACCOUNT_ARCHIVE(int $count): string
    {
        return "{$count} users has been moved to archive.";
    }

    /**
     * Log restoring of user account.
     */
    public static function LOG_ACCOUNT_RESTORE(int $count): string
    {
        return "{$count} users has been restored.";
    }

    /**
     * Log deletion of user account.
     */
    public static function LOG_ACCOUNT_DELETE(int $count): string
    {
        return "{$count} users has been deleted.";
    }

    /**
     * Log user registration
     */
    public static function LOG_REGISTER_ADMIN(mixed $row): string
    {
        return "{$row->firstname} {$row->lastname} has registered an account for {$row->firstname} {$row->lastname}.";
    }
    
}
