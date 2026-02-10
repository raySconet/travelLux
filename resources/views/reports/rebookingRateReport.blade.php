<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-percent mr-2 text-[#f18325]"></i>{{ __('Rebooking Rate Report') }}
            </h2>
            
            <button class="flex items-center gap-2 bg-white border py-2 px-4 border-[#f18325] text-[#f18325]"><i class="fas fa-cloud-download-alt"></i>Download</button>

            <button class="flex items-center gap-2"><i class="far fa-calendar-alt text-[#212121] text-2xl" onclick="openReportDateRangeModal()"></i>-</button>

        </div>
    </x-slot>

    <div class="p-2">
       <div class="bg-white shadow rounded-none ml-2">
        <div class="flex items-end justify-end px-6 py-4">
            <select id="agents" name="agents" class="w-64 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">-- Select Agent -- </option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->name }}
                    </option>
                @endforeach    
            </select>
        </div>
       </div>
    </div>

    <x-rebookingRate-report-range></x-rebookingRate-report-range>
</x-app-layout>
