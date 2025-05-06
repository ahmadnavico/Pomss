<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /* Include Tailwind CSS styles here if Vite isn't available */
        </style>
    @endif
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div class="container px-4 py-10 sm:px-6 lg:py-14 mx-auto ">
        <header class="grid grid-cols-2 items-center gap-2 lg:grid-cols-3">
            <div class="flex lg:justify-center lg:col-start-2">
                <!-- SVG Logo -->
                <svg class="h-12 w-auto text-white lg:h-16 lg:text-[#FF2D20]" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Your SVG Path Data -->
                </svg>
            </div>
            @if (Route::has('login'))
                <nav class="-mx-3 flex flex-1 justify-end">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Display Posts Section -->
        <div class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-6 text-center">Blog Posts</h1>

            @if ($posts->isEmpty())
                <p class="text-gray-500 text-center">No Blog Posts found.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($posts as $post)
                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col transition-transform duration-300 hover:scale-105">
                            <!-- Optional: Add thumbnail image -->
                            <div class="h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <img class="w-full rounded-xl" src="{{ $post->getFeaturedImage() }}" alt="Blog Image">
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <h2 class="text-xl font-semibold mb-2">{{ $post->title }}</h2>
                                <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                                <a href="#" class="mt-auto text-[#FF2D20] hover:underline self-start">Read more...</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>