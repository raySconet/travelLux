<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-list-alt mr-2 text-[#f18325]"></i>{{ __('1099 Report') }}
            </h2>
            
            <button class="flex items-center gap-2 bg-white border py-2 px-4 border-[#f18325] text-[#f18325]"><i class="fas fa-cloud-download-alt"></i>Download</button>

            <button class="flex items-center gap-2"><i class="far fa-calendar-alt text-[#212121] text-2xl" onclick="openReportDateRangeModal()"></i>-</button>

        </div>
    </x-slot>

    <div class="mx-autp py-2 px-4">
       
        <div class="p-3 bg-white shadow sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th colspan="8" class="px-6 py-6 text-center">
                                <span class="inline-block bg-[#bdbdbd] px-3 py-3 rounded-2xl font-normal">
                                    Total Paid: <b>$</b>
                                </span>
                            </th>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Agent
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Address
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Cell Phone Number
                            </th>   
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Birth Date
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                SSN
                            </th>  
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                EIN
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Postal Code
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Commission Paid
                            </th>         
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 cursor-pointer">
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $user->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6] border-b-2 border-t-2 border-[#dee2e6]">
                                    
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                                    
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">

                                </td>
                                
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">

                                </td>    

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    
</x-app-layout>
<x-report-range-date>
    <x-slot name="footer">
        <x-primary-btn id="1099ReportBtn"><i class="fa fa-paper-plane"></i><span>Run Report</span></x-primary-btn>
        <x-secondary-btn onclick="closeReportDateRangeModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
    </x-slot>
</x-report-range-date>
