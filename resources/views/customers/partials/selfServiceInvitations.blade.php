<div class="relative flex flex-row justify-between gap-3 mt-5">
    <div class="flex flex-row gap-2">
        <h6 class="text-xl">Self Service Invitations</h6>
        <div class="relative group">
            <i class="fas fa-info-circle text-xl mt-1 cursor-pointer"></i>
            
            <span class="absolute left-0 top-8 bg-gray-500 text-white text-sm rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 -translate-x-1/2">
                Customers receive an email with temporary link to <br> perform certain activities 
            </span>
        </div>
    </div>

    <button type="button" id="sendNewInvitationBtn" data-customer="{{ $customer->id }}" class="space-x-2 bg-[#B6844A] text-white font-semibold py-2 px-7 rounded cursor-pointer border border-transparent hover:bg-white hover:border-[#B6844A] hover:text-[#B6844A] transition-all duration-200 ">
        <i class="fas fa-paper-plane"></i>
        Send New Invitation
    </button>
</div> 
<hr class="mt-4 w-full border-b-1 border-[#dee2e6]">
<div class="relative flex flex-col gap-3 mt-3">
    <div class="flex flex-row gap-2">
        <h6 class="text-base">Invitations to Update Profile Information</h6>

        <div class="relative group">
            <i class="fas fa-info-circle text-xl mt-1 cursor-pointer"></i>

            <span class="absolute left-0 top-8 bg-gray-500 text-white text-sm rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 -translate-x-1/2">
                Allows updating of personal info including:<br>
                Name <br> Contact Info <br> Birthdate <br> Additional Family Members
            </span>
        </div>
    </div>

    @if(!$isNewCustomer)
        @if($invitations->count())
            @foreach($invitations as $invitation)

                @php
                    $sentOn = \Carbon\Carbon::parse($invitation->created_on);
                    $expiresOn = (clone $sentOn)->addDay();

                    $statusText = '';
                    $expiredText = '';
                    $lineThrough = '';

                    if ($invitation->status === 'C') {
                        $statusText = "<div class='text-[#B6844A]'>Not Submitted By Customer</div>";
                        $lineThrough = "lineThrough";
                    }

                    if ($invitation->status === 'P') {
                        $statusText = "<div class='text-[#B6844A]'>Not Submitted By Customer</div>";
                    }

                    if ($invitation->status === 'S' && $invitation->submit_flag == 1) {
                        if ($invitation->submitted_on) {
                            $submittedOn = \Carbon\Carbon::parse($invitation->submitted_on)->format('m/d/Y h:i:s A');
                            $statusText = "<div class='text-[#50c878]'>Customer Submitted On {$submittedOn}</div>";
                        } else {
                            $statusText = "<div class='text-[#50c878]'>Customer Submitted</div>";
                        }
                    }

                    if ($invitation->expired_flag == 1) {
                        $expiredText = "<span class='text-[#ed2939] ml-3'>Expired</span>";
                    }
                @endphp

                <div class="flex gap-4 items-start  pb-3">

                    <i class="fas fa-address-card mt-3 text-2xl"></i>

                    <div class="text-sm flex-1">

                        <div>
                            <i class="fas fa-calendar-alt"></i>
                            <span class="{{ $invitation->status === 'C' ? 'line-through' : '' }}">
                                Sent On {{ $sentOn->format('m/d/Y h:i:s A') }}
                            </span>
                        </div>

                        <div>
                            <i class="fas fa-clock"></i>
                            <span class="{{ $invitation->status === 'C' ? 'line-through' : '' }}">
                                Expires On {{ $expiresOn->format('m/d/Y h:i:s A') }}
                            </span>

                            {!! $expiredText !!}
                        </div>

                        <div>{!! $statusText !!}</div>

                    </div>

                    @if($invitation->status === 'C')
                        <div class="ml-auto flex items-center">
                            <div class="bg-[#e0e0e0] text-[#212121] rounded-full px-4 py-1 text-sm font-medium text-center">
                                Canceled
                            </div>
                        </div>
                    @endif

                </div>

            @endforeach
        @else
            <div class="text-center">
                There are no invitations for this customer.
            </div>
        @endif
    @endif
</div>
<hr class="mt-3 w-full border-b-1 border-[#dee2e6]">

<div class="relative flex flex-row justify-between gap-3 mt-5">
    <h6 class="text-xl">Intake Form</h6>

    <button type="button" id="sendIntakeFormBtn" data-customer="{{ $customer->id }}" class="space-x-2 bg-[#B6844A] text-white font-semibold py-2 px-7 rounded cursor-pointer border border-transparent hover:bg-white hover:border-[#B6844A] hover:text-[#B6844A] transition-all duration-200">
        <i class="fas fa-paper-plane"></i>
        Send Intake Form
    </button>
</div>

<hr class="mt-3 w-full border-b-1 border-[#dee2e6]">

<div class="mt-5 flex flex-col gap-4">

    @if(!$isNewCustomer)
        @if($intakeForms->count())

            @foreach($intakeForms as $form)

                @php
                    $sentOn = \Carbon\Carbon::parse($form->created_on)->format('m/d/Y h:i:s A');

                    $resentOn = null;
                    if (!empty($form->resent_on)) {
                        $resentOn = \Carbon\Carbon::parse($form->resent_on)->format('m/d/Y h:i:s A');
                    }

                    $statusText = '';

                    if ($form->status === 'P') {
                        $statusText = "<div class='text-[#B6844A]'>Not Submitted By Customer</div>";
                    }

                    if ($form->status === 'S' && $form->submit_flag == 1) {
                        if ($form->submitted_on) {
                            $submittedOn = \Carbon\Carbon::parse($form->submitted_on)->format('m/d/Y h:i:s A');
                            $statusText = "<div class='text-[#50c878]'>Customer Submitted On {$submittedOn}</div>";
                        } else {
                            $statusText = "<div class='text-[#50c878]'>Customer Submitted</div>";
                        }
                    }
                @endphp

                <div class="flex gap-4 items-start {{ ($form->status === 'S' && $form->submit_flag == 1) ? 'cursor-pointer hover:bg-gray-50 rounded p-2 transition' : '' }}"

                    @if($form->status === 'S' && $form->submit_flag == 1)
                        onclick='openFormIntakePreviewModal(@json($form->submitted_form_content))'
                        title="Click to Open"
                    @endif
                >

                    <div class="flex flex-col items-center mt-4">
                        <i class="fas fa-address-card text-2xl"></i>

                        @if($form->status !== 'S')
                            <button type="button" class="mt-2 resendIntakeFormBtn cursor-pointer" data-form="{{ $form->id }}" title="Resend">
                                <i class="fas fa-redo text-xl text-[#B6844A]"></i>
                            </button>
                        @endif
                    </div>

                    <div class="text-base flex-1">

                        <div class="font-medium">
                            Trip {{ $form->counter }}
                        </div>

                        <div>
                            <i class="fas fa-calendar-alt"></i>
                            <span>Sent On {{ $sentOn }}</span>
                        </div>

                        @if($resentOn)
                            <div>
                                <i class="fas fa-calendar-alt"></i>
                                <span>Resent On {{ $resentOn }}</span>
                            </div>
                        @endif

                        <div>{!! $statusText !!}</div>

                    </div>

                </div>

            @endforeach

        @else
            <div class="text-center">No Intake Forms Required</div>
        @endif
    @endif

</div>
<x-form-intake-preview />