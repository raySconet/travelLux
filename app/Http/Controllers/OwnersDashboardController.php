<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnersDashboardController extends Controller
{
    public function index()
    {
       return view('dashboards.ownersDashboard');
    }

}
