<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class top15ExpensiveTripsReportController extends Controller
{
    public function index()
    {
       return view('reports.top15ExpensiveTripsReport');
    }

}
