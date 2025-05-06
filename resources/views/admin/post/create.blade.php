@push('css')
    <style>
        #editor {
            height: 600px;
        }
    </style>
@endpush
<x-app-layout>
    <x-slot:title>
        Create / Edit Post
    </x-slot>
    <livewire:post.create-post :post="$post ?? null">
</x-app-layout>
