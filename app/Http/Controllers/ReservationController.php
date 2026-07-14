<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Destination;
use App\Models\ResortShip;
use App\Models\CruiseItinerary;
use App\Models\CustomersForm;
use App\Models\ReservationTask;
use App\Models\ReservationPayment;
use App\Models\ReservationDiningNote;
use App\Models\ReservationGift;
use App\Models\ReservationPhoneNote;
use App\Models\ReservationCommissionFee;
use App\Models\ReservationTraveler;
use App\Models\ReservationLink;
use App\Models\TimelineTask;
use App\Models\AutomatedEmail;
use App\Models\CustomerAutomatedEmail;
use App\Models\ItineraryTrip;
use App\Models\ReservationAttachment;
use Illuminate\Support\Facades\Storage;
use App\Models\ReservationPaidInFullAudit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\FormSent;
use App\Mail\CustomerFormMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
       $statuses = $request->input('status', ['Active']);
       $search = $request->input('search');


       $agentId = $request->input('users', auth()->id());

       $users = User::select('id','fname', 'lname','email')->where('isDeleted',0)->get();

        $reservationsQuery = Reservation::with('agent', 'customer', 'product', 'destination')
                                         ->select('id', 'status', 'created_on', 'reservation_number', 'reservation_name', 'customer_id', 'agent_id', 'product_id', 'destination_id', 'checkin_date', 'final_payment_due_date')
                                         ->where('is_deleted', 0);

        $reservationsQuery->where(function ($query) use ($statuses) {

            $regularStatuses = array_diff($statuses,['Paid in Full Paid by Archer','Paid in Full Not Paid by Archer']);

            if (!empty($regularStatuses)) {
                $query->whereIn('status', $regularStatuses);
            }

            if (in_array('Paid in Full Paid by Archer', $statuses)) {
                $query->orWhere(function ($q) {
                    $q->where('status', 'Paid in Full')->where('agent_commission_received', 1);
                });
            }

            if (in_array('Paid in Full Not Paid by Archer', $statuses)) {
                $query->orWhere(function ($q) {
                    $q->where('status', 'Paid in Full')->where('agent_commission_received', 0);
                });
            }
        });

        if($agentId !=-1){
            $reservationsQuery->where('agent_id', $agentId);
        }

        if ($search) {
            $reservationsQuery->where(function ($query) use ($search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('fname', 'like', "%{$search}%")
                    ->orWhere('lname', 'like', "%{$search}%");
                })
                ->orWhereHas('product', function ($q) use ($search) {
                    $q->where('product_name', 'like', "%{$search}%");
                })
                ->orWhereHas('destination', function ($q) use ($search) {
                    $q->where('destination_name', 'like', "%{$search}%");
                })
                ->orWhere('reservation_number', 'like', "%{$search}%")
                ->orWhere('reservation_name', 'like', "%{$search}%");
            });
        }

        $reservations = $reservationsQuery->orderBy('created_on', 'asc')->get(); 
        return view('reservations.reservationList', compact('users', 'reservations', 'statuses', 'agentId'));
    }

    public function getDestinationsByProduct(Request $request)
    {
        return Destination::select('id','destination_name','product_id')->where('is_deleted', 0)->where('product_id', $request->product_id)->orderBy('destination_name')->get();
    }

    public function getResortsByDestination(Request $request)
    {
        return ResortShip::select('id','resort_ship_name','destination_id')->where('is_deleted', 0)->where('destination_id', $request->destination_id)->orderBy('resort_ship_name')->get();
    }

    public function getCruisesByResort(Request $request)
    {
        return CruiseItinerary::select('id','cruise_name','resort_ship_id')->where('is_deleted', 0)->where('resort_ship_id', $request->resort_id)->orderBy('cruise_name')->get();
    }

    public function create(Reservation $reservation)
    {
        $reservation = new Reservation();
        $users = User::select('id','fname', 'lname' ,'email', 'commission')->where('isDeleted',0)->get();

        $isNewReservation = true;
        
        $user = auth()->user();

        $products = Product::orderBy('product_name')->where('is_deleted',0)->get();
        $referralCustomers = Customer::where('agent_id', auth()->id())->where('is_deleted',0)->orderBy('lname')->get();
        $itineraryTrips = ItineraryTrip::where('is_deleted', 0)->where('created_by', auth()->id())->orderBy('date', 'desc')->get();   
        $overdueTasksCount = ReservationTask::where('reservation_id', $reservation->id)->where('is_deleted', 0)->where('is_completed', 0)->whereDate('due_date', '<=', now())->count();
       
        $customersPayload = Cache::remember('customers_payload_'.$user->id, 600, function () use ($user) {

        return Customer::query()
            ->select('id','fname','lname','agent_id','email','cellphone')
            ->where('is_deleted', 0)
            ->when(!$user->isAdmin(), fn($q) => $q->where('agent_id', $user->id))
            ->with(['familyMembers' => function ($q) {
                $q->select('id', 'customer_id', 'fname', 'lname', 'email')
                ->where('is_deleted', 0)
                ->whereNotNull('email')
                ->where('email', '!=', '');
            }])
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'fname' => $c->fname,
                    'lname' => $c->lname,
                    'agent_id' => $c->agent_id,
                    'email' => $c->email,
                    'cellphone' => $c->cellphone,
                    'family_members' => $c->familyMembers->map(fn($m) => [
                        'fname' => $m->fname,
                        'lname' => $m->lname,
                        'email' => $m->email,
                    ])->values(),
                ];
            })
            ->values();
        });

        return view('reservations.reservationDetails', compact('users', 'reservation', 'isNewReservation', 'products',  'referralCustomers','itineraryTrips', 'overdueTasksCount','customersPayload'));
    }

    public function edit(Reservation $reservation)
    {
        $users = User::select('id','fname', 'lname', 'email', 'phone_number','commission')->where('isDeleted',0)->get();
        $isNewReservation = false;

        $user = auth()->user();
        if (!$user->isAdmin()) {

            if ($reservation->agent_id != $user->id) {
                abort(403);
            }
        }

        $products = Product::orderBy('product_name')->where('is_deleted',0)->get();
        $availableForms = CustomersForm::where('is_deleted', 0)
                            ->where('is_active', 1)
                            ->whereHas('customersFormRequired', function ($q) use ($reservation) {
                                $q->where(function ($subQ) use ($reservation) {
                                    $subQ->where('all_reservations_required', 1)->orWhere(function ($matchQ) use ($reservation) {
                                        $matchQ->where('product_id', $reservation->product_id)->where('destination_id', $reservation->destination_id);
                                    });

                                });
                                $q->where('is_deleted', 0);
                            })
                            ->get();
                    
        $referralCustomers = Customer::where('agent_id', auth()->id())->where('is_deleted',0)->orderBy('lname')->get();  
        $itineraryTrips = ItineraryTrip::where('is_deleted', 0)->where('created_by', auth()->id())->orderBy('date', 'desc')->get();               
        $linkedReservations = ReservationLink::where('reservation_id', $reservation->id)->where('is_linked', 1) ->with('linkedReservation')->get();
        $overdueTasksCount = ReservationTask::where('reservation_id', $reservation->id)->where('is_deleted', 0)->where('is_completed', 0)->whereDate('due_date', '<=', now())->count();
        $timelineTasks = $reservation->tasks()->with('agent')->where('is_deleted',0)->where('is_timeline_task',1)->get();
        $generalTasks = $reservation->tasks()->with('agent')->where('is_deleted',0)->where('is_timeline_task',0)->get();

        $customersPayload = Cache::remember('customers_payload_'.$user->id, 600, function () use ($user) {
            return Customer::query()
                ->select('id','fname','lname','agent_id','email','cellphone')
                ->where('is_deleted', 0)
                ->when(!$user->isAdmin(), fn($q) => $q->where('agent_id', $user->id))
                ->with(['familyMembers' => function ($q) {
                    $q->select('id', 'customer_id', 'fname', 'lname', 'email')
                    ->where('is_deleted', 0)
                    ->whereNotNull('email')
                    ->where('email', '!=', '');
                }])
                ->get()
                ->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'fname' => $c->fname,
                        'lname' => $c->lname,
                        'agent_id' => $c->agent_id,
                        'email' => $c->email,
                        'cellphone' => $c->cellphone,
                        'family_members' => $c->familyMembers->map(fn($m) => [
                            'fname' => $m->fname,
                            'lname' => $m->lname,
                            'email' => $m->email,
                        ])->values(),
                    ];
                })
                ->values();
        });

        $sentForms = FormSent::with('form')->where('reservation_id', $reservation->id)->orderByDesc('sent_on')->get();

        return  view('reservations.reservationDetails', compact('users', 'reservation' ,'isNewReservation','products',  'availableForms','referralCustomers', 'linkedReservations','itineraryTrips', 'overdueTasksCount', 'timelineTasks', 'generalTasks', 'customersPayload','sentForms'));
    }

    private function generateTimelineTasks($reservation)
    {
        ReservationTask::where('reservation_id', $reservation->id)->where('is_timeline_task', 1)->delete();

        if (!$reservation->product_id || !$reservation->destination_id) {
            return;
        }

        $timelineTasks = TimelineTask::where('product_id', $reservation->product_id)->where('destination_id', $reservation->destination_id)->get();

        foreach ($timelineTasks as $task) {

            $baseDate = match ($task->date_type) {
                'Check In Date' => $reservation->checkin_date,
                'Check Out Date' => $reservation->checkout_date,
                'Deposit Due Date' => $reservation->deposit_due_date,
                'Final Payment Due Date' => $reservation->final_payment_due_date,
                default => $reservation->created_on,
            };

            $dueDate = $baseDate ? \Carbon\Carbon::parse($baseDate) ->{strtolower($task->before_after) === 'before' ? 'subDays' : 'addDays'}($task->due_days) : null;

            ReservationTask::create([
                'reservation_id' => $reservation->id,
                'timeline_task_id' => $task->id,
                'task_name' => $task->task_name,
                'priority' => $task->priority,
                'due_date' => $dueDate,
                'date_type' => $task->date_type,
                'is_timeline_task' => 1,
                'created_by' => auth()->id(),
                'created_on' => now(),
            ]);
        }
    }

    private function generateAutomatedEmails($reservation)
    {
        if ($reservation->stop_auto_emails) {
            return;
        }

        CustomerAutomatedEmail::where('reservation_id', $reservation->id)->delete();

        if (!$reservation->product_id || !$reservation->destination_id || !$reservation->agent_id ) {
            return;
        }

        $emails = AutomatedEmail::where('is_deleted', 0)
            ->where('is_disabled', 0)
            ->where('product_list', $reservation->product_id)
            ->where('destination_list', $reservation->destination_id)
            ->where('agent_id', $reservation->agent_id)
            ->get();

        foreach ($emails as $email) {

            $baseDate = match ($email->email_type) {
                'Check In Date' => $reservation->checkin_date,
                'Check Out Date' => $reservation->checkout_date,
                'Deposit Due Date' => $reservation->deposit_due_date,
                'Final Payment Due Date' => $reservation->final_payment_due_date,
                default => $reservation->created_on,
            };

            $sendDate = $baseDate ? \Carbon\Carbon::parse($baseDate)->{strtolower($email->before_after) === 'before' ? 'subDays' : 'addDays'}($email->days) : null;

            CustomerAutomatedEmail::create([
                'customer_id' => $reservation->customer_id,
                'reservation_id' => $reservation->id,
                'automated_email_id' => $email->id,
                'date' => $sendDate,
            ]);
        }
    }

    public function store(Request $request)
    {
        $messages = [
            'agent_id.required' => 'The Agent field is required.',
            'customer_id.required' => 'The Customer field is required.',
            'reservation_number.required' => 'The Reservation Number field is required.',
            'reservation_name.required' => 'The Reservation Name field is required.',
            'reservation_cost.required' => 'The Total Cost field is required.',
            'agency_commission.required' => 'The Total Agency Commission field is required.',
            'agent_commission.required' => 'The Agent Commission field is required.',
            'product_id.required' => 'The Product field is required.',
            'destination_id.required' => 'The Destination field is required',
            'resort_id.required' => 'The Resort/Ship field is required.',
            'onboard_credit_from_cruise_line.numeric' => 'The OBC From Cruise Line field must be numeric and may contain 2 decimal points.',
            'onboard_credit_from_agent' => 'The OBC From Agent field must be numeric and may contain 2 decimal points.'
        ];

        $data = $request->validate([
            'agent_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'reservation_number' => 'required|string|max:255',
            'reservation_name' => 'required|string|max:255',
            'reservation_cost' => 'required|numeric',
            'agency_commission' => 'required|numeric',
            'agent_commission' => 'required|numeric',
            'status' => 'required|string|max:255',
            'product_id' => 'required|integer',
            'destination_id' => 'required|integer',
            'resort_id' => 'required|integer',

            'group_number' => 'nullable|string|max:50',
            'special_offer' => 'nullable|string|max:255',
            'spouse_email' => 'nullable|string|max:255',
            'email_to_send' => 'nullable|string|max:255',
            'celebrations' => 'nullable|array',
            'celebrations.*' => 'string|max:255',
            'celebration_notes' => 'nullable|string|max:255',
            'room_category' => 'nullable|string|max:255',
            'stateroom_number' => 'nullable|string|max:255',
            'embarkation_port' => 'nullable|string|max:255',
            'ticket_types' => 'nullable|array',
            'ticket_types.*' => 'integer',
            'dining_option' => 'nullable|string|max:255',
            'add_on_options' => 'nullable|array',
            'add_on_options.*' => 'integer',
            'transportation_options' => 'nullable|array',
            'transportation_options.*' => 'integer',
            'cruise_level' => 'nullable|string|max:255',

            'onboard_credit_from_cruise_line' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],
            'onboard_credit_from_agent' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],

            'commission_notes' => 'nullable|string',
            'reservation_notes' => 'nullable|string',
            'flight_notes' => 'nullable|string',
            'notes' => 'nullable|string',

            'checkin_date' => 'nullable|date',
            'checkout_date' => 'nullable|date',
            'deposit_due_date' => 'nullable|date',
            'final_payment_due_date' => 'nullable|date',
            'unknown_reservation_date' => 'nullable|date',

            'commission_received' => 'nullable|integer',
            'commission_claimed' => 'nullable|integer',
            'look_up' => 'nullable|integer',
            'document_fee' => 'nullable|integer',
            'agent_commission_percentage' => 'nullable|integer',
            'commission_to_agent' => 'nullable|integer',
            'non_commissionable' => 'nullable|integer',
            'is_surprise' => 'nullable|integer',
            'agent_personal_travel' => 'nullable|integer',
            'secondary_agent' => 'nullable|integer',
            'cruise_itinerary_id' => 'nullable|integer',
            'days_of_tickets' => 'nullable|integer',
            'concierge_ship' => 'nullable|integer',
            'club_level_resort' => 'nullable|integer',
            'submitted_to_rewards' => 'nullable|integer',
            'agent_commission_received' => 'nullable|integer',
            'mentor_commission_received' => 'nullable|integer',
            'remove_mentor_commission' => 'nullable|integer',
            'unknown_reservation_checked_flag' => 'nullable|integer',
            'stop_auto_emails' => 'nullable|integer',
            'radio_station_ads' => 'nullable|integer',
            'itinerary_trip_id' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'last_modified_by' => 'nullable|integer',
            'is_deleted' => 'nullable|integer',

            'is_website_lead_knot' => 'nullable|integer',
            'is_website_lead' => 'nullable|integer',
            'is_virtuoso_lead' => 'nullable|integer',
            'is_luxury_magazine_lead' => 'nullable|integer',
            'is_facebook_lead' => 'nullable|integer',
            'is_instagram_lead' => 'nullable|integer',
            'is_radio_lead' => 'nullable|integer',

        ], $messages);

        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        $data['ticket_types'] = !empty($data['ticket_types']) ? implode(',', $data['ticket_types']) : null;
        $data['add_on_options'] = !empty($data['add_on_options']) ? implode(',', $data['add_on_options']) : null;    
        $data['transportation_options'] = !empty($data['transportation_options']) ? implode(',', $data['transportation_options']) : null;
        $data['celebrations'] = !empty($data['celebrations']) ? implode(',', $data['celebrations']) : null;

        $user = auth()->user();

        $data['agent_id'] = $user->isAdmin() ? $data['agent_id'] : $user->id;

        $reservation = Reservation::create($data);
        $this->generateTimelineTasks($reservation);
        $this->generateAutomatedEmails($reservation);

        $customer = Customer::with(['familyMembers' => function ($q) { $q->where('is_deleted', 0); }])->find($data['customer_id']);

        if ($customer && $customer->familyMembers->count() > 0) {

            $travelersData = $customer->familyMembers->map(function ($familyMember) use ($reservation) {

                return [
                    'reservation_id' => $reservation->id,
                    'customer_family_member_id' => $familyMember->id,
                    'is_included' => 0,
                    'is_deleted' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

            })->toArray();

            ReservationTraveler::insert($travelersData);
        }

        return redirect()
            ->route('reservations.reservationList')
            ->with('success', 'Reservation created successfully');
    }


    public function update(Request $request, Reservation $reservation)
    {
        $messages = [
            'agent_id.required' => 'The Agent field is required.',
            'customer_id.required' => 'The Customer field is required.',
            'reservation_number.required' => 'The Reservation Number field is required.',
            'reservation_name.required' => 'The Reservation Name field is required.',
            'reservation_cost.required' => 'The Total Cost field is required.',
            'agency_commission.required' => 'The Total Agency Commission field is required.',
            'agent_commission.required' => 'The Agent Commission field is required.',
            'product_id.required' => 'The Product field is required.',
            'destination_id.required' => 'The Destination field is required',
            'resort_id.required' => 'The Resort/Ship field is required.',
            'onboard_credit_from_cruise_line' => 'The OBC From Cruise Line field must be numeric and may contain 2 decimal points.',
            'onboard_credit_from_agent' => 'The OBC From Agent field must be numeric and may contain 2 decimal points.',
            'days_of_tickets.numeric' => 'Invalid',
        ];

        $data = $request->validate([
            'agent_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'reservation_number' => 'required|string|max:255',
            'reservation_name' => 'required|string|max:255',
            'reservation_cost' => 'required|numeric',
            'agency_commission' => 'required|numeric',
            'agent_commission' => 'required|numeric',
            'status' => 'required|string|max:255',
            'product_id' => 'required|integer',
            'destination_id' => 'required|integer',
            'resort_id' => 'required|integer',

            'group_number' => 'nullable|string|max:50',
            'special_offer' => 'nullable|string|max:255',
            'spouse_email' => 'nullable|string|max:255',
            'email_to_send' => 'nullable|string|max:255',
            'celebrations' => 'nullable|array',
            'celebrations.*' => 'string|max:255',
            'celebration_notes' => 'nullable|string|max:255',
            'room_category' => 'nullable|string|max:255',
            'stateroom_number' => 'nullable|string|max:255',
            'embarkation_port' => 'nullable|string|max:255',
            'ticket_types' => 'nullable|array',
            'ticket_types.*' => 'integer',
            'dining_option' => 'nullable|string|max:255',
            'add_on_options' => 'nullable|array',
            'add_on_options.*' => 'integer',
            'transportation_options' => 'nullable|array',
            'transportation_options.*' => 'integer',
            'cruise_level' => 'nullable|string|max:255',

            'onboard_credit_from_cruise_line' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],
            'onboard_credit_from_agent' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],

            'commission_notes' => 'nullable|string',
            'reservation_notes' => 'nullable|string',
            'flight_notes' => 'nullable|string',
            'notes' => 'nullable|string',

            'checkin_date' => 'nullable|date',
            'checkout_date' => 'nullable|date',
            'deposit_due_date' => 'nullable|date',
            'final_payment_due_date' => 'nullable|date',
            'unknown_reservation_date' => 'nullable|date',

            'commission_received' => 'nullable|integer',
            'commission_claimed' => 'nullable|integer',
            'look_up' => 'nullable|integer',
            'document_fee' => 'nullable|integer',
            'agent_commission_percentage' => 'nullable|integer',
            'commission_to_agent' => 'nullable|integer',
            'non_commissionable' => 'nullable|integer',
            'is_surprise' => 'nullable|integer',
            'agent_personal_travel' => 'nullable|integer',
            'secondary_agent' => 'nullable|integer',
            'cruise_itinerary_id' => 'nullable|integer',
            'days_of_tickets' => 'nullable|integer',
            'concierge_ship' => 'nullable|integer',
            'club_level_resort' => 'nullable|integer',
            'submitted_to_rewards' => 'nullable|integer',
            'agent_commission_received' => 'nullable|integer',
            'mentor_commission_received' => 'nullable|integer',
            'remove_mentor_commission' => 'nullable|integer',
            'unknown_reservation_checked_flag' => 'nullable|integer',
            'stop_auto_emails' => 'nullable|integer',
            'radio_station_ads' => 'nullable|integer',
            'itinerary_trip_id' => 'nullable|integer',
            'is_deleted' => 'nullable|integer',

            'is_website_lead_knot' => 'nullable|integer',
            'is_website_lead' => 'nullable|integer',
            'is_virtuoso_lead' => 'nullable|integer',
            'is_luxury_magazine_lead' => 'nullable|integer',
            'is_facebook_lead' => 'nullable|integer',
            'is_instagram_lead' => 'nullable|integer',
            'is_radio_lead' => 'nullable|integer',
        ], $messages);

        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $data['ticket_types'] = !empty($data['ticket_types']) ? implode(',', $data['ticket_types']) : null;
        $data['add_on_options'] = !empty($data['add_on_options']) ? implode(',', $data['add_on_options']) : null;
        $data['transportation_options'] = !empty($data['transportation_options']) ? implode(',', $data['transportation_options']) : null;
        $data['celebrations'] = !empty($data['celebrations']) ? implode(',', $data['celebrations']) : null;
    
        $user = auth()->user();

        if (!$user->isAdmin()) {
            $data['agent_id'] = $user->id;
        }

        $oldStatus = $reservation->status;

        if ( $data['status'] === 'Paid in Full' && $oldStatus !== 'Paid in Full' && empty($reservation->paid_in_full_date) ) {
            $data['paid_in_full_date'] = now();
        }

        $wasPaidInFullBefore = !empty($reservation->paid_in_full_date);

        if ($wasPaidInFullBefore) {

            $changes = [];

            foreach ($data as $field => $newValue) {

                if ($field === 'last_modified_on' || $field === 'last_modified_by') {
                    continue;
                }

                $oldValue = $reservation->$field;

                if ( str_starts_with($field, 'is_') || in_array($field, [
                        'non_commissionable',
                        'agent_personal_travel',
                        'stop_auto_emails',
                        'radio_station_ads',
                        'remove_mentor_commission',
                        'commission_received',
                        'commission_claimed',
                        'look_up',
                        'document_fee',
                        'commission_to_agent',
                        'submitted_to_rewards',
                        'agent_commission_received',
                        'mentor_commission_received',
                    ])
                ) {
                    $oldValue = $oldValue ?? 0;
                    $newValue = $newValue ?? 0;
                }

                if (is_array($oldValue)) $oldValue = implode(',', $oldValue);
                if (is_array($newValue)) $newValue = implode(',', $newValue);

                if ($oldValue != $newValue) {
                    $changes[] = [
                        'reservation_id' => $reservation->id,
                        'field_name' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                        'modified_by' => auth()->id(),
                        'modified_on' => now(),
                    ];
                }
            }

            if (!empty($changes)) {
                ReservationPaidInFullAudit::insert($changes);
            }
        }

        $reservation->update($data);
        $this->generateTimelineTasks($reservation);
        $this->generateAutomatedEmails($reservation);

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {

                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();

                $attachment = ReservationAttachment::create([
                    'reservation_id' => $reservation->id,
                    'file_name' => $originalName,
                    'file_extension' => $extension,
                    'file_size' => $file->getSize(),
                    'created_by' => auth()->id(),
                    'created_on' => now(),
                ]);

                $fileName = $attachment->id . '.' . $extension;

                $file->storeAs(
                    'attachments/reservations',
                    $fileName,
                    'public'
                );
            }
        }

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Reservation updated successfully')
                ->with('activeTab', $request->input('activeTab', 'reservation-details'));
    }

    public function destroyAttachment(ReservationAttachment $attachment)
    {
        Storage::disk('public')->delete(
            'attachments/reservations/' . $attachment->id . '.' . $attachment->file_extension
        );

        $attachment->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
                ->route('reservations.reservationDetails', $attachment->reservation_id)
                ->with('success', 'Reservation attachments deleted successfully')
                ->with('activeTab', 'attachments');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
            ->route('reservations.reservationList')
            ->with('success', 'Reservation deleted successfully');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->selected_reservations;

        if (!$ids || count($ids) == 0) {
            return redirect()->back();
        }

        Reservation::whereIn('id', $ids)->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
            ->route('reservations.reservationList')
            ->with('success', 'Reservations deleted successfully.');
    }

    public function duplicate(Reservation $reservation)
    {
        $copy = $reservation->replicate();

        $copy->id = null;
        $copy->created_at = null;
        $copy->updated_at = null;

        $copy->reservation_name = $reservation->reservation_name . ' (Copy)';

        $users = User::select('id','fname','lname','email','commission')->where('isDeleted', 0)->get();

        $customers = Customer::select('id','fname','lname','agent_id','email','cellphone')->where('is_deleted', 0)->get();

        $products = Product::orderBy('product_name')->where('is_deleted', 0)->get();
        $destinations = Destination::orderBy('destination_name')->where('is_deleted', 0)->get();
        $resortShips = ResortShip::orderBy('resort_ship_name')->where('is_deleted', 0)->get();
        $cruiseItineraries = CruiseItinerary::orderBy('cruise_name')->where('is_deleted', 0)->get();

        $referralCustomers = Customer::where('agent_id', auth()->id())->where('is_deleted', 0)->orderBy('lname')->get();

        $itineraryTrips = ItineraryTrip::where('is_deleted', 0)->where('created_by', auth()->id())->orderBy('date', 'desc')->get();

        $isNewReservation = true;

        return view('reservations.reservationDetails', compact('copy','users','customers','products','destinations','resortShips','cruiseItineraries','referralCustomers','itineraryTrips','isNewReservation'))->with('reservation', $copy);
    }

    public function storeTask(Request $request, Reservation $reservation)
    {
        $messages = [
            'task_name.required' => 'The Task Name field is required.',
            'due_date.required' => 'The Due Date field is required.',
            'priority.required' => 'The Priority field is required.',
        ];

        $validator = \Validator::make($request->all(), [
            'task_name' => 'required|string|max:255',
            'priority' => 'required|string',
            'due_date' => 'required|date',
            'task_notes' => 'nullable|string',
        ], $messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->withErrors($validator, 'taskStore')
                ->withInput()
                ->with('activeTab', 'tasks')
                ->with('openTaskModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationTask::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Task added successfully')
                ->with('activeTab', 'tasks');
    }

    public function updateTask(Request $request, ReservationTask $task)
    {
        $validator = \Validator::make($request->all(), [
            'task_name' => 'required|string|max:255',
            'priority' => 'required|string',
            'due_date' => 'required|date',
            'task_notes' => 'nullable|string',
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

        $task->update($data);

        return redirect()
                ->back()
                ->with('success', 'Task updated successfully')
                ->with('activeTab', 'tasks');
    }

    public function toggleCompleteTask(ReservationTask $task)
    {
        $completed = !$task->is_completed;

        $task->update([
            'is_completed' => $completed,
            'is_completed_by' => auth()->id(),
            'is_completed_on' => now(),
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_completed' => $completed,
            ]);
        }

        return redirect()
            ->route('reservations.reservationDetails', $task->reservation_id)
            ->with('success', $completed ? 'Task completed' : 'Task marked incomplete')
            ->with('activeTab', 'tasks');
    }

    public function deleteTask(ReservationTask $task)
    {
        $task->update([
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
            ->route('reservations.reservationDetails', $task->reservation_id)
            ->with('success', 'Task deleted successfully')
            ->with('activeTab', 'tasks');
    }
    
    public function storePayment(Request $request, Reservation $reservation)
    {
        $messages = [
            'notes.required' => 'The Payment Amount field is required',
            'payment_type.required' => 'The Payment Type is required.',
            'payment_method.required' => 'The Payment Method is required.',
        ];

        $validator = \Validator::make($request->all(), [
            'amount' => 'required|integer', 
            'payment_type' => 'required|string',
            'payment_method' => 'required|string',
            'payment_date' => 'nullable|date',
            'check_number' => 'nullable|integer',
            'credit_card_number' => 'nullable|integer',
            'notes' => 'required|string',
        ], $messages); 

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                    ->route('reservations.reservationDetails', $reservation->id)
                    ->withErrors($validator, 'paymentStore')
                    ->withInput()
                    ->with('activeTab', 'payments')
                    ->with('openPaymentModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationPayment::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Customer Payments added successfully')
                ->with('activeTab', 'payments');
    }

    public function updatePayment(Request $request, ReservationPayment $payment)
    {
        $validator = \Validator::make($request->all(), [
            'amount' => 'required|integer',
            'payment_type' => 'required|string',
            'payment_method' => 'required|string',
            'payment_date' => 'nullable|date',
            'check_number' => 'nullable|integer',
            'credit_card_number' => 'nullable|integer',
            'notes' => 'required|string',
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

        $payment->update($data);

        return redirect()
                ->back()
                ->with('success', 'Payment updated successfully')
                ->with('activeTab', 'payments');
    }

    public function deletePayment(ReservationPayment $payment)
    {
        $reservationId = $payment->reservation_id;

        $payment->update([
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
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Payment deleted successfully')
                ->with('activeTab', 'payments');
    }

    public function storeDiningNote(Request $request, Reservation $reservation)
    {
        $messages = [
            'notes.required' => 'The Note field is required.',
        ];

        $validator = \Validator::make($request->all(), [
            'dining_date' => 'nullable|date',
            'dining_time' => 'nullable|date_format:H:i',
            'meal' => 'nullable|string',
            'notes' => 'required|string',
        ],$messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                    ->route('reservations.reservationDetails', $reservation->id)
                    ->withErrors($validator, 'diningNoteStore')
                    ->withInput()
                    ->with('activeTab', 'diningInformation')
                    ->with('openDiningInfoModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationDiningNote::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Dining Note added successfully')
                ->with('activeTab', 'diningInformation');
    }

    public function updateDiningNote(Request $request, ReservationDiningNote $diningNote)
    {
        $validator = \Validator::make($request->all(), [
            'dining_date' => 'nullable|date',
            'dining_time' => 'nullable|date_format:H:i:s',
            'meal' => 'nullable|string',
            'notes' => 'required|string',
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
        

        $diningNote->update($data);

        return redirect()
                ->back()
                ->with('success', 'Dining note updated successfully')
                ->with('activeTab', 'diningInformation');
    }

    public function toggleCancelDiningNote(ReservationDiningNote $diningNote)
    {
        $diningNote->update([
            'is_canceled' => $diningNote->is_canceled ? 0 : 1,
            'canceled_by' => auth()->id(),
            'canceled_on' => now(),
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
                ->route('reservations.reservationDetails', $diningNote->reservation_id)
                ->with('success', $diningNote->is_canceled ? 'Dining Note marked as uncanceled' : 'Dining Note marked as canceled')
                ->with('activeTab', 'diningInformation');
    }

    public function deleteDiningNote(ReservationDiningNote $diningNote)
    {
        $reservationId = $diningNote->reservation_id;

        $diningNote->update([
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
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Dining Note deleted successfully')
                ->with('activeTab', 'diningInformation');
    }

    public function storeGift(Request $request, Reservation $reservation)
    {
        $messages = [
            'gift_type.required' => 'Gift Type is required.',
            'gift_date.required' => 'Gift Date is required.',
            'amount.required' => 'Amount is required.',
        ];

        $validator = \Validator::make($request->all(), [
            'gift_date' => 'required|date',
            'gift_type' => 'required|string',
            'amount' => 'required|integer',
            'notes' => 'nullable|string'
        ], $messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                    ->route('reservations.reservationDetails', $reservation->id)
                    ->withErrors($validator, 'giftStore')
                    ->withInput()
                    ->with('activeTab', 'giftsInfo')
                    ->with('openGiftsModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationGift::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Gift added successfully')
                ->with('activeTab', 'giftsInfo');
    }

    public function updateGift(Request $request, ReservationGift $gift)
    {
        $validator = \Validator::make($request->all(), [
            'gift_date' => 'required|date',
            'gift_type' => 'required|string',
            'amount' => 'required|integer',
            'notes' => 'nullable|string',
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

        $gift->update($data);

        return redirect()
                ->back()
                ->with('success', 'Gift updated successfully')
                ->with('activeTab', 'giftsInfo');
    }

    public function deleteGift(ReservationGift $gift)
    {
        $reservationId = $gift->reservation_id;

        $gift->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now()
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Gift deleted successfully')
                ->with('activeTab', 'giftsInfo');
    }

    public function storePhoneNote(Request $request, Reservation $reservation)
    {
        $messages = [
            'notes.required' => 'The Notes field is required.',
        ];

        $validator = \Validator::make($request->all(), [
            'category' => 'nullable|string',
            'caller_name' => 'nullable|string',
            'caller_phone_number' => 'nullable|string',
            'notes' => 'required|string',
        ], $messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->withErrors($validator, 'phoneNoteStore')
                ->withInput()
                ->with('activeTab', 'phoneNotes')
                ->with('openPhoneNotesModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationPhoneNote::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Phone Note added successfully')
                ->with('activeTab', 'phoneNotes');
    }

    public function updatePhoneNote(Request $request, ReservationPhoneNote $phoneNote)
    {

        $validator = \Validator::make($request->all(), [
            'category' => 'nullable|string',
            'caller_name' => 'nullable|string',
            'caller_phone_number' => 'nullable|string',
            'notes' => 'required|string',
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

        $phoneNote->update($data);

        return redirect()
                ->back()
                ->with('success', 'Phone Note updated successfully')
                ->with('activeTab', 'phoneNotes');
    }

    public function toggleCancelPhoneNote(ReservationPhoneNote $phoneNote)
    {
        $canceled = !$phoneNote->is_canceled;

        $phoneNote->update([
            'is_canceled' => $canceled,
            'canceled_by' => auth()->id(),
            'canceled_on' => now(),
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_canceled' => $canceled,
            ]);
        }

        return redirect()
            ->route('reservations.reservationDetails', $phoneNote->reservation_id)
            ->with('success',$canceled ? 'Phone Note marked as canceled' : 'Phone Note marked as uncanceled')
            ->with('activeTab', 'phoneNotes');
    }

    public function deletePhoneNote(ReservationPhoneNote $phoneNote)
    {
        $reservationId = $phoneNote->reservation_id;

        $phoneNote->update([
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
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Phone Note deleted successfully')
                ->with('activeTab', 'phoneNotes');
    }

    public function storeCommissionFee(Request $request, Reservation $reservation)
    {
        $messages = [
            'fee_type.required' => 'The Fee Type field is required.',
            'amount.required' => 'The Fee Amount field is required.'
        ];

        $validator = \Validator::make($request->all(), [
            'fee_type' => 'required|string',
            'amount' => 'required|integer',
            'notes' => 'nullable|string',
        ], $messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->withErrors($validator, 'commissionFeeStore')
                ->withInput()
                ->with('activeTab', 'agentPayments')
                ->with('openCommissionFeesModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationCommissionFee::create($data);

        return redirect()
            ->route('reservations.reservationDetails', $reservation->id)
            ->with('success', 'Commission Fee added successfully')
            ->with('activeTab', 'agentPayments');
    }

    public function updateCommissionFee(Request $request, ReservationCommissionFee $commissionFee)
    {
        $validator = \Validator::make($request->all(), [
            'fee_type' => 'required|string',
            'amount' => 'required|integer',
            'notes' => 'nullable|string',
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

        $commissionFee->update($data);

        return redirect()
            ->back()
            ->with('success', 'Commission Fee updated successfully')
            ->with('activeTab', 'agentPayments');
    }

    public function deleteCommissionFee(ReservationCommissionFee $commissionFee)
    {
        $reservationId = $commissionFee->reservation_id;

        $commissionFee->update([
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
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Commission Fee deleted successfully')
                ->with('activeTab', 'agentPayments');
    }

    public function toggleIncludeTraveler(ReservationTraveler $traveler)
    {
        $traveler->update([
            'is_included' => $traveler->is_included ? 0 : 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
                ->route('reservations.reservationDetails', $traveler->reservation_id)
                ->with('success', $traveler->is_included ? 'Traveler marked excluded' : 'Traveler marked included')
                ->with('activeTab', 'travelers');
    }

    public function getActiveReservations(Request $request, $customerId)
    {
        $currentId = $request->current_reservation_id;

        $linkedIds = ReservationLink::where('reservation_id', $currentId)->pluck('linked_reservation_id')->toArray();

        $reverseLinkedIds = ReservationLink::where('linked_reservation_id', $currentId)->pluck('reservation_id')->toArray();

        $allLinkedIds = array_merge($linkedIds, $reverseLinkedIds);

        $allLinkedIds[] = $currentId;

        $reservations = Reservation::where('customer_id', $customerId)
            ->where('status', 'Active')
            ->where('is_deleted', 0)
            ->whereNotIn('id', $allLinkedIds)
            ->select('id', 'reservation_number', 'reservation_name', 'checkin_date', 'checkout_date')
            ->get();

        return response()->json($reservations);
    }

    
    public function linkReservation(Request $request, Reservation $reservation)
    {
        $linkedId = $request->linked_reservation_id;

        if (!$linkedId || $linkedId == $reservation->id) {
            return response()->json(['message' => 'Invalid reservation'], 422);
        }

        $exists = ReservationLink::where(function ($q) use ($reservation, $linkedId) {
            $q->where('reservation_id', $reservation->id)
            ->where('linked_reservation_id', $linkedId);
        })->orWhere(function ($q) use ($reservation, $linkedId) {
            $q->where('reservation_id', $linkedId)
            ->where('linked_reservation_id', $reservation->id);
        })->exists();

        if ($exists) {
            return response()->json(['message' => 'Already linked'], 409);
        }

        ReservationLink::create([
            'reservation_id' => $reservation->id,
            'linked_reservation_id' => $linkedId,
            'is_linked' => 1,
            'created_by' => auth()->id(),
            'created_on' => now(),
        ]);

        ReservationLink::create([
            'reservation_id' => $linkedId,
            'linked_reservation_id' => $reservation->id,
            'is_linked' => 1,
            'created_by' => auth()->id(),
            'created_on' => now(),
        ]);

        return response()->json(['message' => 'Linked successfully']);
    }

    public function unlinkReservation(Request $request, Reservation $reservation)
    {
        $linkedId = $request->linked_reservation_id;

        if (!$linkedId) {
            return response()->json(['message' => 'Invalid reservation'], 422);
        }

        ReservationLink::where('reservation_id', $reservation->id)
            ->where('linked_reservation_id', $linkedId)
            ->update([
                'is_linked' => 0,
                'last_modified_by' => auth()->id(),
                'last_modified_on' => now()
            ]);

        ReservationLink::where('reservation_id', $linkedId)
            ->where('linked_reservation_id', $reservation->id)
            ->update([
                'is_linked' => 0,
                'last_modified_by' => auth()->id(),
                'last_modified_on' => now()
            ]);

        return response()->json(['message' => 'Unlinked successfully']);
    }

    public function sendForm(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|integer',
            'form_id' => 'required|integer',
        ]);

        $reservation = Reservation::with('customer')->findOrFail($request->reservation_id);

        $form = CustomersForm::findOrFail($request->form_id);

        $customer = $reservation->customer;

        if (!$customer) {
            return response()->json([
                'flag' => -1,
                'message' => 'Customer not found.'
            ]);
        }

        $email = $reservation->email_to_send ?: $customer->email;

        if (empty($email)) {
            return response()->json([
                'flag' => -1,
                'message' => 'Customer email is missing.'
            ]);
        }

        $sentForm = FormSent::create([
            'customer_id'    => null,
            'reservation_id' => $reservation->id,
            'form_id'        => $form->id,
            'sent_by'        => Auth::id(),
            'sent_on'        => now(),
        ]);

        $token = encrypt($sentForm->id);

        Mail::to($email)->send(
            new CustomerFormMail(
                $customer->fname,
                Auth::user()->fname . ' ' . Auth::user()->lname,
                $token
            )
        );

        return response()->json([
            'flag' => 1,
            'message' => 'Form sent successfully.'
        ]);
    }
    
    public function resendForm(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|integer',
            'sent_form_id'   => 'required|integer',
        ]);

        $sentForm = FormSent::with([
            'reservation.customer',
            'form'
        ])->find($request->sent_form_id);

        if (!$sentForm) {
            return response()->json([
                'flag' => -1,
                'message' => 'Form not found.'
            ]);
        }

        $reservation = $sentForm->reservation;

        if (!$reservation || !$reservation->customer) {
            return response()->json([
                'flag' => -1,
                'message' => 'Reservation or customer not found.'
            ]);
        }

        $customer = $reservation->customer;

        $email = $reservation->email_to_send ?: $customer->email;

        if (empty($email)) {
            return response()->json([
                'flag' => -1,
                'message' => 'Customer email is missing.'
            ]);
        }

        $token = encrypt($sentForm->id);

        Mail::to($email)->send(
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
