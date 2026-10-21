<?php

namespace App\Services\Reports;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FinancialReports
{
    private const COMPLETED_STATUSES = [
        'Paid in Full',
        'Canceled w/ Insurance Payout',
        'Canceled - Commission Protected',
    ];

    public function run(string $report, array $filters, User $user): array
    {
        return match ($report) {
            'reservationsNotPaidByTraveluxReport' => $this->reservationPayments($filters, $user, false),
            'reservationsPaidByTraveluxReport' => $this->reservationPayments($filters, $user, true),
            'agentExpensesReport' => $this->expenses($filters, $user),
            'finalAgencyCommissionReport' => $this->finalAgencyCommission($filters, $user),
            'totalCommissionReport' => $this->totalCommission($filters, $user),
            default => throw new InvalidArgumentException('Unknown financial report.'),
        };
    }

    private function reservations(array $filters, User $user, bool $excludeDeleted = true, bool $excludeLegacyAccount = true): Builder
    {
        $query = DB::table('reservations as r')
            ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
            ->join('users as u', 'u.id', '=', 'r.agent_id')
            ->where('u.isDeleted', 0)
            ->where('u.is_disabled', '!=', 1)
            ->select([
                'r.*', 'c.fname as customer_fname', 'c.lname as customer_lname',
                'u.fname as agent_fname', 'u.lname as agent_lname',
            ])
            ->orderBy('u.lname')->orderBy('u.fname')
            ->orderBy('r.checkout_date')->orderBy('r.id');

        $this->scopeAgent($query, 'r.agent_id', $filters, $user);
        if ($excludeDeleted) {
            $query->where('r.is_deleted', 0);
        }
        if ($excludeLegacyAccount) {
            $query->where('r.agent_id', '!=', 6680);
        }

        return $query;
    }

    private function scopeAgent(Builder $query, string $column, array $filters, User $user): void
    {
        if (! $user->isAdmin()) {
            $query->where($column, $user->id);
        } elseif (isset($filters['agentId']) && (int) $filters['agentId'] > 0) {
            $query->where($column, (int) $filters['agentId']);
        }
    }

    private function dates(Builder $query, string $column, array $filters): void
    {
        $query->whereDate($column, '>=', $filters['beginDate'])
            ->whereDate($column, '<=', $filters['endDate']);
    }

    private function paidInPeriod(Builder $query, array $filters): void
    {
        // The source reports count each matching check, including mentor checks.
        // Preserve that join cardinality so migrated totals match the old reports.
        $query->join('paid_commissions as pc', 'pc.reservation_id', '=', 'r.id');
        $this->dates($query, 'pc.check_date', $filters);
    }

    private function reservationPayments(array $filters, User $user, bool $paid): array
    {
        $query = $this->reservations($filters, $user)
            ->whereIn('r.status', self::COMPLETED_STATUSES)
            ->where('r.non_commissionable', 0)
            ->where('r.commission_received', 1)
            ->where('r.agent_commission_received', (int) $paid);

        if ($paid) {
            $this->paidInPeriod($query, $filters);
        } else {
            // Legacy dates select agents; their detail includes all unpaid reservations.
            $query->whereIn('r.agent_id', function (Builder $agents) use ($filters) {
                $agents->select('agent_id')->from('reservations')
                    ->whereIn('status', self::COMPLETED_STATUSES)
                    ->where('agent_commission_received', 0)->where('is_deleted', 0)
                    ->whereDate('checkin_date', '>=', $filters['beginDate'])
                    ->whereDate('checkout_date', '<=', $filters['endDate']);
            });
        }

        $columns = [
            'customer' => 'Customer', 'reservation_number' => 'Reservation Number',
            'checkout_date' => 'Checkout Date', 'total_commission' => 'Total Commission',
            'agent_commission' => 'Agent Commission',
        ];
        $formats = [
            'checkout_date' => 'date', 'total_commission' => 'money',
            'agent_commission' => 'money', 'remitted' => 'money', 'unpaid' => 'money',
        ];

        return $query->get()->groupBy('agent_id')->map(function (Collection $reservations) use ($paid, $columns, $formats) {
            $rows = $reservations->map(fn ($r) => [
                ...$this->reservationFields($r),
                'total_commission' => (float) $r->agency_commission,
                'agent_commission' => (float) $r->agent_commission,
                'remitted' => (float) $r->agent_commission,
                'unpaid' => $paid ? 0.0 : (float) $r->agent_commission,
            ]);

            $section = $this->section($this->agentName($reservations->first()), $columns, $rows, $formats, [
                'total_commission', 'agent_commission', 'remitted', 'unpaid',
            ]);
            $section['summary'] = [
                ['label' => 'Total Unpaid', 'value' => $section['totals']['unpaid'], 'format' => 'money'],
                ['label' => 'Total Remitted', 'value' => $section['totals']['remitted'], 'format' => 'money'],
            ];

            return $section;
        })->values()->all();
    }

    private function expenses(array $filters, User $user): array
    {
        $query = DB::table('reservation_gifts as g')
            ->leftJoin('reservations as r', function ($join) {
                $join->on('r.id', '=', 'g.reservation_id')->where('r.is_deleted', 0);
            })
            ->leftJoin('users as u', 'u.id', '=', 'g.created_by')
            ->where('g.is_deleted', 0)
            ->where('g.created_by', '!=', 6680)
            ->select([
                'g.*', 'r.reservation_number',
                'u.fname as agent_fname', 'u.lname as agent_lname',
            ])
            ->orderBy('u.lname')->orderBy('u.fname')->orderBy('g.gift_date')->orderBy('g.id');
        // Unlike the agency reports, the source expenses report always shows
        // the signed-in creator, including administrators.
        $query->where('g.created_by', $user->id);
        $this->dates($query, 'g.gift_date', $filters);

        return $query->get()->groupBy('created_by')->map(function (Collection $expenses) {
            $rows = $expenses->map(fn ($expense) => [
                'id' => $expense->id,
                'customer' => $expense->customer_name,
                'reservation_number' => $expense->reservation_number,
                'amount' => (float) $expense->amount,
                'paid_by' => $expense->paid_by,
                'last4_of_card' => $expense->last4_of_card,
                'gift_type' => $expense->gift_type,
                'gift_date' => $expense->gift_date,
                'notes' => $expense->notes,
            ]);

            $section = $this->section($this->agentName($expenses->first()), [
                'customer' => 'Customer Name', 'reservation_number' => 'Reservation #',
                'amount' => 'Amount', 'paid_by' => 'Paid By', 'last4_of_card' => 'Last 4 Digits',
                'gift_type' => 'Paid For', 'gift_date' => 'Date', 'notes' => 'Notes',
            ], $rows, ['amount' => 'money', 'gift_date' => 'date'], ['amount']);
            $section['summary'] = [
                ['label' => 'Total Amount', 'value' => $section['totals']['amount'], 'format' => 'money'],
            ];

            return $section;
        })->values()->all();
    }

    private function finalAgencyCommission(array $filters, User $user): array
    {
        $agents = DB::table('users')->where('isDeleted', 0)->where('is_disabled', '!=', 1)
            ->where('id', '!=', 6680)
            ->whereIn('id', function (Builder $payments) use ($filters) {
                $payments->select('agent_id')->from('paid_commissions')->where('is_agent', 1);
                $this->dates($payments, 'check_date', $filters);
            });
        $this->scopeAgent($agents, 'id', $filters, $user);

        $payments = DB::table('paid_commissions')
            ->select('reservation_id', 'agent_id', 'amount as paid_amount', 'check_date')
            ->where('is_agent', 1);
        $this->dates($payments, 'check_date', $filters);

        $query = $this->reservations($filters, $user, false)
            ->joinSub($payments, 'pc', function ($join) {
                $join->on('pc.reservation_id', '=', 'r.id')->on('pc.agent_id', '=', 'r.agent_id');
            })
            ->where('r.commission_received', 1)
            ->where('r.agent_commission_received', 1)
            ->addSelect('pc.paid_amount', 'pc.check_date');

        $reservationsByAgent = $query->get()->groupBy('agent_id');

        return $agents->orderBy('id')->get()->map(function ($agent) use ($reservationsByAgent) {
            // The source renders every paid-check recipient, even when the detail
            // flags no longer match any reservations.
            $reservations = $reservationsByAgent->get($agent->id, collect());
            $rows = $reservations->map(fn ($r) => [
                ...$this->reservationFields($r),
                'total_commission' => (float) $r->agency_commission,
                // This report's legacy agency figure is before the mentor share.
                'agency_commission' => (float) $r->agency_commission - (float) $r->agent_commission,
                'agent_commission' => (float) $r->agent_commission,
                'agent_percentage' => (float) ($r->agent_commission_percentage ?? 0),
                'mentor' => 'N/A',
                'paid_amount' => (float) $r->paid_amount,
                'check_date' => $r->check_date,
            ]);

            $section = $this->section(trim($agent->fname.' '.$agent->lname), [
                'customer' => 'Customer', 'reservation_number' => 'Reservation Number',
                'checkout_date' => 'Checkout Date', 'total_commission' => 'Total Commission',
                'agency_commission' => 'Agency Commission', 'mentor' => 'Mentor',
                'paid_amount' => 'Paid To Agent', 'check_date' => 'Check Date',
            ], $rows, [
                'checkout_date' => 'date', 'check_date' => 'date',
                'total_commission' => 'money', 'agency_commission' => 'money',
                'agent_commission' => 'money', 'paid_amount' => 'money',
            ], ['total_commission', 'agency_commission', 'agent_commission', 'paid_amount']);
            $section['summary'] = [
                ['label' => 'Total Paid', 'value' => $section['totals']['paid_amount'], 'format' => 'money'],
                ['label' => 'Total Agency Commission', 'value' => $section['totals']['agency_commission'], 'format' => 'money'],
            ];

            return $section;
        })->values()->all();
    }

    private function totalCommission(array $filters, User $user): array
    {
        $eligibleAgentQuery = DB::table('users')
            ->where('isDeleted', 0)->where('is_disabled', '!=', 1)
            ->whereIn('id', function (Builder $agents) {
                $agents->select('agent_id')->from('reservations')
                    ->whereIn('status', self::COMPLETED_STATUSES)
                    ->where('commission_received', 1)->where('is_deleted', 0)
                    ->where('agent_id', '>', 0)->where('agent_id', '!=', 6680);
            })->orderByRaw('CASE WHEN id = 8 THEN 0 ELSE 1 END')->orderBy('lname')->orderBy('fname');
        $this->scopeAgent($eligibleAgentQuery, 'id', $filters, $user);
        $eligibleAgents = $eligibleAgentQuery->get();
        if ($eligibleAgents->isEmpty()) {
            return [];
        }

        $query = $this->reservations($filters, $user, false)
            ->whereIn('r.agent_id', $eligibleAgents->pluck('id'))
            ->whereIn('r.status', self::COMPLETED_STATUSES)
            ->where('r.commission_received', 1)
            ->where('r.agent_commission_received', 1);
        $this->paidInPeriod($query, $filters);
        $reservations = $query->get()->groupBy('agent_id');

        $mentors = DB::table('mentors as m')
            ->leftJoin('users as u', 'u.id', '=', 'm.mentor_id')
            ->where('m.is_deleted', 0)
            ->whereIn('m.user_id', $eligibleAgents->pluck('id'))
            ->select('m.*', 'u.fname as mentor_fname', 'u.lname as mentor_lname')
            ->orderBy('m.id')->get()->groupBy('user_id');

        // Source total report reuses the unpaid mentor check-writer query. Its
        // date arguments are ignored, so this branch intentionally has no dates.
        $mentees = DB::table('users')->where('isDeleted', 0)
            ->where('is_disabled', '!=', 1)->get(['id', 'mentor_ids']);
        $menteeReservations = $this->reservations([], $user, false, false)
            ->whereIn('r.status', self::COMPLETED_STATUSES)
            ->where('r.commission_received', 1)->where('r.mentor_commission_received', 0)
            ->get()->groupBy('agent_id');

        $moneyColumns = ['total_commission', 'agent_commission', 'mentor_commission', 'agency_commission', 'fees'];
        $formats = array_fill_keys($moneyColumns, 'money') + [
            'checkout_date' => 'date', 'agent_percentage' => 'percent', 'mentor_percentage' => 'percent',
        ];
        $columns = [
            'customer' => 'Customer', 'reservation_number' => 'Reservation',
            'checkout_date' => 'Checkout', 'fees_description' => 'Fees',
            'total_commission' => 'Total Commission', 'agent_commission' => 'Agent Commission',
            'mentor_commission' => 'Mentor Commission', 'agency_commission' => 'Agency Commission',
        ];

        $summaryRows = collect();
        $sections = $eligibleAgents->map(function ($agent) use ($reservations, $mentors, $mentees, $menteeReservations, $columns, $formats, $moneyColumns, $summaryRows) {
            $agentReservations = $reservations->get($agent->id, collect())
                ->map(fn ($r) => ['reservation' => $r, 'mentoring' => false]);
            foreach ($mentees as $mentee) {
                if (in_array($agent->id, explode(',', (string) $mentee->mentor_ids))) {
                    foreach ($menteeReservations->get($mentee->id, collect()) as $reservation) {
                        $agentReservations->push(['reservation' => $reservation, 'mentoring' => true]);
                    }
                }
            }

            $rows = $agentReservations->map(function (array $entry) use ($mentors, $agent) {
                $r = $entry['reservation'];
                // Legacy mentoring branch uses the report agent, not the mentee ID.
                $mentor = $mentors->get($agent->id, collect())->first(function ($m) use ($r) {
                    // Preserve Home_Model::getMentorInfoForCompletedReservations date semantics.
                    return $r->checkin_date && $r->checkout_date
                        && $m->mentor_begin_date && $m->mentor_end_date
                        && $m->mentor_begin_date >= $r->checkin_date
                        && $m->mentor_end_date <= $r->checkout_date;
                });
                $total = (float) $r->agency_commission;
                $agent = (float) $r->agent_commission;
                $mentorPercentage = (float) ($mentor->mentor_commission ?? 0);
                $mentorCommission = $total * $mentorPercentage / 100;
                $lookup = (int) $r->look_up;
                $document = (int) $r->document_fee;
                $lookupFee = ! $entry['mentoring'] && $lookup === 1 && in_array($document, [0, 1], true) ? 15 : 0;
                $documentFee = ! $entry['mentoring'] && $document === 1 && in_array($lookup, [0, 1], true) ? 10 : 0;
                $feeDescriptions = array_filter([
                    $lookupFee ? 'Look Up ($-15)' : null,
                    $documentFee ? 'Document Fee ($-10)' : null,
                ]);

                return [
                    ...$this->reservationFields($r),
                    'personal_travel' => (int) $r->agent_personal_travel === 1 ? 'Yes' : 'No',
                    'mentoring' => $entry['mentoring'],
                    'total_commission' => $total,
                    'agent_commission' => $agent,
                    'agent_percentage' => $total != 0 ? $agent * 100 / $total : 0,
                    'mentor' => $mentor ? trim($mentor->mentor_fname.' '.$mentor->mentor_lname) : '',
                    'mentor_percentage' => $mentorPercentage,
                    'mentor_commission' => $mentorCommission,
                    // Source detail is before mentor; the section total is after mentor.
                    'agency_commission' => $total - $agent,
                    'fees' => $lookupFee + $documentFee,
                    'fees_description' => $feeDescriptions ? implode(', ', $feeDescriptions) : 'NA',
                ];
            });
            $name = trim($agent->fname.' '.$agent->lname);
            $section = $this->section($name, $columns, $rows, $formats, $moneyColumns);
            $section['totals']['agency_commission'] = (float) (
                $rows->sum('total_commission') - $rows->sum('agent_commission') - $rows->sum('mentor_commission')
            );
            $section['kind'] = 'commission_details';
            $section['agent_percentage'] = (float) ($agent->commission ?? 0);
            $section['summary'] = [
                ['label' => 'Agency Commission', 'value' => $section['totals']['agency_commission'], 'format' => 'money'],
                ['label' => 'Total Unpaid', 'value' => $section['totals']['agent_commission'], 'format' => 'money'],
            ];
            $summaryRows->push(['agent_id' => $agent->id, 'agent' => $name, ...$section['totals']]);

            return $section;
        })->filter(fn (array $section) => $section['rows']->isNotEmpty())->values()->all();

        // Unknown reservations have no agent/check recipient; the old report uses travel dates.
        $unknown = 0;
        if ($user->isAdmin() && empty($filters['agentId'])) {
            $unknown = DB::table('reservations')
                ->where('agent_id', 0)
                ->whereIn('status', self::COMPLETED_STATUSES)
                ->where('commission_received', 1)->where('agent_commission_received', 1)
                ->whereDate('checkin_date', '>=', $filters['beginDate'])
                ->whereDate('checkout_date', '<=', $filters['endDate'])
                ->sum('agency_commission');
        }

        if ($summaryRows->isNotEmpty()) {
            // Preserve the legacy ownership accounting: 8 is the owner and 82
            // is deducted from the combined agency/owner total by its original JS.
            $owner = $summaryRows->firstWhere('agent_id', 8);
            $ownerCommission = (float) ($owner['agent_commission'] ?? 0);
            $excludedCommission = (float) ($summaryRows->firstWhere('agent_id', 82)['agent_commission'] ?? 0);
            $agencyCommission = $summaryRows->sum('agency_commission');
            $agentsCommission = $summaryRows->where('agent_id', '!=', 8)->sum('agent_commission');
            $fees = $summaryRows->sum('fees');
            $payoutRows = $summaryRows->where('agent_id', '!=', 8)
                ->filter(fn (array $row) => $row['agent_commission'] != 0)
                ->map(fn (array $row) => ['agent' => $row['agent'], 'total' => $row['agent_commission']])
                ->values();
            if ($fees != 0) {
                $payoutRows->push(['agent' => 'Fees', 'total' => $fees]);
            }
            if ((float) $unknown != 0) {
                $payoutRows->push(['agent' => 'Unknown', 'total' => (float) $unknown]);
            }
            $payoutRows->push(['agent' => 'Total', 'total' => $agentsCommission + $fees]);
            $summary = $this->section('DeeAna', [
                'agent' => 'Agent', 'total' => 'Total',
            ], $payoutRows, ['total' => 'money'], []);
            $summary['kind'] = 'commission_summary';
            $summary['summary'] = [
                ['label' => 'Agency', 'value' => $agencyCommission, 'format' => 'money'],
                ['label' => 'DeeAna', 'value' => $ownerCommission, 'format' => 'money'],
                ['label' => 'Total', 'value' => $agencyCommission + $ownerCommission - $excludedCommission, 'format' => 'money'],
                ['label' => 'Agent Total', 'value' => $agentsCommission, 'format' => 'money'],
            ];
            $sections[] = $summary;
        }

        return $sections;
    }

    private function reservationFields(object $reservation): array
    {
        return [
            'reservation_id' => $reservation->id,
            'customer' => trim(($reservation->customer_fname ?? '').' '.($reservation->customer_lname ?? '')),
            'reservation_number' => $reservation->reservation_number,
            'checkout_date' => $reservation->checkout_date,
        ];
    }

    private function agentName(object $row): string
    {
        return trim(($row->agent_fname ?? '').' '.($row->agent_lname ?? '')) ?: 'Unknown Agent';
    }

    private function section(string $title, array $columns, Collection $rows, array $formats, array $sum): array
    {
        $totals = [];
        foreach ($sum as $field) {
            $totals[$field] = (float) $rows->sum($field);
        }

        return compact('title', 'columns', 'rows', 'formats', 'totals');
    }
}
