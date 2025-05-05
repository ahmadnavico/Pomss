<?php

namespace App\Actions\Role;

use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CreateRole
{
    public function handle(array $input): Role
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validate();

        return Role::create([
            'name' => $input['name'],
            'guard_name' => 'web',
        ]);
    }
}
