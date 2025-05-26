<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserManagementController extends Controller
{
    public function all()
    {
        return view('admin.user.showAll');
    }
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }
    public function show(User $user)
    {
        return view('admin.user.view', compact('user'));
    }
}
