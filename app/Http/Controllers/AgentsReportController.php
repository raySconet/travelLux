<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AgentsReportController extends Controller
{
    public function index()
    {
       $users = User::select('id','fname', 'lname' ,'email','first_address_line','state','city','cell_phone_number','commission')
                    ->where('isDeleted',0)
                    ->where('is_disabled',0)
                    ->get();
       return view('reports.agentsReport', compact('users'));
    }

}
