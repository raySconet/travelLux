<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommissionsRemittancesController extends Controller
{
    public function index()
    {
       return view('commissions.commissionsRemittances');
    }

}
