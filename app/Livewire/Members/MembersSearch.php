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
            ->map(function ($member) {
                $expRaw = $member->experience;

                if (is_string($expRaw)) {
                    $expRaw = json_decode($expRaw, true);
                }

                $totalYears = 0;
                if (is_array($expRaw)) {
                    $totalYears = collect($expRaw)->sum(function ($exp) {
                        $from = isset($exp['from']) ? (int) $exp['from'] : null;
                        $to = isset($exp['to']) ? (int) $exp['to'] : null;

                        return ($from && $to && $to >= $from) ? ($to - $from) : 0;
                    });
                }

                $member->calculated_experience = $totalYears;

                return $member;
            })
            ->filter(function ($member) {
                if (!$this->min_experience) return true;
                return $member->calculated_experience >= $this->min_experience;
            });

        return view('livewire.members.members-search', [
            'members' => $members,
        ]);
    }
}
