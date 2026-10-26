@if(!empty($section['description']))
    <p class="text-sm text-gray-500 mb-3">{{ $section['description'] }}</p>
@endif
<div class="overflow-x-auto">
    <table class="min-w-full text-sm" @if($section['sortable'] ?? false) data-report-sortable data-report-sort-column="0" @endif>
        <thead class="bg-white">
            <tr>
                @foreach($section['columns'] as $key => $label)
                    <th scope="col" data-sort-type="{{ in_array($section['formats'][$key] ?? '', ['money', 'integer', 'percent']) ? 'number' : (($section['formats'][$key] ?? '') === 'date' ? 'date' : 'text') }}" class="px-4 py-3 text-left font-bold border-b-2 border-t-2 border-[#dee2e6]">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($section['rows'] as $row)
                <tr class="hover:bg-gray-50">
                    @foreach($section['columns'] as $key => $label)
                        <td data-sort-value="{{ data_get($row, $key) }}" @if(($section['alignments'][$key] ?? '') === 'center') style="text-align: center" @endif class="px-4 py-3 text-gray-600 border-b border-[#dee2e6]">{{ \App\Services\Reports\ReportValue::format(data_get($row, $key), $section['formats'][$key] ?? null) }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ count($section['columns']) }}" class="px-4 py-8 text-center text-gray-500">No Data Available.</td></tr>
            @endforelse
        </tbody>
        @if(!empty($section['totals']))
            <tfoot>
                <tr class="font-bold bg-gray-50">
                    @foreach($section['columns'] as $key => $label)
                        <td class="px-4 py-3">{{ \App\Services\Reports\ReportValue::format($section['totals'][$key] ?? ($loop->first ? 'Total' : ''), $section['formats'][$key] ?? null) }}</td>
                    @endforeach
                </tr>
            </tfoot>
        @endif
    </table>
</div>
