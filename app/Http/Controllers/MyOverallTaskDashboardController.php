<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyOverallTaskDashboardController extends Controller
{
    public function index()
    {
       return view('dashboards.myOverallTaskDashboard');
    }

}
