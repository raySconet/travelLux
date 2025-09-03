@vite('resources/css/category.css')

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Calendar') }}
            </h2>
            <a href="{{ route('calendar') }}" class="outline-none hover:outline-none focus:outline-none">
                <x-primary-btn>
                        <i class="fas fa-calendar-alt"></i>
                        <span class="ml-2">{{ __('Calendar View') }}</span>
                </x-primary-btn>
            </a>
        </div>
    </x-slot>


    <div id="addEventModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl p-6 relative border border-gray-300">
            <div class="modal-content">
                <x-calendar-components.add-event-case />
            </div>
        </div>
    </div>
</x-app-layout>
