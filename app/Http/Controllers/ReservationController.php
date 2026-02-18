<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
       $status = $request->input('status', 'Active');

       $agentId = $request->input('users', auth()->id());

       $users = User::select('id','name', 'email')->get();

       $reservationsQuery = Reservation::with('agent')
                                        ->select('id', 'status', 'created_on', 'reservation_number', 'reservation_name', 'customer_id', 'agent_id', 'product_id', 'destination_id', 'checkin_date', 'final_payment_due_date')
                                        ->where('status',$status);

        if($agentId !=-1){
            $reservationsQuery->where('agent_id', $agentId);
        }

        $reservations = $reservationsQuery->orderBy('created_on', 'asc')->get(); 
        return view('reservations.reservationList', compact('users', 'reservations', 'status', 'agentId'));
    }

    public function create(Reservation $reservation)
    {
        $users = User::select('id','name','email')->get();
        $isNewReservation = true;

        return view('reservations.reservationDetails', compact('users', 'reservation', 'isNewReservation'));
    }

    public function edit(Reservation $reservation)
    {
        $users = User::select('id','name','email')->get();
        $isNewReservation = false;

        return view('reservations.reservationDetails', compact('users', 'reservation' ,'isNewReservation'));
    }

}
