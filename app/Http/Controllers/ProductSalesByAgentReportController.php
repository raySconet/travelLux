<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductSalesByAgentReportController extends Controller
{
    public function index()
    {
       return view('reports.productSalesByAgentReport');
    }

}
