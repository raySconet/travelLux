<div>
    <p class="text-xl">Home Address</p>


    <div class="relative mt-3">
        <x-text-input type="text" id="addressLine1" name="addressLine1" value="{{ old('address_line1', $customer->address_line1 ?? '') }}" />

        <x-input-label for="addressLine1">Address Line 1</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="addressLine2" name="addressLine2"  value="{{ old('address_line2', $customer->address_line2 ?? '') }}" />

        <x-input-label for="addressLine2">Address Line 2</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="city" name="city" value="{{ old('city', $customer->city ?? '') }}" />

        <x-input-label for="city">City</x-input-label>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-6">
            <label for="state">State/Province/Region</label>
            <select name="state" id="state" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="">--Select State/Province/Region--</option>

                @foreach($states as $state)
                    <option value="{{ $state->name }}"
                        {{ old('state', $customer->state ?? '') == $state->name ? 'selected' : '' }}>
                        {{ $state->name }}
                    </option>
                @endforeach
            </select>
        </div>
        

        <div class="relative mt-5">
            <x-text-input type="text" id="postalCode" name="postalCode" value="{{ old('postal_code', $customer->postal_code ?? '') }}" />

            <x-input-label for="postalCode">Postal Code</x-input-label>
        </div>
    </div>

    <div class="relative mt-3">
        <label for="country">Country</label>
        <select name="country" id="country" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
            <option value="">--Select Country--</option>

            @foreach($countries as $country)
                <option value="{{ $country->name }}"
                    {{ old('country', $customer->country ?? '') == $country->name ? 'selected' : '' }}>
                    {{ $country->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>