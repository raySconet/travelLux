@if ($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Agent Payments will be available after Reservation is saved.</span>
        </div>
        <h6 class="font-bold text-[#6c757d] text-base mb-4">Agent Payments</h6>
        <hr class="border-b border-[#bdbdbd]">
        <div class="relative flex flex-row justify-between gap-3 mt-5">
            <h6 class="font-bold text-[#6c757d] text-base">Commission Fees</h6>

            <button type="button" class="text-[#f18325] text-2xl flex-shrink-0">
                <i class="fas fa-plus-circle"></i>
            </button>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-xl">Agent Payment Information</h6>
    </div>
    <h6 class="text-base font-bold mt-2" style="color: rgba(0,0,0,0.54)">Agent Payment</h6>
    <p class="text-base text-center">No payments have been made.</p>
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-base font-bold mt-2" style="color: rgba(0,0,0,0.54)">Commission Fees</h6>

        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0" onclick="openAgentPaymentsModal()">
            <i class="fas fa-plus-circle"></i>
        </button>
    </div>
@endif      

<!-- Reservation Agent Payments Modal -->
<x-reservations-modal id="agentPaymentsModal" title="Add Fee" close="closeAgentPaymentsModal()" saveClass="agentPaymentsModal">
    <div class="relative mt-3">
        <label for="feeType">Fee Type</label>
        <select name="feeType" id="feeType" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
            <option value="-1">-- Select Type--</option>
            <option value="breakfast">Breakfast</option>
            <option value="lunch">Lunch</option>
            <option value="dinner">Dinner</option>
        </select>
    </div>

    <div class="relative mt-5">
        <label for="agentPaymentsAmount" class="block text-sm">Amount/label>
        
        <div class="relative">
            <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

            <x-text-input type="text" id="agentPaymentsAmount" name="agentPaymentsAmount" class="pl-7" />
        </div>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="agentPaymentsNotes" name="agentPaymentsNotes" />

        <x-input-label for="agentPaymentsNotes">Notes</x-input-label>
    </div>
</x-reservations-modal>