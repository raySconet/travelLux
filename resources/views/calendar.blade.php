@vite('resources/css/calendar.css')

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Calendar') }}
            </h2>
            <a href="{{ route('category') }}" class="outline-none hover:outline-none focus:outline-none">
                <x-primary-btn>
                    <i class="fas fa-filter"></i>
                    <span class="ml-2">{{ __('Filter by category') }}</span>
                </x-primary-btn>
            </a>
        </div>
    </x-slot>

    @include('calendar.calendar-view')

    <x-general-modal id="addEventCaseModal">
        <x-slot name="header">
            <div class="grid grid-cols-[auto_1fr] items-center gap-2">
                <i class="fa-solid fa-calendar-plus fa-xl primary-color" id="headerIcon" style="color: #14548d;"></i>
                <h2 class="addEventCaseModalTitle text-xl font-semibold text-gray-800">
                    {{ __('Add Event or Case') }}
                </h2>
            </div>
            <i
                id="closeAddEventCaseModal"
                class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                role="button"
                data-bs-dismiss="modal"
                aria-label="Close">
            </i>
        </x-slot>

        @include('calendar.calendar-parts.add-event-case')

        <x-slot name="footer">
            <div id="modalFooterAction" class="flex flex-col gap-2">
                <x-primary-btn class="self-end" id="submitAddEventCaseBtn">
                    {{ __('Add E/C') }}
                </x-primary-btn>
            </div>
        </x-slot>
    </x-general-modal>

    <div class="refreshCalendar fixed right-3 bottom-2 flex items-center gap-2 p-2 bg-white rounded-md shadow-sm border border-gray-200 w-fit hover:bg-gray-50 cursor-pointer group">
        <label class="text-sm font-semibold text-gray-700 group-hover:text-[#14548d] cursor-pointer">
            Refresh Calendar
        </label>
        <i class="fa-solid fa-arrows-rotate fa-lg text-gray-500 transition-transform duration-300 group-hover:text-[#14548d]"
        id="refreshCalendarIcon"
        title="Refresh Calendar"></i>
    </div>
</x-app-layout>
