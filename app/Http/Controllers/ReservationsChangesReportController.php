<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationsChangesReportController extends Controller
{
    public function index()
    {
       return view('reports.reservationsChangesReport');
    }

}
