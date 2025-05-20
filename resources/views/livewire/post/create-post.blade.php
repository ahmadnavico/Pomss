<div x-data="{
    title: @entangle('title'),
    slug: '',
    meta_description: @entangle('meta_description'),
}">

    <!-- Hero -->
    <div class="relative overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-24">
            <div class="text-center">
                <h2 class="text-4xl font-medium text-gray-800">
                    Create / Edit Post
                </h2>
                @isset($post->status)
                    <span
                        class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-gray-700 dark:text-gray-400 border border-gray-500 ">
                        {{ $post->status }}
                    </span>
                @endisset
                @isset($post->is_feature)
                    <span
                        class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-gray-700 dark:text-gray-400 border border-gray-500 ">
                        Fetaured
                    </span>
                @endisset
                @isset($post->updated_at)
                    <span
                        class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                        <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z" />
                        </svg>
                        {{ $post->updated_at->diffForHumans() }}
                    </span>
                @endisset

                <div class="mt-7 mx-auto max-w-3xl relative">
                    <!-- Form -->
                    <form method="POST" wire:submit.prevent="savePost" class="otherforms">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-2 w-full">
                            @isset($post->slug)
                                <div class="mb-4 w-full">
                                    <label for="Slug"
                                        class="block text-sm font-medium text-start mb-2"><span>Slug</span></label>
                                    <input type="text" id="Slug" value="{{ $post->slug }}" disabled="disabled"
                                        class="py-3 px-4 block w-full border-dark-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                        placeholder="Slug*">
                                    <p class="help-text text-left">This slug cannot be updated</p>
                                    @error('slug')
                                        <p class="error text-start">{{ $message }}</p>
                                    @enderror
                                </div>
                            @else
                                <div class="mb-4 w-full">
                                    <label for="Slug"
                                        class="block text-sm font-medium text-start mb-2"><span>Slug</span></label>
                                    <input type="text" id="Slug" name="slug" disabled="disabled" x-model="slug"
                                        x-init="$watch('title', value => slug = value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, ''))"
                                        class="py-3 px-4 block w-full border-dark-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                        placeholder="Slug*">
                                    <p class="help-text">Slug can be changed only once when created our review team will
                                        decide
                                        which
                                        slug to use.</p>
                                    @error('slug')
                                        <p class="error text-start">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endisset

                            <div class="mb-4 w-full">
                                <label for="meta_title" class="block text-sm font-medium text-start mb-2"><span>Meta
                                        Title*</span></label>
                                <input type="text" id="meta_title" name="meta_title"
                                    wire:keydown="clearError('meta_title')" wire:model.defer="meta_title" autofocus
                                    class="py-3 px-4 block w-full border-dark-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                    placeholder="Meta Title">
                                @error('meta_title')
                                    <p class="error text-start">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="meta_description" class="block text-sm font-medium text-start mb-2">
                                <span>Meta Description*</span>
                            </label>

                            <textarea id="meta_description" name="meta_description" data-enable-grammarly="false" x-model="meta_description"
                                wire:model.defer="meta_description" wire:keydown="clearError('meta_description')"
                                x-init="$nextTick(() => { 
                                    $watch('meta_description', value => {
                                        $refs.charCount.innerText = value.length;
                                    });
                                })"
                                maxlength="{{ getSettingValue('post-description-limit') ?? 150 }}"
                                class="px-4 py-4 block w-full border-dark-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none resize-none h-28"
                                placeholder="Meta Description"></textarea>
                            <p class="help-text text-left">
                                Max Characters (<span x-ref="charCount">{{ strlen($meta_description ?? '') }}</span>/{{ getSettingValue('post-description-limit') ?? 150 }})
                            </p>

                            @error('meta_description')
                                <p class="error text-start">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="title"
                                class="block text-sm font-medium text-start mb-2"><span>Title*</span></label>
                            <input type="text" id="title" x-model="title" wire:keydown="clearError('title')"
                                name="title" wire:model.defer="title"
                                class="py-3 px-4 block w-full border-dark-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                placeholder="Title*">
                            @error('title')
                                <p class="error text-start">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4 text-left editor">
                            <label for="editor"
                                class="block text-sm font-medium text-start mb-2"><span>Content*</span></label>
                            @livewire('quill', ['value' => $this->content])
                            @error('content')
                                <p class="error text-start">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-label for="Featured Image " class="text-left block text-sm font-medium">
                                Featured Image
                            </x-label>
                            @if ($post && $post->featured_image_path)
                                <div class="mt-2 text-left">
                                    <img src="{{ Storage::url($post->featured_image_path) }}" alt="Featured Image"
                                        class="w-[308px] h-[194px] object-cover border-dark-200">
                                    <button type="button" wire:click="deleteFeaturedImage"
                                        class="text-red-500 bg-dark-200">Delete
                                        Image</button>
                                </div>
                            @else
                                <div class="custom-file-upload border-dark-200" style="border: 1px solid;">
                                    <span id="file-name" class="text-sm text-left" style="color: #061a2c;">
                                        {{ $fileName ?? 'Featured Image 1200x686 pixels.' }}
                                    </span>
                                    <label for="file-input" class="custom-file-button text-xl px-4">Choose File</label>
                                    <input type="file" id="file-input" wire:keydown="clearError('featured_image')"
                                        name="featured_image" wire:model="featured_image" class="hidden border-dark-200">
                                </div>
                                @error('featured_image')
                                    <p class="error text-start">{{ $message }}</p>
                                @enderror
                            @endif


                        </div>

                        <div class="mb-4 texct-left">
                            <x-label class="text-left" for="selectedCategories">
                                Categories *
                            </x-label>
                            <div wire:ignore>
                                <select multiple="" name="selectedCategories"
                                    wire:change="clearError('selectedCategories')" wire:model="selectedCategories"
                                    class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm "
                                    data-hs-select='{
                    "hasSearch": true,
                    "isSearchDirectMatch": false,
                    "searchPlaceholder": "Search...",
                    "searchClasses": "block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3",
                    "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
                    "placeholder": "Select multiple categories...",
                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                    "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",
                    "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                    "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                    "optionTemplate": "<div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div><div class=\"hs-selected:font-semibold text-sm text-gray-800 \" data-title></div></div><div class=\"ms-auto\"><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-4 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" viewBox=\"0 0 16 16\"><path d=\"M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z\"/></svg></span></div></div>",
                    "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                    }'
                                    class="hidden">

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            @error('selectedCategories')
                                <p class="error text-start">{{ $message }}</p>
                            @enderror

                        </div>

                        <div class="mb-4 text-left">

                            <x-label class="text-left" for="selectedTags">
                                Tags*
                            </x-label>
                            <div wire:ignore>
                                <select multiple="" id="selectedTags" wire:change="clearError('selectedTags')"
                                    name="selectedTags" wire:model="selectedTags"
                                    class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm "
                                    data-hs-select='{

                            "placeholder": "Select option...",
                            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                            "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                            "mode": "tags",
                            "dropdownPlacement": "top",
                            "wrapperClasses": "relative ps-0.5 pe-9 min-h-[46px] flex items-center flex-wrap text-nowrap w-full border border-gray-200 rounded-lg text-start text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400",
                            "tagsItemTemplate": "<div class=\"flex flex-nowrap items-center relative z-10 bg-white border border-gray-200 rounded-full p-1 m-1 dark:bg-neutral-900 dark:border-neutral-700 \"><div class=\"size-6 me-1\" data-icon></div><div class=\"whitespace-nowrap text-gray-800 dark:text-neutral-200 \" data-title></div><div class=\"inline-flex shrink-0 justify-center items-center size-5 ms-2 rounded-full text-gray-800 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 text-sm dark:bg-neutral-700/50 dark:hover:bg-neutral-700 dark:text-neutral-400 cursor-pointer\" data-remove><svg class=\"shrink-0 size-3\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M18 6 6 18\"/><path d=\"m6 6 12 12\"/></svg></div></div>",
                            "tagsInputId": "hs-tags-input",
                            "tagsInputClasses": "py-3 px-2 rounded-lg order-1 text-sm outline-none dark:bg-neutral-900 dark:placeholder-neutral-500 dark:text-neutral-400",
                            "optionTemplate": "<div class=\"flex items-center\"><div class=\"size-8 me-2\" data-icon></div><div><div class=\"text-sm font-semibold text-gray-800 dark:text-neutral-200 \" data-title></div><div class=\"text-xs text-gray-500 dark:text-neutral-500 \" data-description></div></div><div class=\"ms-auto\"><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-4 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" viewBox=\"0 0 16 16\"><path d=\"M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z\"/></svg></span></div></div>",
                            "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                            }'
                                    class="hidden">
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->name }}"
                                            {{ in_array($tag->name, $selectedTags) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <!-- End Select -->
                            @error('selectedTags')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- event -->
                         <div>
                            <label class="block text-sm text-left font-medium text-gray-700">Event Type</label>
                            <select wire:model="event_type" wire:change="eventTypeUpdated" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Select Event Type</option>
                                <option value="virtual">Virtual</option>
                                <option value="physical">Physical</option>
                            </select>
                            @error('event_type')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                            {{-- Event For --}}
                            @if($event_type)
                                <div>
                                    <label class="block text-sm text-left font-medium text-gray-700">Event For</label>
                                    <select wire:model="event_for"  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" wire:change="eventForUpdated">
                                        <option value="">Select Event For</option>
                                        <option value="public">Public</option>
                                        <option value="members">Members</option>
                                    </select>
                                </div>
                                
                            @endif

                            {{-- Event Cost --}}
                            @if($event_for)
                                <div>
                                    <label class="block text-sm text-left font-medium text-gray-700">Event Cost</label>
                                    <select wire:model="event_cost" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"  wire:change="eventCostUpdated">
                                        <option value="">Select Cost Type</option>
                                        <option value="free">Free</option>
                                        <option value="paid">Paid</option>
                                    </select>

        
                                </div>
                            @endif

                            {{-- Meeting Link (only for virtual) --}}
                            @if($event_type === 'virtual')
                                <div>
                                    <label class="block text-sm text-left font-medium text-gray-700">Meeting Link</label>
                                    <input type="text" wire:model="meeting_link" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="https://zoom.us/...">

                                </div>
                            @endif

                            {{-- Venue and Entry Code (only for physical) --}}
                            @if($event_type === 'physical')
                                <div>
                                    <label class="block text-sm  text-left font-medium text-gray-700">Venue</label>
                                    <input type="text" wire:model="venue" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Venue address">
        
                                </div>

                                <div>
                                    <label class="block text-sm  text-left  font-medium text-gray-700">Entry Code</label>
                                    <input type="text" wire:model="entry_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="ABC123">
        
                                </div>
                            @endif
                        <!-- event -->
                        {{-- confirmation model --}}
                        <div>
                            <x-confirmation-modal id="publish-post-modal" maxWidth="lg"
                                wire:model="showPublishPostModal">
                                <x-slot name="title">
                                    Publish Post
                                </x-slot>
                                <x-slot name="content">
                                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Important Notice
                                        Before Publishing</h2>
                                    <p class="text-gray-600 mb-4">
                                        Please review the terms carefully before proceeding to publish your
                                        post:
                                    </p>
                                    <!-- <ol class="list-decimal list-inside text-gray-600 mb-4">
                                        <li>
                                            <strong>Plagiarism Check:</strong> Once you publish
                                            your post, it will
                                            automatically undergo AI detection and a plagiarism check to ensure
                                            originality and compliance. After the check, your post will be under review
                                            by our team.
                                        </li>
                                        <li>
                                            <strong>Retry Option:</strong> You Will have 3 tries to publish post within
                                            24 hours.
                                        </li>
                                    </ol> -->
                                    <p class="text-gray-600 mb-6">
                                        By publishing, you agree to these terms. Make sure your content is
                                        original and adheres to our
                                        guidelines to avoid any delays. For more details, refer to our <a
                                            href="#" class="text-blue-500 hover:underline">Content
                                            Guidelines</a>.
                                    </p>
                                    @if ($post)
                                        @if ($post->is_feature)
                                            <p class="text-green-600 font-semibold">This post is already featured.</p>
                                        @else
                                            <div class="mb-4">
                                                <label for="feature_post" class="flex items-center">
                                                    <input type="checkbox" wire:model="featurePost" id="feature_post" class="mr-2">
                                                    <span>Feature this post</span>
                                                </label>
                                            </div>                                       
                                        @endif
                                    @endif


                                </x-slot>
                                <x-slot name="footer">
                                    <x-button wire:click="publishPost" class="text-white" type="button" wire:loading.attr="disabled">
                                        <span wire:loading.remove class="text-white">Publish</span>
                                        <span wire:loading class="text-white">Publishing...</span>
                                    </x-button>
                                    <x-secondary-button x-on:click="show = false">
                                        Cancel
                                    </x-secondary-button>
                                </x-slot>
                            </x-confirmation-modal>
                        </div>
                        {{-- confirmation model --}}


                        <div class="mt-2 flex items-center justify-between gap-4">
                            @if (!$post)
                                <!-- Publish Button -->
                                <button
                                    class="button-bg-gold w-1/4 py-3 px-6 text-sm uppercase font-medium rounded-lg text-white"
                                    onclick="publishPostAndSetQuill()">
                                    Publish Post
                                </button>
                            @elseif($post)
                                @if ($showPublishPostButton)
                                    <!-- Publish Button -->
                                    <button
                                        class="button-bg-gold w-1/4 py-3 px-6 text-sm uppercase font-medium rounded-lg text-white"
                                        onclick="publishPostAndSetQuill()">
                                        Publish Post
                                    </button>
                                @endif
                            @endif
                            <!-- Save Button -->
                            <button type="submit"
                                onclick="setQuillValue()"
                                wire:loading.attr="disabled"
                                class="button-bg-gold w-1/4 py-3 px-6 text-sm uppercase font-medium rounded-lg text-white">
                                
                                <span wire:loading.remove class="text-white">
                                    {{ $post && $post->status->value === 'draft' ? 'Save as Draft' : 'Save Post' }}
                                </span>
                                
                                <span wire:loading class="text-white">
                                    {{ $post && $post->status->value === 'draft' ? 'Saving Draft...' : 'Saving Post...' }}
                                </span>
                            </button>
                        </div>
                        <div class="text-right flex-1">
                            @if ($post)
                                @if ($post->status->value == 'in_review')
                                    <p>Your post is under Review.</p>
                                @elseif($post->publish_attempts > 3)
                                    <p>Your Publish attempts limit reached for 24 hours.</p>
                                @endif
                            @endif
                        </div>
                    </form>
                    <!-- End Form -->
        <script>
            document.addEventListener('livewire:initialized', () => {
            console.log('Livewire initialized - Initializing HSSelect');
            // Initialize all HSSelect instances
            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit(['select']);
            } else {
                console.warn('HSStaticMethods not found - Ensure HSSelect library is loaded');
            }
        });

    // Re-initialize on Livewire updates
    document.addEventListener('livewire:updated', () => {
        console.log('Livewire updated - Reinitializing HSSelect');
        if (window.HSStaticMethods) {
            window.HSStaticMethods.autoInit(['select']);
        } else {
            console.warn('HSStaticMethods not found on update');
        }
    });
                        document.addEventListener('DOMContentLoaded', function() {
                            if (document.querySelector('.ql-editor')) {
                                let quill = Quill.find(document.querySelector('.ql-container'));

                                // // Listen for text changes in the Quill editor
                                // quill.on('text-change', function () {
                                //     setQuillValue();
                                // });
                            }
                        });

                        function publishPostAndSetQuill() {
                            setQuillValue();
                            Livewire.dispatch('showPublishModal');
                        }
                        // Function to set Quill editor value to Livewire component
                        function setQuillValue() {
                            let value = document.getElementsByClassName('ql-editor')[0].innerHTML;
                            //Get Quill Words
                            let quillText = document.getElementsByClassName('ql-editor')[0].innerText;
                            const selectEl = window.HSSelect.getInstance('#selectedTags');
                            // console.log(selectEl.selectedItems);
                            @this.set('selectedTags', selectEl.selectedItems);
                            @this.set('content', value);
                            @this.set('contentWords', quillText);
                            Livewire.dispatch('clearError', {
                                field: 'content'
                            });
                        }
                        document.getElementById("file-input").addEventListener("change", function() {
                            let fileName = this.files[0] ? this.files[0].name : "No file chosen";
                            document.getElementById("file-name").textContent = fileName;
                        });
                        //On Livewire Init set the HSSelect
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero -->



</div>
