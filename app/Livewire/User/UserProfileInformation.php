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

    public $name;
    public $email;
    public $is_active;
    public $roles;
    public $selected_role;

    // Member-related fields
    public $experience;
    public $location;
    public $bio;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->is_active = $user->is_active;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roles = Role::all(['id', 'name']);
        $this->selected_role = $user->roles->first()->id ?? null;

        // Load member data if it exists
        $this->experience = $user->member->experience ?? '';
        $this->location = $user->member->location ?? '';
        $this->bio = $user->member->bio ?? '';
    }

    public function updateProfileInformation()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'selected_role' => 'required|exists:roles,id',
            'experience' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'bio' => 'required|string|max:1000',
        ]);

        // Update user base fields
        $this->user->update([
            'name' => $this->name,
            'is_active' => $this->is_active,
        ]);

        // Sync role
        $role = Role::find($this->selected_role);
        $this->user->syncRoles([$role]);

        // Update member data ONLY if not Admin
        if (! $this->user->hasRole('Admin')) {
            $this->user->member()->updateOrCreate([], [
                'experience' => $this->experience,
                'location' => $this->location,
                'bio' => $this->bio,
            ]);
        }

        $this->dispatch('notify', title: 'Success', message: 'Profile updated successfully.', type: 'success');
        $this->dispatch('saved');
    }


    public function render()
    {
        return view('livewire.user.user-profile-information');
    }
}
