<div id="importReservationBookingsModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[9999]">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative">

        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
            <h2>Import Reservation Bookings</h2>

            <button onclick="closeImportReservationBookings()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                ✕ 
            </button>
        </div>

        <div class="px-6 py-4 space-y-4">
            <div class="relative mt-2 mb-4">
                <x-text-input type="text" id="reservation_number" name="reservation_number" />

                <x-input-label for="reservation_number">Reservation number</x-input-label>
            </div>
        </div>


        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2 mt-3">
            <x-secondary-btn type="button" onclick="closeImportReservationBookings()">
                <span>Cancel</span>
            </x-secondary-btn>

            <x-primary-btn type="submit">
                <span>Import bookings</span>
            </x-primary-btn>
        </div>
    </div>
</div>