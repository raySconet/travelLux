@props([
    'id',
    'title',
    'close',
    'saveClass' => null,
])

<div id="{{ $id }}"
     class="fixed inset-0 bg-black/30 flex items-center justify-center hidden z-[9999]">
    
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative">

        
        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-t-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="fas fa-plus-circle text-[#f18325] text-base"></i>
                <h2 class="text-base">{{ $title }}</h2>
            </div>

            <button onclick="{{ $close }}" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        
        <div class="px-6 py-4 space-y-4">
            {{ $slot }}
        </div>

        
        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
            <x-primary-btn class="{{ $saveClass }}">
                <i class="fa fa-paper-plane"></i>
                <span>Save</span>
            </x-primary-btn>

            <x-secondary-btn onclick="{{ $close }}">
                <i class="fa fa-times-circle"></i>
                <span>Cancel</span>
            </x-secondary-btn>
        </div>

    </div>
</div>
