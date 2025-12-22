<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SystemUsersController extends Controller
{
    public function index()
    {
       $users = User::select('id','name', 'email')->get();
       return view('system-users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('system-users.edit' , compact('user'));
    }

}
