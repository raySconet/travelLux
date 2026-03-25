@php
    $overdueTasksCount = $reservation->tasks()
        ->where('is_deleted', 0)
        ->where('is_completed', 0)
        ->whereDate('due_date', '<=', \Carbon\Carbon::today())
        ->count();
@endphp
<form method="POST"
      action="{{ $isNewReservation
            ? route('reservations.store')
            : route('reservations.update' , $reservation->id)}}">
    @csrf
    @if(!$isNewReservation)
        @method('PUT')
    @endif           
       
    <x-app-layout>
        <x-slot name="header">
            <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
                <h2 class=" text-xl text-gray-500 leading-tight">
                    <i class="fa-solid fas fa-tag mr-2 text-[#f18325]"></i>{{ __('Reservations') }}
                </h2>

                @if($isNewReservation)
                    <div class="space-x-2">
                        <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save Reservation</span></x-secondary-btn>
                        <x-primary-btn type="button" onclick="window.location='{{ route('reservations.reservationList') }}'"><i class="far fa-minus-square"></i><span>Close Reservation</span></x-primary-btn>
                    </div>
                @else
                    <div class="space-x-2">
                        @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('reservations.destroy', $reservation->id )}}" class="inline delete-form">
                                @csrf
                                @method('DELETE')

                                <x-secondary-buttonToDelete type="button" onclick="openDeleteModal(this)">
                                    <i class="fas fa-trash"></i><span>Delete</span>
                                </x-secondary-buttonToDelete>

                            </form>
                        @endif
                        <x-secondary-buttonToDelete type="submit"><i class="fas fa-copy"></i><span>Duplicate</span></x-secondary-buttonToDelete>
                        <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save Reservation</span></x-secondary-btn>
                        <x-primary-btn type="button" onclick="window.location='{{ route('reservations.reservationList') }}'"><i class="far fa-minus-square"></i><span>Close Reservation</span></x-primary-btn>
                    </div>
                @endif    
            </div>
        </x-slot>

        <div class="mx-auto py-2 px-4 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            <div class="p-3 bg-white shadow sm:rounded-lg">
                @include('reservations.partials.reservation-info')
            </div>

            <div class="p-3 bg-white shadow sm:rounded-lg" x-data="{ section: '{{ session('activeTab', 'reservaton-details') }}' }">
                <div class="topButtonsGroup">
                    <div class="btn-group systemUsersNav" role="group">
                        <button type="button" class="systemUsersSectionBtn" :class="{ 'active': section === 'reservaton-details' }" @click="section = 'reservaton-details'">
                            <i style="font-size:20px;" class="fas fa-globe"></i>
                        </button>
                        <button type="button" class="systemUsersSectionBtn relative" :class="{ 'active': section === 'tasks' }" @click="section = 'tasks'">
                            <i style="font-size:20px;" class="fas fa-clock"></i>

                            @if($overdueTasksCount > 0)
                                <span class="absolute -top-2 -right-3 bg-red-500 text-white text-base px-1.5 py-0.250 rounded-full">
                                    {{ $overdueTasksCount }}
                                </span>
                            @endif
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
</form>    
<x-delete-modal />