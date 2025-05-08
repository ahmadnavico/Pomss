<?php

namespace App\Livewire\Permission;

use Livewire\Component;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class UserPermissionForm extends Component
{
    #[Locked]
    public User $user;

    public $name;
    public $is_active;
    public $email;
    public $roles;
    public $selected_role;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->is_active = $user->is_active;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roles = Role::all(['id', 'name']);
        $this->selected_role = $user->roles->first()->id ?? null;
    }

    public function updatePermissions()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'selected_role' => 'required|exists:roles,id',
        ]);
        $role = Role::find($this->selected_role);
        $this->user->update([
            'name' => $this->name,
            'is_active' => $this->is_active,
        ]);
        $this->user->syncRoles([$role]);

        $this->dispatch('notify', title: 'Success', message: 'Profile updated successfully.', type: 'success');
        $this->dispatch('saved');
    }

    public function render()
    {
        return view('livewire.permission.user-permission-form');
    }


}
