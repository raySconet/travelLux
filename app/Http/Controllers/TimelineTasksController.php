<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TimelineTasksController extends Controller
{
    public function index()
    {
       $users = User::select('id','name', 'email')->get();
       return view('timelineTasks', compact('users'));
    }

    public function edit(User $user)
    {
        return view('timeline-tasks.edit' , compact('user'));
    }
}
