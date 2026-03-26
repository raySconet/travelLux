<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PleasantEvokeReportController extends Controller
{
    public function index()
    {
       return view('reports.pleasantEvokeReport');
    }

}
