<div class="overflow-x-auto">
    <table class="sales-report-table table archerTable min-w-full"
           @if(($reportKey ?? '') !== 'rebookingRateReport')
               data-report-sortable data-report-sort-column="{{ str_starts_with($reportKey ?? '', 'alias') ? 1 : 0 }}" data-report-sort-direction="asc"
           @endif>
        <thead>
            <tr>
                @foreach($section['columns'] as $field => $label)
                    <th data-sort-type="{{ in_array($section['formats'][$field] ?? '', ['money', 'number', 'percent'], true) ? 'number' : (($section['formats'][$field] ?? '') === 'date' ? 'date' : 'text') }}">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($section['rows'] as $row)
                <tr>
                    @foreach($section['columns'] as $field => $label)
                        <td data-sort-value="{{ data_get($row, $field) }}">{{ \App\Services\Reports\ReportValue::format(data_get($row, $field), $section['formats'][$field] ?? null) }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($section['columns']) }}" class="text-center text-gray-500">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
        @if(($reportKey ?? '') === 'totalSalesReport' && collect($section['rows'])->isNotEmpty())
            <tfoot>
                <tr class="font-bold makeFontBold">
                    @foreach($section['columns'] as $field => $label)
                        <td>{{ $loop->first ? 'Total:' : \App\Services\Reports\ReportValue::format($section['totals'][$field] ?? '', $section['formats'][$field] ?? null) }}</td>
                    @endforeach
                </tr>
            </tfoot>
        @endif
    </table>
</div>
