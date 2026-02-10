<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TotalCommissionReportController extends Controller
{
    public function index()
    {
       return view('reports.totalCommissionReport');
    }

}
