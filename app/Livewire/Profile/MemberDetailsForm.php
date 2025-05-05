<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MemberDetailsForm extends Component
{
    public $experience;
    public $location;
    public $bio;

    protected function rules()
    {
        return [
            'experience' => 'nullable|string|max:255',
            'location'   => 'nullable|string|max:255',
            'bio'        => 'nullable|string',
        ];
    }

    public function mount()
    {
        $member = Auth::user()->member;
        $this->experience = $member->experience   ?? '';
        $this->location   = $member->location     ?? '';
        $this->bio        = $member->bio          ?? '';
    }

    public function save()
    {
        $this->validate();

        Auth::user()->member()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'experience' => $this->experience,
                'location'   => $this->location,
                'bio'        => $this->bio,
            ]
        );
        $this->dispatch('saved');
    }

    public function render()
    {
        return view('livewire.profile.member-details-form');
    }
}
