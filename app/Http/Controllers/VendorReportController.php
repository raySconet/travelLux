<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorReportController extends Controller
{
    public function index()
    {
       return view('reports.vendorReport');
    }

}
