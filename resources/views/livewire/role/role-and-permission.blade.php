<div class="relative overflow-hidden rolespermission-container">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="">
            <div class="border-b border-gray-300 py-8 text-center">
                <h2 class="text-3xl font-medium text-gray-800">
                    Roles and Permissions Management
                </h2>
            </div>
    
            <div class="relative">

                <div class="profileformsec">
                    @can('role create')
                        <livewire:role.create-role />
                        <x-section-border />
                    @endcan
                    @can('permission create')
                        <livewire:role.create-permission />
                        <x-section-border />
                    @endcan
                    @if (auth()->user()->hasRole('Admin'))
                        <livewire:role.assign-permissions-to-role />
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
