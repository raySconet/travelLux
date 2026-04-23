<x-app-layout>
    
    <div class="py-4 px-4 bg-white ml-4 mr-3 mb-1 shadow sm:rounded-lg flex items-center justify-between">
        <h2 class="text-xl text-gray-500 leading-tight">
            <i class="fa-solid fas fa-calendar-check mr-2 text-[#f18325]"></i>{{ __('Checking in this Week') }}
        </h2>
        
    </div>
  

    <div class="mx-auto py-2 px-4">
       
        <div class="p-3 bg-white shadow sm:rounded-lg">
           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Customer Name
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Reservation Number
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Product
                            </th>   
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Checkin Date
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Checkout Date
                            </th>  
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($thisWeekReservations as $reservation)
                            <tr class="hover:bg-gray-50 cursor-pointer">
                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $reservation->customer ? $reservation->customer->fname . ',' . $reservation->customer->lname : ' ' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $reservation->reservation_number }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $reservation->product ? $reservation->product->product_name : ' ' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('m/d/Y') }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('m/d/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    No data available in this table
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="py-4 px-4 mt-2 ml-4 mr-3 mb-1 bg-white shadow sm:rounded-lg flex items-center justify-between">
        <h2 class="text-xl text-gray-500 leading-tight">
            <i class="fa-solid fas fa-calendar-check mr-2 text-[#f18325]"></i>{{ __('Checking in Next Week') }}
        </h2>
        
    </div>


    <div class="mx-auto py-2 px-4">
       
        <div class="p-3 bg-white shadow sm:rounded-lg">
           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Customer Name
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Reservation Number
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Product
                            </th>   
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Checkin Date
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Checkout Date
                            </th>  
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($nextWeekReservations as $reservation)
                            <tr class="hover:bg-gray-50 cursor-pointer">
                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $reservation->customer ? $reservation->customer->fname . ',' . $reservation->customer->lname : ' ' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $reservation->reservation_number }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $reservation->product ? $reservation->product->product_name : ' ' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('m/d/Y') }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('m/d/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    No data available in this table
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
