<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-list-ol mr-2 text-[#f18325]"></i>{{ __('Booked Reservations Per Months') }}
            </h2>
            
            <button class="flex items-center gap-2 bg-white border py-2 px-4 border-[#f18325] text-[#f18325]"><i class="fas fa-cloud-download-alt"></i>Download</button>

            <button class="flex items-center gap-2"><i class="far fa-calendar-alt text-[#212121] text-2xl" onclick="openReportDateRangeModal()"></i>-</button>

        </div>
    </x-slot>

    <div class="p-2">
       
        <div class="bg-white shadow rounded-none ml-2">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="w-1/3 px-4 py-3 text-left text-sm font-normal border-b-2 border-t-2 border-[#dee2e6]">
                                Month
                            </th>
                            <th class="w-1/3 px-4 py-3 text-left text-sm font-normal border-b-2 border-t-2 border-[#dee2e6]">
                                Reservations Booked
                            </th>
                            <th class="w-1/3 px-4 py-3 text-left text-sm font-normal border-b-2 border-t-2 border-[#dee2e6]">
                                Reservations Traveled
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
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-report-range-date>
        <x-slot name="footer">
            <x-primary-btn id="bookedReservationsPerMonthReportBtn"><i class="fa fa-paper-plane"></i><span>Run Report</span></x-primary-btn>
            <x-secondary-btn onclick="closeReportDateRangeModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
        </x-slot>
    </x-report-range-date>
</x-app-layout>
