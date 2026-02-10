<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AllTripsByTravelDateReportController extends Controller
{
    public function index()
    {
       return view('reports.allTripsByTravelDateReport');
    }

}
