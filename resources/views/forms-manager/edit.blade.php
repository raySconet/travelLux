<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-server mr-2 text-[#f18325]"></i>{{ __('Customers Forms') }}
            </h2>

            <div class="space-x-2">
                <x-secondary-buttonToDelete><i class="fas fa-trash"></i><span>Delete</span></x-secondary-buttonToDelete>
                <x-secondary-btn><i class="fas fa-save"></i><span>Save User</span></x-secondary-btn>
                <x-primary-btn onclick="window.history.back()"><i class="far fa-minus-square"></i><span>Close User</span></x-primary-btn>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="bg-white shadow rounded-none p-6" x-data="{ section: 'general' }">
            <div class="topButtonsGroup">
                <div class="btn-group systemUsersNav" role="group">
                    <button type="button"  class="systemUsersSectionBtn" :class="{ 'active': section === 'general' }" @click="section = 'general'">
                        <span style="font-size:15px;">General Info</span>
                    </button>
                    <button type="button"  class="systemUsersSectionBtn" :class="{ ' active': section === 'formContent' }" @click="section = 'formContent'">
                        <span style="font-size:15px;">Form Content</span> 
                    </button>
                </div>
            </div>
                <div class="mt-4">
                    <div x-show="section === 'general'" x-cloak>
                        @include('forms-manager.partials.general-info')
                    </div>

                    <div x-show="section === 'formContent'" x-cloak>
                        @include('forms-manager.partials.form-content')
                    </div>

                </div>
        </div>
    </div>
</x-app-layout>