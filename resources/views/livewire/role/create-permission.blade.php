<div>
     <form wire:submit="createPermission" class="otherforms py-10 border-b border-gray-300">
            
            <div class="mb-4 text-start">
                <h3 class="text-2xl font-medium">
                    Create Permission
                </h3>
                <p class="mt-2 text-base">
                    Create a new permission and later assign it ot a role.
                </p>
            </div>

            <div class="w-full">
            <div class="mb-4 w-full">
                <label for="permission_name" wire:model="permission_name" class="block text-sm font-medium"><span class="sr-only">Permission Name</span></label>
                
                <input type="text" id="permission_name" name="permission_name" wire:model="permission_name" required autocomplete="permission_name"  class="py-4 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="Permission Name">
                <input-error for="permission_name" class="mt-2" />
            </div>
            <div class="mb-4">
                @foreach ($permissions as $permission)
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 rounded-xl bg-white mb-1 px-4 py-2">
                        <p class="text-base">
                           {{ $permission->name }}<span class="text-sm">(Last
                                            updated
                                            {{ $permission->updated_at->diffForHumans() }})</span>
                        </p>
                        <div class="text-end">
                        <button  type="button" wire:click="editPermission({{ $permission->id }})" class="py-2 px-6 text-base font-medium rounded-lg" style="background-color: #E9EEF4;">Edit</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-2 text-end">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white py-3 px-6 uppercase font-medium rounded-lg"> {{ $permission_id ? __('Update') : __('Save') }}</button>
            </div>

        </div>
    </form>  
</div>

