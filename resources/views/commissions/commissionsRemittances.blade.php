<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class="text-2xl text-gray-500 leading-tight flex items-baseline">
                <i class="fa-solid fa-download mr-2 text-[#f18325]"></i>
                <span>Commissions Remittances</span>
                <span class="text-base ml-2">- Received from vendors</span>
            </h2>

            <div class="space-x-2">
                <x-primary-btn><i class="far fa-plus-square"></i><span>Add Agent Reservation</span></x-primary-btn>
                <x-primary-btn><i class="far fa-plus-square"></i><span>Add Unknown Reservation</span></x-primary-btn>
            </div>
        </div>
    </x-slot>


    <div class="bg-[#90caf9] shadow rounded-lg p-6">
        <p class="text-base font-semibold text-white">Search For Reservations</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
            <div class="relative mt-3">
                <x-text-input type="text" id="reservationNumber" name="reservationNumber" class="border-b-[#fff]"  />

                <x-input-label for="reservationNumber" class="text-white">Reservation Number</x-input-label>
            </div>

            <div class="relative mt-3">
                <x-text-input type="text" id="customerLastName" name="customerLastName" class="border-b-[#fff]" />

                <x-input-label for="customerLastName" class="text-white">Customer Last Name</x-input-label>
            </div>

            <div class="relative mt-3">
                <x-text-input type="text" id="groupNumber" name="groupNumber" class="border-b-[#fff]"/>

                <x-input-label for="groupNumber" class="text-white">Group Name</x-input-label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
            <div class="relative mt-3">
                <x-text-input type="text" id="reservationCost" name="reservationCost"  class="border-b-[#fff]"/>

                <x-input-label for="reservationCost" class="text-white">Reservation Cost</x-input-label>
            </div>

            <div class="relative mt-3">
                <x-text-input type="text" id="agencyComission" name="agencyComission" class="border-b-[#fff]" />

                <x-input-label for="agencyComission" class="text-white">Agency Commission</x-input-label>
            </div>
        </div>

        <div class="space-x-2 flex justify-end">
            <x-secondary-btn class="border-none"><i class="far fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
            <x-primary-btn><i class="fas fa-sync"></i><span>Search</span></x-primary-btn>
        </div>
    </div>

    <div class="bg-[#c8e6c9] shadow rounded-lg p-6 mt-5">
        <div class="flex flex-row justify-between">
            <div class="flex flex-row gap-1">
                <i class="fas fa-check text-[#00a86b] mt-1 text-2xl font-extrabold"></i>
                <p class="text-xl text-black">Received</p>
                <p class="text base mt-1">(Ready for Agent Payment)</p>
            </div>
            <p class="text-xl font-bold">Total:$</p>
        </div>

        <div class="p-6">
       
        <div class="bg-white shadow rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Customer
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Reservation
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Group Number
                            </th>   
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Product
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Cost
                            </th>  
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Checkout
                            </th> 
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Commission
                            </th> 
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Commission Remittance
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Agent
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Name
                            </th>        
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                            <tr class="hover:bg-gray-50 cursor-pointer">
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">

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

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
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
    </div>
</x-app-layout>
