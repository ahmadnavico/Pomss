<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            CategorySeeder::class,
            SettingSeeder::class,
        ]);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Member']);
        //Create Permissions
        Permission::create(['name' => 'permission create']);
        Permission::create(['name' => 'role create']);
        Permission::create(['name' => 'role management']);
        Permission::create(['name' => 'permission management']);
        Permission::create(['name' => 'members management']);
        Permission::create(['name' => 'view member']);
        Permission::create(['name' => 'post management']);
        Permission::create(['name' => 'edit member']);
        Permission::create(['name' => 'member show all']);
        Permission::create(['name' => 'member edit profile info']);
        Permission::create(['name' => 'member view profile info']);
        Permission::create(['name' => 'administration management']);
        Permission::create(['name' => 'category management']);
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'delete category']);
        Permission::create(['name' => 'settings management']);
        Permission::create(['name' => 'edit setting']);
        Permission::create(['name' => 'delete setting']);
        Permission::create(['name' => 'manage requests']);
        
        
        //Assign Permissions to Roles
        $role = Role::findByName('Admin');
        //Give All Permissions to Admin
        $role->givePermissionTo(Permission::all());

        //Create Admin User
        User::factory()->create([
            'first_name' => 'Ahmad',
            'last_name' => 'Admin',
            'full_name' => 'Ahmad Admin',
            'email' => 'ahmad@admin.com',
            'password' => Hash::make('11111111'),
        ]);

        //Assign Admin Role to Admin User
        $user = User::where('email', 'ahmad@admin.com')->first();
        $user->assignRole('Admin');
    }
}
