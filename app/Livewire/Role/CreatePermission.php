<?php

namespace App\Livewire\Role;

use Livewire\Component;
use App\Actions\Role\CreatePermission as CreatePermissionAction;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class CreatePermission extends Component
{
    public $permission_name;
    public $permissions;
    public $permission_id = null; // Add permission_id to track the permission being edited

    protected $rules = [
        'permission_name' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->getPermissions();
    }

    public function createPermission(CreatePermissionAction $createPermission)
    {
        $this->validate();
        $this->permission_name = strtolower($this->permission_name);
        try {
            if ($this->permission_id) {
                // Update existing permission
                $permission = Permission::find($this->permission_id);
                $permission->name = $this->permission_name;
                $permission->save();
                $message = 'Permission updated successfully.';
            } else {
                // Create new permission
                $response = $createPermission->handle([
                    'name' => $this->permission_name,
                ]);
                if ($response) {
                    $message = 'Permission created successfully.';
                }
            }

            $this->dispatch('notify', title: 'Success', message: $message, type: 'success');
            $this->dispatch('permissionCreated');
            $this->getPermissions();
            $this->resetForm();
            return;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        $this->dispatch('notify', title: 'Error', message: 'Permission creation failed.', type: 'error');
    }

    public function editPermission($id)
    {
        $permission = Permission::find($id);
        $this->permission_id = $permission->id;
        $this->permission_name = $permission->name;
    }

    private function getPermissions()
    {
        // Get all the permissions latest on top
        $this->permissions = Permission::latest()->get(['id', 'name', 'updated_at'])->sortBy('name');
    }

    private function resetForm()
    {
        $this->permission_id = null;
        $this->permission_name = '';
    }

    public function render()
    {
        return view('livewire.role.create-permission');
    }
}
