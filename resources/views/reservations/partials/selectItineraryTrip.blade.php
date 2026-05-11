<div>
    <p class="text-xl">Select Itinerary Trip</p>

    <div class="flex items-end gap-4 mt-6">
        <div class="relative flex-1">
            <select name="itinerary_trip_id" id="itinerary_trip_id" class="w-3/4 border-b border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#B6844A]">

                <option value="-1">-- Select Trip --</option>

                @foreach($itineraryTrips as $trip)
                    <option value="{{ $trip->id }}"
                        {{ old('itinerary_trip_id', $reservation->itinerary_trip_id ?? null) == $trip->id ? 'selected' : '' }}>
                        {{ $trip->name }}
                    </option>
                @endforeach

            </select>
        </div>

        <div>
            <x-secondary-buttonToDelete><i class="fas fa-file-pdf"></i><span>Pdf</span></x-secondary-buttonToDelete>
        </div>
    </div>
</div>