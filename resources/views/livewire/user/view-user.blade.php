<div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('members-management.all') }}" class="inline-flex items-center px-4 py-2">
                ← Back to Member List
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                <!-- User Information -->
                <div class="mb-6">
                    <h2 class="text-3xl font-semibold text-gray-800 mb-4">Member Information</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="flex flex-col">
                            <label class="text-lg font-medium text-gray-600">Name</label>
                            <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">{{ $user->full_name }}</div>
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col">
                            <label class="text-lg font-medium text-gray-600">Email</label>
                            <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">{{ $user->email }}</div>
                        </div>

                        <!-- Role -->
                        <div class="flex flex-col">
                            <label class="text-lg font-medium text-gray-600">Role</label>
                            <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">
                                {{ $user->roles->pluck('name')->implode(', ') }}
                            </div>
                        </div>

                        @if($user->member)
                            <!-- Title -->
                            <div class="flex flex-col">
                                <label class="text-lg font-medium text-gray-600">Title</label>
                                <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">
                                    {{ $user->member->title ?? 'N/A' }}
                                </div>
                            </div>

                            <!-- Date of Birth -->
                            <div class="flex flex-col">
                                <label class="text-lg font-medium text-gray-600">Date of Birth</label>
                                <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">
                                    {{ optional($user->member->dob)->format('Y-m-d') ?? 'N/A' }}
                                </div>
                            </div>

                            <!-- Qualifications -->
                            <div class="flex flex-col">
                                <label class="text-lg font-medium text-gray-600">Qualifications</label>
                                <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">
                                    @if($user->member && $user->member->qualifications->isNotEmpty())
                                        {{ $user->member->qualifications->pluck('name')->implode(', ') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="flex flex-col">
                                <label class="text-lg font-medium text-gray-600">Location</label>
                                <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">{{ $user->member->location ?? 'N/A' }}</div>
                            </div>

                            <!-- Bio -->
                            <div class="flex flex-col sm:col-span-2">
                                <label class="text-lg font-medium text-gray-600">Bio</label>
                                <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">
                                    {{ $user->member->bio ?? 'N/A' }}
                                </div>
                            </div>

                            <!-- Experience (Decoded JSON) -->
                            @php
                                $experiences = json_decode($user->member->experience, true);
                            @endphp
                            @if($experiences && is_array($experiences))
                                <div class="flex flex-col sm:col-span-2">
                                    <label class="text-lg font-medium text-gray-600">Experience</label>
                                    <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700 space-y-2">
                                        @foreach ($experiences as $exp)
                                            <div>
                                                <strong>Hospital:</strong> {{ $exp['hospital'] ?? 'N/A' }}<br>
                                                <strong>From:</strong> {{ $exp['from'] ?? 'N/A' }} -
                                                <strong>To:</strong> {{ $exp['to'] ?? 'N/A' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Uploaded Documents -->
                            <div class="mt-8">
                                <h3 class="text-2xl font-semibold mb-2">Uploaded Documents</h3>

                                @php
                                    $docs = [
                                        'CNIC Copy' => $user->member->cnic_copy ?? null,
                                        'PMDC Licence Copy' => $user->member->pmdc_licence_copy ?? null,
                                        'FCPS Degree Copy' => $user->member->fcps_degree_copy ?? null,
                                    ];
                                @endphp

                                <ul class="list-disc list-inside text-gray-700 space-y-2">
                                    @foreach($docs as $label => $path)
                                        @if($path && Storage::disk('member')->exists($path))
                                            <li>
                                                <strong>{{ $label }}:</strong>
                                                <a href="{{ Storage::disk('member')->url($path) }}" class="text-blue-600 underline" target="_blank">
                                                    View Document
                                                </a>
                                            </li>
                                        @else
                                            <li><strong>{{ $label }}:</strong> Not Uploaded</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Specialities -->
                            @php
                                $specialities = $user->member->specialities;
                            @endphp

                            @if($specialities && is_array($specialities))
                                <div class="flex flex-col sm:col-span-2">
                                    <label class="text-lg font-medium text-gray-600">Specialities</label>
                                    <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">
                                        {{ implode(', ', $specialities) }}
                                    </div>
                                </div>
                            @endif

                            <!-- Certifications -->
                            @php
                                $certifications = json_decode($user->member->certifications, true);
                            @endphp
                            @if($certifications && is_array($certifications))
                                <div class="flex flex-col sm:col-span-2">
                                    <label class="text-lg font-medium text-gray-600">Certifications</label>
                                    <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700 space-y-2">
                                        @foreach ($certifications as $cert)
                                            <div>
                                                <strong>Name:</strong> {{ $cert['name'] ?? 'N/A' }}<br>
                                                @if(!empty($cert['image']))
                                                    <a href="{{ Storage::disk('member')->url($cert['image']) }}" target="_blank" class="text-blue-600 underline">
                                                        View File
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Availability -->
                            @php
                                $availability = $user->member->availability;
                            @endphp

                            @if($availability && is_array($availability))
                                <div class="flex flex-col sm:col-span-2">
                                    <label class="text-lg font-medium text-gray-600">Availability</label>
                                    <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700 space-y-2">
                                        @foreach($availability as $day => $times)
                                            <div>
                                                <strong>{{ ucfirst($day) }}:</strong>
                                                @if(!empty($times['open']) && !empty($times['close']))
                                                    {{ $times['open'] }} - {{ $times['close'] }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col sm:col-span-2">
                                    <label class="text-lg font-medium text-gray-600">Availability</label>
                                    <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700 text-red-500">
                                        Availability not provided.
                                    </div>
                                </div>
                            @endif

                            <!-- Testimonials -->
                            @if($user->member->testimonials && $user->member->testimonials->count())
                                <div class="flex flex-col sm:col-span-2">
                                    <label class="text-lg font-medium text-gray-600">Testimonials</label>
                                    <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700 space-y-4">
                                        @foreach ($user->member->testimonials as $testimonial)
                                            <div class="border p-3 rounded bg-white shadow">
                                                <div><strong>Patient:</strong> {{ $testimonial->patient_name }}</div>
                                                <div><strong>Feedback:</strong> {{ $testimonial->feedback }}</div>
                                                @if($testimonial->patient_image)
                                                    <div>
                                                        <a href="{{ Storage::disk('member')->url($testimonial->patient_image) }}" target="_blank" class="text-blue-600 underline">
                                                            View Image
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
