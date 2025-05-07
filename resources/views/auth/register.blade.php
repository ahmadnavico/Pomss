<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <!-- <x-validation-errors class="mb-4" /> -->

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="mb-2">
                <x-label for="first_name" value="{{ __('First Name') }}" />
                <x-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" autofocus autocomplete="first_name" />
                @error('first_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-2">
                <x-label for="last_name" value="{{ __('Last Name') }}" />
                <x-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" autofocus autocomplete="last_name" />
                @error('last_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-2">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autocomplete="username" />
                @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-2">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-2">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                @error('password_confirmation') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-2">
                <x-label for="title" value="{{ __('Title') }}" />
                <select id="title" name="title" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Select Title --</option>
                    <option value="Mr." {{ old('title') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                    <option value="Mrs." {{ old('title') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                </select>
                @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-2">
                <x-label for="dob" value="{{ __('Date of Birth') }}" />
                <x-input id="dob" class="block mt-1 w-full" type="date" name="dob" :value="old('dob')" />
                @error('dob') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-2">
                <x-label for="phone_number" value="{{ __('Phone Number') }}" />
                <x-input 
                    id="phone_number" 
                    class="block mt-1 w-full" 
                    type="text" 
                    name="phone_number" 
                    :value="old('phone_number')" 
                    autocomplete="tel"
                    oninput="this.value = this.value.replace(/[^0-9+()\s-]/g, '')"
                    placeholder="+1234567890"
                />
                @error('phone_number') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" />
                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                        @error('terms') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
