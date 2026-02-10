<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommissionClaimReportController extends Controller
{
    public function index()
    {
       return view('reports.commissionClaimReport');
    }

}
