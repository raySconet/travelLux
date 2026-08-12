<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationsPaidByTraveluxReportController extends Controller
{
    public function index()
    {
       return view('reports.reservationsPaidByTraveluxReport');
    }

}
