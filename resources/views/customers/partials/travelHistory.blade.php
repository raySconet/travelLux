@if ($isNewCustomer)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Travel History will be available after customer is saved.</span>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">Travel History</h6>
    </div>    
    <div class="relative flex flex-col mt-5">
        <h6 class="text-base">Leads</h6>
        <p class="text-base text-center">No Leads.</p>
    </div>
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-col mt-3">
        <h6 class="text-base">Upcoming</h6>
        <p class="text-base text-center">No Upcoming Travel</p>
    </div>
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-col mt-3">
        <h6 class="text-base">History</h6>
        <p class="text-base text-center">No Travel History</p>
    </div>
@endif 