<div>
    <p class="text-xl">Airline Rewards</p>

    <div class="relative mt-3">
        <x-text-input type="text" id="unitedAirlines" name="unitedAirlines" value="{{ old('united_airlines_reward', $customer->united_airlines_reward ?? '') }}"  />

        <x-input-label for="unitedAirlines">United Airlines</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="deltaAirlines" name="deltaAirlines" value="{{ old('delta_airlines_reward', $customer->delta_airlines_reward ?? '') }}" />

        <x-input-label for="deltaAirlines">Delta Airlines</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="southWestAirlines" name="southWestAirlines" value="{{ old('southwest_airlines_reward', $customer->southwest_airlines_reward ?? '') }}" />

        <x-input-label for="southWestAirlines">SouthWest Airlines</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="americanWestAirlines" name="americanWestAirlines" value="{{ old('american_airlines_reward', $customer->american_airlines_reward ?? '') }}" />

        <x-input-label for="americanWestAirlines">American Airlines</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="other" name="other" value="{{ old('other_airlines_reward', $customer->other_airlines_reward ?? '') }}" />

        <x-input-label for="other">Other</x-input-label>
    </div>

   <p class="text-xl mt-6">Cruises Rewards</p>

    <div class="relative mt-6">
        <x-text-input type="text" id="crownAndAnchor" name="crownAndAnchor" value="{{ old('crownandanchor_cruise_reward', $customer->crownandanchor_cruise_reward ?? '') }}" />

        <x-input-label for="crownAndAnchor">Crown and Anchor</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="castaway" name="castaway"  value="{{ old('castaway_cruise_reward', $customer->castaway_cruise_reward ?? '') }}"/>

        <x-input-label for="castaway">Castaway</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="VIFP" name="VIFP"  value="{{ old('vifp_cruise_reward', $customer->vifp_cruise_reward ?? '') }}"/>

        <x-input-label for="VIFP">VIFP</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="latitudes" name="latitudes"  value="{{ old('latitudes_cruise_reward', $customer->latitudes_cruise_reward ?? '') }}"/>

        <x-input-label for="latitudes">Latitudes</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="other" name="other"  value="{{ old('other_cruise_reward', $customer->other_cruise_reward ?? '') }}"/>

        <x-input-label for="other">Other</x-input-label>
    </div>
</div>