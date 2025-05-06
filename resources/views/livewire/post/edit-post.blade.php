<div x-data="{
        title: @entangle('formData.title'),
        slug: @entangle('formData.slug'),
        meta_description: @entangle('formData.meta_description'),
    }">

<!-- Hero -->
<div class="relative overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-24">
      <div class="text-center">
        <h2 class="text-4xl font-medium text-gray-800">
          Create / Edit Post
        </h2>
        @if($post->status->value === 'published')
            <a href="#" target="_blank" 
            class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-gray-700 dark:text-gray-400 border border-gray-500 ">
                View post
            </a>
        @endif
        @isset($post->status)
            <span
                class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-gray-700 dark:text-gray-400 border border-gray-500 ">
                {{ $post->status }}
            </span>
        @endisset
        @isset($post->publish_attempts)
            <span
                class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-gray-700 dark:text-gray-400 border border-gray-500 ">
                Publish Attempts: {{ $post->publish_attempts }}
            </span>
        @endisset
        @if($post->is_feature == true)
            <span
                class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-gray-700 dark:text-gray-400 border border-gray-500 ">
                Featured
            </span>
        @endif
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
                <div class="mb-4 w-full">
                    <label for="Slug" class="block text-sm font-medium"><span class="sr-only">Slug</span></label>
                    <input type="text" id="Slug" wire:model.defer="formData.slug"   @if(!auth()->user()->hasRole('Admin')) disabled="disabled" @endif  class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="Slug*">
                    <!-- <p class="help-text text-left">This slug cannot be updated</p> -->
                    <div class="text-start">
                        @error('formData.slug')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>    
                
              <div class="mb-4 w-full">
                <label for="meta_title" class="block text-sm font-medium"><span class="sr-only">Meta Title</span></label>

                <input type="text" id="meta_title" name="meta_title" wire:keydown="clearError('formData.meta_title')" wire:model.defer="formData.meta_title" autofocus class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="Meta Title">
                @error('formData.meta_title')
                <div class="text-start">
                    <span class="error">{{ $message }}</span>
                </div>
                @enderror
              </div>
            </div>

           <div class="mb-4">
                <label for="meta_description" class="block text-sm font-medium">
                    <span class="sr-only">Meta Description</span>
                </label>

                <textarea data-enable-grammarly="false" id="meta_description" 
                    wire:keydown="clearError('formData.meta_description')"  
                    class="px-4 py-4 block w-full border-dark-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none resize-none h-28"
                    name="meta_description"
                    x-model="meta_description"
                    wire:model.defer="formData.meta_description"
                    x-init="$nextTick(() => { 
                        $watch('meta_description', value => {
                            $refs.charCount.innerText = value.length;
                        });
                    })"
                    maxlength="{{ getSettingValue('post-description-limit') ?? 150 }}">
                </textarea>

                <p class="help-text text-left">
                    Max Characters (<span x-ref="charCount">{{ strlen($formData['meta_description'] ?? '') }}</span>/{{ getSettingValue('post-description-limit') ?? 150 }})
                </p>

                @error('formData.meta_description')
                    <div class="text-start">
                        <span class="error">{{ $message }}</span>
                    </div>
                @enderror
            </div>


            <div class="mb-4">
                <label for="title" class="block text-sm font-medium"><span class="sr-only">Title</span></label>
                <input type="text" id="title" x-model="title" wire:keydown="clearError('formData.title')" 
                    name="title" wire:model.defer="formData.title" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="Title*">
                @error('formData.title')
                <div class="text-start">
                    <span class="error">{{ $message }}</span>
                </div>
                @enderror
            </div>

            <div class="mb-4 text-left editor">
                <label for="editor" class="block text-sm font-medium"><span class="sr-only">Content</span></label>
                @livewire('quill', ['value' => $this->formData['content']])
                @error('formData.content')
                 <div class="text-start">
                    <span class="error">{{ $message }}</span>
                 </div>
                @enderror
                {{-- <textarea class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" rows="5" placeholder="Content" data-hs-textarea-auto-height=""></textarea> --}}
            </div>

            <div class="mb-4">
                <x-label for="Featured Image " class="text-left block text-sm font-medium">
                    Featured Image
                </x-label>
                @if ($post && $post->featured_image_path)
                    <div class="mt-2 text-left">
                        <img src="{{ Storage::url($post->featured_image_path) }}" alt="Featured Image"
                            class="w-32 h-32 object-cover">
                        <button type="button" wire:click="deleteFeaturedImage"
                            class="text-red-500 bg-gray-200">Delete
                            Image</button>
                    </div>                    
                @else
                    <div class="custom-file-upload">
                        <span id="file-name" class="text-sm text-left" style="color: #061a2c;">
                            {{ $fileName ?? 'Featured Image 1200x686 pixels.' }}
                        </span>
                        <label for="file-input" class="custom-file-button text-xl px-4">Choose File</label>
                        <input type="file" id="file-input" wire:keydown="clearError('featured_image')" name="featured_image" wire:model="featured_image" class="hidden">
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
                    <select multiple="" name="selectedCategories" wire:change="clearError('selectedCategories')" wire:model="selectedCategories" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm "
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
                 <div class="text-start">
                    <span class="error">{{ $message }}</span>
                 </div>
                @enderror
             
            </div>

            <div class="mb-4 text-left">

                <x-label class="text-left" for="selectedTags">
                    Tags
                </x-label>
                <div wire:ignore>
                    <select multiple="" id="selectedTags" name="selectedTags" wire:change="clearError('selectedTags')" wire:model="selectedTags" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm "
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
                            <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                        @endforeach

                    </select>
                </div>
                <!-- End Select -->
                @error('selectedTags')
                 <div class="text-start">
                    <span class="error">{{ $message }}</span>
                 </div>
                @enderror
             </div> 
             <div class="mb-4 text-left">
                <x-label for="status">
                    Status *
                </x-label>

                <div wire:ignore>
                    <select id="status" name="status" wire:model="formData.status"
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
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ $status->value == $formData['status'] ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div
                @error('formData.status')
                <div class="text-start">
                    <span class="error">{{ $message }}</span>
                </div>
                @enderror
            </div>
            
            


                <div>
                    <x-confirmation-modal id="publish-post-modal" maxWidth="lg"
                        wire:model="showPublishPostModal">
                        <x-slot name="title">
                            Publish Post
                        </x-slot>
                        <x-slot name="content">
                            <p class="text-gray-600 mb-6">Publish Post </p>
                            @if($featurePost)
                                <p class="text-green-600 font-semibold">This post is already featured.</p>
                            @endif
                                <div class="mb-4">
                                    <label for="feature_post" class="flex items-center">
                                        <input type="checkbox" wire:model.defer="featurePost" id="feature_post" class="mr-2">
                                        <span>Feature this post</span>
                                    </label>
                                </div>
                           
                        </x-slot>
                        <x-slot name="footer">
                            <x-button wire:click="publishPost" type="button" wire:loading.attr="disabled">
                                <span wire:loading.remove>Publish</span>
                                <span wire:loading>Publishing...</span>
                            </x-button>
                            <x-secondary-button x-on:click="show = false">
                                Cancel
                            </x-secondary-button>
                        </x-slot>
                    </x-confirmation-modal>
                </div>
             {{-- confirmation model --}}
    
            <div class="mt-2">
                <div class="text-right">
                    <button class="mt-4 mb-4 button-bg-gold w-1/4 py-3 px-6 text-sm uppercase font-medium rounded-lg text-white ml-auto" wire:click="showPublishModal">
                        Publish Post
                    </button>
                </div>
                <button type="submit"  onclick="setQuillValue()" class="button-bg-gold w-full py-3 px-6 text-xl uppercase font-medium rounded-lg text-white">Update Post</button>                 
            </div>
            
        </form>
        <!-- End Form -->
        <h2 class="mt-5 font-semibold text-xl text-gray-800 leading-tight">
            Logs
        </h2>

        <livewire:post.post-logs-table :logs="collect($formData['logs'])" />

      </div>
    </div>
  </div>
  </div>
  <!-- End Hero -->
</div>
<script>
    document.addEventListener('livewire:load', function() {
        // Initialize HSSelect components
        const selectElements = document.querySelectorAll('.hs-select');
        selectElements.forEach(function(el) {
            window.HSSelect.init(el);
        });

        // Synchronize selected tags with Livewire component
        const selectedTagsSelect = document.getElementById('selectedTags');
        const hsSelectedTags = window.HSSelect.getInstance(selectedTagsSelect);

        hsSelectedTags.on('change', function() {
            @this.set('selectedTags', hsSelectedTags.getData());
        });
    });

    document.addEventListener('livewire:load', () => {
        // Initialize HSSelect component
        window.HSSelect.init('#selectedTags');

        // Function to set selected tags to Livewire component
        function setSelectedTags() {
            let selectEl = window.HSSelect.getInstance('#selectedTags');
            @this.set('selectedTags', selectEl.getData());
        }

        // Sync HSSelect changes to Livewire
        const hsSelectedTags = window.HSSelect.getInstance('#selectedTags');

        hsSelectedTags.on('change', function() {
            setSelectedTags();
        });

        // Set initial selected tags
        setSelectedTags();
    });

    // Function to set Quill editor value to Livewire component
    function setQuillValue() {
        let value = document.getElementsByClassName('ql-editor')[0].innerHTML;
        let quillText = document.getElementsByClassName('ql-editor')[0].innerText;
        @this.set('formData.content', value);
        @this.set('contentWords', quillText);
    }
    document.getElementById("file-input").addEventListener("change", function() {
        let fileName = this.files[0] ? this.files[0].name : "No file chosen";
        document.getElementById("file-name").textContent = fileName;
    });
</script>