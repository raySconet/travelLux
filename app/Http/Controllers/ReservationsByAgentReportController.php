<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationsByAgentReportController extends Controller
{
    public function index()
    {
       return view('reports.reservationsByAgentReport');
    }

}
