<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl text-gray-500 leading-tight">
                <i class="fa-solid {{ $definition['icon'] }} mr-2 text-[#B6844A]"></i>{{ $definition['title'] }}
            </h2>
            @if($definition['download'])
            <button type="button" data-report-print class="flex items-center gap-2 bg-white border py-2 px-4 border-[#B6844A] text-[#B6844A] cursor-pointer">
                <i class="fas fa-cloud-download-alt"></i>Download
            </button>
            @endif
            @if($definition['dates'] || $definition['year'] || $definition['years'])
                <button type="button" class="flex items-center gap-2 cursor-pointer" data-report-open-range>
                    <i class="far fa-calendar-alt text-[#212121] text-2xl"></i>
                    <span id="selectedReportDates" class="text-[#212121] text-base font-bold">
                        @if($definition['year'])
                            {{ $filters['year'] }}
                        @elseif($definition['years'])
                            {{ $filters['beginYear'] }} - {{ $filters['endYear'] }}
                        @else
                            {{ \App\Services\Reports\ReportValue::format($filters['beginDate'], 'date') }} - {{ \App\Services\Reports\ReportValue::format($filters['endDate'], 'date') }}
                        @endif
                    </span>
                </button>
            @endif
        </div>
    </x-slot>

    <div class="mx-auto py-2 px-4">
        <div class="p-3 bg-white shadow sm:rounded-lg">
            <form method="GET" action="{{ url()->current() }}" id="reportFilters">
                <input type="hidden" name="dateBasis" value="{{ $filters['dateBasis'] }}">
                <div class="topButtonsGroup flex flex-wrap gap-4 items-center justify-between mb-4">
                    @if($definition['basis'])
                        <div class="btn-group systemUsersNav flex" role="group" aria-label="Report date type">
                            @foreach(['checkin' => 'Checkin Date', 'book' => 'Book Date', 'both' => 'Checkin & Book Date'] as $basis => $label)
                                @continue($basis === 'both' && $reportKey !== 'totalSalesReport')
                                <a href="{{ request()->fullUrlWithQuery(['dateBasis' => $basis, 'export' => null]) }}"
                                   class="systemUsersSectionBtn cursor-pointer {{ $filters['dateBasis'] === $basis ? 'active' : '' }}"
                                   style="padding:5px 8px;font-size:13px"
                                   @if($filters['dateBasis'] === $basis) aria-current="page" @endif>{{ $label }}</a>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex flex-wrap items-center gap-4 ml-auto">
                        @if($agents->isNotEmpty())
                            <label>
                                <span class="sr-only">Agent</span>
                                <select name="agentId" aria-label="Agent" class="border-0 border-b-2 border-gray-400 text-sm" data-report-submit>
                                    <option value="">{{ $reportKey === 'agentExpensesReport' ? 'My Expenses' : 'All Agents' }}</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" @selected(($filters['agentId'] ?? '') == $agent->id)>{{ $agent->fname }} {{ $agent->lname }}</option>
                                    @endforeach
                                </select>
                            </label>
                        @endif
                        @if($definition['state'])
                            <label>
                                <span class="sr-only">State</span>
                                <select name="state" aria-label="State" class="border-0 border-b-2 border-gray-400 text-sm" data-report-submit>
                                    <option value="">All States</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state }}" @selected(($filters['state'] ?? '') === $state)>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </label>
                        @endif
                        @if($definition['search'])
                        <input type="search" name="search" aria-label="Quick Search" placeholder="{{ $reportKey === 'unknownReservationsReport' ? 'Reservation # Search' : 'Quick Search' }}"
                               value="{{ $filters['search'] ?? '' }}" maxlength="200"
                               data-report-live-search="{{ $reportKey }}"
                               class="w-64 border-0 border-b-2 border-gray-400 text-sm px-1 py-1">
                        @endif
                    </div>
                </div>

                <dialog id="reportRangeModal" class="travelux-report-dialog bg-white rounded-lg shadow-lg" aria-labelledby="reportRangeTitle"
                    @if(($definition['dates'] || $definition['year'] || $definition['years']) && !request()->hasAny(['beginDate', 'endDate', 'year', 'beginYear', 'endYear', 'expenseId', 'newExpense'])) data-report-auto-open @endif>
                    <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
                        <h2 id="reportRangeTitle" class="text-xl font-semibold text-gray-700">
                            <i class="far fa-calendar-alt mr-2 text-[#B6844A]"></i>Report Date Range
                        </h2>
                        <button type="button" data-report-close-range class="text-gray-400 hover:text-gray-600 cursor-pointer" aria-label="Close">&times;</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-4 py-6">
                        @if($definition['year'])
                            <label class="text-sm">Year
                                <input type="number" name="year" min="1900" max="2100" value="{{ $filters['year'] }}" required class="w-full border-0 border-b-2 border-[#bdbdbd]">
                            </label>
                        @elseif($definition['years'])
                            <label class="text-sm">Begin Year
                                <input type="number" name="beginYear" min="1900" max="2100" value="{{ $filters['beginYear'] }}" required class="w-full border-0 border-b-2 border-[#bdbdbd]">
                            </label>
                            <label class="text-sm">End Year
                                <input type="number" name="endYear" min="1900" max="2100" value="{{ $filters['endYear'] }}" required class="w-full border-0 border-b-2 border-[#bdbdbd]">
                            </label>
                        @else
                            <label class="text-sm">Begin Date
                                <input type="date" name="beginDate" value="{{ $filters['beginDate'] }}" required class="w-full border-0 border-b-2 border-[#bdbdbd]">
                            </label>
                            <label class="text-sm">End Date
                                <input type="date" name="endDate" value="{{ $filters['endDate'] }}" required class="w-full border-0 border-b-2 border-[#bdbdbd]">
                            </label>
                            <label class="text-sm">Or Range
                                <select data-report-range class="w-full border-0 border-b-2 border-[#bdbdbd]">
                                    <option value="">-- Select Range --</option>
                                    @foreach(['-30' => '-30 Days', '-45' => '-45 Days', '-90' => '-90 Days', '-180' => '-180 Days', 'currentYear' => 'Current Year', 'lastYear' => 'Last Year', 'nextYear' => 'Next Year', 'lastQuarter' => 'Last Quarter', 'currentQuarter' => 'Current Quarter', 'nextQuarter' => 'Next Quarter', '+90' => '+90 Days'] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
                            @if($definition['rangeByYear'])
                                <label class="text-sm">Range By Year
                                    <select data-report-range-year class="w-full border-0 border-b-2 border-[#bdbdbd]">
                                        <option value="9999">All Years</option>
                                        @for($rangeYear = now()->year - 15; $rangeYear <= now()->year + 1; $rangeYear++)
                                            <option value="{{ $rangeYear }}" @selected($rangeYear === now()->year)>{{ $rangeYear }}</option>
                                        @endfor
                                    </select>
                                </label>
                            @endif
                        @endif
                    </div>
                    <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
                        <button type="submit" class="bg-[#B6844A] text-white rounded px-4 py-2 cursor-pointer"><i class="fa fa-paper-plane mr-2"></i>Run Report</button>
                        <button type="button" data-report-close-range class="bg-gray-100 text-gray-700 rounded px-4 py-2 cursor-pointer"><i class="fa fa-times-circle mr-2"></i>Cancel</button>
                    </div>
                </dialog>
            </form>
            @if($errors->any())
                <ul role="alert" class="mb-4 text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            @if(!empty($filters['search']))
                <p class="mb-3 text-sm text-gray-500">Showing matching rows. Report totals cover the selected dates and agent.</p>
            @endif
            <div id="reportDataContainer">
                <div class="hidden print:block mb-4 font-bold">{{ $definition['title'] }} — <span data-report-print-dates>{{ $definition['year'] ? $filters['year'] : ($definition['years'] ? $filters['beginYear'].' - '.$filters['endYear'] : ($definition['dates'] ? $filters['beginDate'].' - '.$filters['endDate'] : '')) }}</span></div>
                @if($sections === [] && $reportKey !== 'agentExpensesReport')
                    <p class="text-center text-gray-500 py-8">No Data Available for the selected dates.</p>
                @else
                    @include($definition['partial'])
                @endif
            </div>
        </div>
    </div>
    <style>
        .travelux-report-dialog { margin: auto; border: 0; padding: 0; width: min(95vw, 680px); max-height: 90vh; }
        .travelux-report-dialog::backdrop { background: rgb(0 0 0 / 50%); }
        #reportDataContainer table { width: 100%; border-collapse: collapse; }
        #reportDataContainer details > summary { cursor: pointer; }
    </style>
    @vite('resources/js/dataReports.js')
</x-app-layout>
