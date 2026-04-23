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

    <div class="flex justify-between mt-5">
        <div class="flex space-x-3">
            <i class="fas fa-file text-[#000] text-2xl mt-3"></i>
            <div class="flex flex-col">
                <p class="text-base">test</p>
                <p class="text-[#989898] text-sm">Size:... Bytes</p>
            </div>
        </div>

        <div class="space-x-4">
            <i title="Download Attachment" class="fas fa-cloud-download-alt text-[#bdbdbd] text-xl mt-3"></i>
            <i title="Delete Attachment" class="fas fa-trash text-[#bdbdbd] text-xl mt-3"></i>
        </div>
    </div>
@endif    