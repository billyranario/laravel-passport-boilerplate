<?php

namespace App\Constants;

class ServiceResponseMessages
{
    /**
     * Common messages
     */
    const SUCCESS = 'Success';
    const ERROR = 'Error';
    const REGISTER_SUCCESS = 'Account created successfully.';
    const REGISTER_ERROR = 'Unable to create account. Please try again later.';
    const EMAIL_NOT_FOUND = 'Email does not exists.';
    const INCORRECT_PASSWORD = 'Incorrect password.';
    const INVALID_CREDENTIALS = 'Invalid credentials.';
    const LOGIN_SUCCESS = 'Authenticated.';
    const LOGOUT_SUCCESS = 'Logged out successfully.';
    const LOGOUT_ERROR = 'Unable to logout. Please try again later.';
    const REFRESH_TOKEN_SUCCESS = 'Token refreshed successfully.';
    const REFRESH_TOKEN_ERROR = 'Unable to refresh token. Please try again later.';
    const UNAUTHENTICATED = 'Unauthenticated.';

    /**
     * @param string $email
     * @return string
     */
    public static function PASSWORD_RESET_LINK_SUCCESS(string $email): string
    {
        return "Password reset link sent to {$email}.";
    }

    /**
     * @param string $email
     * @return string
     */
    public static function PASSWORD_RESET_LINK_ERROR(string $email): string
    {
        return "Unable to send password reset link to {$email}. Please try again later.";
    }

    /**
     * @param string $email
     * @return string
     */
    public static function PASSWORD_RESET_SUCCESS(string $email): string
    {
        return "Password reset successfully for {$email}.";
    }

    /**
     * @param string $email
     * @return string
     */
    public static function PASSWORD_RESET_ERROR(string $email): string
    {
        return "[{$email}] Token is either invalid or expired.";
    }
}
