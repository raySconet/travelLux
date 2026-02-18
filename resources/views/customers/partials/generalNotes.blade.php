<div class="flex flex-col mt-4">
    <label for="generalNotes" class="mb-1 text-sm text-gray-700">General Notes</label>
    <textarea
        id="generalNotes"
        name="generalNotes"
        rows="3"
        class="w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1"
    >{{ old('general_notes', $customer->general_notes ?? '') }}</textarea>
</div>
<div class="relative mt-7">
    <label for="customerTags">Customer Tags</label>
    <select name="customerTags" id="customerTags" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
        <option value="-1">-- Select Customer Tags--</option>
    </select>
</div>