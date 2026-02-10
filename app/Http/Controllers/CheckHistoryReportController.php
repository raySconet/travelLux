<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckHistoryReportController extends Controller
{
    public function index()
    {
       return view('reports.checkHistoryReport');
    }

}
