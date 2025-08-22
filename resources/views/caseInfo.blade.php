@vite('resources/css/calendar.css')

<x-app-layout>
    <x-slot name="header" >
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendar') }}
        </h2>
    </x-slot>

    <div class="text-gray-900 px-5 py-5 grid grid-cols-1 2xl:grid-cols-12 gap-2">
        <div class="2xl:col-span-6 flex flex-col h-full">
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @include('case-info.main-section')
                </div>
            </div>
        </div>
        <div class="2xl:col-span-3 flex flex-col h-full overflow-x-auto">
            <div class="min-w-[1075px] flex flex-col h-full">
                <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                    <div class="p-6">
                        @include('calendar.calendar-header')
                    </div>
                </div>
            </div>
        </div>
        <div class="2xl:col-span-3 flex flex-col h-full overflow-x-auto">
            <div class="min-w-[1075px] flex flex-col h-full">
                <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                    <div class="p-6">
                        @include('calendar.calendar-header')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
