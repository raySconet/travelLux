<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class="text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-plane mr-2 text-[#B6844A]"></i>{{ __('Itinerary') }}
            </h2>

            <div class="space-x-2">
                <x-secondary-btn type="submit" form="createTripForm">
                    <i class="fas fa-save"></i>
                    <span>Save</span>
                </x-secondary-btn>

                <x-primary-btn onclick="window.history.back()">
                    <i class="far fa-minus-square"></i>
                    <span>Close Trip</span>
                </x-primary-btn>
            </div>
        </div>
    </x-slot>

    <form id="createTripForm" method="POST" action="{{ route('itinerary.store') }}">
        @csrf

        <div class="bg-white shadow sm:rounded-lg p-6 ml-4 mt-2 mr-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                <div class="relative mt-3">
                    <x-text-input type="text" id="name" name="name" />

                    <x-input-label for="name">Name</x-input-label>

                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="relative mt-3">
                    <x-text-input type="date" id="date" name="date" />

                    <x-input-label for="date">Date</x-input-label>

                    @error('date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>
        </div>
    </form>
</x-app-layout>