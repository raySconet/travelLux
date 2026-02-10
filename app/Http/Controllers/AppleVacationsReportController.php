<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppleVacationsReportController extends Controller
{
    public function index()
    {
       return view('reports.appleVacationsReport');
    }

}
