@if($agents->isEmpty())

    <div class="bg-white rounded shadow p-6 text-center text-gray-500">
        No Data Available.
    </div>

@else

    @foreach($agents as $agent)

        <div class="checkHistoryCard bg-white shadow mb-5 overflow-hidden border border-[#dee2e6]">

            <div class="flex">

                <div class="w-50 border-r border-[#dee2e6] p-6 flex flex-col justify-center">

                    <div class="text-lg font-semibold text-gray-700">
                        {{ $agent->lname ?? '' }}, {{ $agent->fname ?? '' }}
                    </div>

                    <div class="text-base font-bold text-[#B6844A] mt-6">
                        ${{ number_format($agent->totalPaid,2) }}
                    </div>

                    <div class="mt-1 text-sm tracking-wide text-[#8c8c8c]">
                        Total Paid
                    </div>


                </div>

                <div class="flex-1">

                    <table class="w-full">

                        <thead>
                            <tr class="text-center text-sm font-bold border border-[#dee2e6]">
                                <th class="px-6 py-4 w-1/2">Check Date</th>
                                <th class="px-6 py-4">Total Amount</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($agent->history as $commission)

                                <tr class="checkHistoryTableRecord cursor-pointer bg-[#1976d2] border-b border-[#dee2e6]" data-id="{{ $commission->id }}">

                                    <td class="px-6 py-4 text-center text-white">
                                        {{ $commission->formatted_check_date }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-white">
                                        {{ $commission->formatted_amount }}

                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <i class="fas fa-chevron-down checkHistoryChevron text-white"></i>
                                    </td>

                                </tr>


                                <tr class="checkHistoryDetails hidden" data-detail="{{ $commission->id }}">

                                    <td colspan="3" class="bg-[#1976d2] p-5">

                                        @if($commission->reservation)

                                            @php
                                                $reservation = $commission->reservation;
                                                $customer = $reservation->customer;
                                            @endphp

                                            <table class="w-full border border-[#dee2e6] overflow-hidden">

                                                <thead>

                                                    <tr class="bg-white text-sm">
                                                        <th class="p-3 text-left">Customer</th>
                                                        <th class="p-3 text-left">Reservation Number</th>
                                                        <th class="p-3 text-left">Checkout Date</th>
                                                        <th class="p-3 text-left">Total Commission</th>
                                                        <th class="p-3 text-left">Paid</th>
                                                        @if(auth()->user()->isAdmin())
                                                            <th class="p-3 text-left">Options</th>
                                                        @else    
                                                            <th class="p-3 text-left"></th>
                                                        @endif
                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    <tr class="text-sm border-t bg-white">

                                                        <td class="p-3">
                                                            {{ $customer->fname ?? ''}}
                                                            {{ $customer->lname ?? ''}}
                                                        </td>

                                                        <td class="p-3">
                                                            {{ $reservation->reservation_number }}
                                                        </td>

                                                        <td class="p-3">
                                                            {{ $reservation->checkout_date ? date('m/d/Y', strtotime($reservation->checkout_date)) : '' }}
                                                        </td>

                                                        <td class="p-3">
                                                            ${{ number_format($reservation->agency_commission,2) }}
                                                        </td>

                                                        <td class="p-3">
                                                            ${{ number_format($commission->amount, 2) }}

                                                            <i class="fas fa-bars ml-2 text-gray-500 cursor-pointer" title="Agency Commission: ${{ number_format($reservation->agency_commission, 2) }}&#10;Agent Commission: ${{ number_format($reservation->agent_commission, 2) }}&#10;Percentage: {{ $reservation->agent_commission_percentage }}%"></i>
                                                        </td>

                                                        <td class="p-3">

                                                            @if(auth()->user()->isAdmin())

                                                                <button class="undoPaymentBtn px-3 py-1 border border-[#B6844A] text-[#B6844A] rounded hover:bg-yellow-50"
                                                                    data-reservation="{{ $commission->reservation_id }}"
                                                                    data-agent="{{ $agent->id }}"
                                                                    data-check="{{ $commission->check_number }}"
                                                                >
                                                                    Undo Payment
                                                                </button>

                                                            @endif

                                                        </td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    @endforeach


@endif