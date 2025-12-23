<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">System Users</h2>

            <div class="space-x-2">
                <button class="bg-gray-800 text-white px-4 py-2 rounded space-x-2"><i class="fas fa-trash"></i><span>Delete</span></button>
                <button class="bg-gray-400 text-white px-4 py-2 rounded space-x-2"><i class="fas fa-save"></i><span>Save User</span></button>
                <x-primary-btn class="space-x-2"><i class="far fa-minus-square"></i><span>Close User</span></x-primary-btn>
            </div>
        </div>
    </x-slot>

    <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <div class="bg-white shadow rounded-lg p-6">
            @include('system-users.partials.user-info')
        </div>

        <div class="bg-white shadow rounded-lg p-6" x-data="{ section: 'account' }">
            <div class="topButtonsGroup">
                <div class="btn-group systemUsersNav" role="group">
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'bg-gray-800 text-white': section === 'account' }" @click="section = 'account'">
                        <i class="fas fa-user-circle"></i>
                    </button>
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'bg-gray-800 text-white': section === 'home' }" @click="section = 'home'">
                        <i class="fas fa-map-marker-alt"></i>
                    </button>
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'bg-gray-800 text-white': section === 'notes' }" @click="section = 'notes'">
                        <i class="fas fa-sticky-note"></i>
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