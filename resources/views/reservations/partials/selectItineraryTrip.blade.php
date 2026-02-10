<div>
    <p class="text-xl">Select Itinerary Trip</p>

    <div class="flex items-end gap-4 mt-6">
        <div class="relative flex-1">
            <select name="selectItineraryTrip"
                    id="selectItineraryTrip"
                    class="w-3/4 border-b border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">-- Select Trip --</option>
            </select>
        </div>

        <div>
            <x-secondary-buttonToDelete><i class="fas fa-file-pdf"></i><span>Pdf</span></x-secondary-buttonToDelete>
        </div>
    </div>
</div>