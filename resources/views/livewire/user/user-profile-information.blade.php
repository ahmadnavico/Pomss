<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <x-section-title>
            <x-slot name="title">
                {{ __('Profile Information') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Update user account\'s profile information.') }}
            </x-slot>
        </x-section-title>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form wire:submit="updateProfileInformation">
                <div class="px-4 py-5 bg-white sm:p-6 shadow space-y-4">
                    <div class="grid grid-cols-6 gap-6">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" required
                                autocomplete="name" />
                            <x-input-error for="name" class="mt-2" />
                        </div>
                        <!-- Email -->
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="email"
                                disabled />
                        </div>

                        <!-- Role -->
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="selected_role" value="{{ __('Select Role') }}" />
                            <select name="selected_role" wire:model.live="selected_role"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                id="selected_role">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="selected_role" class="mt-1" />
                        </div>
                        <!-- Active/Inactive Status -->
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="is_active" value="{{ __('Account Status') }}" />
                            <select name="is_active" wire:model="is_active"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                id="is_active">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Inactive') }}</option>
                            </select>
                            <x-input-error for="is_active" class="mt-1" />
                        </div>
                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="experience" value="Experience" />
                                <x-input id="experience" type="text" class="mt-1 block w-full"
                                        wire:model.defer="experience" />
                                <x-input-error for="experience" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4 mt-4">
                                <x-label for="location" value="Location" />
                                <x-input id="location" type="text" class="mt-1 block w-full"
                                        wire:model.defer="location" />
                                <x-input-error for="location" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4 mt-4">
                                <x-label for="bio" value="Bio" />
                                <textarea id="bio" rows="4"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        wire:model.defer="bio"></textarea>
                                <x-input-error for="bio" class="mt-2" />
                            </div>
                    </div>
                </div>

                <div
                    class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                    <x-action-message class="me-3" on="saved">
                        {{ __('Saved.') }}
                    </x-action-message>

                    <x-button wire:loading.attr="disabled" wire:target="photo">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>

</div>
