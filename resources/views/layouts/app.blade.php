<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')
        @stack('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script></script>
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('notify', (event) => {
                    console.log(event[0]);
                    if (event.type === 'success') {
                        toastr.success(event.message, event.title);
                    } else if (event.type === 'error') {
                        toastr.error(event.message, event.title);
                    } else if (event.type === 'warning') {
                        toastr.warning(event.message, event.title);
                    } else {
                        toastr.info(event.message, event.title);
                    }
                });
            });
            //Handle error or success messages from laravel sessions
            @if (session('success'))
                toastr.success("{{ session('success') }}", "Success");
            @elseif (session('error'))
                toastr.error("{{ session('error') }}", "Error");
            @endif
            document.addEventListener('livewire:load', function() {
                console.log('Livewire loaded!');
            });

        </script>

        @livewireScripts
    </body>
</html>
