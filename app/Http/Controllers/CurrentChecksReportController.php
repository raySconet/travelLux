<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrentChecksReportController extends Controller
{
    public function index()
    {
       return view('reports.currentChecksReport');
    }

}
