<div>
    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div  class="relative mt-3">
            <label for="email_type">Email Type</label>
            <select name="email_type" id="email_type" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="" {{ old('email_type', $automatedEmail->email_type ?? '') == '-1' ? 'selected' : '' }}>--Select Email Type--</option>
                <option value="Anniversary Email" {{ old('email_type', $automatedEmail->email_type ?? '') == 'Anniversary Email' ? 'selected' : '' }} >Anniversary Email</option>
                <option value="Upcoming Anniversary Email" {{ old('email_type', $automatedEmail->email_type ?? '') == 'Upcoming Anniversary Email' ? 'selected' : '' }}>Upcoming Anniversary Email</option>
                <option value="Birthday Email" {{ old('email_type', $automatedEmail->email_type ?? '') == 'Birthday Email' ? 'selected' : '' }}>Birthday Email</option>
                <option value="Upcoming Birthday Email" {{ old('email_type', $automatedEmail->email_type ?? '') == 'Upcoming Birthday Email' ? 'selected' : '' }}>Upcoming Birthday Email</option>
                <option value="New Year Email" {{ old('email_type', $automatedEmail->email_type ?? '') == 'New Year Email' ? 'selected' : '' }}>New Year Email</option>
                <option value="Reservation Reminder" {{ old('email_type', $automatedEmail->email_type ?? '') == 'Reservation Reminder' ? 'selected' : '' }}>Reservation Reminder</option>
                <option value="Agents Birthday Email" {{ old('email_type', $automatedEmail->email_type ?? '') == 'Agents Birthday Email' ? 'selected' : '' }}>Agents Birthday Email</option>
                <option value="Return/Survey Email" {{ old('email_type', $automatedEmail->email_type ?? '') == 'Return/Survey Email' ? 'selected' : ''}}>Return/Survey Email</option>
            </select>

            <x-input-error :messages="$errors->get('email_type')" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <x-text-input type="text" id="subject" name="subject" value="{{ old('subject', $automatedEmail->subject ?? '') }}" />

            <x-input-label for="subject">Email Subject</x-input-label>

            <x-input-error :messages="$errors->get('subject')" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-8">
            <label for="before_after">Send Before/After</label>
            <select name="before_after" id="before_after" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="" {{ old('before_after', $automatedEmail->before_after ?? '') == '-1' ? 'selected' : '' }}>--Select Before/After--</option>
                <option value="Before" {{ old('before_after', $automatedEmail->before_after ?? '') == 'Before' ? 'selected' : '' }}>Before</option>
                <option value="After" {{ old('before_after', $automatedEmail->before_after ?? '') == 'After' ? 'selected' : '' }}>After</option>
            </select>

            <x-input-error :messages="$errors->get('before_after')" />
        </div>

        <div class="relative mt-7">
            <x-text-input type="text" id="days" name="days" value="{{ old('days', $automatedEmail->days ?? '') }}" />

            <x-input-label for="days">Days</x-input-label>

            <x-input-error :messages="$errors->get('days')" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="mt-3">
            <label for="message">Message</label>

            <textarea 
                id="message" 
                name="message"
                rows="3"
                class="w-full border-b-2 border-[#bdbdbd] rounded-sm p-2 focus:outline-none"
            >{{ old('message', $automatedEmail->message ?? '') }}</textarea>

            <x-input-error :messages="$errors->get('message')" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-8">
            <label for="agent_id">Automated Email For</label>
            <select name="agent_id" id="agent_id" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Agent--</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('agent_id', $automatedEmail->agent_id ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->fname . ' ' . $user->lname }}
                        </option>
                    @endforeach        
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-3">
            <input type="hidden" name="bcc_agent" value="0">
            <input type="checkbox" class="h-4 w-4" name="bcc_agent" value="1" {{ old('bcc_agent', $automatedEmail->bcc_agent ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">BCC agent on this email</label>
        </div>
    </div>

    <div id="reservationReminderSection" style="display: {{ old('email_type', $automatedEmail->email_type ?? '') === 'Reservation Reminder' ? 'grid' : 'none' }};" class="grid grid-cols-1 md:grid-cols-5 gap-x-5 gap-y-4 items-end">
        <div class="relative mt-8">
            <label for="product_list">Product</label>
            <select name="product_list" id="product_list" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="">--Select Product--</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                            {{ old('product_list', $automatedEmail->product_list) == $product->id ? 'selected' : '' }}>
                            {{ $product->product_name }}
                    </option>
                @endforeach            
            </select>
        </div>

        <div class="relative mt-8">
            <label for="destination_list">Destination</label>
            <select name="destination_list" id="destination_list" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="">--Select Destination--</option>
                @foreach($destinations as $destination)
                    <option value="{{ $destination->id }}"
                            {{ old('destination_list', $automatedEmail->destination_list) == $destination->id ? 'selected' : ''}}>
                            {{ $destination->destination_name }}
                    </option>
                @endforeach            
            </select>
        </div>

        <div class="relative mt-8">
            <label for="resort_list">Resort/Ship</label>
            <select name="resort_list" id="resort_list" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="">--Select Resort/Ship--</option>
                @foreach($resortShips as $resortShip)
                    <option value="{{ $resortShip->id }}"
                            {{ old('resort_list', $automatedEmail->resort_list) == $resortShip->id ? 'selected' : '' }}>
                            {{ $resortShip->resort_ship_name }}
                    </option>
                @endforeach            
            </select>
        </div>

        <div class="relative mt-8">
            <label for="cruise_itinerary_list">Cruise/Type</label>
            <select name="cruise_itinerary_list" id="cruise_itinerary_list" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="">--Select Cruise/Type--</option>
                @foreach($cruiseItineraries as $cruiseItinerary)
                    <option value="{{ $cruiseItinerary->id }}"
                            {{ old('cruise_itinerary_list', $automatedEmail->cruise_itinerary_list) == $cruiseItinerary->id ? 'selected' : '' }}>
                            {{ $cruiseItinerary->cruise_name }}
                    </option>
                @endforeach            
            </select>
        </div>

        <div class="flex justify-center items-center">
            <i id="addRowAutomatedEmails" class="fa-solid fa-circle-plus text-[#f18325] mb-4 text-2xl cursor-pointer"></i>
        </div>
    </div>
    <div id="addRowAutomatedEmailsContainer" class="mt-4 space-y-3"></div>


    <div class="mt-7 space-x-2">
        <label class="text-base">Attachments</label>
        <i class="fas fa-paperclip text-[#f18325] text-base"></i>

        <div class="overflow-x-auto mt-2">
            <table class="min-w-full text-sm">
                <thead class="bg-white">
                    <tr>
                        <th class="w-1/12 px-4 py-3 text-left text-sm font-extrabold border-b-2 border-t-2 border-[#dee2e6]">
                            Actions
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-extrabold border-b-2 border-t-2 border-[#dee2e6]">
                            File Name
                        </th>
                    </tr>
                </thead>


                @if(!$isNewAutomatedEmail)
                    <tbody class="divide-y">
                        {{-- @foreach ($users as $user) --}}
                            <tr class="hover:bg-gray-50 cursor-pointer" >
                                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                                    <i class="fas fa-trash text-center text-lg text-[#989898] ml-3"></i>
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                                test
                                </td>

                            </tr>
                        {{-- @endforeach --}}
                    </tbody>
                @endif    
            </table>
        </div>
    </div>

</div>