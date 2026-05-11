<div id="formPreviewModal" class="fixed inset-0 bg-white z-[9999] hidden border border-[#B6844A]">

    <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
        <div class="flex items-center space-x-2">
            <i class="fas fa-eye text-[#B6844A] text-base"></i>
            <h2 class="text-base">Form Preview</h2>
        </div>
        
        <div class="flex items-center space-x-2">
            <button class="flex items-center gap-2 bg-white border py-2 px-4 border-[#B6844A] text-[#B6844A]">
                <i class="fas fa-cloud-download-alt"></i>
                Download
            </button>

            <button type="button" onclick="closeFormPreviewModal()" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>
    </div>

    <div class="px-6 py-4 space-y-4 overflow-auto flex-1">
        <div id="formPreviewContent"></div>
    </div>

</div>