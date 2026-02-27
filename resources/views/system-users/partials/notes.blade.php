<div class="flex flex-col mt-4">
    <label for="general_notes" class="mb-1 text-sm text-gray-700">Enter General Notes</label>
    <textarea
        id="general_notes"
        name="general_notes"
        rows="3"
        class="w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1"
    >{{ old('general_notes', $user->general_notes ?? '') }}</textarea>
</div>
