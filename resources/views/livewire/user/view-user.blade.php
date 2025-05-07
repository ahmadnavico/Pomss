<div>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('members-management.all') }}"
                class="inline-flex items-center px-4 py-2">
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
                                <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">{{ $user->name }}</div>
                            </div>

                            <!-- Email -->
                            <div class="flex flex-col">
                                <label class="text-lg font-medium text-gray-600">Email</label>
                                <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">{{ $user->email }}</div>
                            </div>

                            <!-- User Role -->
                            <div class="flex flex-col">
                                <label class="text-lg font-medium text-gray-600">Role</label>
                                <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">
                                    {{ $user->roles->pluck('name')->implode(', ') }}
                                </div>
                            </div>
                            @if($user->member)
                                <div class="flex flex-col">
                                    <label class="text-lg font-medium text-gray-600">Experience</label>
                                    <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">{{ $user->member->experience }}</div>
                                </div>

                                <!-- Location -->
                                <div class="flex flex-col">
                                    <label class="text-lg font-medium text-gray-600">Location</label>
                                    <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700">{{ $user->member->location }}</div>
                                </div>

                                <!-- Bio -->
                                <div class="flex flex-col sm:col2">
                                    <label class="text-lg font-medium text-gray-600">Bio</label>
                                    <div class="bg-gray-100 p-4 rounded-md text-lg text-gray-700 ">
                                        {{ $user->member->bio }}
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
