<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
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


    <div class="bg-[#90caf9] shadow rounded-none p-6 w-406 ml-3">
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

    <div class="bg-[#c8e6c9] shadow rounded-none p-6 mt-5 w-406 ml-3">
        <div class="flex flex-row justify-between">
            <div class="flex flex-row gap-1">
                <i class="fas fa-check text-[#00a86b] mt-1 text-2xl font-extrabold"></i>
                <p class="text-xl text-black">Received</p>
                <p class="text base mt-1">(Ready for Agent Payment)</p>
            </div>
            <p class="text-xl font-bold">Total:$</p>
        </div>

        <div class="p-6">
       
        <div class="bg-white shadow rounded-none">
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
                                    <i class="fas fa-tag text-[#f18325] text-xl" onclick="openEditReservationModal()"></i>
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
                                   <i class="fas fa-envelope text-sm"></i>
                                </td>
                                
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">
                                   
                                </td>    
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Reservation Modal -->
    <div id="editReservationModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[9999]">
        <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative">
            
        
            <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-edit text-xl text-[#f18325]"></i>
                    <h2 id="vendorModalTitle" class="text-xl font-semibold text-gray-700">
                       Edit Reservation
                    </h2>
                </div>

                <button onclick="closeEditReservationModal()" class="text-gray-400 hover:text-gray-600">
                    ✕
                </button>
            </div>

     
            <div class="px-6 py-4 space-y-4">
                <div>
                    <p class="text-base">
                       Reservation #:
                    </p>

                    <p class="text-base">
                       Customer:
                    </p>
                </div>

                <div class="relative mt-6">
                    <x-text-input type="text" id="totalReservationCost" name="totalReservationCost"  />

                    <x-input-label for="totalReservationCost">Total Reservation Cost</x-input-label>
                </div>

                <div class="relative mt-8">
                    <x-text-input type="text" id="grossCommission" name="grossCommission"  />

                    <x-input-label for="grossCommission">Gross Commission</x-input-label>
                </div>

                <div class="relative mt-8">
                    <x-text-input type="text" id="BDMemail" name="BDMemail"  />

                    <x-input-label for="BDMemail">BDM Email</x-input-label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="relative mt-3">
                        <x-text-input type="text" id="agentCommission" name="agentCommission"  />

                        <x-input-label for="agentCommission">Agent Commission</x-input-label>
                    </div>

                    <div class="relative mt-3">
                        <x-text-input type="text" id="percentage" name="percentage"  />

                        <x-input-label for="percentage">Percentage %</x-input-label>
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" class="h-4 w-4">
                    <label class="text-sm" for="nonCommissionable">Non Commissionable</label>
                </div>
            </div>

            <div class="flex justify-between px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
                <div>
                    <i class="fas fa-external-link-alt text-xl mt-2 text-[#f18325]"></i>
                </div>
                <div>
                    <x-primary-btn><i class="fa fa-paper-plane"></i><span>Save</span></x-primary-btn>
                    <x-secondary-btn onclick="closeEditReservationModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
