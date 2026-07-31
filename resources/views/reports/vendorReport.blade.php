<x-app-layout>

    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-list-alt mr-2 text-[#B6844A]"></i>{{ __('Vendor Sales') }}
            </h2>

            <button onclick="printElement('vendorReportContainer')" class="flex items-center gap-2 bg-white border py-2 px-4 border-[#B6844A] text-[#B6844A] cursor-pointer">
                <i class="fas fa-cloud-download-alt"></i>
                Download
            </button>

            <button  class="flex items-center gap-2 cursor-pointer" onclick="openReportDateRangeModal()">
                <i class="far fa-calendar-alt text-[#212121] text-2xl" ></i>
                <span id="selectedReportDates" class="text-[#212121] text-base font-bold"></span>
            </button>

        </div>
    </x-slot>

    <div class="p-4 space-y-6">

        <div class="bg-white rounded-lg shadow p-4">

            <div class="flex justify-end items-center">

                <div>

                    <select id="vendorStatus" class="border-b border-[#bdbdbd] px-3 py-2 mr-5">
                        <option value="-1">-- All Reservations --</option>
                        <option value="Active">Active</option>
                        <option value="Canceled">Canceled</option>
                        <option value="Paid in Full">Paid in Full</option>
                        <option value="Canceled w/ Insurance Payout">Canceled w/ Insurance Payout</option>
                        <option value="Canceled - Commission Protected">Canceled - Commission Protected</option>
                    </select>

                </div>

                <div class="flex gap-12">

                    <div class="text-center">

                        <div class="text-lg font-bold text-[#B6844A]" id="totalAgentCommission">
                            $0.00
                        </div>

                        <div class="text-sm text-gray-500">
                            Total Agent Commission
                        </div>

                    </div>

                    <div class="text-center">

                        <div class="text-lg font-bold text-[#B6844A]" id="totalSales">
                            $0.00
                        </div>

                        <div class="text-sm text-gray-500">
                            Total Sales
                        </div>

                    </div>

                </div>

            </div>

            <div id="vendorReportContainer"></div>
        </div>


    </div>

</x-app-layout>

<x-report-range-date>
    <x-slot name="footer">
        <x-primary-btn id="VendorReportBtn">
            <i class="fa fa-paper-plane"></i>
            <span>Run Report</span>
        </x-primary-btn>

        <x-secondary-btn onclick="closeReportDateRangeModal()">
            <i class="fa fa-times-circle"></i>
            <span>Cancel</span>
        </x-secondary-btn>
    </x-slot>
</x-report-range-date>