<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ItineraryController extends Controller
{
    public function index()
    {
       $users = User::select('id','name', 'email')->get();
       return view('itinerary', compact('users'));
    }

    public function edit(User $user)
    {
        return view('itinerary.edit' , compact('user'));
    }
}
