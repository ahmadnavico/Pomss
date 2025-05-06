<?php

use App\Http\Controllers\Admin\Post\CreatePostController;
use App\Http\Controllers\Admin\Post\PostManagementController;
use App\Http\Controllers\Admin\Role\RoleAndPermissionController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::prefix('admin')->group(function () {
        Route::prefix('role-and-permissions')->middleware('can:role management')->group(function () {
            Route::get('/', [RoleAndPermissionController::class, 'show'])->name('role-and-permissions.show');
        });
        Route::prefix('post')->middleware('can:post management')->group(function () {
            Route::get('create/{post?}', CreatePostController::class)->name('post.create');
        });
        Route::prefix('posts-management')->middleware('can:post management')->group(function () {
            Route::get('/', [PostManagementController::class, 'all'])->name('posts-management.all');
            Route::get('/edit/{post}', [PostManagementController::class, 'edit'])->name('posts-management.edit');
        });
    });
});
