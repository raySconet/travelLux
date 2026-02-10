<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AliasTotalGrossCommissionReportController extends Controller
{
    public function index()
    {
       return view('reports.aliasTotalGrossCommission');
    }

}
