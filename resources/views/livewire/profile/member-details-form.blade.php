<x-form-section submit="save">
    <x-slot name="title">Member Details</x-slot>
    <x-slot name="description">
        Add or update your experience, location and bio.
    </x-slot>

    <x-slot name="form">
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
    </x-slot>

    <x-slot name="actions">
        {{-- Listens for the Livewire 'saved' event --}}
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="save">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
