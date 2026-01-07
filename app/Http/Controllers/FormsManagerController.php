<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FormsManagerController extends Controller
{
    public function index()
    {
       $users = User::select('id','name', 'email')->get();
       return view('formsManager', compact('users'));
    }

    public function edit(User $user)
    {
        return view('forms-manager.edit' , compact('user'));
    }

}
