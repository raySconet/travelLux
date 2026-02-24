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
        <p class="text-center text-base">No Customer Payments</p>

        <div class="flex flex-col gap-2" style="background-color: rgba(241, 131, 37,0.2)">
            <p class="text-right text-base mt-2 mr-2">Total Reservation Cost:<b>$</b></p>
            <p class="text-right text-base mb-2 mr-2">Balance Due: <b>$</b></p>
        </div>
    </div>

    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">

    <div class="relative flex flex-col">
        <p class="text-base mt-2">Important Payment Date</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div x-data="dateDropdown('{{ old('deposit_due_date', $reservation->deposit_due_date ?? '') }}')" class="relative mt-5">
                <label class="block text-sm mb-1">Checkin Date</label>
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
                <label class="block text-sm mb-1">Checkin Date</label>
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
<x-reservations-modal id="reservationPaymentsModal" title="Add Payment" close="closeReservationPaymentsModal()" saveClass="reservationPaymentsSaveBtn">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-2">
            <x-text-input type="text" id="amount" name="amount" />

            <x-input-label for="amount">Amount</x-input-label>
        </div>

        <div class="relative mt-3">
            <label for="paymentType">Payment Type</label>
            <select name="paymentType" id="paymentType" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                <option value="-1">-- Select Payment Type--</option>
                <option value="deposit">Deposit</option>
                <option value="finalPayment">Final Payment</option>
                <option value="fullPayment">Full Payment</option>
                <option value="partialPayment">Partial Payment</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-7">
            <label for="paymentMethods">Payment Methods</label>
            <select name="paymentMethods" id="paymentMethods" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                <option value="-1">-- Select Payment Method --</option>
                <option value="agencyCard">Agency Card</option>
                <option value="cash">Cash</option>
                <option value="check">Check</option>
                <option value="creditCard">Credit Card</option>
                <option value="debitCard">Debit Card</option>
                <option value="disneyVisa">Disney Visa</option>
                <option value="futureCruisePayment">Future Cruise Payment</option>
                <option value="giftCard">Gift Card</option>
                <option value="moneyOrder">Money Order</option>
                <option value="onboardDeposit">Onboard Deposit</option>
                <option value="paypal">Paypal</option>
                <option value="paymentRequestThroughVAX">Payment Request Through VAX</option>
                <option value="rewardsCard">Rewards Card</option>
                <option value="trfFromOtherReservation">Trf from other Reservation</option>
                <option value="wireTransfer">Wire Transfer</option>
            </select>
        </div>

        <div x-data="dateDropdown()" class="relative mt-3">
            <label class="block text-sm mb-1">Payment Date</label>
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
                        <option value="i + 1" x-text="m"></option>
                    </template>
                </select>

                <select x-model="day" class="flex-1 border-0 focus:ring-0 focusLoutline-none px-3 py-2">
                    <option value="">Day</option>
                    <template x-for="d in days" :key="d">
                        <option :value="d" x-text="d"></option>
                    </template>
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-2">
            <x-text-input type="text" id="checkNo" name="checkNo" />

            <x-input-label for="checkNo">Check No.</x-input-label>
        </div>

        <div class="relative mt-2">
            <x-text-input type="text" id="creditCardLast4Digits" name="creditCardLast4Digits" />

            <x-input-label for="creditCardLast4Digits">Credit Card(Last 4 Digits)</x-input-label>
        </div>
    </div>

    <div class="relative mt-3 mb-5">
        <x-text-input type="text" id="notes" name="notes" />

        <x-input-label for="notes">Notes</x-input-label>
    </div>
</x-reservations-modal>