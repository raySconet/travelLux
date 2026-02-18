<div>
    <p class="text-xl">On Board Credit</p>

    <div class="relative mt-3">
        <x-text-input type="text" id="fromCruiseLine" name="fromCruiseLine"  value="{{ old('onboard_credit_from_cruise_line', $reservation->onboard_credit_from_cruise_line ?? '') }}"/>

        <x-input-label for="fromCruiseLine">From Cruise Line</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="fromAgent" name="fromAgent"  value="{{ old('onboard_credit_from_agent', $reservation->onboard_credit_from_agent ?? '') }}"/>

        <x-input-label for="fromAgent">From Agent</x-input-label>
    </div>

</div>