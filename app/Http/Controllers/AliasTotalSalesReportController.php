<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AliasTotalSalesReportController extends Controller
{
    public function index()
    {
       return view('reports.aliasTotalSalesReport');
    }

}
