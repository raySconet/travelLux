<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Reports\ReportValue;

class Report1099Controller extends Controller
{
    public function index()
    {
        return view('reports.1099Report');
    }

    public function loadReport(Request $request)
    {
        $dates = $request->validate([
            'beginDate' => ['nullable', 'date_format:Y-m-d'],
            'endDate' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:beginDate'],
        ]);
        $beginDate = $dates['beginDate'] ?? now()->subMonth()->toDateString();
        $endDate = $dates['endDate'] ?? now()->toDateString();

        // Home_Model::load1099Report: include historical agents, but exclude
        // the same two internal accounts and order by surname, then first name.
        $fields = ['U.id', 'U.fname', 'U.lname', 'U.first_address_line', 'U.city', 'U.state',
            'U.cell_phone_number', 'U.birth_date', 'U.ssn', 'U.ein', 'U.postal_code'];
        $agents = DB::table('paid_commissions as PC')
            ->leftJoin('users as U', 'U.id', '=', 'PC.agent_id')
            ->leftJoin('reservations as R', 'R.id', '=', 'PC.reservation_id')
            ->whereNotIn('PC.agent_id', [6669, 6680])
            ->whereBetween('PC.check_date', [$beginDate, $endDate])
            ->when(! $request->user()->isAdmin(), fn ($query) => $query->where('PC.agent_id', $request->user()->id))
            ->select($fields)->selectRaw('SUM(PC.amount) as total_paid')
            ->groupBy('PC.agent_id', ...$fields)
            ->orderBy('U.lname')->orderBy('U.fname')->get();

        if ($agents->isEmpty()) {

            return response()->json([
                'success' => false,
                'message' => 'No Data Found.'
            ]);
        }

        $rows = [];
        foreach ($agents as $agent) {
            $row = [
                'agent' => trim($agent->fname.' '.$agent->lname),
                'address' => $agent->first_address_line.' - '.$agent->city.' - '.$agent->state,
                'phone' => $agent->cell_phone_number,
                'birth_date' => str_replace('/', '-', ReportValue::format($agent->birth_date, 'date')),
                'ssn' => $agent->ssn,
                'ein' => $agent->ein,
                'postal_code' => $agent->postal_code,
                'commission_paid' => number_format($agent->total_paid, 2),
            ];
            $rows[] = $row;
            if ((int) $agent->id === 8) {
                // The original adds this row without adding it to Total Paid.
                // Inner join is equivalent to its RIGHT JOIN + PC WHERE clauses.
                $agencyAmount = DB::table('paid_commissions as PC')
                    ->join('reservations as R', 'R.id', '=', 'PC.reservation_id')
                    ->whereNotIn('PC.agent_id', [6669, 6680])
                    ->whereBetween('PC.check_date', [$beginDate, $endDate])
                    ->selectRaw('SUM(R.agency_commission - PC.amount) as amount')->value('amount');
                $rows[] = array_replace($row, [
                    'agent' => $row['agent'].' (Agency)',
                    'commission_paid' => number_format((float) $agencyAmount, 2),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'totalPaid' => number_format($agents->sum('total_paid'), 2),
            'data' => $rows,
        ]);
    }
}
