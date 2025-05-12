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
                <x-label value="Qualifications" />

                @foreach($qualifications as $index => $qualification)
                    <div class="flex items-center space-x-4 mb-2">
                        <input type="text"
                            class="w-full"
                            wire:model.defer="qualifications.{{ $index }}"
                            placeholder="Enter Qualification" />
                        <button type="button"
                                wire:click="removeQualification"{{ $index }})"
                                class="text-red-600">
                            Remove
                        </button>
                    </div>
                     {{-- Show the error below the input --}}
                    @error('qualifications.*') 
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                @endforeach

                <button type="button"
                        wire:click="addQualification"
                        class="mt-2 px-3 py-1 bg-gray-200 rounded">
                    + Add qualifications
                </button>

                <x-input-error for="specialities" class="mt-2" />
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
            <div class="col-span-6">
                <x-label value="Experience (Hospital & Duration)" />

                @foreach($experience as $index => $exp)
                    <div class="flex items-center space-x-4 mb-2">
                        <!-- Hospital Name -->
                        <div class="w-1/3">
                            <input type="text" class="w-full" placeholder="Hospital Name"
                                wire:model.defer="experience.{{ $index }}.hospital" />
                            @error('experience.' . $index . '.hospital') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- From Year -->
                        <div class="w-1/4">
                            <input type="text" class="w-full" placeholder="From (e.g. 2018)"
                                wire:model.defer="experience.{{ $index }}.from" />
                            @error('experience.' . $index . '.from') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- To Year -->
                        <div class="w-1/4">
                            <input type="text" class="w-full" placeholder="To (e.g. 2022 or Present)"
                                wire:model.defer="experience.{{ $index }}.to" />
                            @error('experience.' . $index . '.to') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

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
                     {{-- Show the error below the input --}}
                    @error('specialities.*') 
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
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
                <x-label value="Availability" class="mb-2" />

                @foreach($availability as $index => $slot)
                    <div class="grid grid-cols-4 gap-4 items-center mb-3 border rounded p-4 bg-gray-50">
                        {{-- Day Selector --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Day</label>
                            <select wire:model.defer="availability.{{ $index }}.day" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                <option value="">Select Day</option>
                                @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                                    <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Open Time --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Open Time</label>
                            <input type="time" wire:model.defer="availability.{{ $index }}.open" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                        </div>

                        {{-- Close Time --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Close Time</label>
                            <input type="time" wire:model.defer="availability.{{ $index }}.close" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                        </div>

                        {{-- Remove Button --}}
                        <div class="flex items-end">
                            <button type="button"
                                    wire:click="removeAvailabilitySlot({{ $index }})"
                                    class="text-red-600 text-sm underline">
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach

                {{-- Add New Availability Button --}}
                <button type="button"
                        wire:click="addAvailabilitySlot"
                        class="mt-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded shadow hover:bg-blue-700">
                    + Add Availability
                </button>
                <x-input-error for="availability" class="mt-2" />
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
                <x-input-error for="testimonials" class="mt-2" />
            </div>
            <!-- testimonials -->

        </x-slot>
        @if(!auth()->user()->hasRole('Admin') && $profile_approved)
        
            <x-slot name="actions">
                <x-action-message class="me-3" on="saved">
                    {{ __('Saved.') }}
                </x-action-message>

                <x-button wire:loading.attr="disabled" wire:target="save">
                    {{ __('Save') }}
                </x-button>
            </x-slot>
        @else
        
        <x-slot name="actions">
            <P>your are not allowed to do any changes your profile approval is in under process.</P>

            <x-action-message class="me-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button wire:loading.attr="disabled" wire:target="save">
                {{ __('Request for Changes') }}
            </x-button>
        </x-slot>
    
        @endif
    </x-form-section>

    
</div>