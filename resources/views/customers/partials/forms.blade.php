@if ($isNewCustomer)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Forms will be available after record is saved.</span>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">Forms
    </div>
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <h6 class="text-base font-bold mt-2" style="color: rgba(0,0,0,0.54)">Available Forms</h6>

    @forelse($availableForms as $form)
        <div class="flex items-center justify-between mt-1">
            <div class="flex items-center gap-4">
                <i class="fab fa-wpforms text-xl"></i>
                <p class="text-base">{{ $form->form_name }}</p>
            </div>

            <div class="flex items-center gap-4">
                <i class="fas fa-paper-plane text-[#f18325] text-xl cursor-pointer"></i>
                <i class="fas fa-eye text-[#bdbdbd] text-xl cursor-pointer"></i>
            </div>
        </div>
    @empty
        <div class="mt-2 text-gray-500 text-sm">
            No forms available
        </div>
    @endforelse
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <h6 class="text-base font-bold mt-2" style="color: rgba(0,0,0,0.54)">Sent Forms</h6>
    {{-- <div class="flex items-center justify-between mt-2">
        <div class="flex items-center gap-4">
            <i class="fab fa-wpforms text-xl"></i>
            <p class="text-base">sjfekfwwefweijo</p>
        </div>

        <div class="flex items-center gap-4">
            <i class="fa fa-redo text-[#f18325] text-xl"></i>
            <i class="fas fa-eye text-[#bdbdbd] text-xl"></i>
        </div>
    </div> --}}
    <p class="text-base text-center mt-3">No Forms Required</p>
@endif    