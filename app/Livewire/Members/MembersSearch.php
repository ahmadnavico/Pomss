<?php

namespace App\Livewire\Members;

use App\Models\Member;
use Livewire\Component;

class MembersSearch extends Component
{
    public $name = '';
    public $location = '';
    public $min_experience = '';

    public function render()
    {
        $members = Member::with('user')
            ->when($this->name, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('full_name', 'like', '%' . $this->name . '%');
                });
            })
            ->when($this->location, function ($query) {
                $query->where('location', 'like', '%' . $this->location . '%');
            })
            ->get()
            ->filter(function ($member) {
                if (!$this->min_experience) return true;

                $experience = collect($member->experience ?? []);
                $totalYears = $experience->sum(function ($item) {
                    return (int) ($item['years'] ?? 0);
                });

                return $totalYears >= $this->min_experience;
            });

        return view('livewire.members.members-search', [
            'members' => $members,
        ]);
    }
}
