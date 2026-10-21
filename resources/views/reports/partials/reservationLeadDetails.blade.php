<div class="overflow-x-auto">
    <table class="min-w-full text-sm" data-report-sortable data-report-sort-column="0">
        <thead class="bg-white">
            <tr>
                @foreach($section['columns'] as $key => $label)
                    <th scope="col" data-sort-type="{{ $key === 'created_on' ? 'date' : 'text' }}" class="px-4 py-3 text-left font-bold border-b-2 border-t-2 border-[#dee2e6]">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($section['rows'] as $row)
                <tr class="hover:bg-gray-50">
                    @foreach($section['columns'] as $key => $label)
                        <td data-sort-value="{{ data_get($row, $key) }}" class="text-gray-600 border-b border-[#dee2e6]">
                            <a href="{{ route('reservations.reservationDetails', data_get($row, 'id')) }}" class="block px-4 py-3 hover:text-[#B6844A]">
                                {{ \App\Services\Reports\ReportValue::format(data_get($row, $key), $section['formats'][$key] ?? null) }}
                            </a>
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ count($section['columns']) }}" class="px-4 py-8 text-center text-gray-500">No Data Available.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
