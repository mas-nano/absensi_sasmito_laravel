<?php

use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\InternalAppMiddleware;
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
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/refresh', 'refresh');
            Route::post('/logout', 'logout');
        });
    });
    Route::get('/announcement/{announcement}/pdf', [AnnouncementController::class, 'pdf']);
    Route::middleware(['api', 'auth:sanctum'])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::prefix('/attendance')->controller(AttendanceController::class)->group(function () {
            Route::post('/create', 'create');
            Route::get('/showLogin', 'showLogin');
            Route::get('/checkStatus', 'checkStatus');
            Route::get('/checkLeave', 'checkLeave');
        });
        Route::prefix('/users')->controller(UserController::class)->group(function () {
            Route::post('/updateMe', 'updateMe');
            Route::post('/update-profile-picture', 'updateProfilePicture');
        });
        Route::prefix('/leaves')->controller(LeaveController::class)->group(function () {
            Route::get('/get-single-list', 'getSingleList');
            Route::post('/create', 'create');
        });
        Route::prefix('/project')->controller(ProjectController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{project}/users', 'getUsers');
            Route::get('/{project}/list-attendance', 'listAttendance');
            Route::get('/{user}/self-report', 'selfReport');
        });
        Route::prefix('/announcement')->controller(AnnouncementController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{announcement}', 'show');
        });
    });

    Route::middleware(['api', InternalAppMiddleware::class])->group(function () {
        Route::prefix('/project/public')->controller(ProjectController::class)->group(function () {
            Route::get('/', 'indexPublic');
        });
        Route::prefix('/users/public')->controller(UserController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{user}', 'showPublic');
        });
    });
});
