<x-app-layout>
    <x-slot:title>
        Manage Events
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Events
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <livewire:members.my-events-table />
    </div>
</x-app-layout>
