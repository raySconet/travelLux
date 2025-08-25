@vite('resources/css/calendar.css')

<x-app-layout>
    <x-slot name="header" >
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendar') }}
        </h2>
    </x-slot>

    <div class="text-gray-900 px-5 py-5 grid grid-cols-1 2xl:grid-cols-12 gap-2">
        <div class="2xl:col-span-3 flex flex-col h-full">
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="pb-4 border-b flex items-center justify-between">
                        <h2 id="sidebarCalendarMonthYearSelected" class="text-lg font-semibold">{{ date('F Y') }}</h2>
                        <div class="space-x-2">
                            <button id="sidebarCalendarPrevMonth" class="text-gray-600 hover:text-black cursor-pointer">&larr;</button>
                            <button id ="sidebarCalendarNextMonth" class="text-gray-600 hover:text-black cursor-pointer">&rarr;</button>
                        </div>
                    </div>
                    @include('calendar.calendar-sidebar') {{-- Include the calendar sidebar component --}}
                </div>
            </div>
            <div class="bg-white overflow-visible shadow-xs sm:rounded-lg mt-2 py-6 pl-6">
                <div class="h-full max-h-[1040px] overflow-y-auto pr-6">
                    @include('calendar.lawyers-sidebar') {{-- Include the calendar sidebar component --}}
                </div>
            </div>
        </div>
        <div class="2xl:col-span-9 flex flex-col h-full overflow-x-auto">
            <div class="min-w-[1075px] flex flex-col h-full">
                <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                    <div class="p-6">
                        @include('calendar.calendar-header') {{-- Include the calendar header component --}}
                    </div>
                </div>

                <div class="mt-2 flex-1 flex flex-col bg-white xl:overflow-visible overflow-hidden shadow-xs sm:rounded-lg">
                    <div id="viewMonthly" class="p-6">
                        @include('calendar.calendar-view.monthly') {{-- Include the calendar drawing component --}}
                    </div>
                    <div id="viewWeekly" class="p-6 hidden">
                        @include('calendar.calendar-view.weekly') {{-- Include the calendar drawing component --}}
                    </div>
                    <div id="viewDaily" class="p-6 flex justify-center hidden">
                        @include('calendar.calendar-view.daily') {{-- Include the calendar drawing component --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
