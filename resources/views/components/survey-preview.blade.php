<div id="surveyPreviewModal" class="fixed inset-0 bg-white z-[9999] hidden border border-[#B6844A]">

    <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
        <div class="flex items-center space-x-2">
            <i class="fas fa-eye text-[#B6844A] text-base"></i>
            <h2 class="text-base">Survey Preview</h2>
        </div>

        <button type="button" onclick="closeSurveyPreviewModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
            ✕
        </button>
    </div>

    <div class="px-6 py-4 space-y-4 overflow-auto flex-1">
        <div id="surveyPreviewContent"></div>
    </div>
</div>