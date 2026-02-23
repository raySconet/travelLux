<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\State;
use App\Models\Country;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'Active');

        $agentId = $request->input('users', auth()->id());

        $users = User::select('id','name','email')->get();


        $customersQuery = Customer::with('agent')
                                    ->select('id','fname','mname','lname','cellphone','email','status','agent_id')
                                    ->where('status', $status);


        if($agentId != -1){
            $customersQuery->where('agent_id', $agentId);
        }

        $customers = $customersQuery->orderBy('lname', 'asc')->get();

        return view('customers.customerList', compact('users','customers','status','agentId'));
    }

    public function create(Customer $customer)
    {
        $isNewCustomer = true;
        $states = State::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();

        return view('customers.customerDetails', compact('customer', 'isNewCustomer', 'states', 'countries'));
    }

    public function edit(Customer $customer)
    {
        $isNewCustomer = false;
        $states = State::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();

        return view('customers.customerDetails', compact('customer', 'isNewCustomer', 'states', 'countries'));
    }

    public function inviteNewCustomer(){
        return view('customers.inviteNewCustomer');
    }
}
