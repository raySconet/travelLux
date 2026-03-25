<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-list-alt mr-2 text-[#f18325]"></i>{{ __('Reservations Paid By ALT Report') }}
            </h2>
            
            <button class="flex items-center gap-2 bg-white border py-2 px-4 border-[#f18325] text-[#f18325]"><i class="fas fa-cloud-download-alt"></i>Download</button>

            <button class="flex items-center gap-2"><i class="far fa-calendar-alt text-[#212121] text-2xl" onclick="openReportDateRangeModal()"></i>-</button>

        </div>
    </x-slot>

    <div class="p-2">  
       
    </div>

</x-app-layout>
<x-report-range-date>
    <x-slot name="footer">
        <x-primary-btn id="reservationPaidByALTReportBtn"><i class="fa fa-paper-plane"></i><span>Run Report</span></x-primary-btn>
        <x-secondary-btn onclick="closeReportDateRangeModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
    </x-slot>
</x-report-range-date>
