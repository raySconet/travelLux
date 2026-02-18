<div>
    <div class="flex flex-col gap-1">
        <label for="agents" class="text-sm">
            Select Agent
        </label>
        <select name="agents" id="agents" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
            <option value="-1">All Agents</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">
                    {{ $user->name }}
                </option>
            @endforeach        
        </select>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-5 flex items-end gap-3">
            <button type="button" class="text-[#f18325] text-2xl flex-shrink-0">
                <i class="fas fa-user-plus"></i>
            </button>

            <div class="flex-1">
                <label for="customer" class="text-sm block mb-1">Customer</label>
                <select
                    name="customer"
                    id="customer"
                    class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1"
                >
                    <option value="-1">All Customers</option>
                </select>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="reservation_number" name="reservation_number"  value="{{ old('reservation_number', $reservation->reservation_number ?? '') }}"/>

            <x-input-label for="reservation_number">Reservation Number</x-input-label>
            <span class="text-sm">WDW room only travel plan only</span>
        </div>

        <div class="relative mt-9">
            <x-text-input type="text" id="group_phone" name="group_phone"  value="{{ old('group_number', $reservation->group_number ?? '') }}"/>

            <x-input-label for="group_phone">Group Phone</x-input-label>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="special_offer" class="text-sm block mb-1">Special Offers</label>
            <select name="special_offer"id="special_offer" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Offer--</option>
                <option value="Free at Sea" {{ old('special_offer', $reservation->special_offer ?? '') == 'Free at Sea' ? 'selected' : '' }}>Free at Sea</option>
                <option value="Future Cruise Credit" {{ old('special_offer', $reservation->special_offer ?? '') == 'Future Cruise Credit' ? 'selected' : '' }}>Future Cruise Credit</option>
                <option value="Summer Recovery Dine Offer" {{ old('special_offer', $reservation->special_offer ?? '') == 'Summer Recovery Dine Offer' ? 'selected' : '' }}>Summer Recovery Dine Offer</option>
                <option value="Sun and Fun" {{ old('special_offer', $reservation->special_offer ?? '') == 'Sun and Fun' ? 'selected' : '' }}>Sun and Fun</option>
                <option value="Annual Passholder" {{ old('special_offer', $reservation->special_offer ?? '') == 'Annual Passholder' ? 'selected' : '' }}>Annual Passholder</option>
                <option value="Bounceback Offer" {{ old('special_offer', $reservation->special_offer ?? '') == 'Bounceback offer' ? 'selected' : '' }}>Bounceback Offer</option>
                <option value="Disney Visa Cardholder" {{ old('special_offer', $reservation->special_offer ?? '') == 'Disney Visa Cardholder' ? 'selected' : '' }}>Disney Visa Cardholder</option>
                <option value="Disney Wedding Group" {{ old('special_offer', $reservation->special_offer ?? '') == 'Disney Wedding Group' ? 'selected' : '' }}>Disney Wedding Group</option>
                <option value="Event Voucher" {{ old('special_offer', $reservation->special_offer ?? '') == 'Event Voucher' ? 'selected' : '' }}>Event Voucher</option>
                <option value="FL Resident" {{ old('special_offer', $reservation->special_offer ?? '') == 'FL Resident' ? 'selected' : '' }}>FL Resident</option>
                <option value="Free Dining" {{ old('special_offer', $reservation->special_offer ?? '') == 'Free Dining' ? 'selected' : '' }}>Free Dining</option>
                <option value="Gift of Magic" {{ old('special_offer', $reservation->special_offer ?? '') == 'Gift of Magic' ? 'selected' : '' }}>Gift of Magic</option>
                <option value="Gift Vacation Package" {{ old('special_offer', $reservation->special_offer ?? '') == 'Gift Vacation Package' ? 'selected' : '' }}>Gift Vacation Package</option>
                <option value="Group" {{ old('special_offer', $reservation->special_offer ?? '') == 'Group' ? 'selected' : '' }}>Group</option>
                <option value="Kids Sail Free" {{ old('special_offer', $reservation->special_offer ?? '') == 'Kids Sail Free' ? 'selected' : '' }}>Kids Sail Free</option>
                <option value="Military" {{ old('special_offer', $reservation->special_offer ?? '') == 'Military' ? 'selected' : '' }}>Military</option>
                <option value="No Sale" {{ old('special_offer', $reservation->special_offer ?? '') == 'No Sale' ? 'selected' : '' }}>No Sale</option>
                <option value="Onboard Booking" {{ old('special_offer', $reservation->special_offer ?? '') == 'Onboard Booking' ? 'selected' : '' }}>Onboard Booking</option>
                <option value="Package Sale" {{ old('special_offer', $reservation->special_offer ?? '') == 'Package Sale' ? 'selected' : '' }}>Package Sale</option>
                <option value="PIN Code" {{ old('special_offer', $reservation->special_offer ?? '') == 'PIN code' ? 'selected' : '' }}>PIN Code</option>
                <option value="Room Only/Broad Offer" {{ old('special_offer', $reservation->special_offer ?? '') == 'Room Only/Broad offer' ? 'selected' : '' }}>Room Only/Broad Offer</option>
                <option value="Run Disney" {{ old('special_offer', $reservation->special_offer ?? '') == 'Run Disney' ? 'selected' : '' }}>Run Disney</option>
                <option value="RunDisney Charity" {{ old('special_offer', $reservation->special_offer ?? '') == 'RunDisney Charity' ? 'selected' : '' }}>RunDisney Charity</option>
                <option value="Stay, Play and Dine" {{ old('special_offer', $reservation->special_offer ?? '') == 'Stay, Play and Dine' ? 'selected' : '' }}>Stay, Play and Dine</option>
                <option value="Travel Agent Rate" {{ old('special_offer', $reservation->special_offer ?? '') == 'Travel Agent Rate' ? 'selected' : '' }}>Travel Agent Rate</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="reservation_name" name="reservation_name"  value="{{ old('reservation_name', $reservation->reservation_name ?? '') }}"/>

            <x-input-label for="reservation_name">Reservation Name</x-input-label>
            <span class="text-sm">For hotel only put hotel name first</span>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="status" class="text-sm block mb-1">Status</label>
            <select name="status"id="status" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="Active" {{ old('status', $reservation->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Prospect" {{ old('status', $reservation->status ?? '') == 'Prospect' ? 'selected' : '' }}>Prospect</option>
                <option value="Duplicate" {{ old('status', $reservation->status ?? '') == 'Duplicate' ? 'selected' : '' }}>Duplicate</option>
                <option value="Canceled" {{ old('status', $reservation->status ?? '') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                <option value="Paid in Full" {{ old('status', $reservation->status ?? '') == 'Paid in Full' ? 'selected' : '' }}>Paid in Full</option>
                <option value="Claimed" {{ old('status', $reservation->status ?? '') == 'Claimed' ? 'selected' : '' }}>Claimed</option>
                <option value="On Hold" {{ old('status', $reservation->status ?? '') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                <option value="Canceled w/ Insurance Payout" {{ old('status', $reservation->status ?? '') == 'Canceled w/ Insurance Payout' ? 'selected' : '' }}>Canceled w/ Insurance Payout</option>
                <option value="Canceled - Commission Protected" {{ old('status', $reservation->status ?? '') == 'Canceled - Commission Protected' ? 'selected' : '' }}>Canceled - Commission Protected</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-8">
            <label for="celebrations" class="text-sm block mb-1">Celebrations</label>
            <select name="celebrations"id="celebrations" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Celebrations--</option>
                <option value="Anniversary" {{ old('celebrations', $reservation->celebrations ?? '') == 'Anniversary' ? 'selected' : '' }}>Anniversary</option>
                <option value="Birthday" {{ old('celebrations', $reservation->celebrations ?? '') == 'Birthday' ? 'selected' : '' }}>Birthday</option>
                <option value="Family Reunion" {{ old('celebrations', $reservation->celebrations ?? '') == 'Family Reunion' ? 'selected' : '' }}>Family Reunion</option>
                <option value="First Visit" {{ old('celebrations', $reservation->celebrations ?? '') == 'First Visit' ? 'selected' : '' }}>First Visit</option>
                <option value="Graduation" {{ old('celebrations', $reservation->celebrations ?? '') == 'Graduation' ? 'selected' : '' }}>Graduation</option>
                <option value="Honeymoon" {{ old('celebrations', $reservation->celebrations ?? '') == 'Honeymoon' ? 'selected' : '' }}>Honeymoon</option>
                <option value="Life Celebration" {{ old('celebrations', $reservation->celebrations ?? '') == 'Life Celebration' ? 'selected' : '' }}>Life Celebration</option>
                <option value="Retirement" {{ old('celebrations', $reservation->celebrations ?? '') == 'Retirement' ? 'selected' : '' }}>Retirement</option>
                <option value="Summer Family Vacation" {{ old('celebrations', $reservation->celebrations ?? '') == 'Summer Family Vacation' ? 'selected' : '' }}>Summer Family Vacation</option>
                <option value="Wedding" {{ old('celebrations', $reservation->celebrations ?? '') == 'Wedding' ? 'selected' : '' }}>Wedding</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="celebration_notes" name="celebration_notes"  value="{{ old('celebration_notes', $reservation->celebration_notes ?? '') }}"/>

            <x-input-label for="celebration_notes">Celebrations Notes</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div x-data="dateDropdown('{{ old('checkin_date', $reservation->checkin_date ?? '') }}')" class="relative mt-5">
            <label class="block text-sm mb-1">Checkin Date</label>
            <div class="flex w-full border-b-2 border-[#bdbdbd] overflow-hidden outline-none">
                <select x-model="year" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Year</option>
                    <template x-for="y in years" :key="y">
                        <option :value="y" x-text="y" :selected="year == y"></option>
                    </template>
                </select>

                <select x-model="month" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Month</option>
                    <template x-for="(m, i) in months" :key="i">
                        <option :value="i + 1" x-text="m" :selected="month == i + 1"></option>
                    </template>
                </select>

                <select x-model="day" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Day</option>
                    <template x-for="d in days" :key="d">
                        <option :value="d" x-text="d" :selected="day == d"></option>
                    </template>
                </select>
            </div>

            <input type="hidden" name="checkin_date" :value="formattedDate">
        </div>

        <div x-data="dateDropdown('{{ old('checkout_date', $reservation->checkout_date ?? '') }}')" class="relative mt-5">
            <label class="block text-sm mb-1">Checkout Date</label>
            <div class="flex w-full border-b-2 border-[#bdbdbd] overflow-hidden outline-none">
                <select x-model="year" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Year</option>
                    <template x-for="y in years" :key="y">
                        <option :value="y" x-text="y" :selected="year == y"></option>
                    </template>
                </select>

                <select x-model="month" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Month</option>
                    <template x-for="(m, i) in months" :key="i">
                        <option :value="i + 1" x-text="m" :selected="month == i + 1"></option>
                    </template>
                </select>

                <select x-model="day" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Day</option>
                    <template x-for="d in days" :key="d">
                        <option :value="d" x-text="d" :selected="day == d"></option>
                    </template>
                </select>
            </div>

            <input type="hidden" name="checkout_date" :value="formattedDate">
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <label for="reservation_cost" class="block text-sm">Total Reservation Cost</label>
            
            <div class="relative">
                <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

                <x-text-input type="text" id="reservation_cost" name="reservation_cost" class="pl-7" value="{{ old('reservation_cost', $reservation->reservation_cost ?? '') }}"/>
            </div>
        </div>


        <div class="relative mt-5">
            <label for="agency_commission" class="block text-sm">Total Agency Commission</label>
            
            <div class="relative">
                <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

                <x-text-input type="text" id="agency_commission" name="agency_commission" class="pl-7" value="{{ old('agency_commission', $reservation->agency_commission ?? '') }}"/>
            </div>
        </div>

        <div class="relative mt-5">
            <label for="agent_commission" class="block text-sm">Agent Commission</label>
            
            <div class="relative">
                <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

                <x-text-input type="text" id="agent_commission" name="agent_commission" class="pl-7" value="{{ old('agent_commission', $reservation->agent_commission ?? '') }}"/>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="commission_notes" name="commission_notes"  value="{{ old('commission_notes', $reservation->commission_notes ?? '') }}"/>

            <x-input-label for="commission_notes">Commission Notes</x-input-label>
        </div>
    </div>

    <div class="flex flex-col gap-1 mt-6">
        <label for="secondaryAgents" class="text-sm">
            Secondary Agent
        </label>
        <select name="secondaryAgents" id="secondaryAgents" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
            <option value="-1">--Select Secondary Agent--</option>
            
        </select>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-6">
            <input type="hidden" name="non_commissionable" value="0">
            <input type="checkbox" class="h-4 w-4" name="non_commissionable" value="1" {{ old('non_commissionable', $reservation->non_commissionable ??0) ==1 ? 'checked' : ''}}>
            <label class="text-sm">None Commissionable</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="hidden" name="is_surprise" value="0">
            <input type="checkbox" class="h-4 w-4" name="is_surprise" value="1" {{ old('is_surprise', $reservation->is_surprise ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Reservation Is a Surprise</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="hidden" name="agent_personal_travel" value="0">
            <input type="checkbox" class="h-4 w-4" name="agent_personal_travel" value="1" {{ old('agent_personal_travel', $reservation->agent_personal_travel ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Agent Personal Travel</label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-4">
            <input type="hidden" name="is_website_lead_knot" value="0">
            <input type="checkbox" class="h-4 w-4" name="is_website_lead_knot" value="1" {{ old('is_website_lead_knot', $reservation->is_website_lead_knot ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Website Lead Knot</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="hidden" name="is_website_lead" value="0">
            <input type="checkbox" class="h-4 w-4" name="is_website_lead" value="1" {{ old('is_website_lead', $reservation->is_website_lead ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Website Lead</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="hidden" name="is_virtuoso_lead" value="0">
            <input type="checkbox" class="h-4 w-4" name="is_virtuoso_lead" value="1" {{ old('is_virtuoso_lead', $reservation->is_virtuoso_lead ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Virtuoso Lead</label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-4">
            <input type="hidden" name="is_luxury_magazine_lead" value="0">
            <input type="checkbox" class="h-4 w-4" name="is_luxury_magazine_lead" value="1" {{ old('is_luxury_magazine_lead', $reservation->is_luxury_magazine_lead ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Luxury Magazine Lead</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="hidden" name="is_facebook_lead" value="0">
            <input type="checkbox" class="h-4 w-4" name="is_facebook_lead" value="1" {{ old('is_facebook_lead', $reservation->is_facebook_lead ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Facebook Lead</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="hidden" name="is_instagram_lead" value="0">
            <input type="checkbox" class="h-4 w-4" name="is_instagram_lead" value="1" {{ old('is_instagram_lead', $reservation->is_instagram_lead ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Instagram Lead</label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-4">
            <input type="hidden" name="is_radio_lead" value="0">
            <input type="checkbox" class="h-4 w-4" name="is_radio_lead" value="1" {{ old('is_radio_lead', $reservation->is_radio_lead ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Radio Lead</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="hidden" name="stop_auto_emails" value="0">
            <input type="checkbox" class="h-4 w-4" name="stop_auto_emails" value="1" {{ old('stop_auto_emails', $reservation->stop_auto_emails ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Stop Auto Emails</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="hidden" name="radio_station_ads" value="0">
            <input type="checkbox" class="h-4 w-4" name="radio_station_ads" value="1" {{ old('radio_station_ads', $reservation->radio_station_ads ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Radio Station Ads</label>
        </div>
    </div>

    @if(!$isNewReservation)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
            <div class="flex items-center gap-2 mt-4">
                <input type="hidden" name="remove_mentor_commission" value="0">
                <input type="checkbox" class="h-4 w-4" name="remove_mentor_commission" value="1" {{ old('remove_mentor_commission', $reservation->remove_mentor_commission ?? 0) == 1 ? 'checked' : '' }}>
                <label class="text-sm">Remove Mentor Commission</label>
            </div>
        </div>
    @endif
</div>