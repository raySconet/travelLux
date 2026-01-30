<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fas fa-list mr-2 text-[#f18325]"></i>{{ __('Vendors') }}
            </h2>
            
        </div>
    </x-slot>
    

    <div class="p-6">
       
        <div class="bg-white shadow rounded-lg">
           
            <div class="flex items-end justify-end px-6 py-4 ">

                <div class="relative">
                    <input
                        type="text"
                        placeholder="Quick Search"
                        class="w-130 border-0 border-b-2 border-gray-400 text-sm px-1 py-1"
                    >
                </div>
            </div>

           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Product
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Phone Number
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                BDM
                            </th>   
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                BDM Phone Number
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                BDM Email Address
                            </th>  
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                General Notes
                            </th> 
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="openVendorModal('')">
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">
                                    test
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

                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Vendor Modal -->
    <div id="vendorModal" class="fixed inset-0 bg-black/30 flex items-center justify-center hidden z-[9999]">
        <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative">
            
        
            <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-edit text-xl text-[#f18325]"></i>
                    <h2 id="vendorModalTitle" class="text-xl font-semibold text-gray-700">
                       Edit Vendor
                    </h2>
                </div>

                <button onclick="closeVendorModal()" class="text-gray-400 hover:text-gray-600">
                    ✕
                </button>
            </div>

     
            <div class="px-6 py-4 space-y-4">
                <div>
                    <p id="vendorProductName" class="text-2xl font-bold">
                       test
                    </p>
                </div>

                <div class="relative mt-8">
                    <x-text-input type="text" id="BDM" name="BDM"  />

                    <x-input-label for="BDM">BDM</x-input-label>
                </div>

                <div class="relative mt-8">
                    <x-text-input type="text" id="BDMphoneNumber" name="BDMphoneNumber"  />

                    <x-input-label for="BDMphoneNumber">BDM Phone Number</x-input-label>
                </div>

                <div class="relative mt-8">
                    <x-text-input type="text" id="BDMemail" name="BDMemail"  />

                    <x-input-label for="BDMemail">BDM Email</x-input-label>
                </div>

                <div class="relative mt-8 mb-5">
                    <x-text-input type="text" id="notes" name="notes"  />

                    <x-input-label for="notes">Notes</x-input-label>
                </div>
            </div>

            <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
                <x-primary-btn><i class="fa fa-paper-plane"></i><span>Save</span></x-primary-btn>
                <x-secondary-btn onclick="closeVendorModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
            </div>

        </div>
    </div>

</x-app-layout>