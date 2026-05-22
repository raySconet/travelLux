<div class="relative mt-3 mb-8">
    <x-text-input type="text" id="itineraryInfoFormTitle" name="itineraryInfoFormTitle" />

    <x-input-label for="itineraryInfoFormTitle">Title*</x-input-label>
</div>

<div class="mb-8">

    <label class="block text-[14px] text-[#212529] mb-2">
        Note
    </label>

    <textarea class="event_note" name="itineraryInfoFormNote" rows="12"></textarea>

</div>

<h3 class="text-[20px] text-[#212529] mb-1 mt-2">Time</h3>
<div class="grid grid-cols-3 gap-4">
    
    <div class="relative mt-1">
        <x-text-input type="time" id="itineraryInfoFormTime" name="itineraryInfoFormTime" />

        <x-input-label for="itineraryInfoFormTime">Time</x-input-label>
    </div>
    
</div>

<div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
    <x-primary-btn type="submit">
        <span>Save</span>
    </x-primary-btn>
</div>