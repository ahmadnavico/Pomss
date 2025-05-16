<x-app-layout>
    <x-slot:title>
        Members Requests Management | Edit - Admin
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Member Request
        </h2>
    </x-slot>

    <div>
        <livewire:members.edit-members-change-request :id="$id" />
    </div>
</x-app-layout>
