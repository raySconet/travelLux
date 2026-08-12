<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Customer;
use App\Models\CustomersForm;
use App\Models\State;
use App\Models\Country;

class CustomerSectionController extends Controller
{
    public function rewards(Customer $customer)
    {
        return view('customers.partials.airline-cruises-rewards', [
            'customer' => $customer,
        ]);
    }

    public function family(Customer $customer)
    {
        $states = Cache::rememberForever('states', function () {
            return State::select('id', 'name')
                ->orderBy('name')
                ->get();
        });

        $countries = Cache::rememberForever('countries', function () {
            return Country::select('id', 'name')
                ->orderBy('name')
                ->get();
        });

        $familyMembers = $customer->familyMembers()->select([
            'id',
            'customer_id',
            'fname',
            'lname',
            'relation',
            'mname',
            'nickname',
            'birth_date',
            'gender',
            'cellphone',
            'home_phone',
            'work_phone',
            'email',
            'traveler_number',
            'deceased',
            'passport_number',
            'passport_issue_date',
            'passport_expiration_date',
            'address_line1',
            'address_line2',
            'city',
            'state',
            'zip_code',
            'country',
            'special_notes',
        ])->where('is_deleted',0)->get();

        return view('customers.partials.family', [
            'customer' => $customer,
            'familyMembers' => $familyMembers,
            'states' => $states,
            'countries' => $countries,
            'isNewCustomer' => false,
        ]);
    }

    public function forms(Customer $customer)
    {
        $availableForms = CustomersForm::select('id','form_name','preview_form_html_content')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->whereHas('customersFormRequired', function ($q) {
                $q->where('all_customers_required', 1);
            })
        ->get();

        $sentForms = $customer->formSent()
            ->with('form:id,form_name')
            ->orderByDesc('sent_on')
        ->get();

        return view('customers.partials.forms', [
            'customer' => $customer,
            'availableForms' => $availableForms,
            'sentForms' => $sentForms,
        ]);
    }

    public function invitations(Customer $customer)
    {
        $invitations = $customer->customerInvitations()->orderByDesc('created_on')->get();

        $intakeForms = $customer->customerIntakeForms()->orderByDesc('created_on')->get();

        return view('customers.partials.selfServiceInvitations', [
            'customer' => $customer,
            'invitations' => $invitations,
            'intakeForms' => $intakeForms,
            'isNewCustomer' => false,
        ]);
    }

    public function travelHistory(Customer $customer)
    {
        return view('customers.partials.travelHistory', [
            'customer' => $customer,
        ]);
    }

    public function referredBy(Customer $customer)
    {
        $referralCustomers = Customer::select('id','fname','lname')
            ->where('agent_id', auth()->id())
            ->where('is_deleted', 0)
            ->orderBy('lname')
            ->limit(500)
        ->get();

        return view('customers.partials.referredBy', [
            'customer' => $customer,
            'referralCustomers' => $referralCustomers,
        ]);
    }

    public function automatedEmails(Customer $customer)
    {
        $automatedEmails = $customer->automatedEmails()
            ->select('id','customer_id','automated_email_id','reservation_id','date')
            ->where(function ($q) {
                $q->whereNull('reservation_id')
                    ->orWhere('reservation_id', '');
            })
            ->with(['automatedEmail:id,subject'])
            ->orderByDesc('date')
            ->get();

        return view('customers.partials.autoEmails', [
            'customer' => $customer,
            'emails' => $automatedEmails,
            'automatedEmails' => $automatedEmails,
        ]);
    }

    public function generalNotes(Customer $customer)
    {
        return view('customers.partials.generalNotes', [
            'customer' => $customer,
            'isNewCustomer' => false,
        ]);
    }

}