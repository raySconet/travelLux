<div id="creditCardFormConfirmationModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[9999]">
    
    <div class="bg-white w-full max-w-lg rounded-lg relative">


        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
            <h2 class="text-lg">Credit Card Form Confirmation</h2>

            <button type="button" onclick="closeCreditCardFormConfirmationModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                ✕
            </button>
        </div>

        <div class="px-6 py-4 space-y-4 mb-5 mt-3">
            <p class="text-base">Are you sure you want to send the Credit Card form to the client?</p>
        </div>

        <div class="flex justify-end px-6  py-4 border-t-2 border-[#dee2e6] space-x-2">
            <x-primary-btn type="button" id="creditCardFormConfirmationModal_sendBtn">
                <i class="fas fa-paper-plane"></i>
                <span>Send</span>
            </x-primary-btn>

            <x-secondary-btn type="button" onclick="closeCreditCardFormConfirmationModal()">
                <i class="fa fa-times-circle"></i>
                <span>Cancel</span>
            </x-secondary-btn>
        </div>
    </div>
</div>