@php
    $expense = $expense ?? null;
    $paidBy = ['Visa', 'Amex', 'Mastercard', 'Credit', 'Check', 'Cash', 'Bank account', 'Gift card', 'Paypal', 'Other'];
    $paidFor = [
        'Advertising' => 'Advertising', 'Car and truck and gas expense' => 'Car and truck and gas expense',
        'Client Gifts' => 'Client Gifts', 'Commission and Fees' => 'Commission and Fees',
        'Contract labor payroll expense' => 'Contract labor payroll expense',
        'Insurance other then health' => 'Insurance other than health', 'Interest' => 'Interest',
        'Mortgage' => 'Mortgage', 'Legal services' => 'Legal services',
        'Office expense' => 'Office expense (i.e., printer ink, business cards, etc.)',
        'Repairs and Maintnance' => 'Repairs and Maintenance', 'Supplies' => 'Supplies',
        'Taxes and Licenses' => 'Taxes and Licenses', ' Travel and Fams' => 'Travel and Fams',
        'Meals only' => 'Meals only', 'Utilities' => 'Utilities', 'Other expenses' => 'Other expenses',
        'Website, emails, internet and telephone' => 'Website, emails, internet and telephone',
        'Training' => 'Training', 'Dues, Subscribtions, and memberships (adobe, vpn)' => 'Dues, subscriptions, and memberships (Adobe, VPN)',
        'Software expense and crm' => 'Software expense and CRM', 'Fees' => 'Fees', 'License/Bond' => 'License/Bond',
        'Meetings and agent gifts' => 'Meetings and agent gifts', 'Postage' => 'Postage', 'Other' => 'Other',
    ];
    $selectedPaidBy = (string) old('paid_by', $expense->paid_by ?? '');
    $selectedPaidFor = (string) old('gift_type', $expense->gift_type ?? '');
    $selectedReservation = old('reservation_id', $expense->reservation_id ?? -1);
    $reportQuery = array_filter(\Illuminate\Support\Arr::only($filters, ['beginDate', 'endDate', 'agentId', 'search']), fn ($value) => $value !== null && $value !== '');
    $newExpenseUrl = route('reports.agentExpensesReport', [...$reportQuery, 'newExpense' => 1]);
    $cancelExpenseUrl = route('reports.agentExpensesReport', $reportQuery);
@endphp

@if(session('expense_status'))
    <p role="status" class="mb-4 rounded bg-green-50 px-4 py-3 text-green-800">{{ session('expense_status') }}</p>
@endif
<details id="expenseReportFormPanel" class="bg-white mb-4" @if($expense || request()->boolean('newExpense') || old('expense_form')) open @endif>
    <summary class="list-none cursor-pointer py-3 text-right">
        <span class="inline-flex items-center gap-2 bg-[#B6844A] text-white rounded px-4 py-2"><i class="far fa-plus-square" aria-hidden="true"></i>Expense</span>
    </summary>
    <form method="POST" action="{{ $expense ? route('reports.agentExpensesReport.update', $expense->id) : route('reports.agentExpensesReport.store') }}" id="expenseReportForm">
        @csrf
        @if($expense)
            @method('PATCH')
        @endif
        <input type="hidden" name="expense_form" value="1">
        @foreach($reportQuery as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <div class="flex flex-wrap gap-3 items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
            <h2 id="expenseReportTitle" class="text-xl font-semibold text-gray-700">{{ $expense ? 'Edit Expense' : 'Add Expense' }}</h2>
            <div class="flex flex-wrap gap-2">
                @if($expense)
                    <button type="button" data-report-open-dialog="expenseDeleteDialog" class="flex items-center gap-2 border border-red-600 text-red-600 rounded px-4 py-2 cursor-pointer">
                        <i class="fas fa-trash" aria-hidden="true"></i>Delete
                    </button>
                @endif
                <a href="{{ $newExpenseUrl }}" class="flex items-center gap-2 border border-gray-300 rounded px-4 py-2 text-gray-700">
                    <i class="fas fa-redo" aria-hidden="true"></i>Clear
                </a>
                <button type="submit" class="flex items-center gap-2 border border-[#B6844A] text-[#B6844A] rounded px-4 py-2 cursor-pointer">
                    <i class="fas fa-save" aria-hidden="true"></i>Save
                </button>
                <a href="{{ $cancelExpenseUrl }}" class="flex items-center gap-2 bg-[#B6844A] text-white rounded px-4 py-2 cursor-pointer">
                    <i class="far fa-minus-square" aria-hidden="true"></i>Cancel
                </a>
            </div>
        </div>
        @if($errors->any() && old('expense_form'))
            <ul role="alert" class="px-6 pt-4 text-red-600 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 px-6 py-6">
            <label class="text-sm text-gray-600">Reservation Number
                <select name="reservation_id" required data-expense-reservation class="w-full mt-2 border-0 border-b-2 border-[#bdbdbd] focus:border-[#B6844A]">
                    <option value="-1" data-customer-name="" @selected((int) $selectedReservation === -1)>None</option>
                    @foreach($expenseReservations as $reservation)
                        <option value="{{ $reservation->id }}" data-customer-name="{{ trim(($reservation->customer_fname ?? '').' '.($reservation->customer_lname ?? '')) }}" @selected((int) $selectedReservation === (int) $reservation->id)>{{ $reservation->reservation_number }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm text-gray-600">Customer Name
                <input type="text" id="expenseCustomerName" name="customer_name" maxlength="255" value="{{ old('customer_name', $expense->customer_name ?? '') }}" class="w-full mt-2 border-0 border-b-2 border-[#bdbdbd] focus:border-[#B6844A]">
            </label>
            <label class="text-sm text-gray-600">Amount
                <input type="number" name="amount" step="0.01" min="-99999999.99" max="99999999.99" required value="{{ old('amount', $expense->amount ?? '') }}" class="w-full mt-2 border-0 border-b-2 border-[#bdbdbd] focus:border-[#B6844A]">
            </label>
            <label class="text-sm text-gray-600">Paid By
                <select name="paid_by" required class="w-full mt-2 border-0 border-b-2 border-[#bdbdbd] focus:border-[#B6844A]">
                    <option value="">--Select--</option>
                    @if($selectedPaidBy !== '' && !in_array($selectedPaidBy, $paidBy, true))
                        <option value="{{ $selectedPaidBy }}" selected>{{ $selectedPaidBy }}</option>
                    @endif
                    @foreach($paidBy as $option)
                        <option value="{{ $option }}" @selected($selectedPaidBy === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm text-gray-600">Last 4 Digits
                <input type="text" name="last4_of_card" maxlength="4" value="{{ old('last4_of_card', $expense->last4_of_card ?? '') }}" class="w-full mt-2 border-0 border-b-2 border-[#bdbdbd] focus:border-[#B6844A]">
            </label>
            <label class="text-sm text-gray-600">Paid For
                <select name="gift_type" required class="w-full mt-2 border-0 border-b-2 border-[#bdbdbd] focus:border-[#B6844A]">
                    <option value="">--Select--</option>
                    @if($selectedPaidFor !== '' && !array_key_exists($selectedPaidFor, $paidFor))
                        <option value="{{ $selectedPaidFor }}" selected>{{ $selectedPaidFor }}</option>
                    @endif
                    @foreach($paidFor as $value => $label)
                        <option value="{{ $value }}" @selected($selectedPaidFor === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm text-gray-600">Date
                <input type="date" name="gift_date" required value="{{ old('gift_date', $expense->gift_date ?? '') }}" class="w-full mt-2 border-0 border-b-2 border-[#bdbdbd] focus:border-[#B6844A]">
            </label>
            <label class="text-sm text-gray-600">Notes
                <input type="text" name="notes" maxlength="10000" value="{{ old('notes', $expense->notes ?? '') }}" class="w-full mt-2 border-0 border-b-2 border-[#bdbdbd] focus:border-[#B6844A]">
            </label>
        </div>
    </form>
</details>
<style>
    #expenseReportFormPanel[open] > summary { display: none; }
    #expenseReportFormPanel > summary::-webkit-details-marker { display: none; }
</style>
    @if($expense)
        <dialog id="expenseDeleteDialog" class="travelux-report-dialog bg-white rounded-lg shadow-lg" aria-labelledby="expenseDeleteTitle">
            <form method="POST" action="{{ route('reports.agentExpensesReport.destroy', $expense->id) }}" id="expenseDeleteForm">
                @csrf
                @method('DELETE')
                @foreach($reportQuery as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <h2 id="expenseDeleteTitle" class="px-6 py-4 text-xl font-semibold border-b border-gray-300">Delete Expense</h2>
                <p class="px-6 py-6">Are you sure you want to delete this record?</p>
                <div class="flex justify-end gap-2 px-6 py-4 border-t border-gray-300">
                    <button type="submit" class="bg-gray-800 text-white rounded px-4 py-2 cursor-pointer"><i class="fas fa-trash mr-2" aria-hidden="true"></i>Delete</button>
                    <button type="button" data-report-close-dialog class="bg-gray-100 text-gray-700 rounded px-4 py-2 cursor-pointer">Cancel</button>
                </div>
            </form>
        </dialog>
    @endif
