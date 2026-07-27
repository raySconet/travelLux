<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\State;
use App\Models\Country;
use App\Models\CustomersForm;
use App\Models\CustomerFamilyMember;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\InviteCustomerRequest;
use App\Mail\RegistrationInvitationMail;
use App\Models\CustomerInvitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\CustomerIntakeForm;
use App\Mail\IntakeFormMail;
use App\Mail\CustomerFormMail;
use App\Models\FormSent;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $statuses = $request->input('status', ['Active']);

        $loggedUser = auth()->user();

        if ($loggedUser->isAdmin() || $loggedUser->isSubAdmin()) {
            $agentId = $request->input('users', $loggedUser->id);

        } else {
            $agentId = $loggedUser->id;
        }

        $search = $request->input('search');
        $users = User::select('id','fname','lname','email')->where('isDeleted',0)->get();
        $customersQuery = Customer::with('agent')->select('id','fname','mname','lname','cellphone','email','status','agent_id')->whereIn('status', $statuses)->where('is_deleted', 0);

        if ($agentId != -1) {
            $customersQuery->where('agent_id', $agentId);
        }

        if ($search) {
            $customersQuery->where(function($query) use ($search) {
                $query->where('fname', 'like', "%{$search}%")
                    ->orWhere('lname', 'like', "%{$search}%")
                    ->orWhere('mname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cellphone', 'like', "%{$search}%");
            });
        }

        $customers = $customersQuery->orderBy('lname', 'asc')->get();

        return view('customers.customerList', compact('users','customers','statuses','agentId'));
    }

    public function create(Customer $customer)
    {
        $customer = new Customer();
        $isNewCustomer = true;
        $states = State::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();

        $referralCustomers = Customer::where('agent_id', auth()->id())->where('is_deleted', 0)->orderBy('lname')->get();

        return view('customers.customerDetails', compact('customer', 'isNewCustomer', 'states', 'countries','referralCustomers'));
    }

    public function edit(Customer $customer)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {

            if ($customer->agent_id != $user->id) {
                abort(403);
            }
        }

        $isNewCustomer = false;

        $states = State::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();

        $availableForms = CustomersForm::where('is_deleted', 0)->where('is_active', 1)
            ->whereHas('customersFormRequired', function ($q) {
                $q->where('all_customers_required', 1);
            })->get();  

        $sentForms = $customer->formSent()->with('form:id,form_name')->orderByDesc('sent_on')->get();

        $referralCustomers = Customer::where('agent_id', auth()->id())->where('is_deleted', 0)->orderBy('lname')->get();

        $automatedEmails = $customer->automatedEmails()
            ->select('id','customer_id','automated_email_id','reservation_id','date')
            ->where(function ($q) {
                $q->whereNull('reservation_id')
                ->orWhere('reservation_id', '');
            })
            ->with(['automatedEmail:id,subject'])
            ->orderByDesc('date')
            ->get();

        $customer->load(['reservations.customerSurveys' => function ($q) { $q->where('submit_flag', 1); }]);

        $invitations = $customer->customerInvitations()->orderByDesc('created_on')->get();

        $intakeForms = $customer->customerIntakeForms()->orderByDesc('created_on')->get();

        return view('customers.customerDetails', compact('customer','isNewCustomer','states','countries','availableForms','referralCustomers','automatedEmails','sentForms','invitations','intakeForms'));
    }

    public function inviteNewCustomer(){
        return view('customers.inviteNewCustomer');
    }

    public function storeInvitation(InviteCustomerRequest $request)
    {

        if (Customer::where('email', $request->email)->where('is_deleted', 0)->exists()) {
            return back()
                ->withInput()
                ->with('email_exists', true);
        }

        DB::transaction(function () use ($request) {

            $customer = Customer::create([

                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,

                'status' => 'Invited',

                'agent_id' => Auth::id(),
                'created_by' => Auth::id(),

                'is_website_lead_knot' => $request->boolean('is_website_lead_knot'),
                'is_website_lead' => $request->boolean('is_website_lead'),
                'is_virtuoso_lead' => $request->boolean('is_virtuoso_lead'),
                'is_luxury_magazine_lead' => $request->boolean('is_luxury_magazine_lead'),
                'is_facebook_lead' => $request->boolean('is_facebook_lead'),
                'is_instagram_lead' => $request->boolean('is_instagram_lead'),
                'is_radio_lead' => $request->boolean('is_radio_lead'),

                'created_on' => now(),
            ]);

            CustomerFamilyMember::create([

                'customer_id' => $customer->id,
                'fname' => $customer->fname,
                'lname' => $customer->lname,
                'relation' => 'Self',
                'created_by' => Auth::id(),
                'created_on' => now(),
            ]);

            CustomerInvitation::where('customer_id', $customer->id)
                ->where('status', 'P')
                ->update([
                    'status' => 'C',
                    'expired_flag' => 1,
                ]);

            $invitation = CustomerInvitation::create([

                'customer_id' => $customer->id,
                'status' => 'P',
                'created_by' => Auth::id(),
                'created_on' => now(),
            ]);

            $token = base64_encode($invitation->id . config('app.invitation_salt'));

            Mail::to($customer->email)->send(
                new RegistrationInvitationMail(
                    $customer->fname,
                    Auth::user()->fname . ' ' . Auth::user()->lname,
                    $token
                )
            );
        });

        return redirect()
            ->route('customers.customerList')
            ->with('success', 'Invitation sent successfully.');
    }

    public function store(Request $request)
    {
        $messages = [
            'fname.required' => 'The First Name field is required.',
            'lname.required' => 'The Last Name field is required.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Email Address Not Valid.',
            'cellphone.required' => 'The Cell Phone is required.',
            'address_line1.required' => 'The Address Line 1 is required.',
            'city.required' => 'The City field is required',
            'state.required' => 'The State/Province/Region is required.',
            'postal_code.required' => 'The Postal Code field is required.',
        ];

        $data = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cellphone' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'status' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',


            'mname' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'secondary_email' => 'nullable|email|max:255',
            'home_phone' => 'nullable|string|max:255',
            'work_phone' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'profile_type' => 'nullable|string|max:50',
            'marital_status' => 'nullable|string|max:50',
            'special_notes' => 'nullable|string',
            'address_line2' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'united_airlines_reward' => 'nullable|string|max:255',
            'delta_airlines_reward' => 'nullable|string|max:255',
            'southwest_airlines_reward' => 'nullable|string|max:255',
            'american_airlines_reward' => 'nullable|string|max:255',
            'other_airlines_reward' => 'nullable|string|max:255',
            'crownandanchor_cruise_reward' => 'nullable|string|max:255',
            'castaway_cruise_reward' => 'nullable|string|max:255',
            'vifp_cruise_reward' => 'nullable|string|max:255',
            'latitudes_cruise_reward' => 'nullable|string|max:255',
            'other_cruise_reward' => 'nullable|string|max:255',
            'marketing_method' => 'nullable|string|max:255',
            'referral_company' => 'nullable|string|max:255',
            'referred_by_fname' => 'nullable|string|max:255',
            'referred_by_lname' => 'nullable|string|max:255',
            'referred_by_email' => 'nullable|email|max:255',
            'referred_by_phone' => 'nullable|string|max:255',
            'general_notes' => 'nullable|string',


            'retired' => 'nullable|integer',
            'children_at_home' => 'nullable|integer',
            'virtuoso_life' => 'nullable|integer',
            'customer_referral' => 'nullable|integer',
            'is_deleted' => 'nullable|integer',

            'birth_date' => 'nullable|date',
            'anniversary_date' => 'nullable|date',
            'last_modified_on' => 'nullable|date',
        ], $messages);

        $data['agent_id'] = auth()->id();
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        $customer = Customer::create($data);

        $customer->familyMembers()->create([
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'relation' => 'Self',
        
            'created_by' => auth()->id(),
            'created_on' => now(),
        ]);

        return redirect()
            ->route('customers.customerList')
            ->with('success', 'Customer created successfully');
    }

    public function update(Request $request, Customer $customer)
    {
        $messages = [
            'fname.required' => 'The First Name field is required.',
            'lname.required' => 'The Last Name field is required.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Email Address Not Valid.',
            'cellphone.required' => 'The Cell Phone is required.',
            'address_line1.required' => 'The Address Line 1 is required.',
            'city.required' => 'The City field is required',
            'state.required' => 'The State/Province/Region is required.',
            'postal_code.required' => 'The Postal Code field is required.',
        ];

        $data = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cellphone' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'status' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',

            'mname' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'secondary_email' => 'nullable|email|max:255',
            'home_phone' => 'nullable|string|max:255',
            'work_phone' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'profile_type' => 'nullable|string|max:50',
            'marital_status' => 'nullable|string|max:50',
            'special_notes' => 'nullable|string',
            'address_line2' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'united_airlines_reward' => 'nullable|string|max:255',
            'delta_airlines_reward' => 'nullable|string|max:255',
            'southwest_airlines_reward' => 'nullable|string|max:255',
            'american_airlines_reward' => 'nullable|string|max:255',
            'other_airlines_reward' => 'nullable|string|max:255',
            'crownandanchor_cruise_reward' => 'nullable|string|max:255',
            'castaway_cruise_reward' => 'nullable|string|max:255',
            'vifp_cruise_reward' => 'nullable|string|max:255',
            'latitudes_cruise_reward' => 'nullable|string|max:255',
            'other_cruise_reward' => 'nullable|string|max:255',
            'marketing_method' => 'nullable|string|max:255',
            'referral_company' => 'nullable|string|max:255',
            'referred_by_fname' => 'nullable|string|max:255',
            'referred_by_lname' => 'nullable|string|max:255',
            'referred_by_email' => 'nullable|email|max:255',
            'referred_by_phone' => 'nullable|string|max:255',
            'general_notes' => 'nullable|string',

            'retired' => 'nullable|integer',
            'children_at_home' => 'nullable|integer',
            'virtuoso_life' => 'nullable|integer',
            'customer_referral' => 'nullable|integer',
            'agent_id' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'is_deleted' => 'nullable|integer',

            'birth_date' => 'nullable|date',
            'anniversary_date' => 'nullable|date',
            'created_on' => 'nullable|date',
            'last_modified_on' => 'nullable|date',
        ], $messages);

        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $customer->update($data);

        return redirect()
                ->route('customers.customerDetails', $customer->id)
                ->with('success', 'Customer updated successfully')
                ->with('activeTab', $request->input('activeTab', 'home'));
    }

    public function destroy(Customer $customer)
    {
        $customer->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
            ->route('customers.customerList')
            ->with('success', 'Customer deleted successfully');
    }

    public function storeFamilyMember(Request $request, Customer $customer)
    {
        $messages = [
            'fname.required' => 'The First Name field is required.',
            'lname.required' => 'The Last Name field is required.'
        ];

        $validator = \Validator::make($request->all(), [
            'fname' => 'required|string',
            'lname' => 'required|string',
            'mname' => 'nullable|string',
            'nickname' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'relation' => 'nullable|string',
            'gender' => 'nullable|string',
            'cellphone' => 'nullable|integer',
            'home_phone' => 'nullable|integer',
            'work_phone' => 'nullable|integer',
            'email' => 'nullable|string',
            'traveler_number' => 'nullable|integer',
            'deceased' => 'nullable|integer',
            'passport_number' => 'nullable|integer',
            'passport_issue_date' => 'nullable|date',
            'passport_expiration_date' => 'nullable|date',
            'address_line1' => 'nullable|string',
            'address_line2' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|integer',
            'country' => 'nullable|string',
            'special_notes' => 'nullable|string',
        ], $messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                ->route('customers.customerDetails', $customer->id)
                ->withErrors($validator, 'family')
                ->withInput()
                ->with('activeTab', 'family');
        }

        $data = $validator->validated();

        $data['customer_id'] = $customer->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        CustomerFamilyMember::create($data);

        return redirect()
                ->route('customers.customerDetails', $customer->id)
                ->with('success', 'Family Member added successfully')
                ->with('activeTab', 'family');
    }

    public function updateFamilyMember(Request $request, CustomerFamilyMember $familyMember)
    {
        $validator = \Validator::make($request->all(), [
            'fname' => 'required|string',
            'lname' => 'required|string',
            'mname' => 'nullable|string',
            'nickname' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'relation' => 'nullable|string',
            'gender' => 'nullable|string',
            'cellphone' => 'nullable|integer',
            'home_phone' => 'nullable|integer',
            'work_phone' => 'nullable|integer',
            'email' => 'nullable|string',
            'traveler_number' => 'nullable|integer',
            'deceased' => 'nullable|integer',
            'passport_number' => 'nullable|integer',
            'passport_issue_date' => 'nullable|date',
            'passport_expiration_date' => 'nullable|date',
            'address_line1' => 'nullable|string',
            'address_line2' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|integer',
            'country' => 'nullable|string',
            'special_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        $data = $validator->validated();

        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $familyMember->update($data);

        return redirect()
                ->back()
                ->with('success', 'Family member updated successfully')
                ->with('activeTab','family');
    }

    public function deleteFamilyMember(CustomerFamilyMember $member)
    {
        $customerId = $member->customer_id;

        $member->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
                ->route('customers.customerDetails', $customerId)
                ->with('success', 'Family Member deleted successfully')
                ->with('activeTab', 'family');
    }

    public function sendInvitation(Customer $customer)
    {
        if (!$customer || $customer->is_deleted) {

            return response()->json([
                'success' => false,
                'message' => 'Customer not found.'
            ]);
        }

        if (blank($customer->email)) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid email address.'
            ]);
        }

        DB::transaction(function () use ($customer, &$invitation) {

            CustomerInvitation::where('customer_id', $customer->id)
                ->where('status', 'P')
                ->update([
                    'status' => 'C',
                    'expired_flag' => 1,
                ]);

            $invitation = CustomerInvitation::create([
                'customer_id' => $customer->id,
                'status' => 'P',
                'created_by' => Auth::id(),
                'created_on' => now(),
            ]);

        });

        $token = base64_encode($invitation->id . config('app.invitation_salt'));

        Mail::to($customer->email)->send(

            new RegistrationInvitationMail(
                $customer->fname,
                Auth::user()->fname.' '.Auth::user()->lname,
                $token
            )

        );

        $isNewCustomer = false;
        $invitations = $customer->customerInvitations()->orderByDesc('created_on')->get();

        $intakeForms = $customer->customerIntakeForms()->orderByDesc('created_on')->get();

        $html = view('customers.partials.selfServiceInvitations', compact('customer','isNewCustomer','invitations','intakeForms'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }

    public function sendIntakeForm(Customer $customer)
    {
        if ($customer->is_deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.'
            ]);
        }

        if (blank($customer->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email address.'
            ]);
        }

        $intakeForm = DB::transaction(function () use ($customer) {

            $counter = CustomerIntakeForm::where('customer_id', $customer->id)->count() + 1;

            return CustomerIntakeForm::create([
                'customer_id' => $customer->id,
                'status'      => 'P',
                'counter'     => $counter,
                'created_by'  => Auth::id(),
                'created_on'  => now(),
            ]);

        });

        $token = base64_encode($intakeForm->id . config('app.invitation_salt'));

        Mail::to($customer->email)->send(
            new IntakeFormMail(
                $customer->fname,
                Auth::user()->fname . ' ' . Auth::user()->lname,
                $token
            )
        );

        return response()->json([
            'success' => true,
            'message' => 'Record created successfully.',
            'intakeFormId' => $intakeForm->id,
            'counter' => $intakeForm->counter,
        ]);
    }

    public function resendIntakeForm(Request $request)
    {
        $request->validate([
            'intakeFormId' => 'required|integer'
        ]);

        $form = CustomerIntakeForm::with('customer.agent')->find($request->intakeFormId);

        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => 'Intake form not found.'
            ],404);
        }

        $customer = $form->customer;

        if (!$customer || empty($customer->email)) {

            return response()->json([
                'success'=>false,
                'message'=>'Customer email is missing.'
            ],422);
        }

        $form->update([
            'resent_on' => now()
        ]);

        $token = base64_encode($form->id . config('app.invitation_salt'));

        $userName = $customer->agent->fname . ' ' . $customer->agent->lname;

        Mail::to($customer->email)->send(

            new IntakeFormMail(
                $customer->fname,
                $userName,
                $token
            )

        );

        return response()->json([
            'success'=>true
        ]);
    }

    public function sendForm(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'form_id' => 'required|integer'
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        $form = CustomersForm::findOrFail($request->form_id);

        if (empty($customer->email)) {

            return back()->with('error','Customer email is missing.');

        }

        $sentForm = FormSent::create([
            'customer_id' => $customer->id,
            'reservation_id' => null,
            'form_id' => $form->id,
            'sent_by' => Auth::id(),
            'sent_on' => now()
        ]);

        $token = encrypt($sentForm->id);

        Mail::to($customer->email)->send(
            new CustomerFormMail(
                $customer->fname,
                Auth::user()->fname.' '.Auth::user()->lname,
                $token
            )

        );

        return back()->with('success','Form sent successfully.');
    }

    public function resendForm(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'sent_form_id' => 'required|integer',
        ]);

        $sentForm = FormSent::with([
            'customer',
            'form'
        ])->find($request->sent_form_id);

        if (!$sentForm) {

            return response()->json([
                'flag' => -1,
                'message' => 'Form not found.'
            ]);

        }

        $customer = $sentForm->customer;

        if (!$customer || empty($customer->email)) {

            return response()->json([
                'flag' => -1,
                'message' => 'Customer email is missing.'
            ]);

        }

        $token = encrypt($sentForm->id);

        Mail::to($customer->email)->send(
            new CustomerFormMail(
                $customer->fname,
                Auth::user()->fname . ' ' . Auth::user()->lname,
                $token
            )

        );

        return response()->json([
            'flag' => 1,
            'message' => 'Successfully re-sent form to customer.'
        ]);
    }
}
