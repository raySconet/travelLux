@if($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Linked Reservations will be available after this Reservation is saved.</span>
        </div>
    </div>   
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-xl">Traveling With</h6>

        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0" onclick="openTravelingWithModal()">
            <i class="fas fa-plus-circle"></i>
        </button>
    </div>     
@endif    

<!-- Reservations Traveling With Modal -->
<div id="travelingWithModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[9999]">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative">

        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-t-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="fas fa-plus-circle text-[#f18325] text-base"></i>
                <h2 class="text-base"> Add Linked Reservations </h2>
            </div>

            <button type="button" onclick="closeTravelingWithModal()" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        <div class="px-6 py-4 space-y-4">
            <div class="relative mt-3">
                <label for="customer">Customer</label>
                <select name="customer" id="customer" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                    <option value="-1">-- Select Customer --</option>
                </select>
            </div>

            <p class="mt-2 text-sm">Possible Reservations to Link:</p>
        </div>

    </div>
</div>