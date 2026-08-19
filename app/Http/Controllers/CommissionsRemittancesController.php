<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use App\Models\Reservation;


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


    public function remittances()
    {
        $users = User::select('id', 'commission')
            ->selectRaw("CONCAT(fname, ' ', lname) AS userName")
            ->where('isDeleted', 0)
            ->where('id', '!=', 6680)
            ->orderBy('userName')
            ->get();

        $customers = Customer::select('id')
            ->selectRaw("CONCAT(lname, ', ', fname, ' ', COALESCE(mname, '')) AS customerName")
            ->where('is_deleted', 0)
            ->where('agent_id', '!=', 0)
            ->orderBy('customerName')
            ->get();

        $products = Product::where('is_deleted', 0)
            ->orderBy('product_name')
            ->get();

        $reservations = Reservation::with([
                'customer:id,fname,lname',
                'agent:id,fname,lname,email,commission',
                'product:id,product_name'
            ])
            ->where('commission_received', 1)
            ->where('agent_commission_received', 0)
            ->where('non_commissionable', 0)
            ->where('is_deleted', 0)
            ->where('agent_id', '!=', 6680)
            ->whereIn('status', [
                'Paid in Full',
                'Canceled w/ Insurance Payout',
                'Canceled - Commission Protected'
            ])
            ->orderBy(Customer::select('lname')
                ->whereColumn('customers.id', 'reservations.customer_id'))
            ->get();

        $totalCommissionAmount = $reservations->sum('agency_commission');

        return view('commissions.commissionsRemittances', compact(
            'users',
            'customers',
            'products',
            'reservations',
            'totalCommissionAmount'
        ));
    }

}
