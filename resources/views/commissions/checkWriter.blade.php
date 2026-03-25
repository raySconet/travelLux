<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class="text-xl text-gray-500 leading-tight flex items-baseline">
                <i class="fa-solid fa-pen mr-2 text-[#f18325]"></i>
                <span>Check Writer</span>
            </h2>

            <div class="space-x-2">
                <x-primary-btn><i class="fas fa-print"></i><span>Print</span></x-primary-btn>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto py-2 px-4">
       
        <div class="p-3 bg-white shadow sm:rounded-lg">
           
            <div class="flex  justify-between px-6 py-4 ">
                <div class="flex flex-row gap-4">
                    <button class="space-x-2 bg-[#f18325] text-white  py-2 px-4 rounded cursor-pointer border border-transparent
                                    hover:bg-white hover:border-[#f18325] hover:text-[#f18325]
                                    transition-all duration-200">PAY ALL AGENTS</button>
                    <span class="mt-3"> Selected </span>
                </div>
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Quick Search"
                        class="w-130 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 focus:outline-none"
                    >
                </div>
            </div>

            <div class="grid grid-cols-4 mt-5 border-t-2 border-[#dee2e6]">
                <div class="col-span-1 flex flex-col border-l-2 border-r-2 border-[#dee2e6] ">
                    <p>test</p>
                    <p>test</p>
                </div>

                <div class="col-span-3 flex flex-col border-r-2 border-[#dee2e6]">
                    <table class="min-h-full text-sm">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-[#dee2e6]">
                                    <input type="checkbox">
                                </th>

                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-[#dee2e6]">
                                    Customer
                                </th>

                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-[#dee2e6]">
                                    Reservation
                                </th>

                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-[#dee2e6]">
                                    Checkout
                                </th>

                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-[#dee2e6]">
                                    Fees
                                </th>

                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-[#dee2e6]">
                                    Total Commission
                                </th>

                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-[#dee2e6]">
                                    Agent Commission
                                </th>

                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-[#dee2e6]">
                                    Mentor Commission
                                </th>

                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-[#dee2e6]">
                                    Agency Commission
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            <tr class="hover:bg-gray-50 cursor-pointer">
                                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                                    <input type="checkbox">
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">

                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                                    <i class="fas fa-tag text-xl text-[#f18325]" onclick="openEditReservationCheckWriterModal()"></i>
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">

                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">

                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">

                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">

                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">

                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Edit Reservation Check Writer Modal -->
<div id="editReservationCheckWriterModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[9999]">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative max-h-[125vh] overflow-y-auto">

        <div class="flex items-center justify-between px-3 py-4 border-b-2 border-t-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="fas fa-edit text-[#f18325] text-xl"></i>
                <h2 class="text-xl">Edit Reservation</h2>
            </div>

            <button type="button" onclick="closeEditReservationCheckWriterModal()" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        <div class="px-2 py-2 space-y-2">
            <p> Reservation #:</p>
            <p> Customer: </p>

            <div class="relative mt-2">
                <x-text-input type="text" id="reservation_cost" name="reservation_cost" />

                <x-input-label for="reservation_cost">Total Reservation Cost</x-input-label>
            </div>

            <div class="relative mt-4">
                <x-text-input type="text" id="gross_commission" name="gross_commission" />

                <x-input-label for="gross_commission">Gross Commission</x-input-label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3">
                <div class="relative mt-2">
                    <x-text-input type="text" id="agent_commission" name="agent_commission" />

                    <x-input-label for="agent_commission">Agent Commission</x-input-label>
                </div>

                <div class="relative mt-2">
                    <x-text-input type="text" id="agent_commission_percentage" name="agent_commission_percentage" />

                    <x-input-label for="agent_commission_percentage">Percentage %</x-input-label>
                </div>
            </div>

            <div class="relative mt-7 mb-4">
                <input type="checkbox">
                <label>Non Commissionable</label>
            </div>
        </div>

        <div class="flex justify-between px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
            <i class="fas fa-external-link-alt text-lg text-[#f18325] mt-2 -ml-4"></i>

            <div class="-mr-5">
                <x-primary-btn type="submit" class="editReservationCheckWriterSaveBtn">
                    <i class="fas fa-paper-plane"></i>
                    <span>Save</span>
                </x-primary-btn> 
                
                <x-secondary-btn type="button" onclick="closeEditReservationCheckWriterModal()">
                    <i class="fa fa-times-circle"></i>
                    <span>Cancel</span>
                </x-secondary-btn>
            </div>    

        </div>
    </div>
</div>