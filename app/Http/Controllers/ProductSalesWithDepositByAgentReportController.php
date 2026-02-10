<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductSalesWithDepositByAgentReportController extends Controller
{
    public function index()
    {
       return view('reports.productSalesWithDepositByAgentReport');
    }

}
