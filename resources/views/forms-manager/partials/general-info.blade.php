<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <x-text-input type="text" id="formName" name="formName"  />
            <x-input-label for="formName">Form Name</x-input-label>
        </div>

        <div class="relative mt-3">
            <x-text-input type="text" id="formSubject" name="formSubject"  />
            <x-input-label for="formSubject">Form Subject</x-input-label>
        </div>

        <div class="flex items-center gap-2 mt-12">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Active</label>
        </div>
    </div>

    <div class="flex flex-col md:flex-row md:items-start md:gap-6 mt-6">
        <div class="flex-1">
            <span class="block text-lg font-semibold text-[#007bff] mb-2">This Form Is Required for:</span>
            <div class="flex items-center gap-2 mt-2">
                <input type="checkbox" class="h-4 w-4" id="allCustomers">
                <label class="text-sm" for="allCustomers">All Customers</label>
            </div>
            <span class="block mt-2 text-sm mb-1">Make this form required once for each customer</span>
        </div>


        <div class="flex-1">
            <div class="flex items-center gap-2 mt-8">
                <input type="checkbox" class="h-4 w-4" id="allReservations">
                <label class="text-sm" for="allReservations">All Reservations</label>
            </div>
            <span class="block mt-2 text-sm mb-1">Make this form required for every reservation</span>
        </div>


        <div class="flex items-center mt-8 ">
            <x-secondary-btn id="addRowBtn" class="px-4 py-2 text-white rounded flex items-center gap-2" style="margin-right:130px;">
                <i class="far fa-plus-square"></i>Add a specific Product/Destination
            </x-secondary-btn>
        </div>
    </div>
    <div id="productDestinationContainer" class="mt-4 space-y-3"></div>
</div>
