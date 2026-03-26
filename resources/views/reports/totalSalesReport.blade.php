<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-chart-area mr-2 text-[#f18325]"></i>{{ __('Total Sales') }}
            </h2>
            
            <button class="flex items-center gap-2 bg-white border py-2 px-4 border-[#f18325] text-[#f18325]"><i class="fas fa-cloud-download-alt"></i>Download</button>

            <button class="flex items-center gap-2"><i class="far fa-calendar-alt text-[#212121] text-2xl" onclick="openReportDateRangeModal()"></i>-</button>

        </div>
    </x-slot>

    <div class="mx-auto py-2 px-4">
       <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 items-start">
            <div class="bg-white shadow sm:rounded-lg p-3" x-data="{ section: 'checkinDate' }">
                <div class="topButtonsGroup" style="justify-content: left">
                    <div class="btn-group systemUsersNav" role="group">
                        <button type="button" class="systemUsersSectionBtn" style="padding: 5px 8px !important; font-size:13px;" :class="{ 'active': section === 'checkinDate' }" @click="section = 'checkinDate'">
                            Checkin Date
                        </button>
                        <button type="button" class="systemUsersSectionBtn" style="padding: 5px 8px !important; font-size:13px;" :class="{ 'active': section === 'bookDate' }" @click="section = 'bookDate'">
                            Book Date
                        </button>
                        <button type="button" class="systemUsersSectionBtn" style="padding: 5px 8px !important; font-size:13px;" :class="{ 'active': section === 'checkinAndBookDate' }" @click="section = 'checkinAndBookDate'">
                            Checkin & Book Date
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <div x-show="section === 'checkinDate'" x-cloak>
                    
                    </div>

                    <div x-show="section === 'bookDate'" x-cloak>
                        
                    </div>

                    <div x-show="section === 'checkinAndBookDate'" x-cloak>
                    
                    </div>
                </div>
            </div>
        </div>
      
    </div>

</x-app-layout>
<x-report-range-date>
    <x-slot name="footer">
        <x-primary-btn id="totalSalesReportBtn"><i class="fa fa-paper-plane"></i><span>Run Report</span></x-primary-btn>
        <x-secondary-btn onclick="closeReportDateRangeModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
    </x-slot>
</x-report-range-date>
