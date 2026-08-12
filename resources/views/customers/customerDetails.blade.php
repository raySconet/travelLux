<form method="POST" action="{{ $isNewCustomer ? route('customers.store') : route('customers.update', $customer->id) }}">
    @csrf
    @if(!$isNewCustomer)
        @method('PUT')
    @endif

    <input type="hidden" id="customerId" value="{{ $customer->id }}">
    <x-app-layout>
        <x-slot name="header">
            <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
                <h2 class=" text-xl text-gray-500 leading-tight">
                    <i class="fa-solid fas fa-list mr-2 text-[#B6844A]"></i>{{ __('Customers') }}
                </h2>

                @if($isNewCustomer)
                    <div class="space-x-2">
                        <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save Customer</span></x-secondary-btn>
                        <x-primary-btn type="button" onclick="window.location='{{ route('customers.customerList') }}'"><i class="far fa-minus-square"></i><span>Close Customer</span></x-primary-btn>
                    </div>
                @else    
                    <div class="space-x-2">
                        <x-secondary-buttonToDelete type="button" onclick="openDeleteModal(document.getElementById('deleteCustomersForm'))">
                            <i class="fas fa-trash"></i>
                            <span>Delete</span>
                        </x-secondary-buttonToDelete>
                        <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save Customer</span></x-secondary-btn>
                        <x-primary-btn type="button" onclick="window.location='{{ route('customers.customerList') }}'"><i class="far fa-minus-square"></i><span>Close Customer</span></x-primary-btn>
                    </div>
                @endif    
            </div>
        </x-slot>

        <div class="mx-auto py-2 px-4 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            <div class="p-3 bg-white shadow sm:rounded-lg">
                @include('customers.partials.customer-info')
            </div>

            <div id="customerDetailsSection" class="p-3 bg-white shadow sm:rounded-lg" data-active-tab="{{ old('activeTab', session('activeTab', 'home')) }}" x-data="{ section: '{{ old('activeTab', session('activeTab', 'home')) }}' }">

                <input type="hidden" name="activeTab" :value="section">
                <div class="topButtonsGroup">
                    <div class="btn-group systemUsersNav" role="group">
                        <button type="button" class="systemUsersSectionBtn cursor-pointer" :class="{ 'active': section === 'home' }" @click="section = 'home'">
                            <i title="Home Address" style="font-size:20px;" class="fas fa-map-marker-alt"></i>
                        </button>
                       <button type="button" class="systemUsersSectionBtn customerRewardsBtn cursor-pointer"  :class="{ 'active': section === 'rewards' }" @click="section = 'rewards'">
                            <i title="Rewards" style="font-size:20px;" class="fas fa-trophy"></i>
                        </button>
                        <button type="button" class="systemUsersSectionBtn customerFamilyBtn cursor-pointer" :class="{ 'active': section === 'family' }" @click="section = 'family'">
                            <i title="Family" style="font-size:20px;" class="fas fa-user-friends"></i>
                        </button>
                        <button type="button" class="systemUsersSectionBtn customerFormsBtn cursor-pointer" :class="{ 'active': section === 'forms'}" @click="section = 'forms'">
                            <i title="Forms" style="font-size:20px;" class="fab fa-wpforms"></i>
                        </button>    
                        @if(!$isNewCustomer)
                            <button type="button" class="systemUsersSectionBtn customerSelfServiceInvitationsBtn cursor-pointer" :class="{ 'active': section === 'selfServiceInvitations'}" @click="section = 'selfServiceInvitations'">
                                <i title="Self Service Invitations" style="font-size:20px;" class="fas fa-address-card"></i>
                            </button>  
                        @endif
                        <button type="button" class="systemUsersSectionBtn customerTravelHistoryBtn cursor-pointer" :class="{ 'active': section === 'travelHistory'}" @click="section = 'travelHistory'">
                            <i title="Travel History" style="font-size:20px;" class="fas fa-history"></i>
                        </button>     
                        <button type="button" class="systemUsersSectionBtn customerReferredByBtn cursor-pointer" :class="{ 'active': section === 'referredBy'}" @click="section = 'referredBy'">
                            <i title="Referred By" style="font-size:20px;" class="fas fa-tag"></i>
                        </button> 
                        <button type="button" class="systemUsersSectionBtn customerAutomatedEmailsBtn cursor-pointer" :class="{ 'active': section === 'autoEmails'}" @click="section = 'autoEmails'">
                            <i title="Sent Auto Emails" style="font-size:20px;" class="fas fa-envelope"></i>
                        </button>       
                       <button type="button" class="systemUsersSectionBtn customerGeneralNotesBtn cursor-pointer" :class="{ 'active': section === 'generalNotes'}"  @click="section = 'generalNotes'">
                            <i title="General Notes" style="font-size:20px;" class="fas fa-sticky-note"></i>
                        </button>    
                    </div>
                </div>

                <div class="mt-4">
                    <div x-show="section === 'home'" x-cloak>
                        @include('customers.partials.home-address')
                    </div>

                    <div x-show="section === 'rewards'" x-cloak>

                        <div id="customerRewardsContainer">

                            @if($isNewCustomer)
                                @include('customers.partials.airline-cruises-rewards', [
                                    'customer' => $customer,
                                    'isNewCustomer' => true
                                ])
                            @else
                                <div class="text-center p-4">
                                    Click Rewards to load data
                                </div>
                            @endif

                        </div>

                    </div>

                    <div x-show="section === 'family'" x-cloak>

                        <div id="familyMembersListContainer">
                            <div class="text-center p-4">
                                Click Family to load data
                            </div>
                        </div>

                    </div>

                    <div x-show="section === 'forms'" x-cloak>

                        <div id="formsListContainer">
                            <div class="text-center p-4">
                                Click Forms to load data
                            </div>
                        </div>

                    </div>
                    {{-- <div x-show="section === 'surveys'" x-cloak>
                        @include('customers.partials.surveys')
                    </div>   --}}

                    <div x-show="section === 'selfServiceInvitations'" x-cloak>

                        <div id="invitationsListContainer">
                            <div class="text-center p-4">
                                Click Invitations to load data
                            </div>
                        </div>

                    </div>

                    <div x-show="section === 'autoEmails'" x-cloak>

                        <div id="customerAutomatedEmailsContainer">
                            <div class="text-center p-4">
                                Click Emails to load data
                            </div>
                        </div>

                    </div>

                    <div x-show="section === 'travelHistory'" x-cloak>

                        <div id="travelHistoryContainer">
                            <div class="text-center p-4">
                                Click Travel History to load data
                            </div>
                        </div>

                    </div>

                    <div x-show="section === 'referredBy'" x-cloak>

                        <div id="referredByContainer">

                            @if($isNewCustomer)
                        

                                @include('customers.partials.referredBy', [
                                    'customer' => $customer,
                                    'referralCustomers' => $referralCustomers,
                                    'isNewCustomer' => true
                                ])
                            @else
                                <div class="text-center p-4">
                                    Click Referred By to load data
                                </div>
                            @endif

                        </div>

                    </div>

                    <div x-show="section === 'generalNotes'" x-cloak>

                        <div id="generalNotesContainer">

                            @if($isNewCustomer)
                                @include('customers.partials.generalNotes', [
                                    'customer' => $customer,
                                    'isNewCustomer' => true
                                ])
                            @else
                                <div class="text-center p-4">
                                    Click General Notes to load data
                                </div>
                            @endif

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </x-app-layout>
</form>
@if(!$isNewCustomer)
    <form method="POST" action="{{ route('customers.destroy', $customer->id) }}" id="deleteCustomersForm">
        @csrf
        @method('DELETE')
    </form>

    <form id="deleteFamilyMemberForm" method="POST">
        @csrf
        @method('DELETE')
    </form>
@endif
<x-delete-modal />