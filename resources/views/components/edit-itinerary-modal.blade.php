<div id="editItineraryModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[9999]">

    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg relative">

        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-triangle text-[#B6844A] text-base"></i>
                <h2 class="text-base">Edit Itinerary</h2>
            </div>

            <button type="button" onclick="closeEditItineraryModal()" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        <form id="editItineraryForm">

            @csrf
            @method('PUT')

            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-2 md:grid-cols-2 gap-x-6 gap-y-4">

                    <div class="relative mt-3">
                        <x-text-input type="text" id="editItineraryName" name="name" class="w-full" />

                        <x-input-label for="editItineraryName">Title</x-input-label>
                    </div>

                    <div class="relative mt-3">
                        <x-text-input type="date" id="editItineraryDate" name="date" class="w-full" />

                        <x-input-label for="editItineraryDate">Date</x-input-label>
                    </div>

                </div>
            </div>

            <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
                <x-primary-btn type="submit">
                    <span>Save</span>
                </x-primary-btn>
            </div>

        </form>

    </div>
</div>