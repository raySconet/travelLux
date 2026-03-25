<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-money-check-alt mr-2 text-[#f18325]"></i>{{ __('Agent Expenses Report') }}
            </h2>
            
            <button class="flex items-center gap-2 bg-white border py-2 px-4 border-[#f18325] text-[#f18325]"><i class="fas fa-cloud-download-alt"></i>Download</button>

            <button class="flex items-center gap-2"><i class="far fa-calendar-alt text-[#212121] text-2xl" onclick="openReportDateRangeModal()"></i>-</button>

        </div>
    </x-slot>

    <div class="mx-auto py-2 px-4" x-data="{ adding: false }">  
        <div class="p-3 bg-white shadow sm:rounded-lg">
            <div class="flex items-end justify-end px-6 py-3">
                <x-primary-btn  x-show="!adding"  @click="adding = true" class="flex items-center gap-2"><i class="far fa-plus-square"></i>Expense</x-primary-btn>
                <div x-show="adding" class="flex gap-2">
                    <x-secondary-buttonToDelete class="space-x-3"><i class="fas fa-trash"></i><span>Delete</span></x-secondary-buttonToDelete>

                    <x-secondary-buttonToDelete class="space-x-3"><i class="fas fa-redo"></i><span>Clear</span></x-secondary-buttonToDelete>

                    <x-secondary-btn class="space-x-3"><i class="fas fa-save"></i><span>Save</span></x-secondary-btn>

                    <x-primary-btn @click="adding = false" class="space-x-3"><i class="far fa-minus-square"></i><span>Cancel</span></x-primary-btn>
                </div>
            </div>

            <!-- EXPENSE FORM -->
            <div x-show="adding" x-transition class="px-6 pb-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="relative mt-2">
                        <label for="reservationNumber">Reservation Number</label>
                        <select name="reservationNumber" id="reservationNumber" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                            <option value="-1">None</option>
                        </select>
                    </div>

                    <div class="relative mt-2">
                        <x-text-input type="text" id="customerName" name="customerName"  />

                        <x-input-label for="customerName">Customer name</x-input-label>
                    </div>

                    <div class="relative mt-2">
                        <x-text-input type="text" id="amount" name="amount"  />

                        <x-input-label for="amount">Amount</x-input-label>
                    </div>

                    <div class="relative mt-3">
                        <label for="paidFor">Paid By</label>
                        <select name="paidFor" id="paidFor" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                            <option value="-1">--Select--</option>
                            <option value="visa">Visa</option>
                            <option value="amex">Amex</option>
                            <option value="mastercard">Mastercard</option>
                            <option value="credit">Credit</option>
                            <option value="check">Check</option>
                            <option value="cash">Cash</option>
                            <option value="bankAccount">Bank Account</option>
                            <option value="giftCard">Gift Card</option>
                            <option value="paypal">Paypal</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                    <div class="relative mt-2">
                        <x-text-input type="text" id="last4Digits" name="last4Digits"  />

                        <x-input-label for="last4Digits">Last 4 Digits</x-input-label>
                    </div>

                    <div class="relative mt-3">
                        <label for="paidFor">Paid For</label>
                        <select name="paidFor" id="paidFor" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                            <option value="-1">--Select--</option>
                            <option value="advertising">Advertising</option>
                            <option value="carAndTruckAndGasExpense">Car and truch and gas expense</option>
                            <option value="clientGifts">Client Gifts</option>
                            <option value="commissionAndFees">Commission and Fees</option>
                            <option value="contractLaborPayrollExpense">Contract labor payroll expense</option>
                            <option value="insuranceOtherThanHealth">Insurance other than health</option>
                            <option value="interest">Interest</option>
                            <option value="mortgage">Mortgage</option>
                            <option value="legalServices">Legal Services</option>
                            <option value="officeExpense">Office expense (i.e, printer ink, business cards, etc.)</option>
                            <option value="repairsAndMaintnance">Repairs and Maintnance</option>
                            <option value="supplies">Supplies</option>
                            <option value="taxesAndLicenses">Taxes and Licenses</option>
                            <opton value="travelAndFams">Travel and Fams</opton>
                            <option value="mealsOnly">Meals only</option>
                            <option value="utilities">Utilities</option>
                            <option value="otherExpenses">Other expenses</option>
                            <option value="websiteEmailsInternetAndTelephone">Website, emails, internet and telephone</option>
                            <option value="training">Training</option>
                            <option value="duesSubscribtionsAndMemborships(adobeVpn)">Dues, Subscribtions, and memberships (adobe,vpn)</option>
                            <option value="softwareExpenseAndCRM">Software expense and crm</option>
                            <option value="fees">Fees</option>
                            <option value="license/bond">License/Bond</option>
                            <option value="meetingsAndAgentGifts">Meetings and agent gifts</option>
                            <option value="postage">Postage</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="relative mt-2">
                        <x-text-input type="date" id="date" name="date"  />

                        <x-input-label for="date">Date</x-input-label>
                    </div>

                    <div class="relative mt-2">
                        <x-text-input type="notes" id="notes" name="notes"  />

                        <x-input-label for="notes">Notes</x-input-label>
                    </div>

                </div>
            </div>
            <p class="text-center text-base">No Data Available</p>
        </div>
    </div>

</x-app-layout>
<x-report-range-date>
    <x-slot name="footer">
        <x-primary-btn id="agentExpensesReportBtn"><i class="fa fa-paper-plane"></i><span>Run Report</span></x-primary-btn>
        <x-secondary-btn onclick="closeReportDateRangeModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
    </x-slot>
</x-report-range-date>

