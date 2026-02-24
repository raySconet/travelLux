<div class="flex flex-col mt-4">
    <label for="flight_info" class="mb-1 text-sm text-gray-700">Flight Info</label>
    <textarea
        id="flight_info"
        name="flight_info"
        rows="3"
        class="w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1"
    >{{ old('flight_notes', $reservation->flight_notes ?? '') }}</textarea>
</div>
