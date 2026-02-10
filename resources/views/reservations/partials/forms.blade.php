@if ($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Forms will be available after record is ssaved.</span>
        </div>
    </div>   
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">Forms</h6>
    </div>     
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <h6 class="text-base font-bold mt-2" style="color: rgba(0,0,0,0.54)">Available Forms</h6>
    <div class="flex items-center justify-between mt-1">
        <div class="flex items-center gap-4">
            <i class="fab fa-wpforms text-xl"></i>
            <p class="text-base">test</p>
        </div>

        <div class="flex items-center gap-4">
            <i class="fas fa-paper-plane text-[#f18325] text-xl"></i>
            <i class="fas fa-eye text-[#bdbdbd] text-xl"></i>
        </div>
    </div>
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-col mt-2">
        <h6 class="text-base font-bold mt-2" style="color: rgba(0,0,0,0.54)"> Sent Forms </h6>
        <p class="text-base text-center">No Forms Required</p>
    </div>
@endif    