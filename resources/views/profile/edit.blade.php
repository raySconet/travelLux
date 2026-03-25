<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-user-circle mr-2 text-[#f18325]"></i>{{ __('My Profile') }}
            </h2>
             
            <p>*Please fill in required fields.</p>
            <x-primary-btn class="flex items-center gap-2"><i class="fas fa-save"></i>Save</x-primary-btn>
        </div>
    </x-slot>


    <div class="p-2 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start ml-2 mr-1">
        <div class="bg-white shadow rounded-lg p-2">
            @include('system-users.partials.user-info')
        </div>

        <div class="bg-white shadow rounded-lg p-6" >
                <div class="mt-4">
                    <div>
                        @include('system-users.partials.home-address')
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
