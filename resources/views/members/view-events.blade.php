<x-app-layout>
    <x-slot:title>
        All Events
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            All Events
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <livewire:members.view-events-table />
    </div>
</x-app-layout>
