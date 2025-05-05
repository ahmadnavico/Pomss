<?php

namespace App\Livewire\Role;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToRole extends Component
{
    public $roles;
    public $permissions;
    public $selected_role;
    public $selected_permissions = [];

    public function mount()
    {
        $this->getRoles();
        $this->getPermissions();
    }

    public function render()
    {
        return view('livewire.role.assign-permissions-to-role');
    }

    public function updatedSelectedRole()
    {
        $this->validate([
            'selected_role' => 'required|exists:roles,id',
        ]);
        $role = Role::find($this->selected_role);
        $this->selected_permissions = $role->permissions->pluck('id')->toArray();
    }

    public function assignPermissions()
    {
        $this->validate([
            'selected_role' => 'required|exists:roles,id',
            'selected_permissions' => 'array',
            'selected_permissions.*' => 'exists:permissions,id',
        ]);


        $role = Role::find($this->selected_role);
        $permissions = Permission::find($this->selected_permissions);
        $role->syncPermissions($permissions);

        $this->dispatch('notify', title: 'Success', message: 'Permissions updated successfully.', type: 'success');
        $this->dispatch('permissionsAssigned');
    }

    // Method to check all permissions
    public function checkAllPermissions()
    {
        $this->selected_permissions = $this->permissions->pluck('id')->toArray();
    }

    // Method to uncheck all permissions
    public function uncheckAllPermissions()
    {
        $this->selected_permissions = [];
    }

    private function getRoles()
    {
        $this->roles = Role::latest()->get(['id', 'name', 'updated_at'])->sortBy('name');
    }

    private function getPermissions()
    {
        // Get all the permissions in alphabetical order

        $this->permissions = Permission::all(['id', 'name'])->sortBy('name');
    }
}
