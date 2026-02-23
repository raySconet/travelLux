<div>
    <p class="text-xl">On Board Credit</p>

    <div class="relative mt-3">
        <x-text-input type="text" id="onboard_credit_from_cruise_line" name="onboard_credit_from_cruise_line"  value="{{ old('onboard_credit_from_cruise_line', $reservation->onboard_credit_from_cruise_line ?? '') }}"/>

        <x-input-label for="onboard_credit_from_cruise_line">From Cruise Line</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="onboard_credit_from_agent" name="onboard_credit_from_agent"  value="{{ old('onboard_credit_from_agent', $reservation->onboard_credit_from_agent ?? '') }}"/>

        <x-input-label for="onboard_credit_from_agent">From Agent</x-input-label>
    </div>

</div>