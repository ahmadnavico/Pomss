<x-app-layout>
    <x-slot:title>
        Edit Member - Admin
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Members Management - Edit Member Profile
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @can('member edit profile info')
            <livewire:user.user-profile-information :user="$user" />
            <x-section-border />
            
        @endcan

    </div>
</x-app-layout>
