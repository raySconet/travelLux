@php
    $money = static fn ($value) => \App\Services\Reports\ReportValue::format($value ?? 0, 'money');
    $searching = trim($filters['search'] ?? '') !== '';
@endphp

@if($reportKey === 'bookedTripsByStateReport')
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex flex-wrap justify-end gap-12 mb-4">
            <div class="text-center">
                <div class="text-lg font-bold text-[#B6844A]">{{ $money($sections[0]['totals']['reservation_cost'] ?? 0) }}</div>
                <div class="text-sm text-gray-500">Total Sales</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-bold text-[#B6844A]">{{ $money($sections[0]['totals']['agency_commission'] ?? 0) }}</div>
                <div class="text-sm text-gray-500">Total Agency Commission</div>
            </div>
        </div>
        @forelse(array_slice($sections, 1) as $section)
            @continue($searching && collect($section['rows'])->isEmpty())
            <div class="flex flex-col lg:flex-row mb-8 gap-3 bg-white">
                <div class="lg:w-[350px] shrink-0">
                    <div class="border border-[#dee2e6] shadow m-3">
                        <div class="bg-[#c8e6c9] p-5 text-2xl">{{ $section['title'] }}</div>
                        <div class="border-t border-[#dee2e6] p-2 pl-5">
                            <div class="text-base">{{ $money($section['summary']['reservation_cost'] ?? 0) }}</div>
                            <div class="text-[#807e7e]">Total Sales</div>
                        </div>
                        <div class="border-t border-[#dee2e6] p-2 pl-5">
                            <div class="text-base">{{ $money($section['summary']['agency_commission'] ?? 0) }}</div>
                            <div class="text-[#807e7e]">Agency Commission</div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 min-w-0 m-3">
                    @include('reports.partials.dataTable', ['section' => $section])
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500">No Data Available.</div>
        @endforelse
        @if($searching && collect(array_slice($sections, 1))->every(fn ($section) => collect($section['rows'])->isEmpty()))
            <div class="p-6 text-center text-gray-500">No matching reservations.</div>
        @endif
    </div>
@elseif($reportKey === 'unknownReservationsReport')
    <div class="p-3 bg-white shadow sm:rounded-lg">
        <div class="grid grid-cols-2 border-y-2 border-[#dee2e6] text-sm font-bold">
            <div class="px-4 py-3">Month</div>
            <div class="px-4 py-3">Total Agency Commission</div>
        </div>
        @php($months = array_slice($sections, 1, max(count($sections) - 2, 0)))
        @forelse($months as $section)
            <details data-report-unknown-month class="border-b border-[#dee2e6]" @if($searching && collect($section['rows'])->isNotEmpty()) open @endif>
                <summary class="cursor-pointer list-none grid grid-cols-2 hover:bg-gray-50 text-sm text-gray-600">
                    <span class="px-4 py-3">{{ $section['title'] }}</span>
                    <span class="px-4 py-3 flex justify-between items-center">
                        {{ $money($section['summary']['agency_commission'] ?? 0) }}
                        <i class="fas fa-chevron-down text-[#B6844A] unknown-month-chevron" aria-hidden="true"></i>
                    </span>
                </summary>
                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-y border-[#dee2e6]">
                                <th scope="col" class="px-4 py-3 text-left">Reservation #</th>
                                <th scope="col" class="px-4 py-3 text-left">Agency Commission</th>
                                <th scope="col" class="px-4 py-3 text-left"><span class="sr-only">Checked</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($section['rows'] as $row)
                                <tr data-unknown-reservation-number="{{ data_get($row, 'reservation_number') }}" class="border-b border-[#dee2e6]" @if(data_get($row, 'commission_claimed')) style="background-color: #fff59d" @endif>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('reports.unknownReservationsReport.edit', data_get($row, 'id')) }}" data-report-unknown-edit class="block text-gray-600 hover:text-[#B6844A]">
                                            {{ data_get($row, 'reservation_number') }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('reports.unknownReservationsReport.edit', data_get($row, 'id')) }}" data-report-unknown-edit class="block text-gray-600 hover:text-[#B6844A]">
                                            {{ $money(data_get($row, 'agency_commission')) }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="{{ route('reports.unknownReservationsReport.checked') }}">
                                            @csrf
                                            <input type="hidden" name="reservation_id" value="{{ data_get($row, 'id') }}">
                                            <input type="hidden" name="checked" value="0">
                                            <input type="hidden" name="search" value="{{ $filters['search'] ?? '' }}">
                                            <input type="checkbox" name="checked" value="1" data-report-submit-form data-report-unknown-checked
                                                   aria-label="Checked reservation {{ data_get($row, 'reservation_number') }}"
                                                   class="rounded border-gray-400 text-[#B6844A]"
                                                   @checked(data_get($row, 'unknown_reservation_checked_flag'))>
                                            <noscript><button type="submit" class="ml-2 text-[#B6844A] underline">Save</button></noscript>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot data-report-unknown-month-total @if($searching) hidden @endif>
                            <tr class="font-bold">
                                <td class="px-4 py-3">Total:</td>
                                <td class="px-4 py-3">{{ $money($section['totals']['agency_commission'] ?? 0) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </details>
        @empty
            <div class="p-6 text-center text-gray-500">No Data Available.</div>
        @endforelse
        @if($searching && collect($months)->every(fn ($section) => collect($section['rows'])->isEmpty()))
            <div class="p-6 text-center text-gray-500">No matching reservations.</div>
        @endif
        @php($unchecked = $sections[count($sections) - 1]['summary'] ?? [])
        <div class="flex flex-wrap justify-end gap-6 border-t border-[#dee2e6] px-4 py-4 text-sm">
            <div>Total: <strong data-unknown-unchecked-total>{{ $money($unchecked['unclaimed'] ?? 0) }}</strong></div>
            <div class="px-2" style="background-color: #fff59d">Claimed: <strong data-unknown-claimed-total>{{ $money($unchecked['claimed'] ?? 0) }}</strong></div>
        </div>
    </div>
    @include('reports.partials.unknownReservationEditor')
    <style>
        details[open] > summary .unknown-month-chevron { transform: rotate(180deg); }
    </style>
@elseif(in_array($reportKey, ['resleadsReport', 'reservationsLeadReport'], true))
    <div class="p-3 bg-white shadow sm:rounded-lg overflow-x-auto">
        <div class="min-w-[760px]">
            <table class="min-w-full text-sm" data-report-sortable data-report-sort-column="0">
                <thead>
                    <tr class="border-y-2 border-[#dee2e6]">
                        @foreach($sections[0]['columns'] as $key => $label)
                            <th scope="col" data-sort-type="{{ $key === 'source' ? 'text' : 'number' }}" class="px-4 py-3 text-left">{{ $label }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach(array_slice($sections, 1) as $section)
                        @continue($searching && collect($section['rows'])->isEmpty())
                        @php($dialogId = 'reservation-leads-'.$loop->index)
                        <tr class="border-b border-[#dee2e6] hover:bg-gray-50 text-gray-600">
                            @foreach(['source', 'reservations', 'agency_commission', 'agent_commission', 'reservation_cost'] as $key)
                                <td data-sort-value="{{ $section['summary'][$key] ?? '' }}">
                                    <button type="button" data-report-open-dialog="{{ $dialogId }}" aria-haspopup="dialog"
                                            class="block w-full text-left px-4 py-3 cursor-pointer">
                                        {{ in_array($key, ['agency_commission', 'agent_commission', 'reservation_cost'], true) ? $money($section['summary'][$key] ?? 0) : ($section['summary'][$key] ?? '') }}
                                    </button>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-[#dee2e6] font-bold">
                        <td class="px-4 py-3">Total Revenue</td>
                        <td class="px-4 py-3">{{ $sections[0]['totals']['reservations'] ?? 0 }}</td>
                        <td class="px-4 py-3">{{ $money($sections[0]['totals']['agency_commission'] ?? 0) }}</td>
                        <td class="px-4 py-3">{{ $money($sections[0]['totals']['agent_commission'] ?? 0) }}</td>
                        <td class="px-4 py-3">{{ $money($sections[0]['totals']['reservation_cost'] ?? 0) }}</td>
                    </tr>
                </tfoot>
            </table>
            @foreach(array_slice($sections, 1) as $section)
                @continue($searching && collect($section['rows'])->isEmpty())
                @php($dialogId = 'reservation-leads-'.$loop->index)
                <dialog id="{{ $dialogId }}" class="travelux-report-dialog bg-white rounded-lg shadow-lg"
                        style="width: min(95vw, 1200px)" aria-labelledby="{{ $dialogId }}-title">
                    <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#dee2e6]">
                        <h3 id="{{ $dialogId }}-title" class="text-xl font-semibold text-gray-700">Reservations</h3>
                        <button type="button" data-report-close-dialog class="text-gray-400 hover:text-gray-600 cursor-pointer text-xl" aria-label="Close">&times;</button>
                    </div>
                    <div class="p-5">
                        @include('reports.partials.reservationLeadDetails', ['section' => $section])
                    </div>
                    <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6]">
                        <button type="button" data-report-close-dialog class="bg-[#B6844A] text-white rounded px-4 py-2 cursor-pointer">Close</button>
                    </div>
                </dialog>
            @endforeach
            @if($searching && collect(array_slice($sections, 1))->every(fn ($section) => collect($section['rows'])->isEmpty()))
                <div class="p-6 text-center text-gray-500">No matching reservations.</div>
            @endif
        </div>
    </div>
@else
    @forelse($sections as $section)
        <div class="p-3 bg-white shadow sm:rounded-lg">
            @if(count($sections) > 1 || $reportKey === 'reservationsChangesReport')
                <h3 class="font-bold text-gray-700 mb-3">{{ $section['title'] }}</h3>
            @endif
            @if(!empty($section['description']))
                <p class="text-sm text-gray-500 mb-3">{{ $section['description'] }}</p>
            @endif
            @include('reports.partials.dataTable', ['section' => $section])
        </div>
    @empty
        <div class="bg-white shadow sm:rounded-lg p-6 text-center text-gray-500">No Data Available.</div>
    @endforelse
@endif
