<div>
    <form wire:submit.prevent="assignPermissions" class="otherforms py-10 ">
        <div class="mb-4 text-start">
            <h3 class="text-2xl font-medium">
                Assign Permissions to role
            </h3>
            <p class="mt-2 text-base">
                Assign permissions to a role.
            </p>
        </div>

        <div class="w-full">
            <div class="mb-4 bg-white">
                <label for="selected_role" value="{{ __('Select Role') }}" />
                <select name="selected_role" wire:model.live="selected_role" id="selected_role" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm ">
                    <option selected="">Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                </select>
                <input-error for="selected_role" class="mt-1" />
            </div>

            <div class="roles-check-sec">

                <div class="checkbuttons mb-4">
                    <a type="button" wire:click="checkAllPermissions" class="py-3 px-6 me-2 text-base font-medium rounded-lg bg-white">Check All</a>
                    <a type="button" wire:click="uncheckAllPermissions" class="py-3 px-6 text-base font-medium rounded-lg" style="background-color: #D3DDE9;">Uncheck All</a>
                </div>

                <div class="mb-4 grid sm:grid-cols-2 gap-2">
                    @foreach ($permissions as $permission)
                        <div class="flex items-center justify-between rounded-xl bg-white mb-1 px-4 py-4">
                            <div class="flex items-center">
                                <div class="flex">
                                <input type="checkbox" wire:model="selected_permissions" value="{{ $permission->id }}"
                                id="permission_{{ $permission->id }}"
                                 class="shrink-0 mt-0.5 border-gray-200 rounded">
                                </div>
                                <div class="mx-4">
                                <label for="permission_{{ $permission->id }}" class="text-base">{{ $permission->name }}</label>
                                </div>
                            </div>
                        </div>  
                    @endforeach
                </div>
            </div>
            <div class="mt-2 text-end">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white py-3 px-6 uppercase font-medium rounded-lg">Save</button>
            </div>
        </div>
    </form>
</div>


