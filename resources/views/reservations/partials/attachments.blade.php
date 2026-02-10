@if ($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Attachments will be available after Reservation is saved.</span>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-xl">Reservation Attachments</h6>

        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0">
            <i class="fas fa-plus-circle"></i>
        </button>
    </div>    
@endif    