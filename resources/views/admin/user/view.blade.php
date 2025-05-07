<x-app-layout>
    <x-slot:title>
        Edit User - Admin
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Memeber Management - View Member Profile
        </h2>
    </x-slot>

    <div>
        @can('member view profile info')
            <livewire:user.view-user-information :user="$user" />
        @endcan

    </div>
</x-app-layout>
