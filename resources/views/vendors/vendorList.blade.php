<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fas fa-list mr-2 text-[#f18325]"></i>{{ __('Vendors') }}
            </h2>
            
        </div>
    </x-slot>
    

    <div class="p-2">
       
        <div class="bg-white shadow rounded-none ml-2 px-2 mr-1">
           
            <div class="flex items-end justify-end px-6 py-4 ">

                <div class="relative">
                    <form method="GET" action="{{ route('vendors.vendorList') }}">
                        <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Quick Search"
                        class="w-130 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 focus:outline-none"
                        oninput="clearTimeout(this.delay); this.delay=setTimeout(() => this.form.submit(), 500)"
                        >
                    </form>
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
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick='openVendorModal(@json($product))'>

                                <td class="w-1/6 px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $product->product_name }}
                                </td>

                                <td class="w-1/6 px-4 py-3 text-gray-600  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $product->phone_number }}
                                </td>

                                <td class="w-1/6 px-4 py-3 text-gray-600  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $product->vendor_bdm }}
                                </td>

                                <td class="w-1/6 px-4 py-3 text-gray-600  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $product->bdm_phone_number }}
                                </td>

                                <td class="w-1/6 px-4 py-3 text-gray-600  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $product->bdm_email }}
                                </td>

                                <td class="w-1/6 px-4 py-3 text-gray-600  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $product->notes }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-2 text-center">
                                    No data available in table
                                </td>
                            </tr>
                        @endforelse    
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Vendor Modal -->
    <form method="POST" id="vendorForm">
        @csrf
        @method('PUT')

        <div id="vendorModal" class="fixed inset-0 bg-black/30 flex items-center justify-center hidden z-[9999]">
            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative">
                
            
                <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-edit text-xl text-[#f18325]"></i>
                        <h2 id="vendorModalTitle" class="text-xl font-semibold text-gray-700">
                        Edit Vendor
                        </h2>
                    </div>

                    <button type="button" onclick="closeVendorModal()" class="text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>

        
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <p id="product_name" class="text-2xl font-bold"></p>
                    </div>

                    <div class="relative mt-8">
                        <x-text-input type="text" id="vendor_bdm" name="vendor_bdm"  />

                        <x-input-label for="vendor_bdm">BDM</x-input-label>
                    </div>

                    <div class="relative mt-5">
                        <x-text-input type="text" id="bdm_phone_number" name="bdm_phone_number"  />

                        <x-input-label for="bdm_phone_number">BDM Phone Number</x-input-label>
                    </div>

                    <div class="relative mt-8">
                        <x-text-input type="text" id="bdm_email" name="bdm_email"  />

                        <x-input-label for="bdm_email">BDM Email</x-input-label>
                    </div>

                    <div class="relative mt-8 mb-5">
                        <x-text-input type="text" id="notes" name="notes"  />

                        <x-input-label for="notes">Notes</x-input-label>
                    </div>
                </div>

                <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
                    <x-primary-btn type="submit" ><i class="fa fa-paper-plane"></i><span>Save</span></x-primary-btn>
                    <x-secondary-btn type="button" onclick="closeVendorModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
                </div>

            </div>
        </div>
    </form>

</x-app-layout>