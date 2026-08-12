<x-app-layout>
    <x-slot name="header">
        <div class="px-4 py-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fas fa-dollar-sign mr-2 text-[#B6844A]"></i>{{ __('Commission Claim') }}
            </h2>

        </div>
    </x-slot>

    <div class="bg-white shadow sm:rounded-lg p-3 ml-3 mr-3 mt-2">
        <ol class="list-decimal list-inside space-y-2">
            <li>Search by reservation number to try and find a missing commission you were not paid for (either you forgot to submit, it was submitted after it was paid to Travelux, or the CRM didn't record it properly).</li>
            <li>
                If it's found, CLAIM it! An automatic $15 look up fee will be applied and taken out of the commission amount.
                <ol type="a" class="list-[lower-alpha] list-inside ml-6 mt-2 space-y-1">
                    <li>If the commission is not found, please email DeeAna with the details so she can look into it.</li>
                </ol>
            </li>
            <li>
                At month end, the Accountant for Travelux gets a report of all Commission Claims. <u>He will look in to each one automatically.</u> If he finds Travelux to be at fault, the $15 lookup fee will be removed before the monthly checks are paid.
                <ol type="a" class="list-[lower-alpha] list-inside ml-6 mt-2 space-y-1">
                    <li>Something to keep in mind: check the date created for the unknown claim to the date you either added it to the CRM or marked it Paid in Full. </li>
                    <li>If you put it in after the date it was created, the fee is correct.</li>
                    <li>If you never put it in, the fee is correct.</li>
                    <li>If you didn't mark it paid in full (this date is stamped on your reservation) before the date it was received, the fee is correct.</li>
                    <li>Regardless, the Accountant will look into it and act accordingly.</li>
                </ol>
            </li>
            <li>
                Commission Lookup Tip:
                <ol type="a" class="list-[lower-alpha] list-inside ml-6 mt-2 space-y-1">
                    <li>If you are owed $12, and the look up fee is $15 I suggest not claiming it as you will be in the <b>HOLE -$3!!!!!</b></li>
                </ol>
            </li>
            <li>If you are charged a fee after month end has passed, and you believe it to be Travelux's mistake, please email Travelux with the booking info and details so it can be looked in to.</li>
        </ol>
        <div class="flex gap-4">
            <input type="text" id="reservationNumberSearch" placeholder="Reservation # Search" name="reservationNumberSearch" class="w-50 border-b mt-6 focus:outline-none" />

            <x-primary-btn id="searchReservationBtn" type="button" class="flex items-center gap-2 mt-4"><i class="fas fa-paper-plane mr-2"></i>Submit</x-primary-btn>
        </div>
        <div class="overflow-x-auto mt-5">
            <table class="min-w-full text-sm">
                <thead class="bg-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                            Action
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                            Reservation Number
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                            Customer
                        </th>   
                        <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                            Product
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                            Agent Commission
                        </th>  
                    </tr>
                </thead>

                <tbody class="divide-y" id="commissionClaimTableBody"></tbody>
            </table>
        </div>
    </div>
    
    <div id="copySuccessOverlay" class="fixed inset-0 hidden items-center justify-center bg-black/40 z-[9999]">
        <i class="fas fa-check-square text-[#B6844A] text-8xl"></i>
    </div>
</x-app-layout>
