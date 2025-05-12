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