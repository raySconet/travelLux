<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-id-card mr-2 text-[#f18325]"></i>{{ __('Agency Profile') }}
            </h2>

            <x-primary-btn class="flex items-center gap-2"><i class="fas fa-save"></i>Save Profile</x-primary-btn>
        </div>
    </x-slot>

    <div class="bg-white shadow rounded-none p-6 ml-3">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div class="relative mt-3">
                <x-text-input type="text" id="companyName" name="companyName"  />

                <x-input-label for="companyName">Company Name</x-input-label>
            </div>

            <div class="relative mt-3">
                <x-text-input type="text" id="contactPersonName" name="contactPersonName"  />

                <x-input-label for="contactPersonName">Contact Person Name</x-input-label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div class="relative mt-3">
                <x-text-input type="text" id="agencyAddress" name="agencyAddress"  />

                <x-input-label for="agencyAddress">Agency Address</x-input-label>
            </div>

            <div class="relative mt-3">
                <x-text-input type="text" id="newCustomerInviteEmail" name="newCustomerInviteEmail"  />

                <x-input-label for="newCustomerInviteEmail">New Customer Invite Email Subject</x-input-label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div class="relative mt-3">
                <x-text-input type="text" id="agencyPhoneNumber" name="agencyPhoneNumber"  />

                <x-input-label for="agencyPhoneNumber">Agency Phone Number</x-input-label>
            </div>

            <div class="relative mt-3">
                <x-text-input type="text" id="IATA" name="IATA"  />

                <x-input-label for="IATA">IATA</x-input-label>
            </div>
        </div>
    </div>
    
</x-app-layout>
