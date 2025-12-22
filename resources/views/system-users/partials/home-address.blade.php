<div>
    <h4 class="font-semibold">Home Address</h4>

    <div class="relative mt-3">
        <x-text-input type="text" id="addressLine1" name="addressLine1"  />

        <x-input-label for="addressLine1">Address Line 1</x-input-label>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <x-text-input type="text" id="addressLine2" name="addressLine2"  />

            <x-input-label for="addressLine2">Address Line 2</x-input-label>
        </div>

        <div class="relative mt-3">
            <x-text-input type="text" id="city" name="city"  />

            <x-input-label for="city">City</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <x-text-input type="text" id="state" name="state"  />

            <x-input-label for="state">State</x-input-label>
        </div>

        <div class="relative mt-3">
            <x-text-input type="text" id="postalCode" name="postalCode"  />

            <x-input-label for="postalCode">Postal Code</x-input-label>
        </div>
    </div>
</div>