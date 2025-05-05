<?php

namespace App\Actions\Role;

use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class CreatePermission
{
    public function handle(array $input, $validate = false): Permission
    {
        if ($validate) {
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
            ])->validate();
        }

        return Permission::create([
            'name' => $input['name'],
            'guard_name' => 'web',
        ]);
    }
}
