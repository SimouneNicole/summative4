<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — Viewable by Anyone (Welcome, Task List, Profile, About)
|--------------------------------------------------------------------------
*/
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/about', [AboutController::class, 'index'])->name('about');

/*
|--------------------------------------------------------------------------
| Protected Task Management & Account Routes (Laravel Breeze Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('tasks.index'))
        ->middleware('verified')
        ->name('dashboard');

    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/account/profile', [ProfileController::class, 'edit'])->name('account.profile.edit');
    Route::patch('/account/profile', [ProfileController::class, 'update'])->name('account.profile.update');
    Route::delete('/account/profile', [ProfileController::class, 'destroy'])->name('account.profile.destroy');
});

require __DIR__.'/auth.php';
