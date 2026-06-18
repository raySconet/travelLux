<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Product;

class CommissionsRemittancesController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
    }

    public function index()
    {
       $this->checkAdmin();
       $users = User::select('id','fname','lname')->where('isDeleted',0)->orderBy('fname','ASC')->get(); 
       $customers = Customer::select('id','fname','lname')->where('is_deleted',0)->get(); 
       $products = Product::select('id', 'product_name')->where('is_deleted',0)->orderBy('product_name','ASC')->get();
       
       return view('commissions.commissionsRemittances', compact('users','customers','products'));
    }

}
