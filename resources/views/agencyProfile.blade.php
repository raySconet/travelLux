<form method="POST" action="{{ route('agencyProfile.update') }}" id="agencyProfileForm">
    @csrf
    @method('PUT')
    <x-app-layout>
        <x-slot name="header">
            <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
                <h2 class=" text-2xl text-gray-500 leading-tight">
                    <i class="fa-solid fa-id-card mr-2 text-[#f18325]"></i>{{ __('Agency Profile') }}
                </h2>

                <x-primary-btn type="submit" class="flex items-center gap-2"><i class="fas fa-save"></i>Save Profile</x-primary-btn>
            </div>
        </x-slot>

        <div class="bg-white shadow rounded-none p-6 ml-4 mt-2 mr-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div class="relative mt-3">
                    <x-text-input type="text" id="company_name" name="company_name" value="{{ old('company_name', $agencyProfile->company_name ?? '') }}" class="text-[#495057] mt-2" />

                    <x-input-label for="company_name">Company Name</x-input-label>
                </div>

                <div class="relative mt-3">
                    <x-text-input type="text" id="contact_person_name" name="contact_person_name" value="{{ old('contact_person_name', $agencyProfile->contact_person_name ?? '') }}" class="text-[#495057] mt-2" />

                    <x-input-label for="contact_person_name">Contact Person Name</x-input-label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div class="relative mt-3">
                    <x-text-input type="text" id="agency_tag_line" name="agency_tag_line" value="{{ old('agency_tag_line', $agencyProfile->agency_tag_line ?? '') }}"  class="text-[#495057] mt-2" />

                    <x-input-label for="agency_tag_line">Agency Address</x-input-label>
                </div>

                <div class="relative mt-3">
                    <x-text-input type="text" id="new_customer_invite_email_subject" name="new_customer_invite_email_subject" value="{{ old('new_customer_invite_email_subject', $agencyProfile->new_customer_invite_email_subject ?? '') }}"  class="text-[#495057] mt-2" />

                    <x-input-label for="new_customer_invite_email_subject">New Customer Invite Email Subject</x-input-label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div class="relative mt-3">
                    <x-text-input type="text" id="cell_phone_number" name="cell_phone_number" value="{{ old('cell_phone_number', $agencyProfile->cell_phone_number ?? '') }}" class="text-[#495057] mt-2" />

                    <x-input-label for="cell_phone_number">Agency Phone Number</x-input-label>
                </div>

                <div class="relative mt-3">
                    <x-text-input type="text" id="home_phone_number" name="home_phone_number" value="{{ old('home_phone_number', $agencyProfile->home_phone_number ?? '') }}"  class="text-[#495057] mt-2" />

                    <x-input-label for="home_phone_number">IATA</x-input-label>
                </div>
            </div>
        </div>
        
    </x-app-layout>
</form>