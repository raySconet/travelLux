<div>
    <p class="text-xl">Home Address</p>


    <div class="relative mt-3">
        <x-text-input type="text" id="addressLine1" name="addressLine1"  />

        <x-input-label for="addressLine1">Address Line 1</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="addressLine2" name="addressLine2"  />

        <x-input-label for="addressLine2">Address Line 2</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="city" name="city"  />

        <x-input-label for="city">City</x-input-label>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-6">
            <label for="state/province/region">State/Province/Region</label>
            <select name="state/province/region" id="state/province/region" class="w-full border-b-1 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select State/Province/Region--</option>
            </select>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="postalCode" name="postalCode"  />

            <x-input-label for="postalCode">Postal Code</x-input-label>
        </div>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="country" name="country"  />

        <x-input-label for="country">Country</x-input-label>
    </div>
</div>