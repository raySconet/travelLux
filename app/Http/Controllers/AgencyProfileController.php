<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AgencyProfileController extends Controller
{
    public function index()
    {
    //    $users = User::select('id','name', 'email')->get();
       return view('agencyProfile');
    }
}
