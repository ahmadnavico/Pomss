<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input wire:model.live="name" type="text" placeholder="Search by name" class="w-full rounded border px-4 py-2">
        <input wire:model.live="location" type="text" placeholder="Search by location" class="w-full rounded border px-4 py-2">
        <input wire:model.live="min_experience" type="number" placeholder="Min experience (years)" class="w-full rounded border px-4 py-2">
    </div>

    @if($members->isEmpty())
        <p class="text-center text-gray-500">No members found.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($members as $member)
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                    <h2 class="text-xl font-semibold mb-2">{{ $member->user->full_name }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">Location: {{ $member->location ?? 'N/A' }}</p>
                    <p class="text-gray-600 dark:text-gray-300">Experience: {{ $member->calculated_experience }} years</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
