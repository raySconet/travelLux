<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentExpensesReportController extends Controller
{
    public function index()
    {
       return view('reports.agentExpensesReport');
    }

}
