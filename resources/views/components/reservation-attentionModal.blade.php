<div id="attentionReservationModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[9999]">

    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative">


        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-triangle text-[#f18325] text-lg"></i>
                <h2 class="text-lg">Attention</h2>
            </div>

            <button type="button" onclick="closeAttentionModal()" class="text-gray-400 hover:text-gray-400">
                ✕
            </button>
        </div>

        <div class="px-6 py-4 space-y-4">
            <p class="text-base">Please select at least one reservation.</p>
        </div>

        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6]">
            <x-primary-btn type="button" id="attentionModalBtn" onclick="closeAttentionModal()">
                <i class="fa fa-thumbs-up"></i>
                <span>Yes</span>
            </x-primary-btn>
        </div>
    </div>
</div>