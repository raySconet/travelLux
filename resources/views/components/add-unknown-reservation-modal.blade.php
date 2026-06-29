<div id="addUnknownReservationModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[9999] hidden">

    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg relative">
        

        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="fas fa-tag text-[#B6844A] text-xl"></i>
                <h2 class="text-lg">Add Unknown Reservation</h2>
            </div>

            <button type="button" onclick="closeAddUnknownReservationModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                ✕
            </button>
        </div>

        <div class="px-6 py-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-8">
                <div class="relative">
                    <label for="customer_id">Customer</label>
                    <select name="customer_id" id="customer_id" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A]">
                        <option value="">--Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->lname . ',' . $customer->fname }}
                            </option>
                        @endforeach    
                    </select>
                </div>

                <div class="relative">
                    <label for="product_id">Product</label>
                    <select name="product_id" id="product_id" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A]">
                        <option value="">--Select Product--</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->product_name }}
                            </option>
                        @endforeach        
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">
                <div class="relative mt-3">
                    <x-text-input type="text" id="reservation_number" name="reservation_number" />

                    <x-input-label for="reservation_number">Reservation Number</x-input-label>
                </div>

                <div class="relative -mt-2">
                    <label for="agency_commission" class="block text-sm">Total Agency Commission</label>

                    <div class="relative">
                        <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

                        <x-text-input type="text" id="agency_commission" name="agency_commission" class="pl-7" />
                    </div>
                </div>

                <div class="relative mt-3">
                    <x-text-input type="date" id="" name="" />
                    
                    <x-input-label class="block text-sm mb-1">Month</x-input-label>
                </div>
            </div>

            <div class="relative mt-4">
                <x-text-input type="text" id="reservation_notes" name="reservation_notes" />

                <x-input-label for="reservation_notes">Reservation Notes</x-input-label>
            </div>
        </div>

        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
            <x-primary-btn type="submit">
                <i class="fa fa-paper-plane"></i>
                <span>Save</span>
            </x-primary-btn>

            <x-secondary-btn type="button" onclick="closeAddUnknownReservationModal()">
                <i class="fa fa-times-circle"></i>
                <span>Cancel</span>
            </x-secondary-btn>
        </div>
    </div>
</div>