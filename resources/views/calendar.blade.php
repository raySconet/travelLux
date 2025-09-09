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
                <i class="fa-solid fa-calendar-plus fa-xl primary-color" style="color: #14548d;"></i>
                <h2 class="text-xl font-semibold text-gray-800">
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
            <x-primary-btn class="justify-self-end" id="submitAddEventCaseBtn">
                {{ __('Save') }}
            </x-primary-btn>
        </x-slot>
    </x-general-modal>
</x-app-layout>
