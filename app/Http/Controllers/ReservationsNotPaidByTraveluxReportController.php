<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationsNotPaidByTraveluxReportController extends Controller
{
    public function index()
    {
       return view('reports.reservationsNotPaidByTraveluxReport');
    }

}
