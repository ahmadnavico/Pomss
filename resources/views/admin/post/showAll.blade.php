<x-app-layout>
    <x-slot:title>
        Posts Management - Admin
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Posts Management
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <livewire:post.posts-table />
    </div>
</x-app-layout>
