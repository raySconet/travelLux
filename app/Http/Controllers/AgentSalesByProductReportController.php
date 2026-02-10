<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentSalesByProductReportController extends Controller
{
    public function index()
    {
       return view('reports.agentSalesByProductReport');
    }

}
