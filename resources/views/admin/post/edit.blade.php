<x-app-layout>
    <x-slot:title>
        Posts Management | Edit - Admin
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Post
        </h2>
    </x-slot>
    <div>
        <livewire:post.edit-post :post="$post" />
    </div>
</x-app-layout>
