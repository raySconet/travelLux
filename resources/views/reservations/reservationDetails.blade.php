<form method="POST" id="reservationForm" enctype="multipart/form-data" action="{{ $isNewReservation ? route('reservations.store') : route('reservations.update', $reservation->id) }}" data-reservation-list-url="{{ route('reservations.reservationList') }}">
    @csrf
    @if(!$isNewReservation)
        @method('PUT')
    @endif        
    
    <input type="hidden" id="reservationId" value="{{ $reservation->id }}">
       
    <x-app-layout>
        <x-slot name="header">
            <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
                <h2 class=" text-xl text-gray-500 leading-tight">
                    <i class="fa-solid fas fa-tag mr-2 text-[#B6844A]"></i>{{ __('Reservations') }}
                </h2>

                @if($isNewReservation)
                    <div class="space-x-2">
                        <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save Reservation</span></x-secondary-btn>
                        <x-primary-btn type="button" onclick="closeReservation()"><i class="far fa-minus-square"></i><span>Close Reservation</span></x-primary-btn>
                    </div>
                @else
                    <div class="space-x-2">
                        <x-secondary-buttonToDelete type="button" onclick="openDeleteModal(document.getElementById('deleteReservationsForm'))">
                            <i class="fas fa-trash"></i>
                            <span>Delete</span>
                        </x-secondary-buttonToDelete>
                        <x-secondary-buttonToDelete type="button" id="duplicateReservationBtn" data-id="{{ $reservation->id }}">
                            <i class="fas fa-copy"></i><span>Duplicate</span>
                        </x-secondary-buttonToDelete>
                        <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save Reservation</span></x-secondary-btn>
                        <x-primary-btn type="button" onclick="closeReservation()"><i class="far fa-minus-square"></i><span>Close Reservation</span></x-primary-btn>
                    </div>
                @endif    
            </div>
        </x-slot>

        <div class="mx-auto py-2 px-4 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            <div class="p-3 bg-white shadow sm:rounded-lg">
                @include('reservations.partials.reservation-info')
            </div>

            <div class="p-3 bg-white shadow sm:rounded-lg" x-data="{ section: localStorage.getItem('reservationActiveTab') || '{{ session('activeTab', 'reservation-details') }}' }" x-init="$watch('section', value => localStorage.setItem('reservationActiveTab', value))">
                <input type="hidden" name="activeTab" :value="section">
                <div class="topButtonsGroup">
                    <div class="btn-group systemUsersNav" role="group">
                        <button type="button" class="systemUsersSectionBtn cursor-pointer" :class="{ 'active': section === 'reservation-details' }" @click="section = 'reservation-details'">
                            <i title="Reservation Details" style="font-size:20px;" class="fas fa-globe"></i>
                        </button>
                        <button type="button" class="systemUsersSectionBtn reservationTasksBtn relative cursor-pointer" :class="{ 'active': section === 'tasks' }" @click="section = 'tasks'">

                            <i title="Tasks" style="font-size:20px;" class="fas fa-clock"></i>

                            @if(!$isNewReservation)
                                @if($overdueTasksCount > 0)
                                    <span class="absolute -top-2 -right-3 bg-red-500 text-white text-base px-1.5 py-0.250 rounded-full">
                                        {{ $overdueTasksCount }}
                                    </span>
                                @endif
                            @endif
                        </button>
                        <button type="button" class="systemUsersSectionBtn reservationPaymentsBtn cursor-pointer" :class="{ 'active': section === 'payments' }" @click="section = 'payments'">
                            <i title="Payment Info" style="font-size:20px;" class="fas fa-credit-card"></i>
                        </button>
                        <button type="button" class="systemUsersSectionBtn reservationOnBoardCreditBtn cursor-pointer" :class="{ 'active': section === 'onBoardCredit'}" @click="section = 'onBoardCredit'">
                            <i title="On Board Credit" style="font-size:20px;" class="fas fa-clipboard"></i>
                        </button>    
                        <button type="button" class="systemUsersSectionBtn reservationLinkedReservationsBtn cursor-pointer" :class="{ 'active': section === 'linkedReservations' }" @click="section = 'linkedReservations'">
                            <i title="Travel With" style="font-size:20px;" class="fas fa-link"></i>
                        </button>
                        <button type="button" class="systemUsersSectionBtn reservationTravelersBtn cursor-pointer" :class="{ 'active': section === 'travelers' }" @click="section = 'travelers'">
                            <i title="Travelers" style="font-size:20px;" class="fas fa-walking"></i>
                        </button>
                        <button type="button" class="systemUsersSectionBtn reservationFormsBtn cursor-pointer" :class="{ 'active': section === 'forms' }" @click="section = 'forms'">
                            <i title="Forms" style="font-size:20px;" class="fab fa-wpforms"></i>
                        </button>
                        <button type="button" class="systemUsersSectionBtn reservationFlightInfoBtn cursor-pointer" :class="{ 'active': section === 'flightInfo'}" @click="section = 'flightInfo'">
                            <i title="Flight Info" style="font-size:20px;" class="fas fa-plane"></i>
                        </button>       
                        <button type="button" class="systemUsersSectionBtn reservationDiningInformationBtn cursor-pointer" :class="{ 'active': section === 'diningInformation'}" @click="section = 'diningInformation'">
                            <i title="Dining Info" style="font-size:20px;" class="fas fa-utensils"></i>
                        </button>   
                        <button type="button" class="systemUsersSectionBtn reservationGiftsBtn cursor-pointer" :class="{ 'active': section === 'giftsInfo'}" @click="section = 'giftsInfo'">
                            <i title="Gifts" style="font-size:20px;" class="fas fa-gift"></i>
                        </button>  
                        <button type="button" class="systemUsersSectionBtn reservationAutoEmailsBtn cursor-pointer" :class="{ 'active': section === 'autoEmails'}" @click="section = 'autoEmails'">
                            <i title="Sent Auto Emails" style="font-size:20px;" class="fas fa-envelope"></i>
                        </button>  
                        <button type="button" class="systemUsersSectionBtn reservationNotesBtn cursor-pointer" :class="{ 'active': section === 'notes'}" @click="section = 'notes'">
                            <i title="Notes" style="font-size:20px;" class="fas fa-sticky-note"></i>
                        </button>  
                        <button type="button" class="systemUsersSectionBtn reservationPhoneNotesBtn cursor-pointer" :class="{ 'active': section === 'phoneNotes'}" @click="section = 'phoneNotes'">
                            <i title="Document Phone Notes" style="font-size:20px;" class="fas fa-phone"></i>
                        </button>    
                        <button type="button" class="systemUsersSectionBtn reservationAgentPaymentsBtn cursor-pointer" :class="{ 'active': section === 'agentPayments'}" @click="section = 'agentPayments'">
                            <i title="Agent Payment Info" style="font-size:20px;" class="fas fa-dollar-sign"></i>
                        </button>        
                        <button type="button" class="systemUsersSectionBtn reservationAttachmentsBtn cursor-pointer" :class="{ 'active': section === 'attachments'}" @click="section = 'attachments'">
                            <i title="Attachments" style="font-size:20px;" class="fas fa-paperclip"></i>
                        </button>   
                        <button type="button" class="systemUsersSectionBtn reservationItineraryBtn cursor-pointer" :class="{ 'active': section === 'selectItineraryTrip'}" @click="section = 'selectItineraryTrip'">
                            <i title="Itinerary Trips" style="font-size:20px;" class="fas fa-ship"></i>
                        </button>     
                    </div>
                </div>

                <div class="mt-4">
                    <div x-show="section === 'reservation-details'" x-cloak>
                        @include('reservations.partials.reservation-details')
                    </div>

                    <div x-show="section === 'tasks'" x-cloak>
                        <div id="reservationTasksContainer">
                            <div class="text-center p-4">
                                Click Tasks to load data
                            </div>
                        </div>
                    </div>

                    <div x-show="section === 'payments'" x-cloak>
                        <div id="reservationPaymentsContainer">
                            <div class="text-center p-4">
                                Click Payment Info to load data
                            </div>
                        </div>
                    </div>

                    <div x-show="section === 'onBoardCredit'" x-cloak>
                        <div id="reservationOnBoardCreditContainer">
                            @if($isNewReservation)
                                @include('reservations.partials.onBoardCredit', [
                                    'reservation' => $reservation,
                                ])
                            @else    
                                <div class="text-center p-4">
                                    Click On Board Credit to load data
                                </div>
                            @endif    
                        </div>
                    </div>

                    <div x-show="section === 'linkedReservations'" x-cloak>
                        <div id="reservationLinkedReservationsContainer">
                            <div class="text-center p-4">
                                Click Travel With to load data
                            </div>
                        </div>
                    </div>

                    <div x-show="section === 'travelers'" x-cloak>
                        <div id="reservationTravelersContainer">
                            <div class="text-center p-4">
                                Click Travelers to load data
                            </div>
                        </div>
                    </div>

                    <div x-show="section === 'forms'" x-cloak>
                        <div id="reservationFormsContainer">
                            <div class="text-center p-4">
                                Click Forms to load data
                            </div>
                        </div>
                    </div>

                    <div x-show="section === 'flightInfo'" x-cloak>
                        <div id="reservationFlightInfoContainer">
                            @if($isNewReservation)
                                @include('reservations.partials.flightInfo', [
                                    'reservation' => $reservation,
                                ])
                            @else    
                                <div class="text-center p-4">
                                    Click Flight Info to load data
                                </div>
                            @endif
                        </div>
                    </div>

                    <div x-show="section === 'diningInformation'" x-cloak>
                        <div id="reservationDiningInformationContainer">
                            <div class="text-center p-4">
                                Click Dining Info to load data
                            </div>
                        </div>
                    </div>

                    <div x-show="section === 'giftsInfo'" x-cloak>
                        <div id="reservationGiftsContainer">
                            <div class="text-center p-4">
                                Click Gifts to load data
                            </div>
                        </div>
                    </div>

                    <div x-show="section === 'autoEmails'" x-cloak>
                        <div id="reservationAutoEmailsContainer">
                            <div class="text-center p-4">
                                Click Sent Auto Emails to load data
                            </div>
                        </div>
                    </div>

                    <div x-show="section === 'notes'" x-cloak>
                        <div id="reservationNotesContainer">
                            @if($isNewReservation)
                                @include('reservations.partials.notes', [
                                    'reservation' => $reservation,
                                ])
                            @else    
                                <div class="text-center p-4">
                                    Click Notes to load data
                                </div>
                            @endif
                        </div>
                    </div>

                    <div x-show="section === 'phoneNotes'" x-cloak>
                        <div id="reservationPhoneNotesContainer">
                            <div class="text-center p-4">
                                Click Phone Notes to load data
                            </div>
                        </div>
                    </div>
                    
                    <div x-show="section === 'agentPayments'" x-cloak>
                        <div id="reservationAgentPaymentsContainer">
                            @if($isNewReservation)
                                @include('reservations.partials.agentPayments', [
                                    'reservation' => $reservation,
                                    'isNewReservation' => true,
                                ])
                            @else    
                                <div class="text-center p-4">
                                    Click Agent Payments to load data
                                </div>
                            @endif    
                        </div>
                    </div>

                    <div x-show="section === 'attachments'" x-cloak>
                        <div id="reservationAttachmentsContainer">
                            <div class="text-center p-4">
                                Click Attachments to load data
                            </div>
                        </div>
                    </div>
                    
                    <div x-show="section === 'selectItineraryTrip'" x-cloak>
                        <div id="reservationItineraryContainer">
                            @if($isNewReservation)
                                @include('reservations.partials.selectItineraryTrip', [
                                    'reservation' => $reservation,
                                    'itineraryTrips' => $itineraryTrips
                                ])
                            @else    
                                <div class="text-center p-4">
                                    Click Itinerary Trips to load data
                                </div>
                            @endif    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</form>    
@if(!$isNewReservation)
    <form method="POST" action="{{ route('reservations.destroy', $reservation->id) }}" id="deleteReservationsForm">
        @csrf
        @method('DELETE')
    </form>
    <form method="POST" id="deleteTaskForm" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    <form method="POST" id="deletePaymentForm" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    <form method="POST" id="deleteDiningNoteForm" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    <form method="POST" id="deleteGiftForm" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    <form method="POST" id="deletePhoneNoteForm" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    <form method="POST" id="deleteCommissionFeeForm" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    <form method="POST" id="deleteAttachmentForm" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
@endif
<x-delete-modal />
<x-reservation-itinerary-attention-modal />