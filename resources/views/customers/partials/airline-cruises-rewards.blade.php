<div>
    <p class="text-xl">Airline Rewards</p>

    <div class="relative mt-3">
        <x-text-input type="text" id="united_airlines_reward" name="united_airlines_reward" value="{{ old('united_airlines_reward', $customer->united_airlines_reward ?? '') }}"  />

        <x-input-label for="united_airlines_reward">United Airlines</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="delta_airlines_reward" name="delta_airlines_reward" value="{{ old('delta_airlines_reward', $customer->delta_airlines_reward ?? '') }}" />

        <x-input-label for="delta_airlines_reward">Delta Airlines</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="southwest_airlines_reward" name="southwest_airlines_reward" value="{{ old('southwest_airlines_reward', $customer->southwest_airlines_reward ?? '') }}" />

        <x-input-label for="southwest_airlines_reward">SouthWest Airlines</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="american_airlines_reward" name="american_airlines_reward" value="{{ old('american_airlines_reward', $customer->american_airlines_reward ?? '') }}" />

        <x-input-label for="american_airlines_reward">American Airlines</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="other_airlines_reward" name="other_airlines_reward" value="{{ old('other_airlines_reward', $customer->other_airlines_reward ?? '') }}" />

        <x-input-label for="other_airlines_reward">Other</x-input-label>
    </div>

   <p class="text-xl mt-6">Cruises Rewards</p>

    <div class="relative mt-6">
        <x-text-input type="text" id="crownandanchor_cruise_reward" name="crownandanchor_cruise_reward" value="{{ old('crownandanchor_cruise_reward', $customer->crownandanchor_cruise_reward ?? '') }}" />

        <x-input-label for="crownandanchor_cruise_reward">Crown and Anchor</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="castaway_cruise_reward" name="castaway_cruise_reward"  value="{{ old('castaway_cruise_reward', $customer->castaway_cruise_reward ?? '') }}"/>

        <x-input-label for="castaway_cruise_reward">Castaway</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="vifp_cruise_reward" name="vifp_cruise_reward"  value="{{ old('vifp_cruise_reward', $customer->vifp_cruise_reward ?? '') }}"/>

        <x-input-label for="vifp_cruise_reward">VIFP</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="latitudes_cruise_reward" name="latitudes_cruise_reward"  value="{{ old('latitudes_cruise_reward', $customer->latitudes_cruise_reward ?? '') }}"/>

        <x-input-label for="latitudes_cruise_reward">Latitudes</x-input-label>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="other_cruise_reward" name="other_cruise_reward"  value="{{ old('other_cruise_reward', $customer->other_cruise_reward ?? '') }}"/>

        <x-input-label for="other_cruise_reward">Other</x-input-label>
    </div>
</div>