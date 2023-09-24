<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\AuthRepositoryInterface;
use Billyranario\ProstarterKit\App\Helpers\LoggerHelper;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Laravel\Passport\Passport;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * @var UserRepository $userRepository
     */
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $credentials
     * @param bool $remember
     * @return string|null
     */
    public function authenticate(array $credentials, bool $remember = false): ?string
    {
        if (auth()->attempt($credentials, $remember)) {
            $user = $this->userRepository->findByEmail($credentials['email']);
            $tokenResult = $user->createToken(config('prostarterkit.password_grant'));

            if ($remember) {
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addDays(14);
                $token->save();
            }

            return $tokenResult->accessToken;
        }

        return null;
    }

    /**
     * Refresh Token
     * @return string|null
     */
    public function refreshToken(): ?string
    {
        try {
            $user = auth()->user();
            $token = $user->token();
            $token->revoke();

            $tokenResult = $user->createToken(config('prostarterkit.password_grant'));
            return $tokenResult->accessToken;
        } catch (\Throwable $th) {
            LoggerHelper::logThrowError($th);
            return null;
        }
    }

    /**
     * @param bool $logoutFromAllDevices
     * @return bool
     */
    public function logout(bool $logoutFromAllDevices): bool
    {
        try {
            $user = auth()->user();

            if ($logoutFromAllDevices) {
                $user->tokens->each(function ($token) {
                    $token->delete();
                });
            } else {
                $token = $user->token();
                Passport::token()->where('id', $token->id)->update(['revoked' => true]);
            }
            return true;
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return false;
        }
    }

    /**
     * @param array $params
     * @return string
     */
    public function sendResetLinkViaEmail(array $params): string
    {
        $user = $this->userRepository->findByEmail($params['email']);
        $user->setAdditionalResetParams($params);

        return Password::broker()->sendResetLink(
            $params,
            function ($userInClosure, $token) use ($user) {
                $additionalResetParams = $user->additionalResetParams ?? [];
                $data = array_merge(['token' => $token], $additionalResetParams);
                $userInClosure->sendPasswordResetNotification($data);
            }
        );
    }

    /**
     * @param array $params
     * @return string
     */
    public function resetPassword(array $params): string
    {

        return Password::reset(
            $params,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );
    }
}
