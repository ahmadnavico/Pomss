<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Spatie\Permission\Models\Role;

class UserProfileInformation extends Component
{
    #[Locked]
    public User $user;

    public $first_name;
    public $last_name;
    public $email;
    public $is_active;
    public $roles;
    public $selected_role;


    public function mount(User $user)
    {
        $this->user = $user;
        $this->is_active = $user->is_active;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->roles = Role::all(['id', 'name']);
        $this->selected_role = $user->roles->first()->id ?? null;

    }

    public function updateProfileInformation()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'selected_role' => 'required|exists:roles,id',
        ]);

        // Update user base fields
        $this->user->update([
            'first_name' => $this->first_name,
            'last_name' => $this->first_name,
            'is_active' => $this->is_active,
        ]);

        // Sync role
        $role = Role::find($this->selected_role);
        $this->user->syncRoles([$role]);
        session()->flash('success', 'Profile updated successfully.');
        $this->dispatch('notify', title: 'Member Profile', message: 'Profile updated successfully.', type: 'success'); 
        $this->dispatch('saved');
        // return redirect(route('members-management.all'));
    }


    public function render()
    {
        return view('livewire.user.user-profile-information');
    }
}
