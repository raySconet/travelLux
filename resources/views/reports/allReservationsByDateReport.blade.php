<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-book-open mr-2 text-[#f18325]"></i>{{ __('All Reservations By Date') }}
            </h2>
            
            <button class="flex items-center gap-2 bg-white border py-2 px-4 border-[#f18325] text-[#f18325]"><i class="fas fa-cloud-download-alt"></i>Download</button>

            <button class="flex items-center gap-2"><i class="far fa-calendar-alt text-[#212121] text-2xl" onclick="openReportDateRangeModal()"></i>-</button>

        </div>
    </x-slot>

    <div class="mx-auto py-2 px-4">
       
        <div class="p-3 bg-white shadow sm:rounded-lg">
           
            <div class="flex items-end justify-end px-6 py-4 ">

                <div class="relative">
                    <input
                        type="text"
                        placeholder="Quick Search"
                        class="w-64 border-0 border-b-2 border-gray-400 text-sm px-1 py-1"
                    >
                </div>
            </div>

           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Agent Name
                            </th>
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
                                Destination
                            </th>      
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Resort/Ship
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
                            <tr class="hover:bg-gray-50 cursor-pointer">
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">
                                   
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">
                                    
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">

                                </td>
                                
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">

                                </td>    

                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">

                                </td>    

                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">

                                </td>  
                                
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">

                                </td>    

                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">

                                </td>    
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>
<x-report-range-date>
    <x-slot name="footer">
        <x-primary-btn id="allReservationsByDateReportBtn"><i class="fa fa-paper-plane"></i><span>Run Report</span></x-primary-btn>
        <x-secondary-btn onclick="closeReportDateRangeModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
    </x-slot>
</x-report-range-date>
