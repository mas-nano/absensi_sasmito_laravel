<?php

use App\Livewire\Dashboard;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest:web')->group(function () {
    Route::get('/', Login::class)->name('login');
});
Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::prefix('/employee')->name('employee.')->group(function () {
        Route::get('/', App\Livewire\Employee\Index::class)->name('index');
        Route::get('/create', App\Livewire\Employee\Create::class)->name('create');
        Route::get('/{profile:uuid}', App\Livewire\Employee\Show::class)->name('show');
        Route::get('/{profile:uuid}/edit', App\Livewire\Employee\Edit::class)->name('edit');
    });
    Route::prefix('/project')->name('project.')->group(function () {
        Route::get('/', App\Livewire\Project\Index::class)->name('index');
        Route::get('/create', App\Livewire\Project\Create::class)->name('create');
        Route::get('/{project:uuid}', App\Livewire\Project\Show::class)->name('show');
        Route::get('/{project:uuid}/edit', App\Livewire\Project\Edit::class)->name('edit');
    });
    Route::prefix('/role')->name('role.')->namespace('App\Livewire\Role')->group(function () {
        Route::get('/', 'Index')->name('index');
        Route::get('/{role:uuid}/edit', 'Edit')->name('edit');
    });
    Route::prefix('/report')->name('report.')->namespace('App\Livewire\Report')->group(function () {
        Route::get('/', 'Index')->name('index');
        Route::get('/{project:uuid}', 'Show')->name('show');
    });
    Route::prefix('/announcement')->name('announcement.')->namespace('App\Livewire\Announcement')->group(function () {
        Route::get('/', 'Index')->name('index');
        Route::get('/create', 'Create')->name('create');
        Route::get('/{announcement:uuid}', 'Show')->name('show');
        Route::get('/{announcement:uuid}/edit', 'Edit')->name('edit');
    });
    Route::prefix('/leave')->name('leave.')->namespace('App\Livewire\Leave')->group(function () {
        Route::get('/', 'Index')->name('index');
        Route::get('/{project:uuid}', 'Detail')->name('show');
    });
    Route::prefix('/position')->namespace('App\Livewire\Position')->name('position.')->group(function () {
        Route::get('/', 'Index')->name('index');
        Route::get('/create', 'Create')->name('create');
        Route::get('/{position:uuid}/edit', 'Edit')->name('edit');
    });
    Route::prefix('/overtime')->namespace('App\Livewire\Overtime')->name('overtime.')->group(function () {
        Route::get('/', 'Index')->name('index');
        Route::get('/create', 'Create')->name('create');
    });
    Route::get('/setting', App\Livewire\Setting\Index::class)->name('setting.index');
});
