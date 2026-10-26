@php
    $money = static fn ($value) => \App\Services\Reports\ReportValue::format($value ?? 0, 'money');
    $groupedSales = in_array($reportKey, ['productSalesByAgentReport', 'agentSalesByProductReport', 'productSalesWithDepositByAgentReport'], true);
    $outerRows = collect($sections[0]['rows'] ?? []);
@endphp

<style>
    .sales-report .sales-report-table { width: 100%; border-collapse: collapse; }
    .sales-report .sales-report-table th, .sales-report .sales-report-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #dee2e6; vertical-align: top; }
    .sales-report .sales-report-table th { font-weight: 600; border-bottom-color: #bdbdbd; }
    .sales-report .sales-report-table tbody tr:nth-child(even) { background: #fafafa; }
    .sales-report .sales-report-table tbody tr:hover { background: #f5f5f5; }
    .sales-report .vendorReportTableRowContainer { display: flex; gap: 15px; margin-bottom: 24px; }
    .sales-report .vendorReportProductInfoContainer { width: 300px; flex-shrink: 0; align-self: flex-start; border: 1px solid #dee2e6; box-shadow: 0 1px 3px #0002; }
    .sales-report .vendorReportProcuctNameContainer { min-height: 87px; padding: 15px; display: flex; align-items: center; background: #c8e6c9; font-size: 24px; }
    .sales-report .productSalesWithDepositByAgentReportProcuctNameContainer { min-height: 66px; font-size: 30px; }
    .sales-report .salesReportTotalAmountContainer { background: #bdbdbde6; padding: 10px; border-radius: 14px; }
    .sales-report .vendorReportCommissionInfoContainer { padding: 5px 15px; border-top: 1px solid #ccc; }
    .sales-report .vendorReportComissionLabelContainer { color: #807e7e; }
    .sales-report .vendorReportRowDetailsContainer { flex: 1; min-width: 0; }
    .sales-report .vendorReportAgentSalesInfoContainer { margin-bottom: 15px; border: 1px solid #dee2e6; }
    .sales-report .vendorReportAgentSalesHeaderContainer { display: flex; align-items: center; justify-content: space-between; gap: 15px; padding: 15px; background: #1e88e5; color: white; }
    .sales-report .vendorReportAgentSalesHeaderTitle { font-size: 24px; }
    .sales-report .vendorReportAgentSalesHeaderCommissionInfoContainer { text-align: right; font-size: 14px; line-height: 1.8; }
    .sales-report .vendorReportAgentSalesHeaderCommissionTitle { color: #e0e0e0; }
    .sales-report .agentSalesContainer { display: flex; margin-bottom: 15px; box-shadow: 0 1px 3px #0003; }
    .sales-report .agentInfoContainer { width: 300px; flex-shrink: 0; padding: 20px; display: flex; align-items: center; justify-content: center; flex-direction: column; background: #1e88e5; color: white; font-size: 20px; font-weight: bold; text-align: center; }
    .sales-report .agentSalesChartContainer { flex: 1; min-width: 0; min-height: 250px; overflow-x: auto; }
    .sales-report .rebookingRateTableRecord { display: flex; border: 1px solid #ccc; margin-bottom: 15px; }
    .sales-report .rebookingRateFirstSection { width: 15%; min-width: 180px; flex-shrink: 0; border-right: 1px solid #ccc; padding-bottom: 15px; }
    .sales-report .agentNameAndPercentageContainer { background: #c8e6c9; padding: 10px 14px; border-bottom: 1px solid #ccc; }
    .sales-report .agentNameContainer { font-size: 20px; font-weight: bold; }
    .sales-report .rebookingRateSecondSection { flex: 1; min-width: 0; }
    .sales-report .totalReservationsCountContainer { margin-top: 10px; margin-left: 14px; }
    .sales-report .darkgreyColor { color: #999; }
    @media (max-width: 900px) {
        .sales-report .vendorReportTableRowContainer, .sales-report .agentSalesContainer, .sales-report .rebookingRateTableRecord { flex-direction: column; }
        .sales-report .vendorReportProductInfoContainer, .sales-report .agentInfoContainer, .sales-report .rebookingRateFirstSection { width: 100%; }
        .sales-report .vendorReportAgentSalesHeaderContainer { flex-wrap: wrap; }
    }
    @media print {
        .sales-report .vendorReportTableRowContainer, .sales-report .agentSalesContainer, .sales-report .rebookingRateTableRecord { break-inside: avoid; }
        .sales-report { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
    }
</style>

<div class="sales-report bg-white rounded-lg shadow p-4">
    @if($groupedSales)
        @php
            $byProduct = $reportKey === 'agentSalesByProductReport';
            $deposit = $reportKey === 'productSalesWithDepositByAgentReport';
            $outerKey = $byProduct ? 'product_id' : 'agent_id';
            $outerLabel = $byProduct ? 'product' : 'agent';
            $innerKey = $byProduct ? 'destination_id' : 'product_id';
            $innerLabel = $byProduct ? 'destination' : 'product';
            $showGross = array_key_exists('gross_commission', $sections[0]['columns'] ?? []);
            $middleRows = collect($sections[1]['rows'] ?? []);
            $detailRows = collect($sections[2]['rows'] ?? []);
            $summaryFields = $deposit ? ['total_sales' => 'Total Sales'] : (($showGross ? ['gross_commission' => 'Total Commission'] : []) + ['agent_commission' => 'Agent Commission', 'total_sales' => 'Total Sales']);
        @endphp
        <div class="flex justify-end py-4">
            <div class="salesReportTotalAmountContainer">
                Total Sales: <span class="font-bold ml-1">{{ $money($sections[0]['totals']['total_sales'] ?? 0) }}</span>
            </div>
        </div>
        @forelse($outerRows as $outer)
            @php
                $children = $middleRows->where($outerKey, data_get($outer, $outerKey));
                $outerDetails = $detailRows->where($outerKey, data_get($outer, $outerKey));
            @endphp
            <div class="vendorReportTableRowContainer">
                <div class="vendorReportProductInfoContainer">
                    <div class="vendorReportProcuctNameContainer {{ $deposit ? 'productSalesWithDepositByAgentReportProcuctNameContainer' : '' }}">
                        @unless($byProduct)<i class="fas fa-user mr-2" aria-hidden="true"></i>@endunless
                        {{ data_get($outer, $outerLabel) }}
                    </div>
                    @foreach($summaryFields as $field => $label)
                        <div class="vendorReportCommissionInfoContainer">
                            <div>{{ $money(data_get($outer, $field)) }}</div>
                            <div class="vendorReportComissionLabelContainer">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="vendorReportRowDetailsContainer">
                    @foreach($children as $child)
                        @php
                            $section = $sections[2];
                            $section['rows'] = $outerDetails->where($innerKey, data_get($child, $innerKey));
                            $section['columns'] = $deposit
                                ? ['reservation_number' => 'Reservation Number', 'total_sales' => 'Reservation Cost', 'payment_date' => 'Deposit Date', 'checkin_date' => 'Checkin Date']
                                : [($byProduct ? 'agent' : 'destination') => ($byProduct ? 'Agent' : 'Destination'), 'total_sales' => 'Total Reservation Cost', 'agent_commission' => 'Agent Commission'] + ($showGross ? ['gross_commission' => 'Total Commission'] : []);
                        @endphp
                        <div class="vendorReportAgentSalesInfoContainer">
                            <div class="vendorReportAgentSalesHeaderContainer">
                                <div class="vendorReportAgentSalesHeaderTitle">{{ data_get($child, $innerLabel) }}</div>
                                <div class="vendorReportAgentSalesHeaderCommissionInfoContainer">
                                    @foreach($summaryFields as $field => $label)
                                        <div><span class="vendorReportAgentSalesHeaderCommissionTitle">{{ $label }}:</span> <strong>{{ $money(data_get($child, $field)) }}</strong></div>
                                    @endforeach
                                </div>
                            </div>
                            @include('reports.partials.salesTable', ['section' => $section])
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500">No Data Available.</div>
        @endforelse
    @elseif($reportKey === 'salesReport')
        <div id="salesReportTableContainer">
            @forelse($outerRows as $agent)
                @php
                    $months = collect($sections[1]['rows'] ?? [])->where('agent_id', data_get($agent, 'agent_id'))->values();
                    $maximum = max(1, (float) $months->max('total_sales'));
                    $chartWidth = max(680, $months->count() * 85 + 90);
                    $plotWidth = $chartWidth - 90;
                    $step = $plotWidth / max(1, $months->count());
                    $chartSeries = $months->map(fn ($month) => ['name' => data_get($month, 'month'), 'y' => (int) data_get($month, 'total_sales')])->values();
                @endphp
                <div class="agentSalesContainer">
                    <div class="agentInfoContainer">
                        <div>{{ data_get($agent, 'agent') }}</div>
                        <div class="mt-2">Total Sales: {{ $money(data_get($agent, 'total_sales')) }}</div>
                    </div>
                    <div class="agentSalesChartContainer" data-sales-series="{{ $chartSeries->toJson() }}">
                        <svg width="100%" height="250" viewBox="0 0 {{ $chartWidth }} 250" style="min-width: {{ $chartWidth }}px" role="img" aria-label="Monthly total sales for {{ data_get($agent, 'agent') }}">
                            <title>Monthly sales for {{ data_get($agent, 'agent') }}</title>
                            <text x="15" y="120" transform="rotate(-90 15 120)" font-size="12" text-anchor="middle" fill="#555">Total Sales</text>
                            @for($tick = 0; $tick <= 4; $tick++)
                                @php
                                    $y = 200 - $tick * 40;
                                @endphp
                                <line x1="65" y1="{{ $y }}" x2="{{ $chartWidth - 20 }}" y2="{{ $y }}" stroke="#e6e6e6"/>
                                <text x="58" y="{{ $y + 4 }}" text-anchor="end" font-size="11" fill="#666">{{ number_format($maximum * $tick / 4, 0) }}</text>
                            @endfor
                            @foreach($months as $index => $month)
                                @php
                                    $height = (float) data_get($month, 'total_sales') / $maximum * 160;
                                    $x = 65 + $index * $step + $step * 0.25;
                                    $barWidth = max(4, $step * 0.5);
                                @endphp
                                <rect x="{{ $x }}" y="{{ 200 - $height }}" width="{{ $barWidth }}" height="{{ $height }}" fill="#7cb5ec" rx="1">
                                    <title>{{ data_get($month, 'month') }}: {{ $money(data_get($month, 'total_sales')) }}</title>
                                </rect>
                                <text x="{{ $x + $barWidth / 2 }}" y="222" text-anchor="middle" font-size="11" fill="#666">{{ data_get($month, 'month') }}</text>
                            @endforeach
                        </svg>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">No Data Available.</div>
            @endforelse
        </div>
    @elseif($reportKey === 'rebookingRateReport')
        @forelse($outerRows as $agent)
            @php
                $section = $sections[1];
                $section['rows'] = collect($section['rows'])->where('agent_id', data_get($agent, 'agent_id'));
                $section['columns'] = ['customer' => 'Customer', 'reservations' => 'Total Reservations'];
            @endphp
            <div class="rebookingRateTableRecord">
                <div class="rebookingRateFirstSection">
                    <div class="agentNameAndPercentageContainer"><div class="agentNameContainer">{{ data_get($agent, 'agent') }}</div></div>
                    <div class="totalReservationsCountContainer">
                        <div>{{ number_format(data_get($agent, 'reservations', 0)) }}</div>
                        <div class="darkgreyColor">Total Reservations</div>
                    </div>
                    @if(data_get($agent, 'rebooking_rate', 0) > 0)
                        <div class="totalReservationsCountContainer">
                            <div>{{ number_format(data_get($agent, 'rebooking_rate', 0), 2) }}%</div>
                            <div class="darkgreyColor">Rebooking Percentage</div>
                        </div>
                        <div class="totalReservationsCountContainer">
                            <div>{{ data_get($agent, 'one_time_rate', 0) }}%</div>
                            <div class="darkgreyColor">One-Time Booking Percentage</div>
                        </div>
                    @endif
                </div>
                <div class="rebookingRateSecondSection">
                    @include('reports.partials.salesTable', ['section' => $section])
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500">No Data Available.</div>
        @endforelse
    @else
        @if($reportKey === 'aliasTotalGrossCommission')
            <p class="italic mt-1 mb-4">Alias Total Gross Commissions are the total agency commissions from the reservations, as it's been imputed or paid. Meaning the full commission amount not the split. (keep in mind if you haven't marked something claimed that you claimed its in here twice)</p>
        @endif
        @include('reports.partials.salesTable', ['section' => $sections[0]])
    @endif
</div>

@if($reportKey === 'salesReport')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        if (window.Highcharts) {
            document.querySelectorAll('.sales-report [data-sales-series]').forEach(container => {
                window.Highcharts.chart(container, {
                    chart: { type: 'column' },
                    title: { text: null },
                    xAxis: { type: 'category' },
                    yAxis: { title: { text: 'Total Sales' } },
                    legend: { enabled: false },
                    credits: { enabled: false },
                    series: [{
                        name: 'Sales',
                        colorByPoint: false,
                        data: JSON.parse(container.dataset.salesSeries)
                    }]
                });
            });
        }
    </script>
@endif
