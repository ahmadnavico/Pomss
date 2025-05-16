<?php

namespace App\Livewire\Members;

use App\Models\MemberChangeRequest;
use Livewire\Component;

class EditMembersChangeRequest extends Component
{
    public MemberChangeRequest $request;

    public function mount($id)
    {
        $this->request = MemberChangeRequest::findOrFail($id);
    }

    public function save()
    {
        $this->validate([
            'request.message' => 'required|string',
            'request.status_by_admin' => 'nullable|string',
            'request.request_approved' => 'boolean',
        ]);

        $this->request->save();

        session()->flash('success', 'Request updated successfully.');

        return redirect()->route('members-change-request-table');
    }

    public function render()
    {
        return view('livewire.members.edit-members-change-request');
    }
}
