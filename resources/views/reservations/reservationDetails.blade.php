<x-app-layout>
    <x-slot name="header">
            <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
                <h2 class=" text-2xl text-gray-500 leading-tight">
                    <i class="fa-solid fas fa-tag mr-2 text-[#f18325]"></i>{{ __('Reservations') }}
                </h2>

                <div class="space-x-2">
                    <x-secondary-btn><i class="fas fa-save"></i><span>Save Reservation</span></x-secondary-btn>
                    <x-primary-btn onclick="window.history.back()"><i class="far fa-minus-square"></i><span>Close Reservation</span></x-primary-btn>
                </div>
            </div>
    </x-slot>

    <div class="p-2 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <div class="bg-white shadow rounded-none p-3 ml-2">
            @include('reservations.partials.reservation-info')
        </div>

        <div class="bg-white shadow rounded-none p-6" x-data="{ section: 'reservaton-details' }">
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
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'giftsInfo'}" @click="section = 'giftsInfo'">
                        <i style="font-size:20px;" class="fas fa-gift"></i>
                    </button>  
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'autoEmails'}" @click="section = 'autoEmails'">
                        <i style="font-size:20px;" class="fas fa-envelope"></i>
                    </button>  
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'notes'}" @click="section = 'notes'">
                        <i style="font-size:20px;" class="fas fa-sticky-note"></i>
                    </button>  
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'phoneNotes'}" @click="section = 'phoneNotes'">
                        <i style="font-size:20px;" class="fas fa-phone"></i>
                    </button>    
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'agentPayments'}" @click="section = 'agentPayments'">
                        <i style="font-size:20px;" class="fas fa-dollar-sign"></i>
                    </button>        
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'attachments'}" @click="section = 'attachments'">
                        <i style="font-size:20px;" class="fas fa-paperclip"></i>
                    </button>   
                    <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'selectItineraryTrip'}" @click="section = 'selectItineraryTrip'">
                        <i style="font-size:20px;" class="fas fa-ship"></i>
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
                    
                    <div x-show="section === 'giftsInfo'" x-cloak>
                        @include('reservations.partials.giftsInfo')
                    </div> 
                    
                    <div x-show="section === 'autoEmails'" x-cloak>
                        @include('reservations.partials.autoEmails')
                    </div>

                    <div x-show="section === 'notes'" x-cloak>
                        @include('reservations.partials.notes')
                    </div> 

                    <div x-show="section === 'phoneNotes'" x-cloak>
                        @include('reservations.partials.phoneNotes')
                    </div>

                    <div x-show="section === 'agentPayments'" x-cloak>
                        @include('reservations.partials.agentPayments')
                    </div>    

                    <div x-show="section === 'attachments'" x-cloak>
                        @include('reservations.partials.attachments')
                    </div>   
                    
                    <div x-show="section === 'selectItineraryTrip'" x-cloak>
                        @include('reservations.partials.selectItineraryTrip')
                    </div>    
                </div>
        </div>
    </div>
</x-app-layout>