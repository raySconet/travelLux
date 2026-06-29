<div class="relative mt-3 mb-8">
    <x-text-input type="text" name="itineraryInfoFormTitle" />

    <x-input-label for="itineraryInfoFormTitle">Title*</x-input-label>

    <p class="validation-error text-red-500 text-sm mt-1 hidden"></p>
</div>

<div class="mb-8">

    <label class="block text-[14px] text-[#212529] mb-2">Note</label>

    <textarea class="event_note" name="itineraryInfoFormNote" rows="12"></textarea>

</div>

<h3 class="text-[20px] text-[#212529] mb-1 mt-2">Time</h3>
<div class="grid grid-cols-3 gap-4">
    
    <div class="relative mt-1">
        <x-text-input type="time"  name="itineraryInfoFormTime" />

        <x-input-label for="itineraryInfoFormTime">Time</x-input-label>
    </div>

</div>