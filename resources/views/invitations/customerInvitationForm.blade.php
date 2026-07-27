<x-invitation-layout>
    <div class="bg-[#B6844A] text-white flex justify-center text-[20px] mt-[15px] p-[10px]">
        {{ $customer->fname . ' ' . $customer->lname . ' - Travelux' }}
    </div>

    <div class="flex justify-between items-stretch flex-wrap h-[75px] bg-white mt-[10px] shadow-[0_5px_5px_-11px_rgba(0,0,0,0.2),0_1px_4px_-22px_rgba(0,0,0,0.14),0_3px_14px_2px_rgba(0,0,0,0.12)]">
        <div class="flex items-center flex-row p-6">
            <i class="fas fa-edit text-[22px] text-[#B6844A]" id="personalInformationStepIcon"></i>
            <span class="text-sm ml-[7px] max-[948px]:hidden text-[#B6844A]">Enter your personal information</span>
        </div>

        <hr class="self-center block flex-1 h-0 max-h-0 max-w-full border-t border-t-[rgba(0,0,0,0.12)]"/>

        <div class="flex items-center flex-row p-6">
            <span class="bg-[rgba(0,0,0,0.38)] text-white rounded-full py-[5px] px-[10px]" id="familyMembersStepNumber">2</span>
            <i class="fas fa-edit text-[22px] text-[#B6844A]" id="familyMembersStepIcon" style="display:none;"></i>
            <span class="text-sm ml-[7px] max-[948px]:hidden text-black/40" id="familyMembersStepText">Enter family members</span>
        </div>

        <hr class="self-center block flex-1 h-0 max-h-0 max-w-full border-t border-t-[rgba(0,0,0,0.12)]"/>

        <div class="flex items-center flex-row p-6">
            <span class="bg-[rgba(0,0,0,0.38)] text-white rounded-full py-[5px] px-[10px]" id="interestsStepNumber">3</span>
            <i class="fas fa-edit text-[22px] text-[#B6844A]" id="interestsStepIcon" style="display:none;"></i>
            <span class="text-sm ml-[7px] max-[948px]:hidden text-black/40" id="interestsStepText">Interests/Countries</span>
        </div>

        <hr class="self-center block flex-1 h-0 max-h-0 max-w-full border-t border-t-[rgba(0,0,0,0.12)]"/>

        <div class="flex items-center flex-row p-6">
            <span class="bg-[rgba(0,0,0,0.38)] text-white rounded-full py-[5px] px-[10px]" id="informationReviewStepNumber">4</span>
            <i class="fas fa-edit text-[22px] text-[#B6844A]" id="informationReviewStepIcon" style="display:none;"></i>
            <span class="text-sm ml-[7px] max-[948px]:hidden text-black/40" id="informationReviewStepText">Review your information</span>
        </div>

        <hr class="self-center block flex-1 h-0 max-h-0 max-w-full border-t border-t-[rgba(0,0,0,0.12)]"/>

        <div class="flex items-center flex-row p-6">
            <span class="bg-[rgba(0,0,0,0.38)] text-white rounded-full py-[5px] px-[10px]" id="informationSavedStepNumber">5</span>
            <i class="fas fa-check-circle text-[22px] text-[#B6844A]" id="informationSavedStepIcon" style="display:none;"></i>
            <span class="text-sm ml-[7px] max-[948px]:hidden text-black/40" id="informationSavedStepText">Information saved</span>
        </div>
    </div>

    <form id="invitationForm">
        @csrf

        <input type="hidden" name="encryptedInvitationId" value="{{ $encryptedInvitationId }}">
        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
        <input type="hidden" name="agent_id" value="{{ $customer->agent_id }}">

        <div id="step1">
            <div class="mx-auto py-2 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="p-3 bg-white shadow">
                    @include('customers.partials.customer-invitations.invite-customer-info')
                </div>

                <div class="p-3 bg-white shadow">
                    @include('customers.partials.home-address')
                </div>
            </div>
        </div>

        <div id="step2" class="hidden bg-white p-5" style="margin-top: 25px;">
            @include('customers.partials.customer-invitations.invite-family')
        </div>

        <div id="step3" class="hidden">
            @include('customers.partials.customer-invitations.invite-areas-countries')
        </div>

        <div id="step4" class="hidden">
            @include('customers.partials.customer-invitations.invite-review-your-information')
        </div>

        <div id="step5" class="hidden">
            @include('customers.partials.customer-invitations.invite-information-saved')
        </div>
    </form>


    <div class="flex justify-end mt-2 gap-2">
        <button type="button" id="backBtn" class="hidden pace-x-2 bg-[#bdbdbd] border text-white py-2 px-8 rounded cursor-pointer  hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd] transition-all duration-200">
            BACK
        </button>

        <x-primary-btn type="button" id="nextBtn">
            NEXT
        </x-primary-btn>
    </div>
</x-invitation-layout>