<?php

use App\Http\Controllers\Admin\Role\RoleAndPermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
    });
});
