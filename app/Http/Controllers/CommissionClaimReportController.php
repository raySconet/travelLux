<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class CommissionClaimReportController extends Controller
{
    public function index()
    {
        return view('reports.commissionClaimReport');
    }

    public function search(Request $request)
    {
        $request->validate([
            'reservationNumber' => 'required'
        ]);

        $commissionPercentage = auth()->user()->commission;

        if (!$commissionPercentage || $commissionPercentage <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Agent Commission Percentage is required.'
            ]);
        }

        $reservations = Reservation::with(['customer:id,fname,lname','product:id,product_name'])
            ->where('is_deleted', 0)
            ->whereIn('status', ['Active','Paid in Full','Canceled w/ Insurance Payout','Canceled - Commission Protected'])
            ->where('commission_received', 0)
            ->where('agent_id', 0)
            ->where('reservation_number', $request->reservationNumber)
            ->get();

        if ($reservations->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No reservations found.'
            ]);
        }

        $reservations->transform(function ($reservation) use ($commissionPercentage) {

            $reservation->agent_commission = number_format(($reservation->agency_commission * $commissionPercentage) / 100, 2);

            $reservation->customer_name = optional($reservation->customer)->fname . ' ' . optional($reservation->customer)->lname;

            $reservation->product_name = optional($reservation->product)->product_name;

            return $reservation;
        });

        return response()->json([
            'success' => true,
            'data' => $reservations
        ]);
    }

    public function claim(Request $request)
    {
        $request->validate([
            'reservationId' => 'required|integer',
        ]);

        $user = auth()->user();

        if (!$user->commission || $user->commission <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Agent Commission Percentage is required.',
            ]);
        }

        $reservation = Reservation::find($request->reservationId);

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found.',
            ]);
        }

        if (!$reservation->agency_commission || $reservation->agency_commission <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Agency Commission is required.',
            ]);
        }

        $agentCommission = ($reservation->agency_commission * $user->commission / 100) - 15;

        $reservation->update([
            'agent_id'              => $user->id,
            'agent_commission'      => $agentCommission,
            'status'                => 'Paid in Full',
            'commission_received'   => 1,
            'commission_claimed'    => 1,
            'look_up'               => 1,
            'last_modified_by'      => $user->id,
            'last_modified_on'      => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commission claimed successfully.',
        ]);
    }
}