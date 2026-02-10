<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinalAgencyCommissionReportController extends Controller
{
    public function index()
    {
       return view('reports.finalAgencyCommissionReport');
    }

}
