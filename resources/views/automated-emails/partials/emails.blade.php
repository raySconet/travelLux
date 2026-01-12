<div>
    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <label for="emailType">Email Type</label>
            <select name="emailType" id="emailType" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Email Type--</option>
                <option value="anniversaryEmail">Anniversary Email</option>
                <option value="upcomingAnniversaryEmail">Upcoming Anniversary Email</option>
                <option value="birthdayEmail">Birthday Email</option>
                <option value="upcomingBirthdayEmail">Upcoming Birthday Email</option>
                <option value="newYearEmail">New Year Email</option>
                <option value="reservationReminder">Reservation Reminder</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <x-text-input type="text" id="emailSubject" name="emailSubject"  />

            <x-input-label for="emailSubject">Email Subject</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-8">
            <label for="emailType">Send Before/After</label>
            <select name="emailType" id="emailType" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Before/After--</option>
                <option value="before">Before</option>
                <option value="after">After</option>
            </select>
        </div>

        <div class="relative mt-7">
            <x-text-input type="text" id="days" name="days"  />

            <x-input-label for="days">Days</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <x-text-input type="text" id="message" name="message"  />

            <x-input-label for="message">Message</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-8">
            <label for="emailType">Automated Email For</label>
            <select name="emailType" id="emailType" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Agent--</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-3">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">BCC agent on this email</label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-x-5 gap-y-4 items-end">
        <div class="relative mt-8">
            <label for="emailType">Product</label>
            <select name="emailType" id="emailType" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Product--</option>
            </select>
        </div>

        <div class="relative mt-8">
            <label for="emailType">Destination</label>
            <select name="emailType" id="emailType" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Destination--</option>
            </select>
        </div>

        <div class="relative mt-8">
            <label for="emailType">Resort/Ship</label>
            <select name="emailType" id="emailType" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Resort/Ship--</option>
            </select>
        </div>

        <div class="relative mt-8">
            <label for="emailType">Cruise/Type</label>
            <select name="emailType" id="emailType" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Cruise/Type--</option>
            </select>
        </div>

        <div class="flex justify-center items-center">
            <i class="fa-solid fa-circle-plus text-[#f18325] mb-4 text-2xl cursor-pointer"></i>
        </div>
    </div>

    <div class="mt-7 space-x-2">
        <label class="text-base">Attachments</label>
        <i class="fas fa-paperclip text-[#f18325] text-base"></i>

        <div class="overflow-x-auto mt-2">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-base font-bold 
                                       border-b-2 border-t-2 border-[#dee2e6] w-1/12">
                                Actions
                            </th>
                            <th class="px-4 py-3 text-left text-base font-bold 
                                       border-b-2 border-t-2 border-[#dee2e6]">
                                File Name
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        {{-- @foreach ($users as $user) --}}
                            <tr class="hover:bg-gray-50 cursor-pointer" >
                                <td class="px-4 py-3 font-medium text-gray-800">
                                  test
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                test
                                </td>

                            </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
    </div>

</div>