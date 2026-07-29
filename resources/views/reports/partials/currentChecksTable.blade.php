@if($agents->isEmpty())

    <div class="bg-white rounded shadow p-6 text-center text-gray-500">
        No Data Available
    </div>

@else

    @foreach($agents as $agentReservations)

        @php
            $agent = $agentReservations->first()->agent;

            $totalUnpaid = $agentReservations->sum('agent_commission');
            $totalRemitted = $agentReservations->where('commission_received',1)->sum('agent_commission');
        @endphp

        <div class="flex items-stretch bg-white border border-gray-300 sm:rounded-lg overflow-hidden mb-4">

            <div class="w-[235px] shrink-0">

                <div class="bg-[#c8e6c9] border-b border-[#98c391] px-4 py-3">
                    <div class="text-xl font-semibold text-gray-800">
                        {{ $agent->fname ?? '' }} {{ $agent->lname ?? '' }}
                    </div>
                </div>

                <div class="px-4 py-3">

                    <div class="text-sm">
                        ${{ number_format($totalUnpaid,2) }}
                    </div>

                    <div class="text-gray-400 text-xs mb-3">
                        Total UnPaid
                    </div>

                    <div class="text-sm">
                        ${{ number_format($totalRemitted,2) }}
                    </div>

                    <div class="text-gray-400 text-xs">
                        Total Remitted
                    </div>

                </div>

            </div>

            <div class="w-px bg-gray-300"></div>

            <div class="flex-1 overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b border-gray-300">

                            <th class="px-3 py-2 text-left font-semibold">
                                Customer
                            </th>

                            <th class="px-3 py-2 text-left font-semibold">
                                Reservation Number
                            </th>

                            <th class="px-3 py-2 text-left font-semibold">
                                Checkout Date
                            </th>

                            <th class="px-3 py-2 text-left font-semibold">
                                Total Commission
                            </th>

                            <th class="px-3 py-2 text-left font-semibold">
                                Agent Commission
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($agentReservations as $reservation)

                            <tr class="border-b border-gray-300 last:border-b-0">

                                <td class="px-3 py-2">
                                    {{ $reservation->customer->fname ?? '' }}
                                    {{ $reservation->customer->lname ?? '' }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ $reservation->reservation_number }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('m-d-Y') }}
                                </td>

                                <td class="px-3 py-2">
                                    ${{ number_format($reservation->agency_commission,2) }}
                                </td>

                                <td class="px-3 py-2 whitespace-nowrap">

                                    ${{ number_format($reservation->agent_commission,2) }}

                                    @if($reservation->commission_received)
                                        <i class="fas fa-check-circle text-[#00a86b] ml-1 text-xl" title="Available on Check Writer"></i>
                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endforeach

@endif