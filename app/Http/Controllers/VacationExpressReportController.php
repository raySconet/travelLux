<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VacationExpressReportController extends Controller
{
    public function index()
    {
       return view('reports.vacationExpressReport');
    }

}
