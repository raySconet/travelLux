<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AutomatedEmailsController extends Controller
{
    public function index()
    {
       $users = User::select('id','name', 'email')->get();
       return view('automatedEmails', compact('users'));
    }

    public function edit(User $user)
    {
        return view('automated-emails.edit' , compact('user'));
    }
}
