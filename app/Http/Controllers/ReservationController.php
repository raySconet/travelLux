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

    public function edit(User $user)
    {
        return view('reservations.reservationDetails' , compact('user'));
    }

}
