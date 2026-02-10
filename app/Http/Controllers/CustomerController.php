<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
       $users = User::select('id','name', 'email')->get();
       return view('customers.customerList', compact('users'));
    }

    public function create(User $user)
    {
        $isNewCustomer = true;

        return view('customers.customerDetails', compact('user', 'isNewCustomer'));
    }

    public function edit(User $user)
    {
        $isNewCustomer = false;

        return view('customers.customerDetails' , compact('user', 'isNewCustomer'));
    }

    public function inviteNewCustomer(){
        return view('customers.inviteNewCustomer');
    }

}
