<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationsPaidByALTReportController extends Controller
{
    public function index()
    {
       return view('reports.reservationsPaidByALTReport');
    }

}
