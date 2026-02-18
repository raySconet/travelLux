<div>
    <p class="text-xl">Reservation Details</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-6">
            <label for="product" class="text-sm block mb-1">Product</label>
            <select name="product"id="product" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Product--</option>
            </select>
        </div>

        <div class="flex-1 relative mt-6">
            <label for="destination" class="text-sm block mb-1">Destination</label>
            <select name="destination"id="destination" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Destination--</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-6">
            <label for="resort/ship" class="text-sm block mb-1">Resort/Ship</label>
            <select name="resort/ship"id="resort/ship" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Resort/Ship--</option>
            </select>
        </div>

        <div class="flex-1 relative mt-6">
            <label for="cruiseItinerary" class="text-sm block mb-1">Cruise Itinerary</label>
            <select name="cruiseItinerary"id="cruiseItinerary" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Cruise/Type--</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-6">
            <x-text-input type="text" id="reservationNotes" name="reservationNotes"  value="{{ old('reservation_notes', $reservation->reservation_notes ?? '') }}"/>

            <x-input-label for="reservationNotes">Reservation Notes</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="roomCategory" name="roomCategory"  value="{{ old('room_category', $reservation->room_category ?? '') }}"/>

            <x-input-label for="roomCategory">Room Category</x-input-label>
        </div>

        <div class="relative mt-9">
            <x-text-input type="text" id="stateroomNumber" name="stateroomNumber" value="{{ old('stateroom_number', $reservation->stateroom_number ?? '') }}" />

            <x-input-label for="stateroomNumber">Stateroom Number</x-input-label>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="embarkationPort" class="text-sm block mb-1">Embarkation Port</label>
            <select name="embarkationPort"id="embarkationPort" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Port --</option>
                <option value="Anchorage" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Anchorage' ? 'selected' : '' }}>Anchorage</option>
                <option value="Baltimore" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Baltimore' ? 'selected' : '' }}>Baltimore</option>
                <option value="Barcelona" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Barcelona' ? 'selected' : '' }}>Barcelona</option>
                <option value="Boston" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Boston' ? 'selected' : '' }}>Boston</option>
                <option value="Cape Liberty" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Cape Liberty' ? 'selected' : '' }}>Cape Liberty</option>
                <option value="Charleston" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Charleston' ? 'selected' : '' }}>Charleston</option>
                <option value="Civitavecchia (Rome)" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Civitavecchia (Rome)' ? 'selected' : '' }}>Civitavecchia (Rome)</option>
                <option value="Copenhagen" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Copenhagen' ? 'selected' : '' }}>Copenhagen</option>
                <option value="Dover" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Dover' ? 'selected' : '' }}>Dover</option>
                <option value="Fort Lauderdale" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Fort Lauderdale' ? 'selected' : '' }}>Fort Lauderdale</option>
                <option value="Galveston" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Galveston' ? 'selected' : '' }}>Galveston</option>
                <option value="Honolulu" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Honolulu' ? 'selected' : '' }}>Honolulu</option>
                <option value="Jacksonville" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Jacksonville' ? 'selected' : '' }}>Jacksonville</option>
                <option value="Long Beach/Los Angeles" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Long Beach/Los Angeles' ? 'selected' : '' }}>Long Beach/Los Angeles</option>
                <option value="Miami" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Miami' ? 'selected' : '' }}>Miami</option>
                <option value="Mobile" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                <option value="New Orleans" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'New Orleans' ? 'selected' : '' }}>New Orleans</option>
                <option value="New York City" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'New York City' ? 'selected' : '' }}>New York City</option>
                <option value="Port Canaveral" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Port Canaveral' ? 'selected' : '' }}>Port Canaveral</option>
                <option value="Quebec City" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Quebec City' ? 'selected' : '' }}>Quebec City</option>
                <option value="San Diego" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'San Diego' ? 'selected' : '' }}>San Diego</option>
                <option value="San Francisco" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'San Francisco' ? 'selected' : '' }}>San Francisco</option>
                <option value="San Juan" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'San Juan' ? 'selected' : '' }}>San Juan</option>
                <option value="Seattle" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Seattle' ? 'selected' : '' }}>Seattle</option>
                <option value="Seward" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Seward' ? 'selected' : '' }}>Seward</option>
                <option value="Tampa" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Tampa' ? 'selected' : '' }}>Tampa</option>
                <option value="Vancouver" {{ old('embarkation_port', $reservation->embarkation_port ?? '') == 'Vancouver' ? 'selected' : '' }}>Vancouver</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="daysOfTickets" name="daysOfTickets"  value="{{ old('days_of_tickets', $reservation->days_of_tickets ?? '') }}"/>

            <x-input-label for="daysOfTickets">Days Of Tickets</x-input-label>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="ticketTypes" class="text-sm block mb-1">Ticket Types</label>
            <select name="ticketTypes"id="ticketTypes" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Ticket Types --</option>
                <option value="1" {{ old('ticket_types', $reservation->ticket_types ?? '') == '1' ? 'selected' : '' }}>Base</option>
                <option value="2" {{ old('ticket_types', $reservation->ticket_types ?? '') == '2' ? 'selected' : '' }}>Cirque du Soleil</option>
                <option value="3" {{ old('ticket_types', $reservation->ticket_types ?? '') == '3' ? 'selected' : '' }}>Barcelona</option>
                <option value="4" {{ old('ticket_types', $reservation->ticket_types ?? '') == '4' ? 'selected' : '' }}>MaxPass</option>
                <option value="5" {{ old('ticket_types', $reservation->ticket_types ?? '') == '5' ? 'selected' : '' }}>MNSSHP</option>
                <option value="6" {{ old('ticket_types', $reservation->ticket_types ?? '') == '6' ? 'selected' : '' }}>MVMCP</option>
                <option value="7" {{ old('ticket_types', $reservation->ticket_types ?? '') == '7' ? 'selected' : '' }}>NBA Experience</option>
                <option value="8" {{ old('ticket_types', $reservation->ticket_types ?? '') == '8' ? 'selected' : '' }}>Other</option>
                <option value="9" {{ old('ticket_types', $reservation->ticket_types ?? '') == '9' ? 'selected' : '' }}>Park Hopper</option>
                <option value="10"{{ old('ticket_types', $reservation->ticket_types ?? '') == '10' ? 'selected' : '' }}>SeaWorld</option>
                <option value="11"{{ old('ticket_types', $reservation->ticket_types ?? '') == '11' ? 'selected' : '' }}>Southern CA City Pass</option>
                <option value="12"{{ old('ticket_types', $reservation->ticket_types ?? '') == '12' ? 'selected' : '' }}>Universal</option>
                <option value="13"{{ old('ticket_types', $reservation->ticket_types ?? '') == '13' ? 'selected' : '' }}>Universal 2 park park-to-park</option>
                <option value="14"{{ old('ticket_types', $reservation->ticket_types ?? '') == '14' ? 'selected' : '' }}>Universal 3 park park-to-park</option>
                <option value="15"{{ old('ticket_types', $reservation->ticket_types ?? '') == '15' ? 'selected' : '' }}>Water Park & More/Park Hopper Plus</option>
                <option value="16"{{ old('ticket_types', $reservation->ticket_types ?? '') == '16' ? 'selected' : '' }}>Water Park and Sports</option>
                <option value="17"{{ old('ticket_types', $reservation->ticket_types ?? '') == '17' ? 'selected' : '' }}>Water Park Only</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-8">
            <label for="diningOption" class="text-sm block mb-1">Dining Option</label>
            <select name="diningOption"id="diningOption" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Dining Option --</option>
                <option value="Disney Dining Plan Plus" {{ old('dining_option', $reservation->dining_option ?? '') == 'Disney Dining Plan Plus' ? 'selected' : '' }}>Disney Dining Plan Plus</option>
                <option value="DLR Character Dining" {{ old('dining_option', $reservation->dining_option ?? '') == 'DLR Character Dining' ? 'selected' : '' }}>DLR Character Dining</option>
                <option value="DLR Premium Character Dining" {{ old('dining_option', $reservation->dining_option ?? '') == 'DLR Premium Character Dining' ? 'selected' : '' }}>DLR Premium Character Dining</option>
                <option value="None" {{ old('dining_option', $reservation->dining_option ?? '') == 'None' ? 'selected' : '' }}>None</option>
                <option value="Disney Dining Plan" {{ old('dining_option', $reservation->dining_option ?? '') == 'Disney Dining Plan' ? 'selected' : '' }}>Disney Dining Plan</option>
                <option value="Quick Service" {{ old('dining_option', $reservation->dining_option ?? '') == 'Quick Service' ? 'selected' : '' }}>Quick Service</option>
                <option value="Deluxe Dining" {{ old('dining_option', $reservation->dining_option ?? '') == 'Deluxe Dining' ? 'selected' : '' }}>Deluxe Dining</option>
                <option value="Main Seating" {{ old('dining_option', $reservation->dining_option ?? '') == 'Main Seating' ? 'selected' : '' }}>Main Seating</option>
                <option value="Second Seating" {{ old('dining_option', $reservation->dining_option ?? '') == 'Second Seating' ? 'selected' : '' }}>Second Seating</option>
                <option value="My Time Dining" {{ old('dining_option', $reservation->dining_option ?? '') == 'My Time Dining' ? 'selected' : '' }}>My Time Dining</option>
                <option value="Universal Dining Plan" {{ old('dining_option', $reservation->dining_option ?? '') == 'Universal Dining Plan' ? 'selected' : '' }}>Universal Dining Plan</option>
                <option value="Universal Quick Service Plan" {{ old('dining_option', $reservation->dining_option ?? '') == 'Universal Quick Service Plan' ? 'selected' : '' }}>Universal Quick Service Plan</option>
            </select>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="addOnOptions" class="text-sm block mb-1">Add-on Options</label>
            <select name="addOnOptions"id="addOnOptions" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Options --</option>
                <option value="1" {{ old('add_on_options', $reservation->add_on_options ?? '') == '1' ? 'selected' : '' }}>Blue Man Group</option>
                <option value="2" {{ old('add_on_options', $reservation->add_on_options ?? '') == '2' ? 'selected' : '' }}>Car Rental</option>
                <option value="3" {{ old('add_on_options', $reservation->add_on_options ?? '') == '3' ? 'selected' : '' }}>Character Breakfast</option>
                <option value="4" {{ old('add_on_options', $reservation->add_on_options ?? '') == '4' ? 'selected' : '' }}>Club Mobay</option>
                <option value="5" {{ old('add_on_options', $reservation->add_on_options ?? '') == '5' ? 'selected' : '' }}>Express Pass</option>
                <option value="20"{{ old('add_on_options', $reservation->add_on_options ?? '') == '20' ? 'selected' : '' }}>Genie</option>
                <option value="21"{{ old('add_on_options', $reservation->add_on_options ?? '') == '21' ? 'selected' : '' }}>Harry Potter Package</option>
                <option value="6" {{ old('add_on_options', $reservation->add_on_options ?? '') == '6' ? 'selected' : '' }}>Hyatt Pre-Stay</option>
                <option value="7" {{ old('add_on_options', $reservation->add_on_options ?? '') == '7' ? 'selected' : '' }}>In-Room Celebration</option>
                <option value="8" {{ old('add_on_options', $reservation->add_on_options ?? '') == '8' ? 'selected' : '' }}>Land Tour</option>
                <option value="9" {{ old('add_on_options', $reservation->add_on_options ?? '') == '9' ? 'selected' : '' }}>MaxPass</option>
                <option value="10"{{ old('add_on_options', $reservation->add_on_options ?? '') == '10' ? 'selected' : '' }}>Memory Maker/Photo Pass</option>
                <option value="11"{{ old('add_on_options', $reservation->add_on_options ?? '') == '11' ? 'selected' : '' }}>Outside Insurance</option>
                <option value="12"{{ old('add_on_options', $reservation->add_on_options ?? '') == '12' ? 'selected' : '' }}>Photo Package</option>
                <option value="13"{{ old('add_on_options', $reservation->add_on_options ?? '') == '13' ? 'selected' : '' }}>Post Cruise Stay</option>
                <option value="14"{{ old('add_on_options', $reservation->add_on_options ?? '') == '14' ? 'selected' : '' }}>Pre Cruise Stay</option>
                <option value="15"{{ old('add_on_options', $reservation->add_on_options ?? '') == '15' ? 'selected' : '' }}>Pre-Paid Gratuities</option>
                <option value="16"{{ old('add_on_options', $reservation->add_on_options ?? '') == '16' ? 'selected' : '' }}>Stroller/Scooter Damage Waiver</option>
                <option value="17"{{ old('add_on_options', $reservation->add_on_options ?? '') == '17' ? 'selected' : '' }}>Travel Protection Declined</option>
                <option value="18"{{ old('add_on_options', $reservation->add_on_options ?? '') == '18' ? 'selected' : '' }}>Travel Protection Included</option>
                <option value="19"{{ old('add_on_options', $reservation->add_on_options ?? '') == '19' ? 'selected' : '' }}>VIP Tour</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-8">
            <label for="cruiseLevel" class="text-sm block mb-1">Cruise Level</label>
            <select name="cruiseLevel"id="cruiseLevel" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Cruise Level --</option>
                <option value="Silver" {{ old('cruise_level', $reservation->cruise_level ?? '') == 'Silver' ? 'selected' : '' }}>Silver</option>
                <option value="Gold" {{ old('cruise_level', $reservation->cruise_level ?? '') == 'Gold' ? 'selected' : '' }}>Gold</option>
                <option value="Emerald" {{ old('cruise_level', $reservation->cruise_level ?? '') == 'Emerald' ? 'selected' : '' }}>Emerald</option>
                <option value="Diamond" {{ old('cruise_level', $reservation->cruise_level ?? '') == 'Diamond' ? 'selected' : '' }}>Diamond</option>
                <option value="Diamond Plus" {{ old('cruise_level', $reservation->cruise_level ?? '') == 'Diamond Plus' ? 'selected' : '' }}>Diamond Plus</option>
                <option value="Pinnacle" {{ old('cruise_level', $reservation->cruise_level ?? '') == 'Pinnacle' ? 'selected' : '' }}>Pinnacle</option>
                <option value="Concierge" {{ old('cruise_level', $reservation->cruise_level ?? '') == 'Concierge' ? 'selected' : '' }}>Concierge</option>
            </select>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="transportationOptions" class="text-sm block mb-1">Transportation Options</label>
            <select name="transportationOptions"id="transportationOptions" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Options --</option>
                <option value="1" {{ old('transportation_options', $reservation->transportation_options ?? '') == '1' ? 'selected' : '' }}>Airport to Port</option>
                <option value="2" {{ old('transportation_options', $reservation->transportation_options ?? '') == '2' ? 'selected' : '' }}>Booking Their Own Flights</option>
                <option value="30"{{ old('transportation_options', $reservation->transportation_options ?? '') == '30' ? 'selected' : '' }}>Carey</option>
                <option value="3" {{ old('transportation_options', $reservation->transportation_options ?? '') == '3' ? 'selected' : '' }}>Cruise Line Flights</option>
                <option value="4" {{ old('transportation_options', $reservation->transportation_options ?? '') == '4' ? 'selected' : '' }}>Cruise Line Transfers</option>
                <option value="5" {{ old('transportation_options', $reservation->transportation_options ?? '') == '5' ? 'selected' : '' }}>Disneyland Express One-way</option>
                <option value="6" {{ old('transportation_options', $reservation->transportation_options ?? '') == '6' ? 'selected' : '' }}>Disney Express Round Trip</option>
                <option value="7" {{ old('transportation_options', $reservation->transportation_options ?? '') == '7' ? 'selected' : '' }}>Drive</option>
                <option value="8" {{ old('transportation_options', $reservation->transportation_options ?? '') == '8' ? 'selected' : '' }}>Fly</option>
                <option value="9" {{ old('transportation_options', $reservation->transportation_options ?? '') == '9' ? 'selected' : '' }}>Magical Express Inbound</option>
                <option value="10"{{ old('transportation_options', $reservation->transportation_options ?? '') == '10' ? 'selected' : '' }}>Magical Express Outbound</option>
                <option value="11"{{ old('transportation_options', $reservation->transportation_options ?? '') == '11' ? 'selected' : '' }}>No Transfers Needed</option>
                <option value="12"{{ old('transportation_options', $reservation->transportation_options ?? '') == '12' ? 'selected' : '' }}>Personal Vehicle</option>
                <option value="13"{{ old('transportation_options', $reservation->transportation_options ?? '') == '13' ? 'selected' : '' }}>Port to Airport</option>
                <option value="14"{{ old('transportation_options', $reservation->transportation_options ?? '') == '14' ? 'selected' : '' }}>Port to Resort</option>
                <option value="15"{{ old('transportation_options', $reservation->transportation_options ?? '') == '15' ? 'selected' : '' }}>Private Transfer Roundtrip</option>
                <option value="16"{{ old('transportation_options', $reservation->transportation_options ?? '') == '16' ? 'selected' : '' }}>Private Transfer to Airport</option>
                <option value="17"{{ old('transportation_options', $reservation->transportation_options ?? '') == '17' ? 'selected' : '' }}>Private Transfer to Port</option>
                <option value="18"{{ old('transportation_options', $reservation->transportation_options ?? '') == '18' ? 'selected' : '' }}>Resort to Port</option>
                <option value="19"{{ old('transportation_options', $reservation->transportation_options ?? '') == '19' ? 'selected' : '' }}>Round Trip Universal SuperStar Shuttle</option>
                <option value="20"{{ old('transportation_options', $reservation->transportation_options ?? '') == '20' ? 'selected' : '' }}>Roundtrip Transfers</option>
                <option value="31"{{ old('transportation_options', $reservation->transportation_options ?? '') == '31' ? 'selected' : '' }}>Shared Transfers Roundtrip</option>
                <option value="21"{{ old('transportation_options', $reservation->transportation_options ?? '') == '21' ? 'selected' : '' }}>Shuttle From Hotel</option>
                <option value="22"{{ old('transportation_options', $reservation->transportation_options ?? '') == '22' ? 'selected' : '' }}>Take Two Transfer</option>
                <option value="23"{{ old('transportation_options', $reservation->transportation_options ?? '') == '23' ? 'selected' : '' }}>Universal Super Star Shuttle One-way Airport to Hotel</option>
                <option value="24"{{ old('transportation_options', $reservation->transportation_options ?? '') == '24' ? 'selected' : '' }}>Universal Super Star Shuttle One-way Hotel to Airport</option>
                <option value="25"{{ old('transportation_options', $reservation->transportation_options ?? '') == '25' ? 'selected' : '' }}>Universal Super Star Shuttle Round Trip</option>
                <option value="26"{{ old('transportation_options', $reservation->transportation_options ?? '') == '26' ? 'selected' : '' }}>Universal’s Quick Transportation (Universal Hotel to WDW Hotel)</option>
                <option value="27"{{ old('transportation_options', $reservation->transportation_options ?? '') == '27' ? 'selected' : '' }}>Universal’s Quick Transportation (WDW Hotel to Universal Hotel)</option>
                <option value="28"{{ old('transportation_options', $reservation->transportation_options ?? '') == '28' ? 'selected' : '' }}>Universal’s SuperStar Shuttle (airport to On-site Hotel)</option>
                <option value="29"{{ old('transportation_options', $reservation->transportation_options ?? '') == '29' ? 'selected' : '' }}>Universal’s SuperStar Shuttle (On-site Hotel to airport)</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-6">
            <input type="hidden" name="concierge_ship" value="0">
            <input type="checkbox" class="h-4 w-4" name="concierge_ship" value="1" {{ old('concierge_ship', $reservation->concierge_ship ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Concierge Ship</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="hidden" name="club_level_resort" value="0">
            <input type="checkbox" class="h-4 w-4" name="club_level_resort" value="1" {{ old('club_level_resort', $reservation->club_level_resort ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Club Level Resort</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="hidden" name="submitted_to_rewards" value="0">
            <input type="checkbox" class="h-4 w-4" name="submitted_to_rewards" value="1" {{ old('submitted_to_rewards', $reservation->submitted_to_rewards ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Submitted to Rewards</label>
        </div>
    </div>
</div>