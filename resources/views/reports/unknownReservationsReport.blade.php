<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-question mr-2 text-[#f18325]"></i>{{ __('Unknown Reservations') }}
            </h2>
            
            <button class="flex items-center gap-2 bg-white border py-2 px-4 border-[#f18325] text-[#f18325]"><i class="fas fa-cloud-download-alt"></i>Download</button>

        </div>
    </x-slot>

    <div class="p-2">
       
        <div class="bg-white shadow rounded-none ml-2">
           
            <div class="flex items-end justify-end px-6 py-4 ">

                <div class="relative">
                    <input
                        type="text"
                        placeholder="Reservation # Search"
                        class="w-64 border-0 border-b-2 border-gray-400 text-sm px-1 py-1"
                    >
                </div>
            </div>

           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="w-1/2 px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Month
                            </th>
                            <th class="w-1/2 px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Total Agency Commission
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                            <tr class="hover:bg-gray-50 cursor-pointer">
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

    <x-report-range-date></x-report-range-date>
</x-app-layout>
