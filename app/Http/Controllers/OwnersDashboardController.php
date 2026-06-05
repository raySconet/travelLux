<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Customer;

class OwnersDashboardController extends Controller
{
    public function index()
    {
        return view('dashboards.ownersDashboard');
    }

    public function agencyTotalSales()
    {
        $years = [
            now()->subYear()->year,
            now()->year,
            now()->addYear()->year,
        ];

        $data = [];

        foreach ($years as $year) {

            $totalSales = Reservation::query()
                ->where('is_deleted', 0)
                ->whereIn('status', ['Active', 'Paid in Full'])
                ->whereBetween('checkin_date', [$year . '-01-01', $year . '-12-31' ])
                ->sum('reservation_cost');

            $data[] = ['name' => (string) $year, 'y' => (float) $totalSales ];
        }

        return response()->json($data);
    }

    public function agentBirthdayCounts()
    {
        $today = Carbon::today();

        return response()->json([
            'today' => User::where('isDeleted', 0)->whereMonth('birth_date', $today->month)->whereDay('birth_date', $today->day)->count(),

            'week' => User::where('isDeleted', 0)
                ->whereRaw("
                    STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [$today->toDateString(),$today->copy()->addWeek()->toDateString() ])
                ->count(),

            'month' => User::where('isDeleted', 0)
                ->whereRaw("
                    STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [$today->toDateString(),$today->copy()->addMonth()->toDateString()])
                ->count(),

            'sixMonths' => User::where('isDeleted', 0)
                ->whereRaw("
                    STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [$today->toDateString(),$today->copy()->addMonths(6)->toDateString()])
                ->count(),
        ]);
    }

    public function agentBirthdayDetails($range)
    {
        $today = Carbon::today();

        $query = User::query()->where('isDeleted', 0)->select('fname', 'lname', 'birth_date');

        switch ($range) {

            case 'today':
                $query->whereMonth('birth_date', $today->month)->whereDay('birth_date', $today->day);
                break;

            case 'week':
                $end = $today->copy()->addWeek();

                $query->whereRaw("
                    STR_TO_DATE(
                        CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)),
                        '%Y-%m-%d'
                    )
                    BETWEEN ? AND ?
                ", [ $today->toDateString(), $end->toDateString() ]);
                break;

            case 'month':
                $end = $today->copy()->addMonth();

                $query->whereRaw("
                    STR_TO_DATE(
                        CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)),
                        '%Y-%m-%d'
                    )
                    BETWEEN ? AND ?
                ", [ $today->toDateString(), $end->toDateString() ]);
                break;

            case 'sixMonths':
                $end = $today->copy()->addMonths(6);

                $query->whereRaw("
                    STR_TO_DATE(
                        CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)),
                        '%Y-%m-%d'
                    )
                    BETWEEN ? AND ?
                ", [ $today->toDateString(), $end->toDateString() ]);
                break;

            case 'all':
                $query->where(function ($q) use ($today) {
                    $q->whereMonth('birth_date', $today->month)
                    ->whereDay('birth_date', $today->day);
                })
                ->orWhereRaw("
                    STR_TO_DATE(
                        CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)),
                        '%Y-%m-%d'
                    )
                    BETWEEN ? AND ?
                ", [$today->toDateString(),$today->copy()->addMonths(6)->toDateString() ]);
                break;
        }

        $birthdays = $query->orderByRaw("MONTH(birth_date), DAY(birth_date)")->get();

        return response()->json(
            $birthdays->map(function ($user) {
                return [
                    'name' => $user->fname . ' ' . $user->lname,
                    'date' => Carbon::parse($user->birth_date)
                        ->format('l, F jS Y'),
                ];
            })
        );
    }

    public function customerBirthdayCounts()
    {
        $today = Carbon::today();

        $baseQuery = function () use ($today) {
            return Customer::where('is_deleted', 0)
                ->whereRaw("
                    STR_TO_DATE(
                        CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)),
                        '%Y-%m-%d'
                    )
                ");
        };

        return response()->json([
            'today' => Customer::where('is_deleted', 0)->whereMonth('birth_date', $today->month)->whereDay('birth_date', $today->day)->count(),

            'week' => Customer::where('is_deleted', 0)
                ->whereRaw("
                    STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [$today->toDateString(),$today->copy()->addWeek()->toDateString()])
                ->count(),

            'month' => Customer::where('is_deleted', 0)
                ->whereRaw("
                    STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [ $today->toDateString(), $today->copy()->addMonth()->toDateString() ])
                ->count(),

            'sixMonths' => Customer::where('is_deleted', 0)
                ->whereRaw("
                    STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [$today->toDateString(),$today->copy()->addMonths(6)->toDateString() ])
                ->count(),
        ]);
    }

    public function customerBirthdayDetails($range)
    {
        $today = Carbon::today();

        $query = Customer::query()->where('is_deleted', 0)->select('fname', 'lname', 'birth_date');

        switch ($range) {

            case 'today':
                $query->whereMonth('birth_date', $today->month)->whereDay('birth_date', $today->day);
                break;

            case 'week':
                $query->whereMonth('birth_date', $today->month)
                    ->whereDay('birth_date', '>=', $today->day)
                    ->whereRaw("(
                            (MONTH(birth_date) = ? AND DAY(birth_date) >= ?)
                            OR
                            (MONTH(birth_date) = ? AND DAY(birth_date) <= ?)
                        )", [
                            $today->month,
                            $today->day,
                            $today->copy()->addWeek()->month,
                            $today->copy()->addWeek()->day
                        ]);
                break;

            case 'month':
                $end = $today->copy()->addMonth();

                $query->whereRaw("
                    STR_TO_DATE(
                        CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)),
                        '%Y-%m-%d'
                    )
                    BETWEEN ? AND ?
                ", [ $today->toDateString(), $end->toDateString() ]);
                break;

            case 'sixMonths':
                $end = $today->copy()->addMonths(6);

                $query->whereRaw("
                    STR_TO_DATE(
                        CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)),
                        '%Y-%m-%d'
                    )
                    BETWEEN ? AND ?
                ", [ $today->toDateString(),$end->toDateString() ]);
                break;

            case 'all':
                $query->where(function ($q) use ($today) {
                    $q->whereMonth('birth_date', $today->month)
                    ->whereDay('birth_date', $today->day);
                })
                ->orWhereRaw("
                    STR_TO_DATE(
                        CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)),
                        '%Y-%m-%d'
                    )
                    BETWEEN ? AND ?
                ", [$today->toDateString(),$today->copy()->addMonths(6)->toDateString() ]);
                break;
        }

        $birthdays = $query->orderByRaw("MONTH(birth_date), DAY(birth_date)")->get();

        return response()->json(
            $birthdays->map(function ($customer) {
                return [
                    'name' => $customer->fname . ' ' . $customer->lname,
                    'date' => Carbon::parse($customer->birth_date)
                        ->format('l, F jS Y'),
                ];
            })
        );
    }

    public function customerAnniversaryCounts()
    {
        $today = Carbon::today();

        return response()->json([
            'today' => Customer::where('is_deleted', 0)
                ->whereMonth('anniversary_date', $today->month)
                ->whereDay('anniversary_date', $today->day)
                ->count(),

            'week' => Customer::where('is_deleted', 0)
                ->whereRaw("
                    STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(anniversary_date), '-', DAY(anniversary_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [$today->toDateString(),$today->copy()->addWeek()->toDateString() ])
                ->count(),

            'month' => Customer::where('is_deleted', 0)
                ->whereRaw("
                    STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(anniversary_date), '-', DAY(anniversary_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [ $today->toDateString(), $today->copy()->addMonth()->toDateString() ])
                ->count(),

            'sixMonths' => Customer::where('is_deleted', 0)
                ->whereRaw("STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(anniversary_date), '-', DAY(anniversary_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [$today->toDateString(),$today->copy()->addMonths(6)->toDateString() ])
                ->count(),
        ]);
    }

    public function customerAnniversaryDetails($range)
    {
        $today = Carbon::today();

        $query = Customer::query()->where('is_deleted', 0)->select('fname', 'lname', 'anniversary_date');

        switch ($range) {

            case 'today':
                $query->whereMonth('anniversary_date', $today->month)->whereDay('anniversary_date', $today->day);
                break;

            case 'week':
                $end = $today->copy()->addWeek();

                $query->whereRaw("STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(anniversary_date), '-', DAY(anniversary_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [$today->toDateString(), $end->toDateString() ]);
                break;

            case 'month':
                $end = $today->copy()->addMonth();

                $query->whereRaw("STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(anniversary_date), '-', DAY(anniversary_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [$today->toDateString(),$end->toDateString() ]);
                break;

            case 'sixMonths':
                $end = $today->copy()->addMonths(6);

                $query->whereRaw("STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(anniversary_date), '-', DAY(anniversary_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [ $today->toDateString(), $end->toDateString() ]);
                break;

            case 'all':
                $query->whereRaw("STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(anniversary_date), '-', DAY(anniversary_date)), '%Y-%m-%d')
                    BETWEEN ? AND ?
                ", [$today->toDateString(), $today->copy()->addMonths(6)->toDateString() ]);
                break;
        }

        $data = $query->orderByRaw("MONTH(anniversary_date), DAY(anniversary_date)")->get();

        return response()->json(
            $data->map(function ($c) {
                return [
                    'name' => $c->fname . ' ' . $c->lname,
                    'date' => Carbon::parse($c->anniversary_date)->format('l, F jS Y'),
                ];
            })
        );
    }
}