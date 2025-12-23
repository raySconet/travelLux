<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Timeline Tasks</h2>

            <div class="space-x-2">
                <button class="bg-gray-800 text-white px-4 py-2 rounded space-x-2"><i class="fas fa-trash"></i><span>Delete</span></button>
                <button class="bg-gray-400 text-white px-4 py-2 rounded space-x-2"><i class="fas fa-save"></i><span>Save Task</span></button>
                <x-primary-btn class="space-x-2"><i class="far fa-minus-square"></i><span>Close Task</span></x-primary-btn>
            </div>
        </div>
    </x-slot>

    <div class="p-6 grid grid-cols-1 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            @include('timeline-tasks.partials.product-info')
        </div>
    </div>
</x-app-layout>