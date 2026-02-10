@if ($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Phone Notes will be available after Reservation is saved.</span>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-xl">Phone Notes</h6>

        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0" onclick="openPhoneNotesModal()">
            <i class="fas fa-plus-circle"></i>
        </button>
    </div>    
    <p class="text-base text-center">No Phone Notes.</p>
@endif    

<!-- Reservation Phone Notes Modal -->
<x-reservations-modal id="phoneNotesModal" title="Add Phone Notes" close="closePhoneNotesModal()" saveClass="phoneNotesModalSaveBtn">
    <div class="relative mt-3">
        <label for="category">Category</label>
        <select name="category" id="category" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
            <option value="-1">--Select Category --</option>
            <option value="breakfast">Breakfast</option>
            <option value="lunch">Lunch</option>
            <option value="dinner">Dinner</option>
        </select>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="callerName" name="callerName" />

        <x-input-label for="callerName">Caller Name</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="callerPhoneNumber" name="callerPhoneNumber" />

        <x-input-label for="callerPhoneNumber">Caller Phone Number</x-input-label>
    </div>

    <div class="flex flex-col mt-3">
        <label for="phoneNotes" class="mb-1 text-sm text-gray-700">Notes</label>
        <textarea
            id="phoneNotes"
            name="phoneNotes"
            rows="3"
            class="w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1">
        </textarea>
    </div>
</x-reservations-modal>