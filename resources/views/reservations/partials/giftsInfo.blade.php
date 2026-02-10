@if ($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Gifts Info will be available after Reservation is saved.</span>
        </div>
    </div>
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-xl">Gifts</h6>
        
        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0" onclick="openGiftsModal()">
            <i class="fas fa-plus-circle"></i>
        </button>
    </div>
    <p class="text-base text-center">No Gifts Available.</p>
@endif        

<!-- Reservation Gifts Modal -->
<x-reservations-modal id="giftsModal" title="Add Gift" close="closeGiftsModal()" saveClass="giftsModalSaveBtn">

    <div class="relative mt-3">
        <label for="giftType">Gift Type</label>
        <select name="giftType" id="giftType" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
            <option value="-1">-- Select Gift --</option>
            <option value="giftToTheRoom">Gift To The Room</option>
            <option value="onboardCredit">Onboard Credit</option>
            <option value="spaDay">Spa Day</option>
            <option value="other">Other</option>
        </select>
    </div>

    <div x-data="dateDropdown()" class="relative mt-3">
        <label class="block text-sm mb-1">Gift Date</label>
        <div class="flex w-full border-b-2 border-[#bdbdbd] overflow-hidden outline-none">
            <select x-model="year" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                <option value="">Year</option>
                <template x-for="y in years" :key="y">
                    <option :value="y" x-text="y"></option>
                </template>
            </select>

            <select x-model="month" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                <option value="">Month</option>
                <template x-for="(m, i) in months" :key="i">
                    <option value="i + 1" x-text="m"></option>
                </template>
            </select>

            <select x-model="day" class="flex-1 border-0 focus:ring-0 focusLoutline-none px-3 py-2">
                <option value="">Day</option>
                <template x-for="d in days" :key="d">
                    <option :value="d" x-text="d"></option>
                </template>
            </select>
        </div>
    </div>

    <div class="relative mt-5">
        <label for="amount" class="block text-sm">Amount</label>
        
        <div class="relative">
            <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

            <x-text-input type="text" id="amount" name="amount" class="pl-7" />
        </div>
    </div>

    <div class="flex flex-col mt-3">
        <label for="giftsInfoNotes" class="mb-1 text-sm text-gray-700">Notes</label>
        <textarea
            id="giftsInfoNotes"
            name="giftsInfoNotes"
            rows="3"
            class="w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1">
        </textarea>
    </div>
</x-reservations-modal>