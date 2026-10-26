<dialog id="unknownReservationEditor" class="travelux-report-dialog bg-white rounded-lg shadow-lg"
        style="width: min(95vw, 1140px)" aria-labelledby="unknownReservationEditorTitle">
    <form id="unknownReservationEditorForm" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="search" value="{{ $filters['search'] ?? '' }}">
        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
            <h3 id="unknownReservationEditorTitle" class="text-xl font-semibold text-gray-700">
                <i class="fas fa-question mr-2 text-[#B6844A]" aria-hidden="true"></i>Unknown Reservations
            </h3>
            <button type="button" data-report-close-dialog class="text-gray-400 hover:text-gray-600 cursor-pointer text-xl" aria-label="Close">&times;</button>
        </div>
        <div class="px-6 py-6 space-y-6">
            <div id="unknownReservationEditorErrors" class="text-red-600 text-sm" role="alert" hidden></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <label class="text-sm text-gray-600">Customer
                    <select name="customer_id" required class="block w-full border-0 border-b-2 border-[#bdbdbd]">
                        <option value="">-- Select Customer --</option>
                    </select>
                </label>
                <label class="text-sm text-gray-600">Product
                    <select name="product_id" class="block w-full border-0 border-b-2 border-[#bdbdbd]">
                        <option value="-1">-- Select Product --</option>
                    </select>
                </label>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <label class="text-sm text-gray-600">Reservation Number
                    <input type="text" name="reservation_number" maxlength="255" required class="block w-full border-0 border-b-2 border-[#bdbdbd]">
                </label>
                <label class="text-sm text-gray-600">Total Agency Commission
                    <input type="number" name="agency_commission" min="0" step="0.01" class="block w-full border-0 border-b-2 border-[#bdbdbd]">
                </label>
                <label class="text-sm text-gray-600">Month
                    <input type="month" name="month" required class="block w-full border-0 border-b-2 border-[#bdbdbd]">
                </label>
            </div>
            <label class="block text-sm text-gray-600">Reservation Notes
                <textarea name="reservation_notes" rows="3" maxlength="65535" class="block w-full border-0 border-b-2 border-[#bdbdbd]"></textarea>
            </label>
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 border-t-2 border-[#dee2e6]">
            <button type="submit" class="bg-[#B6844A] text-white rounded px-4 py-2 cursor-pointer"><i class="fa fa-paper-plane mr-2"></i>Save</button>
            <button type="button" data-report-close-dialog class="bg-gray-100 text-gray-700 rounded px-4 py-2 cursor-pointer"><i class="fa fa-times-circle mr-2"></i>Cancel</button>
        </div>
    </form>
</dialog>
