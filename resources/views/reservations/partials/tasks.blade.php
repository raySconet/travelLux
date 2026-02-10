@if ($isNewReservation)
    <div class="space-x-2">
        <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
        <span class="text-[#6c757d] text-base">Tasks will be available after Reservation is saved.</span>
    </div>
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">Reservation Tasks</h6>

        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0" onclick="openReservationsTasksModal()">
            <i class="fas fa-plus-circle"></i>
        </button>

    </div>
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-row justify-between gap-3 mt-3">
        <h5 class="text-base">Timeline Tasks</h5>
    </div>
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-row justify-between gap-3 mt-3">
        <h5 class="text-base">General Tasks</h5>
    </div>
@endif

<!-- Reservations Tasks Modal -->
<x-reservations-modal id="reservationsTasksModal" title="Add Task" close="closeReservationsTasksModal()" saveClass="reservationTasksSaveBtn">
    <div class="relative mt-2">
        <x-text-input type="text" id="taskName" name="taskName"  />

        <x-input-label for="taskName">Task Name</x-input-label>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

        <div x-data="dateDropdown()" class="relative mt-5">
            <label class="block text-sm mb-1">Due Date</label>
            <div class="flex w-full border-b-2 border-[#bdbdbd] overflow-hidden outline-none">
                <select x-model="year" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Year</option>
                    <template x-for="y in years" :key="y">
                        <option :value="y" x-text="y"></option>
                    </template>
                </select>

                <select x-model="month" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Month</option>
                    <template x-for="(m, i) in months" :key="i">
                        <option :value="i + 1" x-text="m"></option>
                    </template>
                </select>

                <select x-model="day" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Day</option>
                    <template x-for="d in days" :key="d">
                        <option :value="d" x-text="d"></option>
                    </template>
                </select>

            </div>
            <input type="hidden" name="birthDate" :value="formattedDate">
        </div>

        <div class="relative mt-9">
            <label for="priority">Priority</label>
            <select name="priority" id="priority" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                <option value="-1">-- Select Priority --</option>
                <option value="lowPriority">Low</option>
                <option value="mediumPriority">Medium</option>
                <option value="highPriority">High</option>
            </select>
        </div>
    </div>

    <div class="relative mt-8 mb-5">
        <x-text-input type="text" id="notes" name="notes"  />

        <x-input-label for="notes">Notes</x-input-label>
    </div>

</x-reservations-modal>