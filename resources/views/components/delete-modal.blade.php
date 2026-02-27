<div id="deleteModal" class="fixed inset-0 bg-black/30 flex items-center justify-center hidden z-[9999]">

    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative">


        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-t-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-triangle text-[#f18325] text-lg"></i>
                <h2 class="text-lg"> Delete </h2>
            </div>

            <button type="button" onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-400">
                ✕
            </button>
        </div>

        <div class="px-6 py-4 space-y-4">
            <p class="text-base">Are you sure you want to delete this record ?</p>
        </div>

        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] gap-2">
            <x-secondary-btn type="button" onclick="closeDeleteModal()">
                <i class="fa fa-thumbs-down"></i>
                <span>No</span>
            </x-secondary-btn>

            <x-primary-btn type="button" id="confirmDeleteBtn">
                <i class="fa fa-thumbs-up"></i>
                <span>Yes</span>
            </x-primary-btn>
        </div>
    </div>
</div>