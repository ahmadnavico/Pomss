<div>
    <x-form-section submit="save">
        <x-slot name="title">Member Details</x-slot>

        <x-slot name="description">
            Add or update your complete member profile information.
        </x-slot>

        <x-slot name="form">
            <!-- Title -->
            <div class="col-span-6">
                <x-label for="title" value="Title" />
                <x-input id="title" type="text" class="mt-1 block w-full" wire:model.defer="title" />
                <x-input-error for="title" class="mt-2" />
            </div>

            <!-- Date of Birth -->
            <div class="col-span-6">
                <x-label for="dob" value="Date of Birth" />
                <x-input id="dob" type="date" class="mt-1 block w-full"
                        wire:model.defer="dob" />
                <x-input-error for="dob" class="mt-2" />
            </div>

            <!-- Phone Number -->
            <div class="col-span-6">
                <x-label for="phone_number" value="Phone Number" />
                <x-input id="phone_number" type="text" class="mt-1 block w-full" wire:model.defer="phone_number" />
                <x-input-error for="phone_number" class="mt-2" />
            </div>

            <!-- CNIC Copy -->
            <div class="col-span-6">
                <x-label for="cnic_copy" value="CNIC Copy" />

                @if(!$existing_cnic_copy)
                    <x-input id="cnic_copy" type="file" class="mt-1 block w-full" wire:model="cnic_copy" />
                    <x-input-error for="cnic_copy" class="mt-2" />
                @else
                    <div class="mt-2">
                        <strong>Uploaded Document:</strong>
                        <a href="{{ Storage::disk('member')->url($existing_cnic_copy) }}" target="_blank" class="text-blue-500 underline block">
                            {{ basename($existing_cnic_copy) }}
                        </a>
                        <button type="button" wire:click="deleteFile('cnic_copy')" class="text-red-600 mt-1">Delete</button>
                    </div>
                @endif
            </div>



            <!-- Repeat for PMDC Licence Copy -->
            <div class="col-span-6">
                <x-label for="pmdc_licence_copy" value="PMDC Licence Copy" />

                @if(!$existing_pmdc_licence_copy)
                    <x-input id="pmdc_licence_copy" type="file" class="mt-1 block w-full" wire:model="pmdc_licence_copy" />
                    <x-input-error for="pmdc_licence_copy" class="mt-2" />
                @else
                    <div class="mt-2">
                        <strong>Uploaded Document:</strong>
                        <a href="{{ Storage::disk('member')->url($existing_pmdc_licence_copy) }}" target="_blank" class="text-blue-500 underline block">
                            {{ basename($existing_pmdc_licence_copy) }}
                        </a>
                        <button type="button" wire:click="deleteFile('pmdc_licence_copy')" class="text-red-600 mt-1">Delete</button>
                    </div>
                @endif
            </div>



            <!-- Repeat for FCPS Degree Copy -->
            <div class="col-span-6">
                <x-label for="fcps_degree_copy" value="FCPS Degree Copy" />

                @if(!$existing_fcps_degree_copy)
                    <x-input id="fcps_degree_copy" type="file" class="mt-1 block w-full" wire:model="fcps_degree_copy" />
                    <x-input-error for="fcps_degree_copy" class="mt-2" />
                @else
                    <div class="mt-2">
                        <strong>Uploaded Document:</strong>
                        <a href="{{ Storage::disk('member')->url($existing_fcps_degree_copy) }}" target="_blank" class="text-blue-500 underline block">
                            {{ basename($existing_fcps_degree_copy) }}
                        </a>
                        <button type="button" wire:click="deleteFile('fcps_degree_copy')" class="text-red-600 mt-1">Delete</button>
                    </div>
                @endif
            </div>


            <div class="col-span-6">
                <x-label class="text-left" for="selectedQualifications">
                    Qualifications*
                </x-label>

                <div wire:ignore>
                    <!-- Multi Select -->
                    <select multiple="" id="selectedQualifications"
                            name="selectedQualifications"
                            wire:model="selectedQualifications"
                            class="hidden"
                            data-hs-select='{
                                "placeholder": "Select option...",
                                "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                                "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                                "mode": "tags",
                                "wrapperClasses": "relative ps-0.5 pe-9 min-h-[46px] flex items-center flex-wrap text-nowrap w-full border border-gray-200 rounded-lg text-start text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400",
                                "tagsItemTemplate": "<div class=\"flex flex-nowrap items-center relative z-10 bg-white border border-gray-200 rounded-full p-1 m-1 dark:bg-neutral-900 dark:border-neutral-700 \"><div class=\"size-6 me-1\" data-icon></div><div class=\"whitespace-nowrap text-gray-800 dark:text-neutral-200 \" data-title></div><div class=\"inline-flex shrink-0 justify-center items-center size-5 ms-2 rounded-full text-gray-800 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 text-sm dark:bg-neutral-700/50 dark:hover:bg-neutral-700 dark:text-neutral-400 cursor-pointer\" data-remove><svg class=\"shrink-0 size-3\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M18 6 6 18\"/><path d=\"m6 6 12 12\"/></svg></div></div>",
                                "tagsInputId": "hs-tags-input",
                                "tagsInputClasses": "py-3 px-2 rounded-lg order-1 text-sm outline-none dark:bg-neutral-900 dark:placeholder-neutral-500 dark:text-neutral-400",
                                "optionTemplate": "<div class=\"flex items-center\"><div class=\"size-8 me-2\" data-icon></div><div><div class=\"text-sm font-semibold text-gray-800 dark:text-neutral-200 \" data-title></div><div class=\"text-xs text-gray-500 dark:text-neutral-500 \" data-description></div></div><div class=\"ms-auto\"><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-4 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" viewBox=\"0 0 16 16\"><path d=\"M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z\"/></svg></span></div></div>",
                                "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                            }'>
                        @foreach ($allQualifications as $qualification)
                            <option value="{{ $qualification->name }}"
                                    @if(in_array($qualification->name, $selectedQualifications)) selected @endif>
                                {{ $qualification->name }}
                            </option>
                        @endforeach
                    </select>
                    <!-- End Select -->

                    @error('selectedQualifications')
                    <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            
            <!-- Certifications -->
            <div class="col-span-6">
                <x-label value="Certifications" />

                @foreach($certifications as $index => $certification)
                    <div class="flex items-center space-x-4 mb-2">
                        <!-- Certificate Name -->
                        <input type="text"
                            class="w-1/2"
                            wire:model.defer="certifications.{{ $index }}.name"
                            placeholder="Certificate Name" />

                        <!-- Show existing image link if available -->
                        @if(isset($certification['image']) && is_string($certification['image']))
                            <a href="{{ Storage::disk('member')->url($certification['image']) }}"
                            target="_blank"
                            class="text-sm text-blue-600 underline">
                                View File
                            </a>
                            <!-- Delete File Button -->
                            <button type="button"
                                    wire:click="deleteCertificationFile({{ $index }})"
                                    class="text-red-500 text-sm ml-2">
                                Delete File
                            </button>
                        @else
                            <!-- Show file input only if image not uploaded yet -->
                            <input type="file" wire:model="certifications.{{ $index }}.image" />
                        @endif

                        <!-- Remove Button -->
                        <button type="button"
                                wire:click="removeCertification({{ $index }})"
                                class="text-red-600">
                            Remove
                        </button>
                    </div>
                @endforeach


                <!-- Add Certification Button -->
                <button type="button" wire:click="addCertification" class="mt-2 px-3 py-1 bg-gray-200 rounded">+ Add Certification</button>

                <!-- Input Error Message for Certifications -->
                <x-input-error for="certifications" class="mt-2" />
            </div>



            <!-- Experience -->
            <<div class="col-span-6">
                <x-label value="Experience (Hospital & Duration)" />

                @foreach($experience as $index => $exp)
                    <div class="flex items-center space-x-4 mb-2">
                        <!-- Hospital Name -->
                        <input type="text" class="w-1/3" placeholder="Hospital Name"
                            wire:model.defer="experience.{{ $index }}.hospital" />

                        <!-- From Year -->
                        <input type="text" class="w-1/4" placeholder="From (e.g. 2018)"
                            wire:model.defer="experience.{{ $index }}.from" />

                        <!-- To Year -->
                        <input type="text" class="w-1/4" placeholder="To (e.g. 2022 or Present)"
                            wire:model.defer="experience.{{ $index }}.to" />

                        <!-- Remove Button -->
                        <button type="button" wire:click="removeExperience({{ $index }})"
                            class="text-red-600">Remove</button>
                    </div>
                @endforeach

                <button type="button" wire:click="addExperience"
                    class="mt-2 px-3 py-1 bg-gray-200 rounded">+ Add Experience</button>

                <x-input-error for="experience" class="mt-2" />
            </div>


            <div class="col-span-6">
                <x-label value="Specialities" />

                @foreach($specialities as $index => $speciality)
                    <div class="flex items-center space-x-4 mb-2">
                        <input type="text"
                            class="w-full"
                            wire:model.defer="specialities.{{ $index }}"
                            placeholder="Enter speciality" />
                        <button type="button"
                                wire:click="removeSpeciality({{ $index }})"
                                class="text-red-600">
                            Remove
                        </button>
                    </div>
                @endforeach

                <button type="button"
                        wire:click="addSpeciality"
                        class="mt-2 px-3 py-1 bg-gray-200 rounded">
                    + Add Speciality
                </button>

                <x-input-error for="specialities" class="mt-2" />
            </div>


            <!-- Bio -->
            <div class="col-span-6">
                <x-label for="bio" value="Bio" />
                <textarea id="bio" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" wire:model.defer="bio"></textarea>
                <x-input-error for="bio" class="mt-2" />
            </div>

            <!-- Location -->
            <div class="col-span-6">
                <x-label for="location" value="Location" />
                <x-input id="location" type="text" class="mt-1 block w-full" wire:model.defer="location" />
                <x-input-error for="location" class="mt-2" />
            </div>

            <!-- Social Links Section -->
            <div class="col-span-6">
                <x-label for="social_links" value="Social Links" />

                @foreach ($socialLinks as $index => $link)
                    <div class="flex space-x-2 items-center mb-2">
                        <x-input
                            type="text"
                            class="w-1/3"
                            placeholder="Platform (e.g., Facebook)"
                            wire:model.defer="socialLinks.{{ $index }}.platform"
                        />
                        <x-input
                            type="text"
                            class="w-2/3"
                            placeholder="https://example.com/yourprofile"
                            wire:model.defer="socialLinks.{{ $index }}.url"
                        />
                        <button type="button" wire:click="removeSocialLink({{ $index }})" class="text-red-500 hover:text-red-700">✕</button>
                    </div>
                @endforeach

                <button type="button" wire:click="addSocialLink" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Add Social Link
                </button>

                <x-input-error for="socialLinks" class="mt-2" />
            </div>


            <!-- Availability -->
            <div class="col-span-6">
                <x-label value="Availability (Open/Close Times)" />
                <div class="grid grid-cols-1 gap-4">
                    @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                        <div class="grid grid-cols-3 items-center gap-2">
                            <x-label class="capitalize">{{ $day }}</x-label>
                            <x-input type="time" wire:model.defer="availability.{{ $day }}.open" />
                            <x-input type="time" wire:model.defer="availability.{{ $day }}.close" />
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- testimonials -->

            <div class="col-span-6">
                <x-label value="Testimonials" />

                @foreach($testimonials as $index => $testimonial)
                    <div class="mb-4 border p-4 rounded-md space-y-2">
                    @if(isset($testimonial['patient_image']) && is_string($testimonial['patient_image']))
                            <image src="{{ Storage::disk('member')->url($testimonial['patient_image']) }}"
                            target="_blank"
                            class="text-sm text-blue-600 underline">
                            </image>
                        @else
                            <input type="file" wire:model="testimonials.{{ $index }}.patient_image" />
                        @endif    
                        <input type="text"
                            class="w-full"
                            placeholder="Patient Name"
                            wire:model.defer="testimonials.{{ $index }}.patient_name" />

                        <textarea
                            class="w-full"
                            placeholder="Feedback"
                            wire:model.defer="testimonials.{{ $index }}.feedback">
                        </textarea>

                        <button type="button"
                                wire:click="removeTestimonial({{ $index }})"
                                class="text-red-600">
                            Remove
                        </button>
                    </div>
                @endforeach

                <button type="button"
                        wire:click="addTestimonial"
                        class="mt-2 px-3 py-1 bg-gray-200 rounded">
                    + Add Testimonial
                </button>
            </div>
            <!-- testimonials -->

        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button wire:loading.attr="disabled" wire:target="save">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-form-section>


    <script>
    function syncHSSelectToLivewire() {
        const selectEl = document.querySelector('#selectedQualifications');
        if (!selectEl) return;

        setTimeout(() => {
            const hsSelectInstance = window.HSSelect.getInstance(selectEl);

            if (!hsSelectInstance) {
                console.error('HSSelect instance not found.');
                return;
            }

            const extractValue = (item) =>
                typeof item === 'string' ? item : item?.value ?? item?.id ?? item?.title ?? item?.label ?? '';

            const syncValues = () => {
                const values = hsSelectInstance.selectedItems.map(extractValue).filter(Boolean);
                console.log("Syncing tags:", values);
                @this.call('updateSelectedQualifications', values);
            };

            // Initial sync
            syncValues();

            // Prevent duplicate listeners
            selectEl.removeEventListener('change', syncValues);
            selectEl.addEventListener('change', syncValues);
        }, 300);
    }

    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized - Initializing HSSelect');
        if (window.HSStaticMethods) {
            window.HSStaticMethods.autoInit(['select']);
        }
        syncHSSelectToLivewire();
    });

    document.addEventListener('livewire:updated', () => {
        if (window.HSStaticMethods) {
            window.HSStaticMethods.autoInit(['select']);
        }
        syncHSSelectToLivewire();
    });
</script>


</div>