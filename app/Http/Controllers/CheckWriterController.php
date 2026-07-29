<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reservation;
class CheckWriterController extends Controller
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
        $agents = $this->loadAgentsForCheckWriter();

        return view('commissions.checkWriter', compact('agents'));
    }

    public function loadAgentsForCheckWriter()
    {
        $agentIds = Reservation::whereIn('status', [
                'Paid in Full',
                'Canceled w/ Insurance Payout',
                'Canceled - Commission Protected'
            ])
            ->where('commission_received', 1)
            ->where('is_deleted', 0)
            ->where('agent_id', '>', 0)
            ->where('agent_id', '!=', 6680)
            ->distinct()
            ->pluck('agent_id');


        if ($agentIds->isEmpty()) {
            return collect();
        }


        return User::where('isDeleted', 0)
            ->where('is_disabled', '!=', 1)
            ->whereIn('id', $agentIds)
            ->orderByRaw('id = 8 DESC')
            ->orderBy('lname')
            ->orderBy('fname')
            ->get([
                'id',
                'fname',
                'lname',
                'commission'
            ]);
    }
}
