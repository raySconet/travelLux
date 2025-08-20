@vite('resources/css/calendar.css')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendar') }}
        </h2>
    </x-slot>

    <div class=" px-5 py-12 grid grid-cols-1 xl:grid-cols-12 gap-2">
        <div class="xl:col-span-3">
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @include('calendar.calendar-sidebar') {{-- Include the calendar sidebar component --}}
                </div>
            </div>
        </div>
        <div class="xl:col-span-9 mx-auto">
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @include('calendar.calendar-header') {{-- Include the calendar header component --}}
                </div>
            </div>

            <div class="mt-2">
                <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @include('calendar.draw-calendar') {{-- Include the calendar drawing component --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
