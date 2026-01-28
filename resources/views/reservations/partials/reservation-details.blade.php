<div>
    <p class="text-xl">Reservation Details</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-8">
            <label for="product" class="text-sm block mb-1">Product</label>
            <select name="product"id="product" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Product--</option>
            </select>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="destination" class="text-sm block mb-1">Destination</label>
            <select name="destination"id="destination" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Destination--</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-8">
            <label for="resort/ship" class="text-sm block mb-1">Resort/Ship</label>
            <select name="resort/ship"id="resort/ship" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Resort/Ship--</option>
            </select>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="cruiseItinerary" class="text-sm block mb-1">Cruise Itinerary</label>
            <select name="cruiseItinerary"id="cruiseItinerary" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Cruise/Type--</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="reservationNotes" name="reservationNotes"  />

            <x-input-label for="reservationNotes">Reservation Notes</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="roomCategory" name="roomCategory"  />

            <x-input-label for="roomCategory">Room Category</x-input-label>
        </div>

        <div class="relative mt-9">
            <x-text-input type="text" id="stateroomNumber" name="stateroomNumber"  />

            <x-input-label for="stateroomNumber">Stateroom Number</x-input-label>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="embarkationPort" class="text-sm block mb-1">Embarkation Port</label>
            <select name="embarkationPort"id="embarkationPort" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Port --</option>
                <option value="Anchorage">Anchorage</option>
                <option value="Baltimore">Baltimore</option>
                <option value="Barcelona">Barcelona</option>
                <option value="Boston">Boston</option>
                <option value="Cape Liberty">Cape Liberty</option>
                <option value="Charleston">Charleston</option>
                <option value="Civitavecchia (Rome)">Civitavecchia (Rome)</option>
                <option value="Copenhagen">Copenhagen</option>
                <option value="Dover">Dover</option>
                <option value="Fort Lauderdale">Fort Lauderdale</option>
                <option value="Galveston">Galveston</option>
                <option value="Honolulu">Honolulu</option>
                <option value="Jacksonville">Jacksonville</option>
                <option value="Long Beach/Los Angeles">Long Beach/Los Angeles</option>
                <option value="Miami">Miami</option>
                <option value="Mobile">Mobile</option>
                <option value="New Orleans">New Orleans</option>
                <option value="New York City">New York City</option>
                <option value="Port Canaveral">Port Canaveral</option>
                <option value="Quebec City">Quebec City</option>
                <option value="San Diego">San Diego</option>
                <option value="San Francisco">San Francisco</option>
                <option value="San Juan">San Juan</option>
                <option value="Seattle">Seattle</option>
                <option value="Seward">Seward</option>
                <option value="Tampa">Tampa</option>
                <option value="Vancouver">Vancouver</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="daysOfTickets" name="daysOfTickets"  />

            <x-input-label for="daysOfTickets">Days Of Tickets</x-input-label>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="ticketTypes" class="text-sm block mb-1">Ticket Types</label>
            <select name="ticketTypes"id="ticketTypes" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Ticket Types --</option>
                <option value="1">Base</option>
                <option value="2">Cirque du Soleil</option>
                <option value="3">Barcelona</option>
                <option value="4">MaxPass</option>
                <option value="5">MNSSHP</option>
                <option value="6">MVMCP</option>
                <option value="7">NBA Experience</option>
                <option value="8">Other</option>
                <option value="9">Park Hopper</option>
                <option value="10">SeaWorld</option>
                <option value="11">Southern CA City Pass</option>
                <option value="12">Universal</option>
                <option value="13">Universal 2 park park-to-park</option>
                <option value="14">Universal 3 park park-to-park</option>
                <option value="15">Water Park & More/Park Hopper Plus</option>
                <option value="16">Water Park and Sports</option>
                <option value="17">Water Park Only</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-8">
            <label for="diningOption" class="text-sm block mb-1">Dining Option</label>
            <select name="diningOption"id="diningOption" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Dining Option --</option>
                <option value="Disney Dining Plan Plus">Disney Dining Plan Plus</option>
                <option value="DLR Character Dining">DLR Character Dining</option>
                <option value="DLR Premium Character Dining">DLR Premium Character Dining</option>
                <option value="None">None</option>
                <option value="Disney Dining Plan">Disney Dining Plan</option>
                <option value="Quick Service">Quick Service</option>
                <option value="Deluxe Dining">Deluxe Dining</option>
                <option value="Main Seating">Main Seating</option>
                <option value="Second Seating">Second Seating</option>
                <option value="My Time Dining">My Time Dining</option>
                <option value="Universal Dining Plan">Universal Dining Plan</option>
                <option value="Universal Quick Service Plan">Universal Quick Service Plan</option>
            </select>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="addOnOptions" class="text-sm block mb-1">Add-on Options</label>
            <select name="addOnOptions"id="addOnOptions" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Options --</option>
                <option value="1">Blue Man Group</option>
                <option value="2">Car Rental</option>
                <option value="3">Character Breakfast</option>
                <option value="4">Club Mobay</option>
                <option value="5">Express Pass</option>
                <option value="20">Genie</option>
                <option value="21">Harry Potter Package</option>
                <option value="6">Hyatt Pre-Stay</option>
                <option value="7">In-Room Celebration</option>
                <option value="8">Land Tour</option>
                <option value="9">MaxPass</option>
                <option value="10">Memory Maker/Photo Pass</option>
                <option value="11">Outside Insurance</option>
                <option value="12">Photo Package</option>
                <option value="13">Post Cruise Stay</option>
                <option value="14">Pre Cruise Stay</option>
                <option value="15">Pre-Paid Gratuities</option>
                <option value="16">Stroller/Scooter Damage Waiver</option>
                <option value="17">Travel Protection Declined</option>
                <option value="18">Travel Protection Included</option>
                <option value="19">VIP Tour</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-8">
            <label for="cruiseLevel" class="text-sm block mb-1">Cruise Level</label>
            <select name="cruiseLevel"id="cruiseLevel" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Cruise Level --</option>
                <option value="Silver">Silver</option>
                <option value="Gold">Gold</option>
                <option value="Emerald">Emerald</option>
                <option value="Diamond">Diamond</option>
                <option value="Diamond Plus">Diamond Plus</option>
                <option value="Pinnacle">Pinnacle</option>
                <option value="Concierge">Concierge</option>
            </select>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="transportationOptions" class="text-sm block mb-1">Transportation Options</label>
            <select name="transportationOptions"id="transportationOptions" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Options --</option>
                <option value="1">Airport to Port</option>
                <option value="2">Booking Their Own Flights</option>
                <option value="30">Carey</option>
                <option value="3">Cruise Line Flights</option>
                <option value="4">Cruise Line Transfers</option>
                <option value="5">Disneyland Express One-way</option>
                <option value="6">Disney Express Round Trip</option>
                <option value="7">Drive</option>
                <option value="8">Fly</option>
                <option value="9">Magical Express Inbound</option>
                <option value="10">Magical Express Outbound</option>
                <option value="11">No Transfers Needed</option>
                <option value="12">Personal Vehicle</option>
                <option value="13">Port to Airport</option>
                <option value="14">Port to Resort</option>
                <option value="15">Private Transfer Roundtrip</option>
                <option value="16">Private Transfer to Airport</option>
                <option value="17">Private Transfer to Port</option>
                <option value="18">Resort to Port</option>
                <option value="19">Round Trip Universal SuperStar Shuttle</option>
                <option value="20">Roundtrip Transfers</option>
                <option value="31">Shared Transfers Roundtrip</option>
                <option value="21">Shuttle From Hotel</option>
                <option value="22">Take Two Transfer</option>
                <option value="23">Universal Super Star Shuttle One-way Airport to Hotel</option>
                <option value="24">Universal Super Star Shuttle One-way Hotel to Airport</option>
                <option value="25">Universal Super Star Shuttle Round Trip</option>
                <option value="26">Universal’s Quick Transportation (Universal Hotel to WDW Hotel)</option>
                <option value="27">Universal’s Quick Transportation (WDW Hotel to Universal Hotel)</option>
                <option value="28">Universal’s SuperStar Shuttle (airport to On-site Hotel)</option>
                <option value="29">Universal’s SuperStar Shuttle (On-site Hotel to airport)</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Concierge Ship</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Club Level Resort</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Submitted to Rewards</label>
        </div>
    </div>
</div>