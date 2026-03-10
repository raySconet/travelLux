<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <x-text-input type="text" id="form_name" name="form_name" value="{{ old('form_name', $customerForm->form_name ?? '') }}" />
            <x-input-label for="form_name">Form Name</x-input-label>

            <x-input-error :messages="$errors->get('form_name')" />
        </div>

        <div class="relative mt-3">
            <x-text-input type="text" id="form_subject" name="form_subject" value="{{ old('form_subject', $customerForm->form_subject ?? '') }}" />
            <x-input-label for="form_subject">Form Subject</x-input-label>

            <x-input-error :messages="$errors->get('form_subject')" />
        </div>

        <div class="flex items-center gap-2 mt-12">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" class="h-4 w-4" name="is_active" value="1" {{ old('is_active', $customerForm->is_active ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Active</label>
        </div>
    </div>

    <div class="flex flex-col md:flex-row md:items-start md:gap-6 mt-6">
        <div class="flex-1">
            <span class="block text-lg font-semibold text-[#007bff] mb-2">This Form Is Required for:</span>
            <div class="flex items-center gap-2 mt-2">
                <input type="hidden" name="all_customers_required" value="0">
                <input type="checkbox" class="h-4 w-4" id="all_customers_required" name="all_customers_required" value="1" {{ old('all_customers_required', $customerFormRequired->all_customers_required ?? 0) == 1 ? 'checked' : '' }}>
                <label class="text-sm" for="all_customers_required">All Customers</label>
            </div>
            <span class="block mt-2 text-sm mb-1">Make this form required once for each customer</span>
        </div>


        <div class="flex-1">
            <div class="flex items-center gap-2 mt-8">
                <input type="hidden" name="all_reservations_required" value="0">
                <input type="checkbox" class="h-4 w-4" id="all_reservations_required" name="all_reservations_required" value="1" {{ old('all_reservations_required', $customerFormRequired->all_reservations_required ?? 0) == 1 ? 'checked' : '' }}>
                <label class="text-sm" for="all_reservations_required">All Reservations</label>
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
