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
 * Common Authenticated API
 */
Route::middleware(['auth:api', 'admin.api'])
    ->group(function () {
        // Activity Logs
        Route::apiResource('activity-logs', Admin\ActivityLogController::class);

        // Users
        Route::apiResource('users', Admin\UserController::class)->except(['delete']);
        Route::post('users/block', [Admin\UserController::class, 'setBlockStatus']);
        Route::post('users/archive', [Admin\UserController::class, 'deleteUsers']);
        Route::post('users/restore', [Admin\UserController::class, 'restoreUsers']);
        Route::post('users/delete', [Admin\UserController::class, 'deleteUsers']);
    });
