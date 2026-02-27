<div>
    <p class="text-xl">Home Address</p>


    <div class="relative mt-3">
        <x-text-input type="text" id="first_address_line" name="first_address_line" value="{{ old('first_address_line', $user->first_address_line ?? '') }}" />

        <x-input-label for="first_address_line">Address Line 1</x-input-label>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-4">
            <x-text-input type="text" id="second_address_line" name="second_address_line" value="{{ old('second_address_line', $user->second_address_line ?? '') }}" />

            <x-input-label for="second_address_line">Address Line 2</x-input-label>
        </div>

        <div class="relative mt-4">
            <x-text-input type="text" id="city" name="city" value="{{ old('city', $user->city ?? '') }}" />

            <x-input-label for="city">City</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-4">
            <x-text-input type="text" id="state" name="state" value="{{ old('state', $user->state ?? '') }}" />

            <x-input-label for="state">State</x-input-label>
        </div>

        <div class="relative mt-4">
            <x-text-input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code ?? '') }}" />

            <x-input-label for="postal_code">Postal Code</x-input-label>
        </div>
    </div>
</div>