<div class="text-gray-900 px-5 py-5 grid grid-cols-1 2xl:grid-cols-12 gap-2">
    <div class="2xl:col-span-3 flex flex-col h-full">
        <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="pb-4 border-b flex items-center justify-between">
                    <div class="relative inline-block">
                        <h2 id="sidebarCalendarMonthYearSelected" class="text-lg font-semibold cursor-pointer">{{ date('F Y') }}</h2>
                        <div id="calendarMonthYearDropdown"
                            class="absolute z-10 mt-2 bg-white border-0 rounded shadow p-2 hidden
                            px-3 py-2 text-sm font-semibold text-gray-900  inset-ring-1 inset-ring-gray-300 hover:bg-gray-50 cursor-pointer
                        ">
                            <div class="flex">
                                <select id="monthSelect" class="border-0 p-1 mr-2"></select>
                                <select id="yearSelect" class="border-0 p-1"></select>
                            </div>
                        </div>
                    </div>
                    <div class="space-x-2">
                        <button id="sidebarCalendarPrevMonth" class="text-gray-600 hover:text-black cursor-pointer pl-2">&larr;</button>
                        <button id ="sidebarCalendarNextMonth" class="text-gray-600 hover:text-black cursor-pointer pr-2">&rarr;</button>
                    </div>
                </div>
                @include('calendar.calendar-parts.calendar-sidebar') {{-- Include the calendar sidebar component --}}
            </div>
        </div>
        <div class="bg-white overflow-visible shadow-xs sm:rounded-lg mt-2 py-6 pl-6">
            <div class="h-full max-h-[1040px] overflow-y-auto pr-6">
                @include('calendar.calendar-parts.lawyers-sidebar') {{-- Include the calendar sidebar component --}}
            </div>
        </div>
    </div>
    <div class="2xl:col-span-9 flex flex-col h-full overflow-x-auto">
        <div class="min-w-[1075px] flex flex-col h-full">
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6">
                    @include('calendar.calendar-parts.calendar-header') {{-- Include the calendar header component --}}
                </div>
            </div>
            <div class="mt-2 flex-1 flex flex-col bg-white xl:overflow-visible overflow-hidden shadow-xs sm:rounded-lg">
                <div id="viewMonthly" class="p-6">
                    @include('calendar.calendar-parts.monthly') {{-- Include the calendar drawing component --}}
                </div>
                <div id="viewWeekly" class="grid grid-rows-1 2xl:grid-rows-[40%_60%] gap-px p-6 h-full hidden">
                    @include('calendar.calendar-parts.weekly') {{-- Include the calendar drawing component --}}
                </div>
                <div id="viewDaily" class="p-6 hidden h-full">
                    <div class="grid grid-cols-12 grid-rows-[auto_1fr] gap-0 h-full">
                        <div class="col-span-12 grid grid-cols-12 gap-0 h-full">
                            @include('calendar.calendar-parts.daily')
                        </div>
                        <div class="col-span-12 grid grid-cols-12 gap-0 h-full">
                            <div id="dailyBox3" class="bg-[#eaf1ff] col-span-12 xl:col-span-6 h-full border-x-1 border-[#fff]"></div>
                            <div id="dailyBox4" class="bg-[#eaf1ff] col-span-12 xl:col-span-6 h-full border-x-1 border-[#fff] hidden"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
