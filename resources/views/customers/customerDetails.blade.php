<x-app-layout>
    <x-slot name="header">
            <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
                <h2 class=" text-2xl text-gray-500 leading-tight">
                    <i class="fa-solid fas fa-list mr-2 text-[#f18325]"></i>{{ __('Customers') }}
                </h2>

                <div class="space-x-2">
                    <x-secondary-buttonToDelete><i class="fas fa-trash"></i><span>Delete</span></x-secondary-buttonToDelete>
                    <x-secondary-btn><i class="fas fa-save"></i><span>Save Customer</span></x-secondary-btn>
                    <x-primary-btn onclick="window.history.back()"><i class="far fa-minus-square"></i><span>Close Customer</span></x-primary-btn>
                </div>
            </div>
    </x-slot>

    <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <div class="bg-white shadow rounded-none p-3">
            @include('customers.partials.customer-info')
        </div>

        <div class="bg-white shadow rounded-lg p-6" x-data="{ section: 'home' }">
            <div class="topButtonsGroup">
                <div class="btn-group systemUsersNav" role="group">
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'home' }" @click="section = 'home'">
                        <i style="font-size:20px;" class="fas fa-map-marker-alt"></i>
                    </button>
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'rewards' }" @click="section = 'rewards'">
                        <i style="font-size:20px;" class="fas fa-trophy"></i>
                    </button>
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'family' }" @click="section = 'family'">
                        <i style="font-size:20px;" class="fas fa-user-friends"></i>
                    </button>
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'forms'}"  @click="section = 'forms'">
                        <i style="font-size:20px;" class="fab fa-wpforms"></i>
                    </button>    
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'surveys'}" @click="section = 'surveys'">
                        <i style="font-size:20px;" class="fas fa-comments"></i>
                    </button>   
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'travelHistory'}" @click="section = 'travelHistory'">
                        <i style="font-size:20px;" class="fas fa-history"></i>
                    </button>     
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'referredBy'}" @click="section = 'referredBy'">
                        <i style="font-size:20px;" class="fas fa-tag"></i>
                    </button> 
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'autoEmails'}" @click="section = 'autoEmails'">
                        <i style="font-size:20px;" class="fas fa-envelope"></i>
                    </button>       
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'generalNotes'}" @click="section = 'generalNotes'">
                        <i style="font-size:20px;" class="fas fa-sticky-note"></i>
                    </button>    
                </div>
            </div>
                <div class="mt-4">
                    <div x-show="section === 'home'" x-cloak>
                        @include('customers.partials.home-address')
                    </div>

                    <div x-show="section === 'rewards'" x-cloak>
                        @include('customers.partials.airline-cruises-rewards')
                    </div>

                    <div x-show="section === 'family'" x-cloak>
                        @include('customers.partials.family')
                    </div>

                    <div x-show="section === 'forms'" x-cloak>
                        @include('customers.partials.forms')
                    </div>

                    <div x-show="section === 'surveys'" x-cloak>
                        @include('customers.partials.surveys')
                    </div>    

                    <div x-show="section === 'travelHistory'" x-cloak>
                        @include('customers.partials.travelHistory')
                    </div>    

                    <div x-show="section === 'referredBy'" x-cloak>
                        @include('customers.partials.referredBy')
                    </div>    

                    <div x-show="section === 'autoEmails'" x-cloak>
                        @include('customers.partials.autoEmails')
                    </div>    

                    <div x-show="section === 'generalNotes'" x-cloak>
                        @include('customers.partials.generalNotes')
                    </div>    
                </div>
        </div>
    </div>
</x-app-layout>