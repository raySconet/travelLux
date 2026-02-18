@if ($isNewCustomer)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Surveys will be available after record is saved.</span>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">Surveys</h6>
    </div>    
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <p class="text-base text-center mt-3">No Surveys Available</p>
@endif    