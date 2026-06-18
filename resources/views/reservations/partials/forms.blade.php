@if ($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Forms will be available after record is saved.</span>
        </div>
    </div>   
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">Forms</h6>
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
                <i title="Send to Customer" class="fas fa-paper-plane text-[#B6844A] text-xl cursor-pointer"></i>
                <i title="Preview Form" onclick='openFormPreviewModal(@json($form->preview_form_html_content))' class="fas fa-eye text-[#bdbdbd] text-xl cursor-pointer"></i>
            </div>
        </div>
    @empty
        <p class="text-center text-base">No Forms Available</p>
    @endforelse            
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-col mt-2">
        <h6 class="text-base font-bold mt-4" style="color: rgba(0,0,0,0.54)">Sent Forms</h6>

        @forelse($sentForms as $sentForm)
            @php
                $formName = $sentForm->form?->form_name;
                $sentOn = \Carbon\Carbon::parse($sentForm->sent_on)->format('m/d/Y h:i:s A');

                if ($sentForm->submit_flag == 1) {
                    if ($sentForm->submitted_on) {
                        $submittedOn = \Carbon\Carbon::parse($sentForm->submitted_on)->format('m/d/Y h:i:s A');

                        $submittedText = "<span class='text-[#50c878]'>
                            Customer Submitted on {$submittedOn}
                        </span>";
                    } else {
                        $submittedText = "<span class='text-[#50c878]'>
                            Submitted by Customer
                        </span>";
                    }
                } else {
                    $submittedText = "<span class='text-[#B6844A]'>
                        Not Submitted By Customer
                    </span>";
                }
            @endphp

            <div class="flex items-center justify-between mt-2">
                <div class="flex items-center gap-4">
                    <i class="fab fa-wpforms text-xl"></i>

                    <div class="flex flex-col">
                        <p class="text-base font-medium">{{ $formName }}</p>

                        <p class="text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Form Sent On {{ $sentOn }}
                        </p>

                        <p class="text-sm">{!! $submittedText !!}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <i title="Re-Send Link" class="fa fa-redo text-[#B6844A] text-xl cursor-pointer"></i>
                    <i title="View Form" class="fas fa-eye text-[#bdbdbd] text-xl cursor-pointer" onclick='openFormPreviewModal(@json($sentForm->form?->preview_form_html_content))'>
                    </i>
                </div>
            </div>

        @empty
            <p class="text-base text-center mt-3">
                No Forms Required
            </p>
        @endforelse
    </div>
@endif    
<x-form-preview />