<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HotelOnlyReportController extends Controller
{
    public function index()
    {
       return view('reports.hotelOnlyReport');
    }

}
