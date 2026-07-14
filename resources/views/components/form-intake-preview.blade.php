<div id="formIntakePreviewModal" class="fixed inset-0 bg-white z-[9999] hidden border border-[#B6844A] flex flex-col">

    <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
        <div class="flex items-center space-x-2">
            <i class="fas fa-eye text-[#B6844A] text-base"></i>
            <h2 class="text-base">Intake Form Preview</h2>
        </div>

        <button class="flex items-center gap-2 bg-white border py-2 px-4 border-[#B6844A] text-[#B6844A]">
            <i class="fas fa-cloud-download-alt"></i>
            Download
        </button>

        <button type="button" onclick="closeFormIntakePreviewModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
            ✕
        </button>
    </div>

    <div class="px-6 py-4 space-y-4 overflow-auto flex-1">
        <div id="formIntakePreviewContent"></div>
    </div>
</div>