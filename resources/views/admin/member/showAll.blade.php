<x-app-layout>
    <x-slot:title>
        Member Request Change Management - Admin
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Member Request Change Management
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <livewire:members.members-change-request-table />
    </div>
</x-app-layout>
