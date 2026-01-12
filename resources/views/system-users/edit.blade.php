<x-app-layout>
    <x-slot name="header">
            <div class="p-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
                <h2 class=" text-2xl text-gray-500 leading-tight">
                    <i class="fa-solid fa-user-circle mr-2 text-[#f18325]"></i>{{ __('System Users') }}
                </h2>

                <div class="space-x-2">
                    <x-secondary-buttonToDelete><i class="fas fa-trash"></i><span>Delete</span></x-secondary-buttonToDelete>
                    <x-secondary-btn><i class="fas fa-save"></i><span>Save User</span></x-secondary-btn>
                    <x-primary-btn><i class="far fa-minus-square"></i><span>Close User</span></x-primary-btn>
                </div>
            </div>
    </x-slot>

    <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <div class="bg-white shadow rounded-lg p-3">
            @include('system-users.partials.user-info')
        </div>

        <div class="bg-white shadow rounded-lg p-6" x-data="{ section: 'account' }">
            <div class="topButtonsGroup">
                <div class="btn-group systemUsersNav" role="group">
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'account' }" @click="section = 'account'">
                        <i style="font-size:20px;" class="fas fa-user-circle"></i>
                    </button>
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'home' }" @click="section = 'home'">
                        <i style="font-size:20px;" class="fas fa-map-marker-alt"></i>
                    </button>
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'notes' }" @click="section = 'notes'">
                        <i style="font-size:20px;" class="fas fa-sticky-note"></i>
                    </button>
                </div>
            </div>
                <div class="mt-4">
                    <div x-show="section === 'account'" x-cloak>
                        @include('system-users.partials.account-details')
                    </div>

                    <div x-show="section === 'home'" x-cloak>
                        @include('system-users.partials.home-address')
                    </div>

                    <div x-show="section === 'notes'" x-cloak>
                        @include('system-users.partials.notes')
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>