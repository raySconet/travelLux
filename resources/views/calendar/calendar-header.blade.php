<div class="calendarHeader">
    <div class="flex justify-between items-center">
        <x-calendar-components.section class="space-x-2">
            <x-calendar-components.secondary-btn id="calendarPrevMonth">
                <i class="fa-solid fa-chevron-left"></i>
            </x-calendar-components.secondary-btn>
            <x-calendar-components.secondary-btn id="calendarNextMonth">
                <i class="fa-solid fa-chevron-right"></i>
            </x-calendar-components.secondary-btn>
            <h2 id="calendarMonthYearSelected" class="text-lg font-semibold text-gray-800">{{ date('F Y') }}</h2>
        </x-calendar-components.section>

        <x-calendar-components.section class="justify-center space-x-2">
            <x-calendar-components.secondary-btn id="calendarEventTypeFilter">
                <i class="fa-solid fa-people-group"></i>
                <span class="ml-2">{{ __('Events') }}</span>
            </x-calendar-components.secondary-btn>
            <x-calendar-components.secondary-btn id="calendarCases">
                <i class="fa-solid fa-briefcase"></i>
                <span class="ml-2">{{ __('Cases') }}</span>
            </x-calendar-components.secondary-btn>
        </x-calendar-components.section>

        <x-calendar-components.section class="justify-end space-x-4">
            <el-dropdown class="inline-block w-max">
                <button class="inline-flex justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring-1 inset-ring-gray-300 hover:bg-[eaf1ff] cursor-pointer w-31">
                    <span id="selectedDayWeekMonthOption">
                        Month View
                    </span>
                    <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="-mr-1 size-5 text-gray-400">
                    <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                    </svg>
                </button>

                <el-menu anchor="bottom end" popover class="origin-top-right rounded-md bg-white shadow-lg outline-1 outline-black/5 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in">
                    <div class="py-1">
                        <x-calendar-components.view-option id="calendarDayViewOption">Day view</x-calendar-components.view-option>
                        <x-calendar-components.view-option id="calendarWeekViewOption">Week view</x-calendar-components.view-option>
                        <x-calendar-components.view-option id="calendarMonthViewOption">Month view</x-calendar-components.view-option>
                    </div>
                </el-menu>
            </el-dropdown>
            <span class="verticalLine"></span>
            <button class="bg-[#1c68aa] hover:opacity-80 text-white font-bold py-2 px-4 rounded cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span class="ml-2">
                    {{ __('Add Event') }}
                </span>
            </button>
        </x-calendar-components.section>
    </div>
</div>
