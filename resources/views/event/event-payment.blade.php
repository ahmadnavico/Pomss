<x-app-layout>
    <x-slot:title>
        Event Payment
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Event Payment
        </h2>
    </x-slot>
    <div>
        <livewire:events.event-payment :post="$post ?? null">
    </div>
</x-app-layout>

