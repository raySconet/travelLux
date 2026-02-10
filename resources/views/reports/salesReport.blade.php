<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-vote-yea mr-2 text-[#f18325]"></i>{{ __('Sales Report') }}
            </h2>

            <button class="flex items-center gap-2"><i class="far fa-calendar-alt text-[#212121] text-2xl" onclick="openReportDateRangeModal()"></i>-</button>

        </div>
    </x-slot>

    <div class="p-2">
       <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 items-start">
            <div class="bg-white shadow rounded-none ml-2 p-6" x-data="{ section: 'checkinDate' }">
                <div class="topButtonsGroup" style="justify-content: left;">
                    <div class="btn-group systemUsersNav" role="group">
                        <button type="button" class="systemUsersSectionBtn" style="padding: 5px 8px !important; font-size:13px;" :class="{ 'active': section === 'checkinDate' }" @click="section = 'checkinDate'">
                            Checkin Date
                        </button>
                        <button type="button" class="systemUsersSectionBtn" style="padding: 5px 8px !important; font-size:13px;" :class="{ 'active': section === 'bookDate' }" @click="section = 'bookDate'">
                            Book Date
                        </button>
                    </div>

                    <div class="relative justify-end items-end">
                        <input type="text" placeholder="Quick Search" class="w-64 border-0 border-b-2 border-gray-400 text-sm px-1 py-1" >
                    </div>
                </div>
                    <div class="mt-4">
                        <div x-show="section === 'checkinDate'" x-cloak>
                           
                        </div>

                        <div x-show="section === 'bookDate'" x-cloak>
                            
                        </div>
                    </div>
            </div>
        </div>
      
    </div>

    <x-report-range-date>
        <x-slot name="footer">
            <x-primary-btn id="totalSalesReportBtn"><i class="fa fa-paper-plane"></i><span>Run Report</span></x-primary-btn>
            <x-secondary-btn onclick="closeReportDateRangeModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
        </x-slot>
    </x-report-range-date>
</x-app-layout>
