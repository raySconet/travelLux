<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnknownReservationsReportController extends Controller
{
    public function index()
    {
       return view('reports.unknownReservationsReport');
    }

}
