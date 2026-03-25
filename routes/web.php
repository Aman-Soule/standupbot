<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StandupController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Workspace
    Route::get('/workspaces/create', [WorkspaceController::class, 'create'])
        ->name('workspaces.create');
    Route::post('/workspaces', [WorkspaceController::class, 'store'])
        ->name('workspaces.store');
    Route::post('/workspaces/{workspace}/switch', [WorkspaceController::class, 'switch'])
        ->name('workspaces.switch');

    // Standups
    Route::get('/standup', [StandupController::class, 'create'])
        ->name('standups.create');
    Route::post('/standup', [StandupController::class, 'store'])
        ->name('standups.store');
    Route::get('/standup/history', [StandupController::class, 'history'])
        ->name('standups.history');

    // Membres
    Route::get('/members', [MemberController::class, 'index'])
        ->name('members.index');
    Route::post('/members/invite', [MemberController::class, 'invite'])
        ->name('members.invite');
    Route::delete('/members/{userId}', [MemberController::class, 'remove'])
        ->name('members.remove');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
