<?php


namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Locked;

class ViewUserInformation extends Component
{
    #[Locked]
    public User $user;

    public function mount(User $user)
    {
        // Eager load the member relationship
        $this->user = $user->load('member');
    }

    public function render()
    {
        return view('livewire.user.view-user');
    }
}
