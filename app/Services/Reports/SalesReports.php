<?php

namespace App\Services\Reports;

use App\Models\User;
use DateTimeImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SalesReports
{
    private const SALES_STATUSES = ['Active', 'Paid in Full', 'Canceled - Commission Protected'];

    private const PAID_STATUSES = ['Paid in Full', 'Canceled w/ Insurance Payout', 'Canceled - Commission Protected'];

    private const CANCELED_STATUSES = ['Canceled', 'Canceled - Commission Protected', 'Canceled w/ Insurance Payout'];

    private const MONEY_COLUMNS = [
        'total_sales' => 'Total Sales',
        'gross_commission' => 'Gross Commission',
        'agent_commission' => 'Agent Commission',
    ];

    public function run(string $report, array $filters, User $user): array
    {
        $sections = match ($report) {
            'productSalesByAgentReport' => $this->productSales($filters, $user, true),
            'agentSalesByProductReport' => $this->productSales($filters, $user, false),
            'totalSalesReport' => $this->totalSales($filters, $user),
            'aliasTotalSalesReport' => $this->aliasTotals($filters, false),
            'aliasTotalGrossCommission' => $this->aliasTotals($filters, true),
            'productSalesWithDepositByAgentReport' => $this->depositSales($filters, $user),
            'salesReport' => $this->monthlySales($filters, $user),
            'rebookingRateReport' => $this->rebooking($filters, $user),
            default => throw new InvalidArgumentException("Unknown sales report: {$report}"),
        };

        $search = trim($filters['search'] ?? '');

        return $search === '' ? $sections : $this->search($sections, $report, $search);
    }

    private function reservations(array $filters, User $user, bool $excludeLegacyAgent = true, bool $excludeDeleted = true): Builder
    {
        $query = DB::table('reservations as r');
        if ($excludeDeleted) {
            $query->where('r.is_deleted', 0);
        }
        if ($excludeLegacyAgent) {
            $query->where('r.agent_id', '!=', 6680);
        }

        $this->scopeAgent($query, $filters, $user, 'r.agent_id');

        if (! empty($filters['status']) && ! in_array($filters['status'], ['all', '-1'], true)) {
            $query->where('r.status', $filters['status']);
        }

        return $query;
    }

    private function scopeAgent(Builder $query, array $filters, User $user, string $column): void
    {
        if (! $user->isAdmin()) {
            $query->where($column, $user->id);
        } elseif ((int) ($filters['agentId'] ?? 0) > 0) {
            $query->where($column, (int) $filters['agentId']);
        }
    }

    private function dates(Builder $query, array $filters, bool $containedTrip = false): void
    {
        $basis = $filters['dateBasis'] ?? 'checkin';

        if ($basis !== 'book') {
            if ($containedTrip) {
                $query->whereDate('r.checkin_date', '>=', $filters['beginDate'])
                    ->whereDate('r.checkout_date', '<=', $filters['endDate']);
            } else {
                $query->whereDate('r.checkin_date', '>=', $filters['beginDate'])
                    ->whereDate('r.checkin_date', '<=', $filters['endDate']);
            }
        }

        if (in_array($basis, ['book', 'both'], true)) {
            // Date comparisons include every booking on the last day, including timestamps.
            $query->whereDate('r.created_on', '>=', $filters['beginDate'])
                ->whereDate('r.created_on', '<=', $filters['endDate']);
        }
    }

    private function productSales(array $filters, User $user, bool $byAgent): array
    {
        $query = $this->reservations($filters, $user, false)
            ->leftJoin('users as u', 'u.id', '=', 'r.agent_id')
            ->leftJoin('products as p', 'p.id', '=', 'r.product_id')
            ->leftJoin('destinations as d', 'd.id', '=', 'r.destination_id')
            ->whereIn('r.status', $byAgent ? [...self::SALES_STATUSES, 'Claimed'] : self::SALES_STATUSES)
            ->whereBetween('r.checkin_date', [$filters['beginDate'], $filters['endDate']]);

        // Fetch once for the nested legacy report. Its three levels intentionally have
        // different status and join rules; keep those rules without per-agent queries.
        $rows = $query->select([
            'r.id', 'r.status', 'r.agent_id', 'u.fname', 'u.lname', 'r.product_id', 'r.destination_id',
            'u.id as known_agent', 'p.id as known_product', 'd.id as known_destination',
            'p.product_name as product', 'd.destination_name as destination',
            'r.reservation_cost as total_sales', 'r.agency_commission as gross_commission',
            'r.agent_commission',
        ])
            ->orderBy($byAgent ? 'u.fname' : 'p.product_name')
            ->orderBy($byAgent ? 'u.lname' : 'd.destination_name')
            ->orderBy($byAgent ? 'p.product_name' : 'u.fname')
            ->orderBy($byAgent ? 'd.destination_name' : 'u.lname')
            ->get()
            ->map(function ($row) {
                $row->agent = trim($row->fname.' '.$row->lname);

                return $row;
            });

        $columns = ['total_sales' => 'Total Reservation Cost', 'agent_commission' => 'Agent Commission'];
        if ($user->isAdmin()) {
            $columns['gross_commission'] = 'Total Commission';
        }
        $moneyFormats = array_fill_keys(array_keys($columns), 'money');
        if ($byAgent) {
            $agentRows = $rows->whereNotNull('known_agent')->where('agent_id', '!=', 6680)
                ->whereIn('status', self::SALES_STATUSES);
            $eligibleAgents = $agentRows->pluck('agent_id')->unique()->all();
            $productRows = $rows->whereIn('agent_id', $eligibleAgents)->whereNotNull('known_product');
            $details = $productRows->whereIn('status', self::SALES_STATUSES)->whereNotNull('known_destination');

            return [
                $this->section('Sales by Agent', ['agent' => 'Agent'] + $columns,
                    $this->rollup($agentRows, ['agent_id'], ['agent']), $moneyFormats),
                // Legacy product headers include Claimed; agent and destination totals do not.
                $this->section('Products by Agent', ['agent' => 'Agent', 'product' => 'Product'] + $columns,
                    $this->rollup($productRows, ['agent_id', 'product_id'], ['agent', 'product']), $moneyFormats),
                $this->section('Destination Details', ['agent' => 'Agent', 'product' => 'Product', 'destination' => 'Destination'] + $columns,
                    $this->rollup($details, ['agent_id', 'product_id', 'destination'], ['agent', 'product', 'destination']), $moneyFormats),
            ];
        }

        $productRows = $rows->whereNotNull('known_product')->where('agent_id', '!=', 6680);
        $eligibleProducts = $productRows->pluck('product_id')->unique()->all();
        // Legacy excludes agent 6680 only from the outer product totals.
        $destinationRows = $rows->whereIn('product_id', $eligibleProducts)->whereNotNull('known_destination');

        return [
            $this->section('Sales by Product', ['product' => 'Product'] + $columns,
                $this->rollup($productRows, ['product_id'], ['product']), $moneyFormats),
            $this->section('Destinations by Product', ['product' => 'Product', 'destination' => 'Destination'] + $columns,
                $this->rollup($destinationRows, ['product_id', 'destination_id'], ['product', 'destination']), $moneyFormats),
            $this->section('Agent Details', ['product' => 'Product', 'destination' => 'Destination', 'agent' => 'Agent'] + $columns,
                $destinationRows->whereNotNull('known_agent')->values(), $moneyFormats),
        ];
    }

    private function totalSales(array $filters, User $user): array
    {
        $query = $this->reservations($filters, $user)
            ->join('users as u', 'u.id', '=', 'r.agent_id')
            ->where('u.isDeleted', 0)
            ->where('u.is_disabled', '!=', 1)
            ->whereNotIn('r.status', array_merge(self::CANCELED_STATUSES, ['Duplicate', 'On Hold', 'Prospect']));
        $eligibility = clone $query;
        $this->dates($eligibility, $filters, true);
        // Preserve the source's active SUM query: date basis filters eligible agents,
        // but their amounts always use the travel interval, even on the Book tab.
        $query->whereIn('r.agent_id', $eligibility->select('r.agent_id'))
            ->where('r.checkin_date', '>=', $filters['beginDate'])
            ->where('r.checkout_date', '<=', $filters['endDate']);

        $rows = $query->select('r.agent_id', 'u.fname', 'u.lname')
            ->selectRaw('SUM(COALESCE(r.reservation_cost, 0)) as total_sales')
            ->selectRaw('SUM(COALESCE(r.agency_commission, 0)) as gross_commission')
            ->selectRaw('SUM(COALESCE(r.agent_commission, 0)) as agent_commission')
            ->groupBy('r.agent_id', 'u.fname', 'u.lname')
            ->orderBy('u.fname')->orderBy('u.lname')
            ->get()->map(function ($row) {
                $row->agent = trim($row->fname.' '.$row->lname);
                $row->gross_commission_placeholder = 0;

                return $row;
            });

        return [$this->section('Total Sales by Agent', [
            'agent' => 'Agent', 'total_sales' => 'Total Sales', 'gross_commission_placeholder' => 'Gross Commission',
            'gross_commission' => 'Agency Commissions', 'agent_commission' => 'Agent Commissions',
        ], $rows, array_fill_keys(array_keys(self::MONEY_COLUMNS), 'money') + ['gross_commission_placeholder' => 'number'])];
    }

    private function aliasTotals(array $filters, bool $commission): array
    {
        // These source reports are anonymized leaderboards shared with every signed-in
        // agent. Do not apply agentId or user scoping, or expose names/identifiers.
        // Deleted reservations remain included, matching the source alias queries.
        $query = DB::table('reservations as r')->whereNotIn('r.status', self::CANCELED_STATUSES)
            ->where('r.checkin_date', '>=', $filters['beginDate'])
            ->where('r.checkout_date', '<=', $filters['endDate']);
        $amount = $commission ? 'agency_commission' : 'reservation_cost';
        $field = $commission ? 'gross_commission' : 'total_sales';
        $sums = $query->select('r.agent_id')->selectRaw("SUM(COALESCE(r.{$amount}, 0)) as amount")->groupBy('r.agent_id');

        $agents = DB::table('users as u')
            ->leftJoinSub($sums, 'sales', 'sales.agent_id', '=', 'u.id')
            ->where('u.isDeleted', 0)
            ->where('u.id', '!=', 6680)
            // The account checkbox is labelled "Do Not Include on Alias Reports".
            ->where('u.include_in_alias_reports', '!=', 1);
        $rows = $agents->select('u.alias')->selectRaw("COALESCE(sales.amount, 0) as {$field}")
            // Legacy DataTables applies ascending amount order after loading aliases.
            ->orderBy($field)->orderBy('u.id')->get();
        $title = $commission ? 'Alias Total Gross Commission' : 'Alias Total Sales';

        return [$this->section($title, ['alias' => 'Alias', $field => $commission ? 'Total Gross Commission' : 'Total Sales'], $rows, [$field => 'money'])];
    }

    private function depositSales(array $filters, User $user): array
    {
        abort_unless($user->isAdmin(), 403);

        // Legacy reports contain one row per matching deposit, including repeat
        // reservation costs when a reservation has several deposit payments.
        $deposits = DB::table('reservation_payments')
            ->where('is_deleted', 0)->where('payment_type', 'Deposit')
            ->whereBetween('payment_date', [$filters['beginDate'], $filters['endDate']])
            ->select('reservation_id', 'payment_date');
        $rows = $this->reservations($filters, $user)
            ->joinSub($deposits, 'deposits', 'deposits.reservation_id', '=', 'r.id')
            ->join('users as u', 'u.id', '=', 'r.agent_id')
            ->leftJoin('products as p', 'p.id', '=', 'r.product_id')
            ->whereIn('r.status', self::SALES_STATUSES)
            ->select([
                'r.id', 'r.agent_id', 'u.fname', 'u.lname', 'r.product_id', 'p.product_name as product', 'p.id as known_product',
                'r.reservation_number', 'r.reservation_cost as total_sales', 'r.checkin_date',
                'deposits.payment_date',
            ])
            ->orderBy('u.fname')->orderBy('u.lname')->orderBy('p.product_name')->orderBy('r.reservation_number')
            ->get()->map(function ($row) {
                $row->agent = trim($row->fname.' '.$row->lname);

                return $row;
            });

        return [
            $this->section('Deposit Sales by Agent', ['agent' => 'Agent', 'total_sales' => 'Total Sales'],
                $this->rollup($rows, ['agent_id'], ['agent'], ['total_sales']), ['total_sales' => 'money']),
            $this->section('Deposit Sales by Product', ['agent' => 'Agent', 'product' => 'Product', 'total_sales' => 'Total Sales'],
                $this->rollup($rows->whereNotNull('known_product'), ['agent_id', 'product_id'], ['agent', 'product'], ['total_sales']), ['total_sales' => 'money']),
            $this->section('Reservations with Deposits', [
                'agent' => 'Agent', 'product' => 'Product', 'reservation_number' => 'Reservation #',
                'total_sales' => 'Reservation Cost', 'payment_date' => 'Deposit Date', 'checkin_date' => 'Checkin Date',
            ], $rows->whereNotNull('known_product')->values(), [
                'total_sales' => 'money', 'checkin_date' => 'date', 'payment_date' => 'date',
            ]),
        ];
    }

    private function monthlySales(array $filters, User $user): array
    {
        $dateColumn = ($filters['dateBasis'] ?? 'checkin') === 'book' ? 'r.created_on' : 'r.checkin_date';
        // Source headers include Canceled and use the exact requested interval.
        $totals = $this->reservations($filters, $user)
            ->whereBetween($dateColumn, [$filters['beginDate'], $filters['endDate']])
            ->select('r.agent_id')
            ->selectRaw('SUM(COALESCE(r.reservation_cost, 0)) as total_sales')
            ->groupBy('r.agent_id')->get()->keyBy('agent_id');

        $agents = DB::table('users as u')->where('u.id', '!=', 6680);
        $this->scopeAgent($agents, $filters, $user, 'u.id');
        $agents = $agents->select('u.id', 'u.fname', 'u.lname')
            ->orderByRaw('u.id = ? DESC', [$user->id])->orderBy('u.fname')->orderBy('u.lname')->get();

        $months = [];
        $month = (new DateTimeImmutable($filters['beginDate']))->modify('first day of this month');
        $end = new DateTimeImmutable($filters['endDate']);
        // Preserve the source's special handling of an interval ending on day 1.
        $lastMonth = $end->format('d') === '01' && (int) $end->format('m') !== (int) now()->format('m') + 2
            ? $end->modify('last day of last month')
            : $end->modify('last day of this month');
        while ($month <= $lastMonth) {
            $months[$month->format('Y-m')] = $month->format('M, Y');
            $month = $month->modify('+1 month');
        }

        // Source chart values use full calendar months and exclude Canceled.
        $monthly = collect();
        if ($months !== []) {
            $first = array_key_first($months).'-01';
            $last = (new DateTimeImmutable(array_key_last($months).'-01'))->format('Y-m-t');
            $monthly = $this->reservations($filters, $user)->where('r.status', '!=', 'Canceled')
                ->whereBetween($dateColumn, [$first, $last])
                ->select('r.agent_id', 'r.reservation_cost', $dateColumn.' as sale_date')->get()
                ->filter(function ($row) use ($dateColumn) {
                    if ($dateColumn === 'r.checkin_date') {
                        return true;
                    }
                    // A legacy BETWEEN ending on YYYY-MM-DD stops at midnight.
                    $date = new DateTimeImmutable($row->sale_date);

                    return $date <= $date->modify('last day of this month')->setTime(0, 0);
                })
                ->groupBy(fn ($row) => $row->agent_id.'|'.substr($row->sale_date, 0, 7))
                ->map(fn (Collection $rows) => $rows->sum('reservation_cost'));
        }
        $details = collect();
        $summaries = collect();
        foreach ($agents as $agent) {
            $name = trim($agent->fname.' '.$agent->lname);
            foreach ($months as $key => $label) {
                $amount = max(0, (int) $monthly->get($agent->id.'|'.$key, 0));
                $details->push(['agent_id' => $agent->id, 'agent' => $name, 'month' => $label, 'total_sales' => $amount]);
            }
            $summaries->push(['agent_id' => $agent->id, 'agent' => $name, 'total_sales' => (float) ($totals->get($agent->id)->total_sales ?? 0)]);
        }

        return [
            $this->section('Sales by Agent', ['agent' => 'Agent', 'total_sales' => 'Total Sales'], $summaries, ['total_sales' => 'money']),
            $this->section('Monthly Sales', ['agent' => 'Agent', 'month' => 'Month', 'total_sales' => 'Total Sales'], $details, ['total_sales' => 'money']),
        ];
    }

    private function rebooking(array $filters, User $user): array
    {
        $beginDate = isset($filters['beginYear']) ? $filters['beginYear'].'-01-01' : $filters['beginDate'];
        $endDate = isset($filters['endYear']) ? $filters['endYear'].'-12-31' : $filters['endDate'];
        $eligibleAgents = $this->reservations($filters, $user)
            ->whereIn('r.status', self::PAID_STATUSES)
            ->where('r.checkin_date', '>=', $beginDate)->where('r.checkout_date', '<=', $endDate)
            ->select('r.agent_id');
        $base = $this->reservations($filters, $user)
            ->join('users as u', 'u.id', '=', 'r.agent_id')
            ->where('u.isDeleted', 0)->where('u.is_disabled', '!=', 1)
            ->whereIn('r.agent_id', $eligibleAgents)
            ->whereIn('r.status', self::PAID_STATUSES)
            ->whereNotIn('r.customer_id', [648, 3219, 3615])
            ->whereDate('r.checkin_date', '>=', $beginDate)
            ->whereDate('r.checkin_date', '<=', $endDate);
        // Source rates count reservation.customer_id, whereas its displayed customer
        // table groups customers.id. Imported orphan references can make these differ.
        $customerCounts = (clone $base)->select('r.agent_id', 'r.customer_id')
            ->selectRaw('COUNT(*) as reservations')->groupBy('r.agent_id', 'r.customer_id')
            ->get()->groupBy('agent_id');
        $rows = (clone $base)->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
            ->select('r.agent_id', 'u.fname', 'u.lname', 'c.id as customer_id', 'c.fname as customer_fname', 'c.lname as customer_lname')
            ->selectRaw('COUNT(*) as reservations')
            ->groupBy('r.agent_id', 'u.fname', 'u.lname', 'c.id', 'c.fname', 'c.lname')
            ->orderByRaw('u.id = 8 DESC')->orderBy('u.fname')->orderBy('u.lname')->orderBy('c.fname')->orderBy('c.lname')
            ->get()->map(function ($row) {
                $row->agent = trim($row->fname.' '.$row->lname);
                $row->customer = trim($row->customer_fname.' '.$row->customer_lname);

                return $row;
            });

        $summaries = $rows->groupBy('agent_id')->filter(fn (Collection $customers) => $customers->where('reservations', '>', 1)->isNotEmpty())->map(function (Collection $customers) use ($customerCounts) {
            $repeatCustomers = $customers->where('reservations', '>', 1);
            $metrics = $customerCounts->get($customers->first()->agent_id, collect());
            $repeatCount = $metrics->where('reservations', '>', 1)->count();
            $rate = $metrics->isEmpty() ? 0 : $repeatCount * 100 / $metrics->count();

            return [
                'agent_id' => $customers->first()->agent_id,
                'agent' => $customers->first()->agent,
                'customers' => $metrics->count(),
                'repeat_customers' => $repeatCount,
                'reservations' => $repeatCustomers->sum('reservations'),
                'repeat_reservations' => $repeatCustomers->sum('reservations'),
                'rebooking_rate' => round($rate, 2),
                'one_time_rate' => 100 - round($rate, 2),
            ];
        })->values();

        return [
            $this->section('Rebooking Rate by Agent', [
                'agent' => 'Agent', 'reservations' => 'Total Reservations',
                'rebooking_rate' => 'Rebooking Percentage', 'one_time_rate' => 'One-Time Booking Percentage',
            ], $summaries, [
                'reservations' => 'number', 'rebooking_rate' => 'percent', 'one_time_rate' => 'percent',
            ]),
            $this->section('Customers with Multiple Reservations', [
                'agent' => 'Agent', 'customer' => 'Customer', 'reservations' => 'Reservations',
            ], $rows->where('reservations', '>', 1)->values(), ['reservations' => 'number']),
        ];
    }

    private function rollup(Collection $rows, array $keys, array $labels, ?array $amounts = null): Collection
    {
        $amounts ??= array_keys(self::MONEY_COLUMNS);

        return $rows->groupBy(fn ($row) => json_encode(array_map(fn ($key) => data_get($row, $key), $keys)))
            ->map(function (Collection $group) use ($keys, $labels, $amounts) {
                $row = [];
                foreach (array_unique([...$keys, ...$labels]) as $label) {
                    $row[$label] = data_get($group->first(), $label);
                }
                foreach ($amounts as $amount) {
                    $row[$amount] = $group->sum($amount);
                }

                return $row;
            })->values();
    }

    private function search(array $sections, string $report, string $search): array
    {
        $search = mb_strtolower($search);
        $matches = static function ($row, array $section) use ($search): bool {
            foreach ($section['columns'] as $field => $label) {
                $value = data_get($row, $field);
                $text = (string) $value.' '.ReportValue::format($value, $section['formats'][$field] ?? null);
                if (str_contains(mb_strtolower($text), $search)) {
                    return true;
                }
            }

            return false;
        };
        $parents = collect($sections[0]['rows']);
        if (count($sections) === 1) {
            $sections[0]['rows'] = $parents->filter(fn ($row) => $matches($row, $sections[0]))->values();

            return $sections;
        }

        if ($report === 'salesReport') {
            // The original live Quick Search checks the blue agent summary card,
            // then shows/hides the entire agent chart without filtering its months.
            $parents = $parents->filter(fn ($row) => $matches($row, $sections[0]) || str_contains('total sales:', $search));
            $agentIds = $parents->pluck('agent_id')->all();
            $sections[0]['rows'] = $parents->values();
            $sections[1]['rows'] = collect($sections[1]['rows'])->whereIn('agent_id', $agentIds)->values();

            return $sections;
        }

        $byProduct = $report === 'agentSalesByProductReport';
        $parentKey = $byProduct ? 'product_id' : 'agent_id';
        $parentId = static fn ($row) => (string) data_get($row, $parentKey);
        $matchedParents = $parents->filter(fn ($row) => $matches($row, $sections[0]))->map($parentId)->flip();
        $children = collect($sections[1]['rows']);

        if (count($sections) === 2) {
            $children = $children->filter(fn ($row) => $matchedParents->has($parentId($row)) || $matches($row, $sections[1]));
            $visibleParents = $children->map($parentId)->flip();
            $sections[0]['rows'] = $parents->filter(fn ($row) => $matchedParents->has($parentId($row)) || $visibleParents->has($parentId($row)))->values();
            $sections[1]['rows'] = $children->values();

            return $sections;
        }

        $childKey = $byProduct ? 'destination_id' : 'product_id';
        $childId = static fn ($row) => $parentId($row).'|'.data_get($row, $childKey);
        $matchedChildren = $children
            ->filter(fn ($row) => $matchedParents->has($parentId($row)) || $matches($row, $sections[1]))
            ->map($childId)->flip();
        $details = collect($sections[2]['rows'])->filter(fn ($row) => $matchedParents->has($parentId($row))
            || $matchedChildren->has($childId($row)) || $matches($row, $sections[2]));
        $visibleChildren = $details->map($childId)->flip();
        $children = $children->filter(fn ($row) => $matchedChildren->has($childId($row)) || $visibleChildren->has($childId($row)));
        $visibleParents = $children->map($parentId)->merge($details->map($parentId))->flip();
        $sections[0]['rows'] = $parents->filter(fn ($row) => $matchedParents->has($parentId($row)) || $visibleParents->has($parentId($row)))->values();
        $sections[1]['rows'] = $children->values();
        $sections[2]['rows'] = $details->values();

        // Search hides other branches while retaining the original report totals,
        // just as the source's client-side quick search does.
        return $sections;
    }

    private function section(string $title, array $columns, Collection $rows, array $formats): array
    {
        $totals = [];
        foreach ($formats as $field => $format) {
            if (in_array($format, ['money', 'number'], true)) {
                $totals[$field] = $rows->sum($field);
            }
        }

        return compact('title', 'columns', 'rows', 'formats', 'totals');
    }
}
