<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpediaReportController extends Controller
{
    public function index()
    {
       return view('reports.expediaReport');
    }

}
