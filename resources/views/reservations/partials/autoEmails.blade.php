@if($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Auto Emails Information will be available after customer is saved.</span>
        </div>
    </div>
@else
    @php
        $autoEmails = $reservation->automatedEmails()->with('automatedEmail')->get();
    @endphp
<div>
    <h6 class="text-xl">Sent Auto Emails</h6>

    <div class="overflow-x-auto mt-4">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b-2 border-t-2 border-[#dee2e6]">
                    <th class="w-7/12 px-3 py-2 text-left font-bold">Auto Email</th>
                    <th class="w-4/12 px-3 py-2 text-left font-bold">Date</th>
                    <th class="w-1/12 px-3 py-2 text-left font-bold"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($autoEmails as $item)
                    <tr class="border-b-1 border-[#dee2e6]">
                        <td class="w-7/12 px-3 py-2">
                            {{ $item->automatedEmail->subject  }}
                        </td>
                        <td class="w-4/12 px-3 py-2">
                            {{ $item->date ? \Carbon\Carbon::parse($item->date)->format('m/d/Y') : '' }}
                        </td>
                        <td class="w-1/12 flex mt-3 space-x-6 text-lg mr-4"> 
                            <i title="Resend to Customer" class="fas fa-paper-plane text-[#B6844A] cursor-pointer"></i> 
                            <i title="View Email" class="fas fa-eye text-[#bdbdbd] cursor-pointer" onclick='openReservationAutomatedEmailModal(@json($item->automatedEmail->message),@json($reservation->agent->fname ?? ""),@json($reservation->agent->lname ?? ""),@json($reservation->agent->phone_number ?? ""),@json($reservation->agent->email ?? ""))'></i>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center py-6">
                            No Auto Emails
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>  
@endif  

<!-- Reservation Automated Email modal -->
<div id="reservationAutomatedEmailModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[9999]">
    
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg relative">
        

        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
            <h2 class="text-base">Reservation Automated Email</h2>

            <button type="button" onclick="closeReservationAutomatedEmailModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                ✕
            </button>
        </div>

        <div class="px-5 py-4 space-y-4">
    
            <div id="reservationAutomatedEmailMessage" class="text-sm whitespace-pre-wrap"></div>

            <div class="pt-4">
                <p class="text-[#FF6600] text-base font-extrabold">
                    Thank you, please let me know if I can further assist you!
                </p>

                <div class="mt-4 text-base text-[#FF6600] space-y-1 font-extrabold">
                    <p id="reservationAutomatedEmailAgentName"></p>
                    <p id="reservationAutomatedEmailAgentCellphone"></p>
                </div>
                <p id="reservationAutomatedEmailAgentEmail" class="text-[#3B38FF] text-base font-extrabold"></p>
                <p class="text-[#3B38FF] text-base font-extrabold mt-4">www.archerluxurytravel.com</p>
                <p class="text-[#006FC9] text-sm font-extrabold mt-4">I book everything from hotels,Disney,Universal Studios,All Inclusive resorts,all cruise lines and more!</p>
            </div>

        </div>
        
        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6]">
            <button type="button" onclick="closeReservationAutomatedEmailModal()" class="space-x-2 bg-[#bdbdbd] text-white font-semibold py-2 px-7 rounded cursor-pointer border border-transparent hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd] transition-all duration-200">
                <i class="fa fa-times-circle"></i>
                <span>Close</span>
            </button>
        </div>
    </div>

</div>