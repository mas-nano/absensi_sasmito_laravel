<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
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

Route::prefix('/v1')->group(function () {
    Route::prefix('/auth')->controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::middleware(['api', 'jwt.verify'])->group(function () {
            Route::post('/refresh', 'refresh');
            Route::post('/logout', 'logout');
        });
    });
    Route::middleware(['api', 'jwt.verify'])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::prefix('/attendance')->controller(AttendanceController::class)->group(function () {
            Route::post('/create', 'create');
            Route::get('/showLogin', 'showLogin');
            Route::get('/checkStatus', 'checkStatus');
        });
    });
});
