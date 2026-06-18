<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TravelImpressionsReportController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
    }

    public function index()
    {
       $this->checkAdmin();
       return view('reports.travelImpressionsReport');
    }

}
