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
    Route::get('/', Login::class);
});
Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', Dashboard::class);
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
});
