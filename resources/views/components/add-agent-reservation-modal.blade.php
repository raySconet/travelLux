<div id="addAgentReservationModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[9999] hidden">

    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg relative">


        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="fas fa-tag text-[#B6844A] text-xl"></i>
                <h2 class="text-lg">Add Agent Reservation</h2>
            </div>

            <button type="button" onclick="closeAddAgentReservationModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                ✕
            </button>
        </div>

        <div class="px-6 py-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">
                <div class="relative">
                    <label for="customer_id">Customer</label>
                    <select name="customer_id" id="customer_id" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A]">
                        <option value="">-- Select Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->lname . ', ' . $customer->fname }}
                            </option>
                        @endforeach    
                    </select>
                </div>

                <div class="relative">
                    <label for="agent_id">Assigned To</label>
                    <select name="agent_id" id="agent_id" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A]">
                        <option value="">--Select Agent--</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->fname . ' ' . $user->lname }}
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

                <div class="relative mt-3">
                    <x-text-input type="text" id="reservation_name" name="reservation_name" />

                    <x-input-label for="reservation_name">Reservation Name</x-input-label>
                </div>

                <div x-data="dateDropdown()" class="relative mt-3">
                    <x-text-input type="date" id="" name="" />

                    <x-input-label class="block text-sm mb-1">Date</x-input-label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">
                <div class="relative mt-3">
                    <label for="reservation_cost" class="block text-sm">Total Reservation Cost</label>

                    <div class="relative">
                        <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

                        <x-text-input type="text" id="reservation_cost" name="reservation_cost" class="pl-7" />
                    </div>
                </div>

                <div class="relative mt-3">
                    <label for="agency_commission" class="block text-sm">Agency Commission</label>

                    <div class="relative">
                        <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

                        <x-text-input type="text" id="agency_commission" name="agency_commission" class="pl-7" />
                    </div>
                </div>

                <div class="relative mt-7">
                    <label for="status" class="text-sm block mb-1">Status</label>
                    <select name="status"id="status" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                        <option value="Active">Active</option>
                        <option value="Prospect">Prospect</option>
                        <option value="Duplicate">Duplicate</option>
                        <option value="Canceled">Canceled</option>
                        <option value="Paid in Full">Paid in Full</option>
                        <option value="Claimed">Claimed</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Canceled w/ Insurance Payout">Canceled w/ Insurance Payout</option>
                        <option value="Canceled - Commission Protected">Canceled - Commission Protected</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
            <x-primary-btn type="submit">
                <i class="fa fa-paper-plane"></i>
                <span>Save</span>
            </x-primary-btn>

            <x-secondary-btn type="button" onclick="closeAddAgentReservationModal()">
                <i class="fa fa-times-circle"></i>
                <span>Cancel</span>
            </x-secondary-btn>
        </div>
    </div>
</div>