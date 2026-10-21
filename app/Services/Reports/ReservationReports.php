<?php

namespace App\Services\Reports;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Reservation reports ported from altsubmit's Home_Model and Reports controller.
 * The four reports absent from that source are documented beside their queries.
 */
class ReservationReports
{
    private array $filters;

    private User $user;

    private const MONEY = [
        'reservation_cost' => 'money',
        'agency_commission' => 'money',
        'agent_commission' => 'money',
    ];

    public function run(string $report, array $filters, User $user): array
    {
        $this->filters = $filters;
        $this->user = $user;

        return match ($report) {
            'unknownReservationsReport' => $this->unknown(),
            'bookedTripsByStateReport' => $this->states(),
            'reservationsChangesReport' => $this->changes(),
            'reservationsByAgentReport' => $this->byAgent(),
            'allReservationsByDateReport' => $this->travelDates(),
            'allTripsByTravelDateReport' => $this->travelDates(true),
            'top15ExpensiveTripsReport' => $this->expensive(),
            'cruisesReport' => $this->cruises(),
            'bookedReservationsPerMonthReport' => $this->months(),
            'resleadsReport', 'reservationsLeadReport' => $this->leads(),
            'hotelOnlyReport' => $this->resorts(32),
            'classicVacationsReport' => $this->resorts(19),
            'pleasantEvokeReport' => $this->resorts(null),
            'appleVacationsReport' => $this->resorts(11),
            'travelImpressionsReport' => $this->resorts(64),
            'vacationExpressReport' => $this->resorts(71),
            'expediaReport' => $this->resorts(25),
            default => throw new InvalidArgumentException('Unknown reservation report.'),
        };
    }

    private function base(bool $excludeLegacyAccount = true, bool $ownUserOnly = false): Builder
    {
        $query = DB::table('reservations as r')->where('r.is_deleted', 0);

        if ($excludeLegacyAccount) {
            // This account is explicitly excluded in the original agency reports.
            $query->where('r.agent_id', '<>', 6680);
        }
        if ($ownUserOnly || ! $this->user->isAdmin()) {
            $query->where('r.agent_id', $this->user->id);
        } elseif (($this->filters['agentId'] ?? 0) > 0) {
            $query->where('r.agent_id', $this->filters['agentId']);
        }

        return $query;
    }

    private function details(Builder $query): Builder
    {
        return $query
            ->leftJoin('users as a', 'a.id', '=', 'r.agent_id')
            ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
            ->leftJoin('products as p', 'p.id', '=', 'r.product_id')
            ->leftJoin('destinations as d', 'd.id', '=', 'r.destination_id')
            ->leftJoin('resort_ships as s', 's.id', '=', 'r.resort_id')
            ->select([
                'r.id', 'r.agent_id', 'r.reservation_number', 'r.status',
                'r.checkin_date', 'r.checkout_date', 'r.reservation_cost',
                'r.agency_commission', 'r.agent_commission',
                'a.fname as agent_fname', 'a.lname as agent_lname',
                'c.fname as customer_fname', 'c.lname as customer_lname',
                'c.email as customer_email', 'p.product_name',
                'd.destination_name', 's.resort_ship_name',
            ]);
    }

    private function names(Collection $rows): Collection
    {
        return $rows->map(function ($row) {
            $row->agent_name = trim(($row->agent_fname ?? '').' '.($row->agent_lname ?? ''));
            $row->customer_name = trim(($row->customer_fname ?? '').' '.($row->customer_lname ?? ''));

            return $row;
        });
    }

    private function dateRange(Builder $query, string $column = 'r.checkin_date'): Builder
    {
        if ($column === 'r.checkin_date') {
            return $query->whereBetween($column, [$this->filters['beginDate'], $this->filters['endDate']]);
        }
        // The local-only audit report stores timestamps rather than travel dates.
        return $query->where($column, '>=', $this->filters['beginDate'])
            ->where($column, '<', CarbonImmutable::parse($this->filters['endDate'])->addDay()->toDateString());
    }

    private function travelRange(Builder $query): Builder
    {
        // The legacy travel reports require the entire stay to fit in the range.
        return $query->where('r.checkin_date', '>=', $this->filters['beginDate'])
            ->where('r.checkout_date', '<=', $this->filters['endDate']);
    }

    private function section(string $title, array $columns, Collection|array $rows, array $formats = [], array $totals = []): array
    {
        return compact('title', 'columns', 'rows', 'formats', 'totals');
    }

    private function moneyTotals(Collection $rows): array
    {
        return [
            'reservation_cost' => $rows->sum('reservation_cost'),
            'agency_commission' => $rows->sum('agency_commission'),
            'agent_commission' => $rows->sum('agent_commission'),
        ];
    }

    private function detailColumns(): array
    {
        return [
            'reservation_number' => 'Reservation #',
            'customer_name' => 'Customer',
            'agent_name' => 'Agent',
            'status' => 'Status',
            'checkin_date' => 'Check-in',
            'checkout_date' => 'Check-out',
            'product_name' => 'Product',
            'destination_name' => 'Destination',
            'resort_ship_name' => 'Resort / Ship',
            'reservation_cost' => 'Reservation Cost',
            'agency_commission' => 'Agency Commission',
            'agent_commission' => 'Agent Commission',
        ];
    }

    private function detailFormats(): array
    {
        return self::MONEY + ['checkin_date' => 'date', 'checkout_date' => 'date'];
    }

    private function travelDates(bool $ownPaidTrips = false): array
    {
        $query = $this->travelRange($this->details($this->base(! $ownPaidTrips, $ownPaidTrips)));
        if ($ownPaidTrips) {
            $query->where('r.status', 'Paid in Full');
        }
        $rows = $this->names($query->orderBy('r.checkin_date')->orderBy('r.id')->get());
        $columns = $ownPaidTrips
            ? ['reservation_number' => 'Reservation Number', 'customer_name' => 'Client Name', 'customer_email' => 'Client Email']
            : [
                'agent_name' => 'Agent Name', 'customer_name' => 'Customer Name',
                'reservation_number' => 'Reservation Number', 'product_name' => 'Product',
                'destination_name' => 'Destination', 'resort_ship_name' => 'Resort/Ship',
                'checkin_date' => 'Checkin Date', 'checkout_date' => 'Checkout Date',
            ];

        return [$this->section('Reservations', $columns, $rows, $this->detailFormats()) + ['sortable' => true]];
    }

    private function expensive(): array
    {
        $rows = $this->names($this->travelRange($this->details($this->base(false)))
            ->where('r.status', 'Paid in Full')->orderByDesc('r.reservation_cost')
            ->limit(15)->get());
        $columns = [
            'agent_name' => 'Agent Name', 'customer_name' => 'Customer Name',
            'reservation_number' => 'Reservation Number', 'product_name' => 'Product',
            'destination_name' => 'Destination', 'resort_ship_name' => 'Resort/Ship',
            'reservation_cost' => 'Reservation Cost ($)',
        ];

        return [$this->section('15 Most Expensive Paid-in-Full Trips', $columns, $rows, self::MONEY) + ['sortable' => true]];
    }

    private function resorts(?int $productId): array
    {
        // Home_Model::loadHotelOnlyReport and the five supplier-specific variants.
        $query = $this->dateRange($this->base())
            ->join('resort_ships as s', 's.id', '=', 'r.resort_id');
        if ($productId !== null) {
            $query->where('r.product_id', $productId);
        } else {
            // No Pleasant/Evoke query exists in the legacy source. Use its same
            // distinct-resort report with the configured supplier names.
            $query->join('products as p', 'p.id', '=', 'r.product_id')
                ->where(function (Builder $q) {
                    $q->whereRaw('LOWER(p.product_name) LIKE ?', ['%pleasant%'])
                        ->orWhereRaw('LOWER(p.product_name) LIKE ?', ['%evoke%']);
                });
        }

        $label = match ($productId) {
            32 => 'Hotel Only',
            19 => 'Classic Vacations',
            11 => 'Apple Vacations',
            64 => 'Travel Impressions',
            71 => 'Vacation Express',
            25 => 'Expedia',
            default => 'Pleasant/Evoke',
        };

        return [$this->section($label, ['resort_ship_name' => $label],
            $query->select('s.resort_ship_name')->distinct()->orderBy('s.resort_ship_name')->get())];
    }

    private function states(): array
    {
        $query = $this->dateRange($this->base())
            ->join('customers as c', 'c.id', '=', 'r.customer_id')
            ->join('states as st', 'st.name', '=', 'c.state')
            ->whereIn('r.status', ['Active', 'Paid in Full', 'Canceled - Commission Protected']);
        if (! empty($this->filters['state']) && $this->filters['state'] !== '-1') {
            $query->where('st.name', $this->filters['state']);
        }
        $summary = (clone $query)->select('st.name as state')
            ->selectRaw('SUM(r.reservation_cost) as reservation_cost, SUM(r.agency_commission) as agency_commission')
            ->groupBy('st.name')->orderBy('st.name')->get();
        $sections = [$this->section('Totals by State', [
            'state' => 'State', 'reservation_cost' => 'Total Sales', 'agency_commission' => 'Agency Commission',
        ], $summary, self::MONEY, [
            'reservation_cost' => $summary->sum('reservation_cost'),
            'agency_commission' => $summary->sum('agency_commission'),
        ])];
        // The legacy summary includes customer 645, while the detail query excludes it.
        $details = $this->names((clone $query)->where('c.id', '<>', 645)
            ->select('st.name as state', 'c.fname as customer_fname', 'c.lname as customer_lname',
                'r.reservation_number', 'r.reservation_cost', 'r.agency_commission')
            ->orderBy('c.fname')->orderBy('c.lname')->get());
        $stateDetails = $details->groupBy('state');
        foreach ($summary as $stateSummary) {
            $rows = $stateDetails->get($stateSummary->state, collect());
            $sections[] = $this->section($stateSummary->state, [
                'customer_name' => 'Customer Name',
                'reservation_cost' => 'Reservation Cost', 'agency_commission' => 'Agency Commission',
            ], $rows->values(), self::MONEY) + ['summary' => (array) $stateSummary];
        }

        return $sections;
    }

    private function unknown(): array
    {
        abort_unless($this->user->isAdmin(), 403);
        // The original unknown report is intentionally all-history, including
        // claimed reservations; only unclaimed amounts contribute to each month.
        $rows = $this->names(DB::table('reservations as r')
            ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
            ->where('r.is_deleted', 0)->where('r.agent_id', 0)
            ->select('r.id', 'r.reservation_number', 'r.unknown_reservation_date', 'r.agency_commission',
                'r.unknown_reservation_checked_flag', 'r.commission_claimed',
                'c.id as matched_customer_id', 'c.fname as customer_fname', 'c.lname as customer_lname')
            ->orderBy('r.unknown_reservation_date')->orderBy('r.reservation_number')->get());
        $summary = [];
        $sections = [];
        // Preserve Home_Model's exact-date grouping. The legacy month drilldown
        // always retrieves the first day of that month, rather than all dates.
        foreach ($rows->groupBy('unknown_reservation_date') as $unknownDate => $items) {
            $validDate = preg_match('/^\d{4}-(0[1-9]|1[0-2])-\d{2}/', (string) $unknownDate);
            $date = $validDate ? CarbonImmutable::parse($unknownDate) : null;
            $label = $date?->format('M Y') ?? 'Undated';
            $unclaimed = $items->whereNotNull('commission_claimed')->where('commission_claimed', 0)->sum('agency_commission');
            $summary[] = ['month' => $label, 'agency_commission' => $unclaimed];
            $monthDetails = $date
                ? $rows->where('unknown_reservation_date', $date->startOfMonth()->toDateString())->whereNotNull('matched_customer_id')
                : collect();
            $display = $monthDetails->map(function ($row) {
                $row->checked = $row->unknown_reservation_checked_flag ? 'Yes' : 'No';
                $row->claimed = $row->commission_claimed ? 'Yes' : 'No';

                return $row;
            })->values();
            $sections[] = $this->section($label, [
                'reservation_number' => 'Reservation #',
                'agency_commission' => 'Agency Commission', 'checked' => 'Checked', 'claimed' => 'Claimed',
            ], $display, self::MONEY, ['agency_commission' => $monthDetails->sum('agency_commission')])
                + ['summary' => ['agency_commission' => $unclaimed]];
        }
        array_unshift($sections, $this->section('Unclaimed Commission by Month', [
            'month' => 'Month', 'agency_commission' => 'Unclaimed Agency Commission',
        ], $summary, self::MONEY, ['agency_commission' => collect($summary)->sum('agency_commission')]));
        $unchecked = $rows->whereNotNull('unknown_reservation_checked_flag')->whereNotNull('commission_claimed')
            ->where('unknown_reservation_checked_flag', 0);
        $uncheckedUnclaimed = $unchecked->where('commission_claimed', 0)->sum('agency_commission');
        $uncheckedClaimed = $unchecked->where('commission_claimed', 1)->sum('agency_commission');
        $sections[] = $this->section('Unchecked Reservations', [
            'category' => 'Category', 'agency_commission' => 'Agency Commission',
        ], [
            ['category' => 'Unclaimed', 'agency_commission' => $uncheckedUnclaimed],
            ['category' => 'Claimed', 'agency_commission' => $uncheckedClaimed],
        ], self::MONEY) + ['summary' => ['unclaimed' => $uncheckedUnclaimed, 'claimed' => $uncheckedClaimed]];

        return $sections;
    }

    private function months(): array
    {
        $year = (int) ($this->filters['year'] ?? now()->year);
        $start = CarbonImmutable::create($year, 1, 1)->startOfDay();
        $today = CarbonImmutable::today();
        // Preserve the original month-number cutoff, including prior-year
        // behavior, and its date-only BETWEEN bounds for created_on timestamps.
        $rows = collect(range(1, 12))->map(function ($month) use ($start, $today) {
            $monthStart = $start->addMonths($month - 1);
            $monthEnd = $monthStart->endOfMonth()->toDateString();
            $booked = $this->base()->whereBetween('r.created_on', [$monthStart->toDateString(), $monthEnd])->count();
            $traveled = 0;
            if ($month <= $today->month) {
                $travelEnd = $month === $today->month ? $today->toDateString() : $monthEnd;
                $traveled = $this->base()->whereBetween('r.checkout_date', [$monthStart->toDateString(), $travelEnd])->count();
            }

            return ['month' => $monthStart->format('M Y'), 'booked' => $booked, 'traveled' => $traveled];
        });

        return [$this->section('Monthly Reservations', [
            'month' => 'Month', 'booked' => 'Reservations Booked', 'traveled' => 'Reservations Traveled',
        ], $rows) + ['alignments' => ['booked' => 'center', 'traveled' => 'center']]];
    }

    private function leads(): array
    {
        // A reservation flagged for multiple sources contributes to each source,
        // as in Home_Model::loadLuxuryMagazinesLeadReservations.
        $sources = [
            'is_luxury_magazine_lead' => 'Luxury Magazines',
            'is_facebook_lead' => 'Facebook',
            'is_website_lead' => 'Website',
            'is_website_lead_knot' => 'Website Knot',
            'is_virtuoso_lead' => 'Virtuoso',
            'is_instagram_lead' => 'Instagram',
            'radio_station_ads' => 'Radio',
        ];
        $base = $this->dateRange($this->base())
            ->whereIn('r.status', ['Active', 'Paid in Full', 'Canceled w/ Insurance Payout', 'Canceled - Commission Protected']);
        $summary = collect();
        $sections = [];
        foreach ($sources as $column => $label) {
            if (! empty($this->filters['source']) && ! in_array($this->filters['source'], [$column, $label], true)) {
                continue;
            }
            $query = (clone $base)->where('r.'.$column, 1);
            $total = (clone $query)->selectRaw('COUNT(*) as reservations, COALESCE(SUM(r.reservation_cost), 0) as reservation_cost,
                COALESCE(SUM(r.agency_commission), 0) as agency_commission, COALESCE(SUM(r.agent_commission), 0) as agent_commission')->first();
            $total->source = $label.' Lead';
            $summary->push($total);
            // loadWebsiteKnotLeadsReservations omits Active reservations from
            // its drilldown even though the lead summary includes them.
            if ($column === 'is_website_lead_knot') {
                $query->where('r.status', '<>', 'Active');
            }
            // The legacy detail modal excludes deleted/missing customers, even
            // though the source summary counts their reservations.
            $details = $this->names($this->details($query)->where('c.is_deleted', 0)
                ->addSelect('r.created_on', 'r.reservation_name', 'c.mname as customer_mname')
                ->orderBy('r.checkin_date')->orderBy('r.id')->get())->map(function ($row) {
                    $row->customer_name = trim(($row->customer_lname ?? '').' , '.($row->customer_fname ?? '').' '.($row->customer_mname ?? ''));

                    return $row;
                });
            $sections[] = $this->section($label.' Lead', [
                'created_on' => 'Date Created', 'reservation_number' => 'Reservation Number',
                'reservation_name' => 'Reservation Name', 'customer_name' => 'Customer',
                'agent_name' => 'Agent', 'product_name' => 'Product', 'destination_name' => 'Destination',
            ], $details, ['created_on' => 'date']) + ['summary' => (array) $total, 'sortable' => true];
        }
        array_unshift($sections, $this->section('Reservations by Lead Source', [
            'source' => 'Lead', 'reservations' => 'Count',
            'agency_commission' => 'Agency Commission', 'agent_commission' => 'Agent Commissions',
            'reservation_cost' => 'Total Reservation Cost',
        ], $summary, self::MONEY + ['reservations' => 'number'],
            $this->moneyTotals($summary) + ['reservations' => $summary->sum('reservations')]));

        return $sections;
    }

    private function changes(): array
    {
        // No legacy equivalent exists. Travelux records field changes only for
        // paid-in-full reservations in this existing audit table.
        $query = $this->base(false)
            ->join('reservation_paid_in_full_audit as h', 'h.reservation_id', '=', 'r.id')
            ->leftJoin('users as editor', 'editor.id', '=', 'h.modified_by');
        $rows = $this->dateRange($query, 'h.modified_on')
            ->select('r.reservation_number', 'h.field_name', 'h.old_value', 'h.new_value', 'h.modified_on',
                'editor.fname as editor_fname', 'editor.lname as editor_lname')
            ->orderByDesc('h.modified_on')->orderByDesc('h.id')->get()
            ->map(function ($row) {
                $row->modified_by = trim(($row->editor_fname ?? '').' '.($row->editor_lname ?? ''));

                return $row;
            });

        return [$this->section('Recorded Changes to Paid-in-Full Reservations', [
            'reservation_number' => 'Reservation #', 'field_name' => 'Field',
            'old_value' => 'Previous Value', 'new_value' => 'New Value',
            'modified_by' => 'Modified By', 'modified_on' => 'Modified On',
        ], $rows)];
    }

    private function byAgent(): array
    {
        // No legacy equivalent exists. Group the existing reservation data by
        // assigned agent, using the check-in range used in agency sales reports.
        $query = $this->dateRange($this->details($this->base()));
        if (! empty($this->filters['status'])) {
            $query->where('r.status', $this->filters['status']);
        }
        $rows = $this->names($query->orderBy('a.fname')->orderBy('a.lname')->orderBy('r.checkin_date')->get());
        $sections = [];
        foreach ($rows->groupBy('agent_id') as $items) {
            $sections[] = $this->section($items->first()->agent_name ?: 'Unassigned',
                $this->detailColumns(), $items->values(), $this->detailFormats(), $this->moneyTotals($items));
        }

        return $sections ?: [$this->section('Reservations by Agent', $this->detailColumns(), [], $this->detailFormats())];
    }

    private function cruises(): array
    {
        // No cruise-report query exists in altsubmit. Use explicit configured
        // cruise types and linked cruise itineraries to identify reservations.
        $query = $this->dateRange($this->details($this->base()))
            ->where(function (Builder $q) {
                $q->whereRaw('LOWER(p.product_type) LIKE ?', ['%cruise%'])
                    ->orWhere('r.cruise_itinerary_id', '>', 0);
            });
        $rows = $this->names($query->orderBy('r.checkin_date')->orderBy('r.id')->get());

        return [$this->section('Cruise Reservations', $this->detailColumns(), $rows,
            $this->detailFormats(), $this->moneyTotals($rows))];
    }
}
