<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CustomersPerAgentController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $agents = DB::table('users as U')
            ->leftJoin('customers as C', function ($join) {
                $join->on('C.agent_id', '=', 'U.id')
                     ->where('C.is_deleted', 0);
            })
            ->where('U.isDeleted', 0)
            ->where('U.is_disabled', '!=', 1)
            ->groupBy('U.id', 'U.fname', 'U.lname')
            ->orderBy('U.fname')
            ->orderBy('U.lname')
            ->select([
                'U.id',
                DB::raw("CONCAT(U.fname,' ',U.lname) as agentName"),

                DB::raw("SUM(CASE WHEN C.status='active' THEN 1 ELSE 0 END) as active"),
                DB::raw("SUM(CASE WHEN C.status='inactive' THEN 1 ELSE 0 END) as inactive"),
                DB::raw("SUM(CASE WHEN C.status='invited' THEN 1 ELSE 0 END) as invited"),
                DB::raw("SUM(CASE WHEN C.status='paused' THEN 1 ELSE 0 END) as paused"),
                DB::raw("SUM(CASE WHEN C.status='prospect' THEN 1 ELSE 0 END) as prospect"),
            ])
            ->get();

        return view('reports.customersPerAgent', compact('agents'));
    }
}