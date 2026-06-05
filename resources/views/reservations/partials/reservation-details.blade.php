<div>
    <p class="text-xl">Reservation Details</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-6">
            <label for="product_id" class="text-sm block mb-1">Product</label>
            <select name="product_id" id="product_id" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="">--Select Product--</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                        {{ old('product_id', $reservation->product_id) == $product->id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach    
            </select>

            <x-input-error :messages="$errors->get('product_id')" />
        </div>

        <div class="flex-1 relative mt-6">
            <label for="destination_id" class="text-sm block mb-1">Destination</label>
            <select name="destination_id"id="destination_id" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="">--Select Destination--</option>
            </select>

            <x-input-error :messages="$errors->get('destination_id')" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-6">
            <label for="resort_id" class="text-sm block mb-1">Resort/Ship</label>
            <select name="resort_id"id="resort_id" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="">--Select Resort/Ship--</option>
            </select>

            <x-input-error :messages="$errors->get('resort_id')" />
        </div>

        <div class="flex-1 relative mt-6">
            <label for="cruise_itinerary_id" class="text-sm block mb-1">Cruise Itinerary</label>
            <select name="cruise_itinerary_id"id="cruise_itinerary_id" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="">--Select Cruise/Type--</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-6">
            <x-text-input type="text" id="reservation_notes" name="reservation_notes"  value="{{ old('reservation_notes', $reservation->reservation_notes ?? '') }}"/>

            <x-input-label for="reservation_notes">Reservation Notes</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="room_category" name="room_category"  value="{{ old('room_category', $reservation->room_category ?? '') }}"/>

            <x-input-label for="room_category">Room Category</x-input-label>
        </div>

        <div class="relative mt-9">
            <x-text-input type="text" id="stateroom_number" name="stateroom_number" value="{{ old('stateroom_number', $reservation->stateroom_number ?? '') }}" />

            <x-input-label for="stateroom_number">Stateroom Number</x-input-label>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="embarkation_port" class="text-sm block mb-1">Embarkation Port</label>
            <select name="embarkation_port"id="embarkation_port" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
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
            <x-text-input type="text" id="days_of_tickets" name="days_of_tickets"  value="{{ old('days_of_tickets', $reservation->days_of_tickets ?? '') }}"/>

            <x-input-label for="days_of_tickets">Days Of Tickets</x-input-label>

            <x-input-error :messages="$errors->get('days_of_tickets')" /> 
        </div>

        @php
            $ticketTypeIds = old('ticket_types', $reservation->ticket_types ? explode(',', $reservation->ticket_types): []);

            $ticketTypeIds = array_filter($ticketTypeIds, function ($v) {return $v !== '-1' && $v !== '' && $v !== null; });
        @endphp
        <div class="flex-1 relative mt-7"
            x-data="{
                open: false,
                selected: {{ json_encode(array_map('strval', (array)$ticketTypeIds)) }},
                options: [
                    {id:'1',label:'Base'},{id:'2',label:'Cirque du Soleil'},{id:'3',label:'Barcelona'},
                    {id:'4',label:'MaxPass'},{id:'5',label:'MNSSHP'},{id:'6',label:'MVMCP'},
                    {id:'7',label:'NBA Experience'},{id:'8',label:'Other'},{id:'9',label:'Park Hopper'},
                    {id:'10',label:'SeaWorld'},{id:'11',label:'Southern CA City Pass'},{id:'12',label:'Universal'},
                    {id:'13',label:'Universal 2 park park-to-park'},{id:'14',label:'Universal 3 park park-to-park'},
                    {id:'15',label:'Water Park & More/Park Hopper Plus'},{id:'16',label:'Water Park and Sports'},
                    {id:'17',label:'Water Park Only'}
                ],
                toggle(id) { this.selected.includes(id) ? this.selected = this.selected.filter(v => v !== id) : this.selected.push(id); },
                label() {
                    if (!this.selected.length) return '-- Select Ticket Types --';
                    return this.options.filter(o => this.selected.includes(o.id)).map(o => o.label).join(', ');
                }
            }" x-cloak>
            <label class="text-sm block mb-1">Ticket Types</label>
            <template x-for="s in selected" :key="s">
                <input type="hidden" name="ticket_types[]" :value="s">
            </template>
            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 bg-white text-left flex justify-between items-center">
                <span x-text="label()" class="text-gray-600 truncate"></span>
                <svg class="w-4 h-4 text-gray-400 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-lg max-h-60 overflow-y-auto">
                <template x-for="opt in options" :key="opt.id">
                    <div @click="toggle(opt.id)" class="flex items-center justify-between px-3 py-2 text-sm cursor-pointer hover:bg-gray-100">
                        <span x-text="opt.label"></span>
                        <svg x-show="selected.includes(opt.id)" class="w-4 h-4 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-8">
            <label for="dining_option" class="text-sm block mb-1">Dining Option</label>
            <select name="dining_option"id="dining_option" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
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

        @php
            $addOnIds = old('add_on_options', $reservation->add_on_options ? explode(',', $reservation->add_on_options): []);

            $addOnIds = array_filter($addOnIds, function ($v) { return $v !== '-1' && $v !== '' && $v !== null; });
        @endphp
        <div class="flex-1 relative mt-8"
            x-data="{
                open: false,
                selected: {{ json_encode(array_map('strval', (array)$addOnIds)) }},
                options: [
                    {id:'1',label:'Blue Man Group'},{id:'2',label:'Car Rental'},{id:'3',label:'Character Breakfast'},
                    {id:'4',label:'Club Mobay'},{id:'5',label:'Express Pass'},{id:'20',label:'Genie'},
                    {id:'21',label:'Harry Potter Package'},{id:'6',label:'Hyatt Pre-Stay'},{id:'7',label:'In-Room Celebration'},
                    {id:'8',label:'Land Tour'},{id:'9',label:'MaxPass'},{id:'10',label:'Memory Maker/Photo Pass'},
                    {id:'11',label:'Outside Insurance'},{id:'12',label:'Photo Package'},{id:'13',label:'Post Cruise Stay'},
                    {id:'14',label:'Pre Cruise Stay'},{id:'15',label:'Pre-Paid Gratuities'},
                    {id:'16',label:'Stroller/Scooter Damage Waiver'},{id:'17',label:'Travel Protection Declined'},
                    {id:'18',label:'Travel Protection Included'},{id:'19',label:'VIP Tour'}
                ],
                toggle(id) { this.selected.includes(id) ? this.selected = this.selected.filter(v => v !== id) : this.selected.push(id); },
                label() {
                    if (!this.selected.length) return '-- Select Add-ons --';
                    return this.options.filter(o => this.selected.includes(o.id)).map(o => o.label).join(', ');
                }
            }" x-cloak>
            <label class="text-sm block mb-1">Add-on Options</label>
            <template x-for="s in selected" :key="s">
                <input type="hidden" name="add_on_options[]" :value="s">
            </template>
            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 bg-white text-left flex justify-between items-center">
                <span x-text="label()" class="text-gray-600 truncate"></span>
                <svg class="w-4 h-4 text-gray-400 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-lg max-h-60 overflow-y-auto">
                <template x-for="opt in options" :key="opt.id">
                    <div @click="toggle(opt.id)" class="flex items-center justify-between px-3 py-2 text-sm cursor-pointer hover:bg-gray-100">
                        <span x-text="opt.label"></span>
                        <svg x-show="selected.includes(opt.id)" class="w-4 h-4 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-8">
            <label for="cruise_level" class="text-sm block mb-1">Cruise Level</label>
            <select name="cruise_level"id="cruise_level" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
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

        @php
            $transportationIds = old('transportation_options', $reservation->transportation_options ? explode(',', $reservation->transportation_options) : []);

            $transportationIds = array_filter($transportationIds, function ($v) {return $v !== '-1' && $v !== '' && $v !== null; });
        @endphp
        <div class="flex-1 relative mt-8"
            x-data="{
                open: false,
                selected: {{ json_encode(array_map('strval', (array)$transportationIds)) }},
                options: [
                    {id:'1',label:'Airport to Port'},{id:'2',label:'Booking Their Own Flights'},{id:'30',label:'Carey'},
                    {id:'3',label:'Cruise Line Flights'},{id:'4',label:'Cruise Line Transfers'},
                    {id:'5',label:'Disneyland Express One-way'},{id:'6',label:'Disney Express Round Trip'},
                    {id:'7',label:'Drive'},{id:'8',label:'Fly'},{id:'9',label:'Magical Express Inbound'},
                    {id:'10',label:'Magical Express Outbound'},{id:'11',label:'No Transfers Needed'},
                    {id:'12',label:'Personal Vehicle'},{id:'13',label:'Port to Airport'},{id:'14',label:'Port to Resort'},
                    {id:'15',label:'Private Transfer Roundtrip'},{id:'16',label:'Private Transfer to Airport'},
                    {id:'17',label:'Private Transfer to Port'},{id:'18',label:'Resort to Port'},
                    {id:'19',label:'Round Trip Universal SuperStar Shuttle'},{id:'20',label:'Roundtrip Transfers'},
                    {id:'31',label:'Shared Transfers Roundtrip'},{id:'21',label:'Shuttle From Hotel'},
                    {id:'22',label:'Take Two Transfer'},
                    {id:'23',label:'Universal Super Star Shuttle One-way Airport to Hotel'},
                    {id:'24',label:'Universal Super Star Shuttle One-way Hotel to Airport'},
                    {id:'25',label:'Universal Super Star Shuttle Round Trip'},
                    {id:'26',label:'Universal\'s Quick Transportation (Universal Hotel to WDW Hotel)'},
                    {id:'27',label:'Universal\'s Quick Transportation (WDW Hotel to Universal Hotel)'},
                    {id:'28',label:'Universal\'s SuperStar Shuttle (airport to On-site Hotel)'},
                    {id:'29',label:'Universal\'s SuperStar Shuttle (On-site Hotel to airport)'}
                ],
                toggle(id) { this.selected.includes(id) ? this.selected = this.selected.filter(v => v !== id) : this.selected.push(id); },
                label() {
                    if (!this.selected.length) return '-- Select Transportation Options --';
                    return this.options.filter(o => this.selected.includes(o.id)).map(o => o.label).join(', ');
                }
            }" x-cloak>
            <label class="text-sm block mb-1">Transportation Options</label>
            <template x-for="s in selected" :key="s">
                <input type="hidden" name="transportation_options[]" :value="s">
            </template>
            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 bg-white text-left flex justify-between items-center">
                <span x-text="label()" class="text-gray-600 truncate"></span>
                <svg class="w-4 h-4 text-gray-400 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-lg max-h-60 overflow-y-auto">
                <template x-for="opt in options" :key="opt.id">
                    <div @click="toggle(opt.id)" class="flex items-center justify-between px-3 py-2 text-sm cursor-pointer hover:bg-gray-100">
                        <span x-text="opt.label"></span>
                        <svg x-show="selected.includes(opt.id)" class="w-4 h-4 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </template>
            </div>
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
