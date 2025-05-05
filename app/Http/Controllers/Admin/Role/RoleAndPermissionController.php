<?php

namespace App\Http\Controllers\Admin\Role;

use App\Actions\Role\CreateRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleAndPermissionController extends Controller
{
    public function createRole(Request $request, CreateRole $createRole)
    {
        $role = $createRole->handle($request->all());
        if ($role) {
            return view('admin.role.show')->with('success', 'Role created successfully.');
        }

        return back()->with('error', 'Role creation failed.');
    }

    public function show()
    {
        return view('admin.role.show');
    }
}
