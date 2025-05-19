<?php

use App\Http\Controllers\Admin\Post\CreatePostController;
use App\Http\Controllers\Admin\Post\PostManagementController;
use App\Http\Controllers\Admin\Role\RoleAndPermissionController;
use App\Http\Controllers\Admin\User\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberChangeRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('post')->group(function () {
        Route::get('create/{post?}', CreatePostController::class)->name('post.create');
    });
    Route::prefix('my-events')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('post.create');
    });
    Route::prefix('admin')->group(function () {
        Route::prefix('role-and-permissions')->middleware('can:role management')->group(function () {
            Route::get('/', [RoleAndPermissionController::class, 'show'])->name('role-and-permissions.show');
        });
        Route::prefix('members-change-requests')->middleware('can:members change request handling')->group(function () {
            Route::get('/', [MemberChangeRequestController::class, 'index'])->name('members-change-requests.show');
            Route::get('/edit/{id}', [MemberChangeRequestController::class, 'edit'])->name('member-change-request.edit');
        });
        
        Route::prefix('posts-management')->middleware('can:post management')->group(function () {
            Route::get('/', [PostManagementController::class, 'all'])->name('posts-management.all');
            Route::get('/edit/{post}', [PostManagementController::class, 'edit'])->name('posts-management.edit');
        });
        Route::prefix('members-management')->middleware('can:members management')->group(function () {
            Route::get('/', [UserManagementController::class, 'all'])->name('members-management.all');
            Route::middleware('can:view member')->get('/memeber/{user}', [UserManagementController::class, 'show'])->name('member-management.view');
            Route::middleware('can:edit member')->get('/edit-member/{user}', [UserManagementController::class, 'edit'])->name('member-management.edit');
        });
    });
});
