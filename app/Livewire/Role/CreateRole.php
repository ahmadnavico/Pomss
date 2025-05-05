<?php

namespace App\Livewire\Role;

use Livewire\Component;
use App\Actions\Role\CreateRole as CreateRoleAction;
use Spatie\Permission\Models\Role;

class CreateRole extends Component
{
    public $role_name;
    public $roles;
    public $role_id = null; // Add role_id to track the role being edited

    protected $rules = [
        'role_name' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->getRoles();
    }

    public function createRole(CreateRoleAction $createRole)
    {
        $this->validate();

        try {
            if ($this->role_id) {
                // Update existing role
                $role = Role::find($this->role_id);
                $role->name = $this->role_name;
                $role->save();
                $message = 'Role updated successfully.';
            } else {
                // Create new role
                $response = $createRole->handle([
                    'name' => $this->role_name,
                ]);
                if ($response) {
                    $message = 'Role created successfully.';
                }
            }

            $this->dispatch('notify', title: 'Success', message: $message, type: 'success');
            $this->dispatch('roleCreated');
            $this->getRoles();
            $this->resetForm();
            return;
        } catch (\Exception $e) {
        }
        $this->dispatch('notify', title: 'Error', message: 'Role creation failed.', type: 'error');
    }

    public function editRole($id)
    {
        $role = Role::find($id);
        $this->role_id = $role->id;
        $this->role_name = $role->name;
    }

    private function getRoles()
    {
        // Get all the roles latest on top
        $this->roles = Role::latest()->get(['id', 'name', 'updated_at'])->sortBy('name');
    }

    private function resetForm()
    {
        $this->role_id = null;
        $this->role_name = '';
    }

    public function render()
    {
        return view('livewire.role.create-role');
    }
}
