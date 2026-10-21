@php
    $money = static fn ($value) => \App\Services\Reports\ReportValue::format($value ?? 0, 'money');
    $searching = trim($filters['search'] ?? '') !== '';
    $visibleSections = collect($sections)->filter(fn ($section) => collect($section['rows'])->isNotEmpty() || ($reportKey === 'finalAgencyCommissionReport' && !$searching));
    $financialValue = static function ($value, $format) use ($reportKey) {
        $formatted = \App\Services\Reports\ReportValue::format($value, $format);
        return $format === 'date' && in_array($reportKey, ['reservationsNotPaidByTraveluxReport', 'reservationsPaidByTraveluxReport', 'totalCommissionReport'])
            ? str_replace('/', '-', $formatted) : $formatted;
    };
@endphp

@if($reportKey === 'agentExpensesReport' && ($expenseFormEnabled ?? false))
    @include('reports.partials.expenseForm')
@endif

@if($visibleSections->isEmpty())
    <div class="bg-white rounded shadow p-6 text-center text-gray-500">
        {{ $searching ? 'No matching records.' : 'No Data Available.' }}
    </div>
@elseif($reportKey === 'totalCommissionReport')
    @foreach($visibleSections->where('kind', 'commission_summary') as $section)
        <div class="bg-white border border-[#dee2e6] shadow sm:rounded-lg overflow-hidden mb-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#dee2e6] p-4">
                <div class="text-xl font-semibold text-gray-800">{{ $section['title'] }}</div>
                <div class="flex flex-wrap gap-x-10 gap-y-4">
                    @foreach($section['summary'] as $metric)
                        <div>
                            <div class="text-base">{{ $money($metric['value']) }}</div>
                            <div class="text-sm text-gray-400">{{ $metric['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @include('reports.partials.dataTable', ['section' => $section])
        </div>
    @endforeach

    @foreach($visibleSections->where('kind', 'commission_details') as $section)
        <details class="bg-white border border-[#dee2e6] sm:rounded-lg overflow-hidden mb-3" @if($searching) open @endif>
            <summary class="cursor-pointer px-4 py-3 text-gray-700 font-semibold">
                {{ $section['title'] }}
                <span class="ml-2 text-sm text-gray-400 font-normal">Commission details</span>
            </summary>
            <div class="flex flex-col lg:flex-row border-t border-[#dee2e6]">
                <div class="lg:w-[235px] shrink-0 border-b lg:border-b-0 lg:border-r border-[#dee2e6]">
                    <div class="bg-[#c8e6c9] border-b border-[#98c391] px-4 py-3">
                        <div class="text-xl font-semibold text-gray-800">{{ $section['title'] }}</div>
                        <div class="text-sm text-gray-600">{{ number_format($section['agent_percentage'], 0) }}%</div>
                    </div>
                    <div class="px-4 py-3 space-y-3">
                        @foreach($section['summary'] as $metric)
                            <div>
                                <div class="text-sm">{{ $money($metric['value']) }}</div>
                                <div class="text-gray-400 text-xs">{{ $metric['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex-1 min-w-0 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-[#dee2e6]">
                                @foreach($section['columns'] as $label)
                                    <th scope="col" class="px-3 py-3 text-left font-semibold">{{ $label }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($section['rows'] as $row)
                                <tr class="border-b border-[#dee2e6] last:border-b-0 hover:bg-gray-50">
                                    @foreach($section['columns'] as $key => $label)
                                        <td class="px-3 py-3 text-gray-600">
                                            @if($key === 'reservation_number')
                                                <a href="{{ route('reservations.reservationDetails', $row['reservation_id']) }}" title="Edit Reservation" class="text-[#B6844A]">
                                                    <i class="fas fa-tag mr-1" aria-hidden="true"></i>{{ $row['reservation_number'] }}
                                                </a>
                                            @else
                                                <span @if(($key === 'agent_commission' && !$row['mentoring']) || ($key === 'mentor_commission' && $row['mentoring'])) class="text-[#00a86b]" @endif>
                                                    {{ $financialValue(data_get($row, $key), $section['formats'][$key] ?? null) }}
                                                </span>
                                            @endif
                                            @if($key === 'agent_commission' && !$row['mentoring'])
                                                <span class="text-xs text-gray-400">{{ number_format($row['agent_percentage'], 0) }}%</span>
                                            @elseif($key === 'mentor_commission' && $row['mentoring'])
                                                <span class="text-xs text-gray-400">{{ number_format($row['mentor_percentage'], 2) }}%</span>
                                            @elseif($key === 'total_commission' && $row['personal_travel'] === 'Yes')
                                                <span class="text-xs text-[#B6844A] whitespace-nowrap" title="Agent Personal Travel"><i class="far fa-flag" aria-hidden="true"></i> PT</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </details>
    @endforeach
@else
    @if($reportKey === 'agentExpensesReport')
        <div class="bg-white rounded shadow px-4 py-3 mb-4 text-right">
            <span class="text-sm text-gray-400 mr-3">Total Amount</span>
            <strong class="text-[#B6844A]">{{ $money(collect($sections)->sum(fn ($section) => $section['totals']['amount'] ?? 0)) }}</strong>
        </div>
    @elseif($reportKey === 'finalAgencyCommissionReport')
        <div class="bg-white rounded shadow px-4 py-3 mb-4 text-right">
            <span class="text-sm text-gray-400 mr-3">Total Agency Commission</span>
            <strong class="text-[#B6844A]">{{ $money(collect($sections)->sum(fn ($section) => $section['totals']['agency_commission'] ?? 0)) }}</strong>
        </div>
    @endif

    @foreach($visibleSections as $section)
        <div class="flex flex-col lg:flex-row items-stretch bg-white border border-gray-300 sm:rounded-lg overflow-hidden mb-4">
            <div class="lg:w-[235px] shrink-0 border-b lg:border-b-0 lg:border-r border-gray-300">
                <div class="bg-[#c8e6c9] border-b border-[#98c391] px-4 py-3">
                    <div class="text-xl font-semibold text-gray-800">{{ $section['title'] }}</div>
                </div>
                <div class="px-4 py-3 space-y-3">
                    @foreach($section['summary'] as $metric)
                        <div>
                            <div class="text-sm">{{ \App\Services\Reports\ReportValue::format($metric['value'], $metric['format']) }}</div>
                            <div class="text-gray-400 text-xs">{{ $metric['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex-1 min-w-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm" @if($reportKey === 'agentExpensesReport') data-report-sortable data-report-sort-column="6" data-report-sort-direction="asc" @endif>
                            <thead>
                                <tr class="border-b-2 border-[#dee2e6]">
                                    @foreach($section['columns'] as $label)
                                        <th scope="col" class="px-4 py-3 text-left font-bold">{{ $label }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($section['rows'] as $row)
                                    <tr class="border-b border-[#dee2e6] last:border-b-0 hover:bg-gray-50">
                                        @foreach($section['columns'] as $key => $label)
                                            <td class="px-4 py-3 text-gray-600">
                                                @if($reportKey === 'agentExpensesReport')
                                                    <a href="{{ request()->fullUrlWithQuery(['expenseId' => data_get($row, 'id'), 'newExpense' => null, 'export' => null]) }}" class="block hover:underline" title="Edit Expense" aria-label="Edit expense for {{ data_get($row, 'customer') ?: 'customer' }}">
                                                        {{ $financialValue(data_get($row, $key), $section['formats'][$key] ?? null) ?: "\u{00a0}" }}
                                                    </a>
                                                @else
                                                    {{ $financialValue(data_get($row, $key), $section['formats'][$key] ?? null) }}
                                                    @if($reportKey === 'finalAgencyCommissionReport' && $key === 'paid_amount')
                                                        <span class="ml-1 cursor-help" tabindex="0" title="Agency Commission: {{ $money($row['total_commission']) }}&#10;Agent - {{ $row['agent_percentage'] }}%: {{ $money($row['agent_commission']) }}&#10;Paid To Agent: {{ $money($row['paid_amount']) }}">
                                                            <i class="fas fa-bars" aria-hidden="true"></i><span class="sr-only">Commission breakdown</span>
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    @endforeach
@endif
