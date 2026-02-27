<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class OverallTaskDashboardController extends Controller
{
    public function index()
    {
       $users = User::select('id','fname', 'lname','email')->get();
       return view('dashboards.overallTaskDashboard', compact('users'));
    }

}
