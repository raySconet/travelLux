<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationsNotPaidByALTReportController extends Controller
{
    public function index()
    {
       return view('reports.reservationsNotPaidByALTReport');
    }

}
