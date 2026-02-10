<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ReservationController extends Controller
{
    public function index()
    {
       $users = User::select('id','name', 'email')->get();
       return view('reservations.reservationList', compact('users'));
    }

    public function create()
    {
        $users = User::select('id','name','email')->get();
        $isNewReservation = true;

        return view('reservations.reservationDetails', compact('users', 'isNewReservation'));
    }

    public function edit($reservation)
    {
        $users = User::select('id','name','email')->get();
        $isNewReservation = false;

        return view('reservations.reservationDetails', compact('users', 'isNewReservation'));
    }

}
