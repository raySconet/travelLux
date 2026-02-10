<div id="reportRangeModal"  class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[9999]">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative">
        
    
        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="far fa-calendar-alt text-xl text-[#f18325]"></i>
                <h2  class="text-xl font-semibold text-gray-700">
                    Report Date Range
                </h2>
            </div>

            <button onclick="closeReportDateRangeModal()" class="text-gray-400 hover:text-gray-600">
                ✕ 
            </button>
        </div>

    
        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-9 gap-y-4 px-4">
            <div class="relative mt-5">
                <x-text-input type="date" id="beginDate" name="beginDate"  />

                <x-input-label for="beginDate">Begin Date</x-input-label>
            </div>

            <div class="relative mt-5">
                <x-text-input type="date" id="endDate" name="endDate"  />

                <x-input-label for="endDate">End Date</x-input-label>
            </div>

            <div class="relative mt-6">
                <label for="reportRange">Or Range</label>
                <select name="reportRange" id="reportRange" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                    <option value="-1">-- Select Range --</option>
                    <option value="-30Days">-30 Days</option>
                    <option value="-45Days">-45 Days</option>
                    <option value="-90Days">-90 Days</option>
                    <option value="-180Days">-180 Days</option>
                    <option value="currentYear">Current Year</option>
                    <option value="lastYear">Last Year</option>
                    <option value="nextYear">Next Year</option>
                    <option value="lastQuarter">Last Quarter</option>
                    <option value="currentQuarter">Current Quarter</option>
                    <option value="nextQuarter">Next Quarter</option>
                    <option value="+90">+90 Days</option>
                </select>
            </div>

            <div class="relative mt-6">
                <label for="reportRangeByYear">Range By Year</label>

                <select name="reportRangeByYear" id="reportRangeByYear" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                    @php
                        $currentYear = now()->year;
                    @endphp

                    @for ($year = $currentYear+1; $year >= $currentYear - 15; $year--)
                        <option value="{{ $year }}" {{ $year === $currentYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

        </div>

        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
            <x-primary-btn id="agentSalesByProductReportBtn"><i class="fa fa-paper-plane"></i><span>Run Report</span></x-primary-btn>
            <x-secondary-btn onclick="closeReportDateRangeModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
        </div>

    </div>
</div>