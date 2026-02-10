<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class Report1099Controller extends Controller
{
    public function index()
    {
       $users = User::select('id','name', 'email')->get();
       return view('reports.1099Report', compact('users'));
    }

}
