<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AutomatedEmail;
use App\Models\Product;
use App\Models\Destination;
use App\Models\ResortShip;
use App\Models\CruiseItinerary;

class AutomatedEmailsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::select('id','fname','lname','email')
                    ->where('isDeleted',0)
                    ->get();

        $agentId = $request->input('agent_id', -1);

        $query = AutomatedEmail::with('agent','product','destination','resortShip','cruiseItinerary')
                                ->select('id','is_disabled','subject','before_after','days','message','agent_id','product_list','destination_list','resort_list','cruise_itinerary_list')
                                ->where('is_deleted',0);

        if ($agentId == -1) {
            $query->where('agent_id', -1); 
        } else {
            $query->where('agent_id', $agentId);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                ->orWhere('before_after','like',"%{$search}%")
                ->orWhere('days', 'like', "%{$search}%")
                ->orWhere('message','like',"%{$search}%");
            });
        }

        $automatedEmails = $query->get();

        return view('automatedEmails', compact('users','automatedEmails', 'agentId'));
    }

    public function create(AutomatedEmail $automatedEmail)
    {
        $automatedEmail = new AutomatedEmail();
        $isNewAutomatedEmail = true;
        $users = User::select('id','fname','lname')->where('isDeleted',0)->get();

        $products = Product::orderBy('product_name')->where('is_deleted',0)->get();
        $destinations = Destination::orderBy('destination_name')->where('is_deleted',0)->get();
        $resortShips = ResortShip::orderBy('resort_ship_name')->where('is_deleted',0)->get();
        $cruiseItineraries = CruiseItinerary::orderBy('cruise_name')->where('is_deleted',0)->get();

        return view('automated-emails.edit', compact('users','automatedEmail','isNewAutomatedEmail','products','destinations','resortShips','cruiseItineraries'));
    }

    public function edit(AutomatedEmail $automatedEmail)
    {
       $isNewAutomatedEmail = false; 
       $users = User::select('id','fname', 'lname' ,'email')->get();

       $products = Product::orderBy('product_name')->where('is_deleted',0)->get();
       $destinations = Destination::orderBy('destination_name')->where('is_deleted',0)->get();
       $resortShips = ResortShip::orderBy('resort_ship_name')->where('is_deleted',0)->get();
       $cruiseItineraries = CruiseItinerary::orderBy('cruise_name')->where('is_deleted',0)->get();

       return view('automated-emails.edit' , compact('users','automatedEmail','isNewAutomatedEmail','products','destinations','resortShips','cruiseItineraries'));
    }

    public function toggle(AutomatedEmail $automatedEmail)
    {
        $automatedEmail->is_disabled = $automatedEmail->is_disabled == 0 ? 1 : 0;
        $automatedEmail->save();

        return redirect()->route('automatedEmails');
    }

    public function store(Request $request)
    {
        $messages = [
            'email_type.required' => 'The Email Type field is required.',
            'subject.required' => 'The Email Subject field is required.',
            'before_after.required' => 'The Before/After field is required.',
            'days.required' => 'The Days field is required.',
            'message.required' => 'The Message field is required.'
        ];

        $data = $request->validate([
            'email_type' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'before_after' => 'required|string|max:255',
            'days' =>'required|integer',
            'message' => 'required|string|max:255',
            'bcc_agent' => 'nullable|integer',
            'product_list' => 'nullable|integer',
            'destination_list' => 'nullable|integer',
            'resort_list' => 'nullable|integer',
            'cruise_itinerary_list' => 'nullable|integer',
            'agent_id' => 'required|integer',
            'is_disabled' => 'nullable|integer',
            'is_deleted' => 'nullable|integer'
        ], $messages);

        $data['created_by'] = auth()->id();
        $data['created_at'] = now();

        AutomatedEmail::create($data);

        return redirect()
            ->route('automatedEmails')
            ->with('success', 'Automated Email created successfully');
    }

    public function update(Request $request, AutomatedEmail $automatedEmail)
    {
        $messages = [
            'email_type.required' => 'The email Type field is required.',
            'subject.required' => 'The Email Subject field is required.',
            'before_after.required' => 'The Before/After field is required.',
            'days.required' => 'The Days field is required.',
            'message.required' => 'The Message field is required.'
        ];

        $data = $request->validate([
            'email_type' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'before_after' => 'required|string|max:255',
            'days' => 'required|integer',
            'message' => 'required|string|max:255',
            'bcc_agent' => 'nullable|integer',
            'product_list' => 'nullable|integer',
            'destination_list' => 'nullable|integer',
            'resort_list' => 'nullable|integer',
            'cruise_itinerary_list' => 'nullable|integer',
            'agent_id' => 'required|integer',
            'is_disabled' => 'nullable|integer',
            'is_deleted' => 'nullable|integer'
        ], $messages);

        $data['last_modified_by'] = auth()->id();
        $data['updated_at'] = now();

        $automatedEmail->update($data);

        return redirect()
            ->route('automatedEmails')
            ->with('success', 'Automated Email updated successfully');
    }

    public function destroy(AutomatedEmail $automatedEmail)
    {
        $automatedEmail->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('automatedEmails')
            ->with('success', 'Automated Email deleted successfully');
    }
}
