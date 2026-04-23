@php 
    $isPaymentModalOpen = session('openPaymentModal') || $errors->paymentStore->any();
@endphp    
@if($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Payments will be available after Reservation is saved.</span>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg space-x-2"><span>Credit Card Authorization Form</span> <i class="fas fa-paper-plane text-[#f18325] text-lg"></i></h6>
    </div>  

    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">Reservation Payments</h6>

        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0" onclick="openReservationPaymentsModal()">
            <i class="fas fa-plus-circle"></i>
        </button>
    </div>

    <div class="relative flex flex-col">
        @forelse($reservation->payments()->where('is_deleted',0)->get() as $payment)
            <hr class="mt-6 w-full border-b-1 border-[#dee2e6]">
            <div class="flex justify-between text-base mb-4 mt-2" onclick='openEditPaymentModal(@json($payment))'>
                <div class="flex flex-col text-sm">
                    <div class="flex space-x-2 text-base">
                        <i class="fas fa-calendar-alt mt-1"></i>
                        <p>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('m/d/Y') : ' ' }}</p>
                    </div>
                    <div class="flex space-x-4 mt-1">
                        <p class="bg-[#50c878] text-[#fff] rounded-xl px-2">$ {{ number_format($payment->amount, 2) }}</p>
                        <p class="text-[#696969]">{{ $payment->payment_method }}</p>
                        <p class="bg-[#e0e0e0] rounded-none px-4 text-[#212121]">{{ $payment->payment_type }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('reservations.payments.delete', $payment->id) }}" class="inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="event.stopPropagation(); openDeleteModal(this)">
                        <i title="Delete Payment" class="fas fa-trash text-[#bdbdbd] mt-3 text-xl"></i>
                    </button>
                </form>
            </div>
        @empty
            <p class="text-center text-base">No Customer Payments</p>
        @endif        

        <div class="flex flex-col gap-2" style="background-color: rgba(241, 131, 37,0.2)">
            <p class="text-right text-base mt-2 mr-2">Total Reservation Cost:<b>$ {{ number_format($reservation->reservation_cost, 2) }}</b></p>
            @php
                $totalPayments = $reservation->payments()
                    ->where('is_deleted', 0)
                    ->sum('amount');

                $balanceDue = ($reservation->reservation_cost ?? 0) - $totalPayments;
            @endphp

            <p class="text-right text-base mb-2 mr-2">Balance Due: <b>$ {{ number_format($balanceDue, 2) }}</b></p>
        </div>
    </div>

    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">

    <div class="relative flex flex-col">
        <p class="text-base mt-2">Important Payment Date</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div x-data="dateDropdown('{{ old('deposit_due_date', $reservation->deposit_due_date ?? '') }}')" class="relative mt-5">
                <label class="block text-sm mb-1">Deposit Due Date</label>
                <div class="flex w-full border-b-2 border-[#bdbdbd] overflow-hidden outline-none">
                    <select x-model="year" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                        <option value="">Year</option>
                        <template x-for="y in years" :key="y">
                            <option :value="y" x-text="y" :selected="year == y"></option>
                        </template>
                    </select>

                    <select x-model="month" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                        <option value="">Month</option>
                        <template x-for="(m, i) in months" :key="i">
                            <option :value="i + 1" x-text="m" :selected="month == i + 1"></option>
                        </template>
                    </select>

                    <select x-model="day" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                        <option value="">Day</option>
                        <template x-for="d in days" :key="d">
                            <option :value="d" x-text="d" :selected="day == d"></option>
                        </template>
                    </select>
                </div>

                <input type="hidden" name="deposit_due_date" :value="formattedDate">
            </div>

            <div x-data="dateDropdown('{{ old('final_payment_due_date', $reservation->final_payment_due_date ?? '') }}')" class="relative mt-5">
                <label class="block text-sm mb-1">Final Payment Date</label>
                <div class="flex w-full border-b-2 border-[#bdbdbd] overflow-hidden outline-none">
                    <select x-model="year" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                        <option value="">Year</option>
                        <template x-for="y in years" :key="y">
                            <option :value="y" x-text="y" :selected="year == y"></option>
                        </template>
                    </select>

                    <select x-model="month" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                        <option value="">Month</option>
                        <template x-for="(m, i) in months" :key="i">
                            <option :value="i + 1" x-text="m" :selected="month == i + 1"></option>
                        </template>
                    </select>

                    <select x-model="day" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                        <option value="">Day</option>
                        <template x-for="d in days" :key="d">
                            <option :value="d" x-text="d" :selected="day == d"></option>
                        </template>
                    </select>
                </div>

                <input type="hidden" name="final_payment_due_date" :value="formattedDate">
            </div>
        </div>
    </div>
@endif    

<!-- Reservation Payments Modal -->
@if(!$isNewReservation)
    <form id="paymentForm" method="POST" action="{{ route('reservations.payments.store', $reservation->id) }}" data-store-url="{{ route('reservations.payments.store', $reservation->id)}}">
        @csrf
        <input type="hidden" id="payment_id_modal" name="payment_id" value="">
        <input type="hidden" name="_method" id="payment_method" value="POST">

        <x-reservations-modal id="reservationPaymentsModal" title="Add Payment" close="closeReservationPaymentsModal()" saveClass="reservationPaymentsSaveBtn" :open="$isPaymentModalOpen">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div class="relative mt-2">
                    <x-text-input type="text" id="payment_amount_modal" name="amount" />

                    <x-input-label for="amount">Amount</x-input-label>

                    <x-input-error :messages="$errors->paymentStore->get('amount')" />
                </div>

                <div class="relative mt-4">
                    <label for="payment_type">Payment Type</label>
                    <select name="payment_type" id="payment_type" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                        <option value="">-- Select Payment Type--</option>
                        <option value="Deposit">Deposit</option>
                        <option value="Final Payment">Final Payment</option>
                        <option value="Full Payment">Full Payment</option>
                        <option value="Partial Payment">Partial Payment</option>
                    </select>
                    <x-input-error :messages="$errors->paymentStore->get('payment_type')" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div class="relative mt-7">
                    <label for="payment_method">Payment Methods</label>
                    <select name="payment_method" id="payment_method_modal" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                        <option value="">-- Select Payment Method --</option>
                        <option value="Agency Card">Agency Card</option>
                        <option value="Cash">Cash</option>
                        <option value="Check">Check</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="Disney Visa">Disney Visa</option>
                        <option value="Future Cruise Payment">Future Cruise Payment</option>
                        <option value="Gift Card">Gift Card</option>
                        <option value="Money Order">Money Order</option>
                        <option value="Onboard Deposit">Onboard Deposit</option>
                        <option value="Paypal">Paypal</option>
                        <option value="Payment Request Through VAX">Payment Request Through VAX</option>
                        <option value="Rewards Card">Rewards Card</option>
                        <option value="Trf from other Reservation">Trf from other Reservation</option>
                        <option value="Wire Transfer">Wire Transfer</option>
                    </select>
                    <x-input-error :messages="$errors->paymentStore->get('payment_method')" />
                </div>

                <div x-init="init()" x-data="dateDropdown('{{ old('payment_date', $reservation->payment_date ?? '') }}')" class="relative mt-5">
                    <label class="block text-sm -mb-2">Due Date</label>
                    <div class="flex w-full border-b-2 border-[#bdbdbd] overflow-hidden outline-none">
                        <select x-model="year" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                            <option value="">Year</option>
                            <template x-for="y in years" :key="y">
                                <option :value="y" x-text="y"></option>
                            </template>
                        </select>

                        <select x-model="month" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                            <option value="">Month</option>
                            <template x-for="(m, i) in months" :key="i">
                                <option :value="i + 1" x-text="m"></option>
                            </template>
                        </select>

                        <select x-model="day" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                            <option value="">Day</option>
                            <template x-for="d in days" :key="d">
                                <option :value="d" x-text="d"></option>
                            </template>
                        </select>

                    </div>
                    <input type="hidden" id="payment_date" name="payment_date" :value="formattedDate">
                    <x-input-error :messages="$errors->paymentStore->get('payment_date')" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div class="relative mt-2">
                    <x-text-input type="text" id="check_number" name="check_number" />

                    <x-input-label for="check_number">Check No.</x-input-label>
                </div>

                <div class="relative mt-2">
                    <x-text-input type="text" id="credit_card_number" name="credit_card_number" />

                    <x-input-label for="credit_card_number">Credit Card(Last 4 Digits)</x-input-label>
                </div>
            </div>

            <div class="relative mt-5 mb-5">
                <x-text-input type="text" id="payment_notes_modal" name="notes" />

                <x-input-label for="notes">Notes</x-input-label>
            </div>
        </x-reservations-modal>
    </form>
@endif