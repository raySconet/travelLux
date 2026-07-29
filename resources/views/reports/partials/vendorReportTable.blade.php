@if($vendors->isEmpty())

    <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
        No Data Available.
    </div>

@else

    <script>

        $('#totalAgentCommission').text('$' + Number({{ $totalAgentCommission }}).toLocaleString('en-US',{
            minimumFractionDigits:2,
            maximumFractionDigits:2
        }));

        $('#totalSales').text('$' + Number({{ $totalSales }}).toLocaleString('en-US',{
            minimumFractionDigits:2,
            maximumFractionDigits:2
        }));

    </script>

    @foreach($vendors as $vendorReservations)

        @php
            $product = $vendorReservations->first()->product;

            $vendorSales = $vendorReservations->sum('reservation_cost');
            $vendorGrossCommission = $vendorReservations->sum('agency_commission');
            $vendorAgentCommission = $vendorReservations->sum('agent_commission');
            $vendorAgencyCommission = $vendorGrossCommission - $vendorAgentCommission;

            $destinations = $vendorReservations->groupBy('destination_id');
        @endphp

        <div class="flex mb-8 gap-3 bg-white">

            <div class="w-[350px] shrink-0">

                <div class="border border-[#dee2e6] shadow m-3">

                    <div class="bg-[#c8e6c9] p-5 text-2xl">
                        {{ optional($product)->product_name }}
                    </div>

                    <div class="border-t border-[#dee2e6] p-1 pl-5">
                        <div class="text-base">
                            ${{ number_format($vendorAgencyCommission,2) }}
                        </div>
                        <div class="text-[#807e7e]">
                            Agency Commission
                        </div>
                    </div>

                    <div class="border-t border-[#dee2e6] p-1 pl-5">
                        <div class="text-base">
                            ${{ number_format($vendorAgentCommission,2) }}
                        </div>
                        <div class="text-[#807e7e]">
                            Agent Commission
                        </div>
                    </div>

                    <div class="border-t border-[#dee2e6] p-1 pl-5">
                        <div class="text-base">
                            ${{ number_format($vendorSales,2) }}
                        </div>
                        <div class="text-[#807e7e]">
                            Total Sales
                        </div>
                    </div>

                </div>

            </div>

            <div class="flex-1">

                @foreach($destinations as $destinationReservations)

                    @php
                        $destination = $destinationReservations->first()->destination;
                        $destinationSales = $destinationReservations->sum('reservation_cost');
                        $destinationGrossCommission = $destinationReservations->sum('agency_commission');
                        $destinationAgentCommission = $destinationReservations->sum('agent_commission');
                        $destinationAgencyCommission = $destinationGrossCommission - $destinationAgentCommission;
                        $resorts = $destinationReservations->groupBy('resort_id');
                    @endphp

                    <div class="bg-[#1e88e5] text-white px-5 py-5 flex justify-between items-start border border-[#dee2e6] m-3">

                        <div class="text-2xl mt-6">
                            {{ optional($destination)->destination_name }}
                        </div>

                        <div class="text-right text-sm leading-7">

                            <div>
                                Agency Commission:
                                <strong>
                                    ${{ number_format($destinationAgencyCommission,2) }}
                                </strong>
                            </div>

                            <div>
                                Agent Commission:
                                <strong>
                                    ${{ number_format($destinationAgentCommission,2) }}
                                </strong>
                            </div>

                            <div>
                                Total Sales:
                                <strong>
                                    ${{ number_format($destinationSales,2) }}
                                </strong>
                            </div>

                        </div>

                    </div>

                    @foreach($resorts as $resortReservations)

                        @php
                            $resort = $resortReservations->first()->resort;
                            $resortSales = $resortReservations->sum('reservation_cost');
                            $resortGrossCommission = $resortReservations->sum('agency_commission');
                            $resortAgentCommission = $resortReservations->sum('agent_commission');
                            $resortAgencyCommission = $resortGrossCommission - $resortAgentCommission;
                        @endphp

                        <div class="flex border shadow-sm mb-4 m-3 -mt-3 border border-[#dee2e6]">

                            <div class="w-[175px] border-r border-[#dee2e6] bg-white shrink-0">

                                <div class="bg-[#bbdefb] p-4 text-2xl min-h-[90px]">
                                    {{ optional($resort)->resort_ship_name ?? 'N/A' }}
                                </div>

                                <div class="border-t border-[#dee2e6] p-1 pl-3">

                                    <div class="text-sm">
                                        ${{ number_format($resortAgencyCommission,2) }}
                                    </div>

                                    <div class="text-[#807e7e]">
                                        Agency Commission
                                    </div>

                                </div>

                                <div class="border-t border-[#dee2e6] p-1 pl-3">

                                    <div class="text-sm">
                                        ${{ number_format($resortAgentCommission,2) }}
                                    </div>

                                    <div class="text-[#807e7e]">
                                        Agent Commission
                                    </div>

                                </div>

                                <div class="border-t border-[#dee2e6] p-1 pl-3">

                                    <div class="text-sm">
                                        ${{ number_format($resortSales,2) }}
                                    </div>

                                    <div class="text-[#807e7e]">
                                        Total Sales
                                    </div>

                                </div>

                            </div>

                            <div class="flex-1 overflow-x-auto">

                                <table class="min-w-full">

                                    <thead class="bg-white border-b border-[#bdbdbd]">

                                        <tr>

                                            <th class="px-4 py-3 text-left">
                                                Reservation Number
                                            </th>

                                            <th class="px-4 py-3 text-left">
                                                Gross Commission
                                            </th>

                                            <th class="px-4 py-3 text-left">
                                                Agent Commission
                                            </th>

                                            <th class="px-4 py-3 text-left">
                                                Agency Commission
                                            </th>

                                            <th class="px-4 py-3 text-left">
                                                Status
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @foreach($resortReservations as $reservation)

                                            <tr class="border-b border-[#dee2e6]">

                                                <td class="px-4 py-2">
                                                    {{ $reservation->reservation_number }}
                                                </td>

                                                <td class="px-4 py-2">
                                                    ${{ number_format($reservation->agency_commission,2) }}
                                                </td>

                                                <td class="px-4 py-2">
                                                    ${{ number_format($reservation->agent_commission,2) }}
                                                </td>

                                                <td class="px-4 py-2">
                                                    ${{ number_format($reservation->agency_commission-$reservation->agent_commission,2) }}
                                                </td>

                                                <td class="px-4 py-2">
                                                    {{ $reservation->status }}
                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    @endforeach

                @endforeach

            </div>

        </div>

    @endforeach

@endif