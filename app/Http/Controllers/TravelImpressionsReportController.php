<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TravelImpressionsReportController extends Controller
{
    public function index()
    {
       return view('reports.travelImpressionsReport');
    }

}
