<?php

namespace App\Http\Middleware\Admin;

use App\Constants\RoleConstant;
use App\Constants\ServiceResponseMessages;
use Billyranario\ProstarterKit\App\Core\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (auth()->check() && in_array($user->role_id, RoleConstant::AUTHORIZED_ROLES)) {
            if (is_null($user->blocked_at)) {
                return $next($request);
            }
        }
        return ResponseHelper::error(ServiceResponseMessages::UNAUTHORIZED, Response::HTTP_UNAUTHORIZED);
    }
}
