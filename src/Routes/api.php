<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Auth API
 */
Route::prefix('auth')
    ->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
    });

Route::middleware('auth:api')
    ->prefix('auth')
    ->group(function () {
        Route::get('me', [AuthController::class, 'authenticatedUser']);

        Route::prefix('tokens')
            ->group(function () {
                Route::post('refresh', [AuthController::class, 'refresh']);
            });
        Route::post('logout', [AuthController::class, 'logout']);
    });
