@vite('resources/css/calendar.css')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendar') }}
        </h2>
    </x-slot>

    <div class="text-gray-900 px-5 py-5 grid grid-cols-1 xl:grid-cols-12 gap-2">
        <div class="xl:col-span-3">
            <div class="row-span-4 bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="px-6 pb-4 border-b flex items-center justify-between">
                        <h2 id="sidebarCalendarMonthYearSelected" class="text-lg font-semibold">{{ date('F Y') }}</h2>
                        <div class="space-x-2">
                            <button class="text-gray-600 hover:text-black">&larr;</button>
                            <button class="text-gray-600 hover:text-black">&rarr;</button>
                        </div>
                    </div>

                    @include('calendar.calendar-sidebar') {{-- Include the calendar sidebar component --}}
                </div>
            </div>
            <div class="row-span-* bg-white overflow-hidden shadow-xs sm:rounded-lg mt-2">
                <div class="p-6">
                    @include('calendar.lawyers-sidebar') {{-- Include the calendar sidebar component --}}
                </div>
            </div>
        </div>

        <div class="xl:col-span-9 mx-auto">
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6">
                    @include('calendar.calendar-header') {{-- Include the calendar header component --}}
                </div>
            </div>

            <div class="mt-2">
                <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                    <div class="p-6">
                        @include('calendar.draw-calendar') {{-- Include the calendar drawing component --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
