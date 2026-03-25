<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CruisesReportController extends Controller
{
    public function index()
    {
       return view('reports.cruisesReport');
    }

}
