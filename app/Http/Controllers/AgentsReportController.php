<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AgentsReportController extends Controller
{
    public function index()
    {
       $users = User::select('id','name', 'email')->get();
       return view('reports.agentsReport', compact('users'));
    }

}
