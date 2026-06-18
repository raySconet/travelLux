<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Top15ExpensiveTripsReportController extends Controller
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
       return view('reports.top15ExpensiveTripsReport');
    }

}
