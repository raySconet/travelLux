<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RebookingRateReportController extends Controller
{
    public function index()
    {
        $users = User::select('id','name', 'email')
                    ->where('isDeleted',0)
                    ->get();
       return view('reports.rebookingRateReport', compact('users'));
    }

}
