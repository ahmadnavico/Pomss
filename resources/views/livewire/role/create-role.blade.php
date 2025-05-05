<div class="mb-4 text-start">
   <form wire:submit="createRole" class="otherforms py-10 border-b border-gray-300">
            <div class="mb-4 text-start">
                <h3 class="text-2xl font-medium">
                    Create Role
                </h3>
                <p class="mt-2 text-base">
                    Create a new role and later assign permissions to it.
                </p>
            </div>

            <div class="w-full">
            <div class="mb-4 w-full">
                <label for="role_name" class="block text-sm font-medium"><span class="sr-only">Role Name</span></label>
                <input type="text" id="role_name" wire:model="role_name" name="role_name" autocomplete="role_name" required class="py-4 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="Role Name">
                <input-error for="role_name" class="mt-2" />
            </div>

            <div class="mb-4">
                @foreach ($roles as $role)
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4  rounded-xl bg-white mb-1 px-4 py-2">
                        <p class="text-base">
                            {{ $role->name }} <span class="text-sm">(Last
                                    updated
                                    {{ $role->updated_at->diffForHumans() }})</span>
                        </p>
                        <div class="text-end">
                            <button wire:click="editRole({{ $role->id }})" class="py-2 px-6 text-base font-medium rounded-lg" style="background-color: #E9EEF4;">Edit</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-2 text-end">
                <button type="submit"
                    class="bg-gray-800 hover:bg-gray-900 text-white py-3 px-6 uppercase font-medium rounded-lg">
                    {{ $role_id ? __('Update') : __('Save') }}
                </button>
            </div>

        </div>

    </form>
</div>