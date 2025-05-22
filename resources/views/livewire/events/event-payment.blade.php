<div class="max-w-xl mx-auto p-4 bg-white shadow rounded mt-5">
    <form wire:submit.prevent="submit">
        <h2 class="text-lg font-bold mb-4">Event Payment for "{{ $post->title }}"</h2>

        <input wire:model="name" type="text" placeholder="Name" class="mb-2 w-full border px-3 py-2 rounded" />
        @error('name')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <input wire:model="email" type="email" placeholder="Email" class="mb-2 w-full border px-3 py-2 rounded" />
        @error('email')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <h4 class="font-semibold mt-4">Card Info</h4>

        <input wire:model="card_number" type="text" placeholder="Card Number" class="mb-2 w-full border px-3 py-2 rounded" />
        @error('card_number')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <input wire:model="cvc" type="text" placeholder="CVC" class="mb-2 w-full border px-3 py-2 rounded" />
        @error('cvc')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <h4 class="font-semibold mt-4">Billing Address</h4>

        <input wire:model="address_line1" type="text" placeholder="Address Line 1" class="mb-2 w-full border px-3 py-2 rounded" />
        @error('address_line1')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <input wire:model="address_line2" type="text" placeholder="Address Line 2" class="mb-2 w-full border px-3 py-2 rounded" />
        @error('address_line2')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <input wire:model="city" type="text" placeholder="City" class="mb-2 w-full border px-3 py-2 rounded" />
        @error('city')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <input wire:model="state" type="text" placeholder="State" class="mb-2 w-full border px-3 py-2 rounded" />
        @error('state')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <input wire:model="postal_code" type="text" placeholder="Postal Code" class="mb-2 w-full border px-3 py-2 rounded" />
        @error('postal_code')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <input wire:model="country" type="text" placeholder="Country" class="mb-2 w-full border px-3 py-2 rounded" />
        @error('country')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit Payment</button>

        @if (session()->has('success'))
            <div class="text-green-600 mt-4">{{ session('success') }}</div>
        @endif
    </form>
</div>
