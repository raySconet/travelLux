<div id="reportRangeModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[9999]">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative">

        
        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
            <div class="flex items-center gap-2">
                <i class="far fa-calendar-alt text-xl text-[#f18325]"></i>
                <h2 class="text-xl font-semibold text-gray-700">
                    Report Date Range
                </h2>    
            </div>

            <button onclick="closeReportDateRangeModal()" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>    
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-9 gap-y-4 px-4">
            <div class="relative mt-6">
                <label for="beginYearRebookingRateReport">Begin Year</label>

                <select name="beginYearRebookingRateReport" id="beginYearRebookingRateReport" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                    @php 
                        $previousYear = now()->year-1;
                    @endphp

                    @for ($year = 2020; $year<=2030; $year++)
                        <option value="{{ $year }}" {{ $year === $previousYear ? 'selected' : ''}}>
                            {{ $year }}
                        </option>
                    @endfor    
                </select>
            </div>

            <div class="relative mt-6">
                <label for="endYearRebookingRateReport">End Year</label>

                <select name="endYearRebookingRateReport" id="endYearRebookingRateReport" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                    @php 
                        $currentYear = now()->year;
                    @endphp
                    
                    @for($year = 2020; $year<=2030; $year++)
                        <option value="{{ $year }}" {{ $year === $currentYear ? 'selected' : ''}}>
                            {{ $year }}
                        </option>
                    @endfor    
                </select>
            </div>
        </div>

        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
            <x-primary-btn id="rebookingRateReportBtn"><i class="fa fa-paper-plane"></i><span>Run Report</span></x-primary-btn>
            <x-secondary-btn onclick="closeReportDateRangeModal()"><i class="fa fa-times-circle"></i><span>Cancel</span></x-secondary-btn>
        </div>

    </div>
</div>