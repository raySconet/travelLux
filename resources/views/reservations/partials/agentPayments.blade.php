@php 
    $isCommissionFeeModalOpen = session('openCommissionFeesModal') || $errors->commissionFeeStore->any();
@endphp    
@if ($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Agent Payments will be available after Reservation is saved.</span>
        </div>
        <h6 class="font-bold text-[#6c757d] text-base mb-4">Agent Payments</h6>
        <hr class="border-b border-[#bdbdbd]">
        <div class="relative flex flex-row justify-between gap-3 mt-5">
            <h6 class="font-bold text-[#6c757d] text-base">Commission Fees</h6>

            <button type="button" class="text-[#f18325] text-2xl flex-shrink-0">
                <i class="fas fa-plus-circle"></i>
            </button>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-xl">Agent Payment Information</h6>
    </div>
    <h6 class="text-base font-bold mt-2" style="color: rgba(0,0,0,0.54)">Agent Payment</h6>
    <p class="text-base text-center">No payments have been made.</p>
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-base font-bold mt-2" style="color: rgba(0,0,0,0.54)">Commission Fees</h6>

        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0" onclick="openCommissionFeesModal()">
            <i class="fas fa-plus-circle"></i>
        </button>

    </div>
    
    @forelse($reservation->commissionFees()->where('is_deleted',0)->get() as $commissionFee)
        <div class="flex justify-between mt-5" onclick='openEditCommissionFeesModal(@json($commissionFee))'>
            <div class="flex flex-col ml-2">
                <p class="text-lg"><b>Fee: ${{ $commissionFee->amount }}</b></p>
                <p class="text-[#989898]">Type: {{ $commissionFee->fee_type }}</p>
                @if(!empty($commissionFee->notes))
                    <p class="text-[#989898]">Notes: {{ $commissionFee->notes }}</p>
                @endif    
            </div>
            <form method="POST" action="{{ route('commissionFees.delete', $commissionFee->id) }}" class="inline delete-form">
                @csrf
                @method('DELETE')

                <button type="button" onclick="event.stopPropagation(); openDeleteModal(this)">
                    <i title="Delete Fee" class="fas fa-trash text-[#bdbdbd] text-2xl mt-7"></i>
                </button>
            </form>
        </div>
        <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    @empty
        <p class="text-base text-center"></p>  
    @endforelse

@endif      

<!-- Reservation Agent Payments Modal -->
@if(!$isNewReservation)
    <form id="commissionFeeForm" method="POST" action="{{ route('reservations.commissionFees.store', $reservation->id) }}" data-store-url="{{ route('reservations.commissionFees.store', $reservation->id)}}">
        @csrf
        <input type="hidden" id="commission_fee_id_modal" name="commission_fee_id" value="">
        <input type="hidden" name="_method" id="commission_fee_method" value="POST">


        <x-reservations-modal id="commissionFeesModal" title="Add Fee" close="closeCommissionFeesModal()" saveClass="commissionFeesModalSaveBtn" :open="$isCommissionFeeModalOpen">
            <div class="relative mt-3">
                <label for="fee_type">Fee Type</label>
                <select name="fee_type" id="fee_type" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                    <option value="">-- Select Type--</option>
                    <option value="General Fee" {{ old('fee_type', $reservation->fee_type ?? '') == 'General Fee' ? 'selected' : '' }}>General Fee</option>
                </select>
                <x-input-error :messages="$errors->commissionFeeStore->get('fee_type')" />
            </div>

            <div class="relative mt-5">
                <label for="amount" class="block text-sm">Amount</label>
                
                <div class="relative">
                    <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

                    <x-text-input type="text" id="commission_fee_amount" name="amount" class="pl-7" value="{{ old('amount', $reservation->amount ?? '') }}" />
                </div>
                <x-input-error :messages="$errors->commissionFeeStore->get('amount')" />
            </div>

            <div class="relative mt-3">
                <x-text-input type="text" id="commission_fee_notes" name="notes" value="{{ old('notes', $reservation->notes ?? '') }}" />

                <x-input-label for="notes">Notes</x-input-label>
            </div>
        </x-reservations-modal>
    </form>    
@endif    