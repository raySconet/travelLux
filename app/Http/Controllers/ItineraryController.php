<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ItineraryController extends Controller
{
    public function index(Request $request)
    {
       $users = User::select('id','name', 'email')->get();
       $agentId = $request->input('users', auth()->id());
       
       return view('itinerary', compact('users', 'agentId'));
    }

    public function edit(User $user)
    {
        return view('itinerary.edit' , compact('user'));
    }
}
