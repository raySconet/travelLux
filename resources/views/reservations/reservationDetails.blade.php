<x-app-layout>
    <x-slot name="header">
            <div class="p-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
                <h2 class=" text-2xl text-gray-500 leading-tight">
                    <i class="fa-solid fas fa-tag mr-2 text-[#f18325]"></i>{{ __('Reservations') }}
                </h2>

                <div class="space-x-2">
                    <x-secondary-btn><i class="fas fa-save"></i><span>Save Reservation</span></x-secondary-btn>
                    <x-primary-btn onclick="window.history.back()"><i class="far fa-minus-square"></i><span>Close Reservation</span></x-primary-btn>
                </div>
            </div>
    </x-slot>

    <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <div class="bg-white shadow rounded-lg p-3">
            @include('reservations.partials.reservation-info')
        </div>

        <div class="bg-white shadow rounded-lg p-6" x-data="{ section: 'reservaton-details' }">
            <div class="topButtonsGroup">
                <div class="btn-group systemUsersNav" role="group">
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'reservaton-details' }" @click="section = 'reservaton-details'">
                        <i style="font-size:20px;" class="fas fa-globe"></i>
                    </button>
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'tasks' }" @click="section = 'tasks'">
                        <i style="font-size:20px;" class="fas fa-clock"></i>
                    </button>
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'payments' }" @click="section = 'payments'">
                        <i style="font-size:20px;" class="fas fa-credit-card"></i>
                    </button>
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'onBoardCredit'}"  @click="section = 'onBoardCredit'">
                        <i style="font-size:20px;" class="fas fa-clipboard"></i>
                    </button>    
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'linkedReservations'}" @click="section = 'linkedReservations'">
                        <i style="font-size:20px;" class="fas fa-link"></i>
                    </button>   
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'travelers'}" @click="section = 'travelers'">
                        <i style="font-size:20px;" class="fas fa-walking"></i>
                    </button>     
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'forms'}" @click="section = 'forms'">
                        <i style="font-size:20px;" class="fab fa-wpforms"></i>
                    </button> 
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'flightInfo'}" @click="section = 'flightInfo'">
                        <i style="font-size:20px;" class="fas fa-plane"></i>
                    </button>       
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'diningInformation'}" @click="section = 'diningInformation'">
                        <i style="font-size:20px;" class="fas fa-utensils"></i>
                    </button>    
                </div>
            </div>
                <div class="mt-4">
                    <div x-show="section === 'reservaton-details'" x-cloak>
                        @include('reservations.partials.reservation-details')
                    </div>

                    <div x-show="section === 'tasks'" x-cloak>
                        @include('reservations.partials.tasks')
                    </div>

                    <div x-show="section === 'payments'" x-cloak>
                        @include('reservations.partials.payments')
                    </div>

                    <div x-show="section === 'onBoardCredit'" x-cloak>
                        @include('reservations.partials.onBoardCredit')
                    </div>

                    <div x-show="section === 'linkedReservations'" x-cloak>
                        @include('reservations.partials.linkedReservations')
                    </div>    

                    <div x-show="section === 'travelers'" x-cloak>
                        @include('reservations.partials.travelers')
                    </div>    

                    <div x-show="section === 'forms'" x-cloak>
                        @include('reservations.partials.forms')
                    </div>    

                    <div x-show="section === 'flightInfo'" x-cloak>
                        @include('reservations.partials.flightInfo')
                    </div>    

                    <div x-show="section === 'diningInformation'" x-cloak>
                        @include('reservations.partials.diningInformation')
                    </div>    
                </div>
        </div>
    </div>
</x-app-layout>