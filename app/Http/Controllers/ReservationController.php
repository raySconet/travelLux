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
use App\Models\ReservationDiningNote;
use App\Models\ReservationGift;
use App\Models\ReservationPhoneNote;
use App\Models\ReservationCommissionFee;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
       $status = $request->input('status', 'Active');
       $search = $request->input('search');


       $agentId = $request->input('users', auth()->id());

       $users = User::select('id','fname', 'lname','email')
                    ->where('isDeleted',0)
                    ->get();

       $reservationsQuery = Reservation::with('agent', 'customer', 'product', 'destination')
                                        ->select('id', 'status', 'created_on', 'reservation_number', 'reservation_name', 'customer_id', 'agent_id', 'product_id', 'destination_id', 'checkin_date', 'final_payment_due_date')
                                        ->where('status',$status)   
                                        ->where('is_deleted',0);

        if($agentId !=-1){
            $reservationsQuery->where('agent_id', $agentId);
        }

        if($search) {
            $reservationsQuery->where(function($query) use ($search) {
                $query->whereHas('customer', function($q) use ($search) {
                    $q->where('fname', 'like', "%{$search}%")
                    ->orWhere('lname', 'like', "%{$search}%")
                    ->orWhere('mname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cellphone', 'like', "%{$search}%");
                })
                ->orWhere('reservation_number', 'like', "%{$search}%")
                ->orWhere('reservation_name', 'like', "%{$search}%");
            });
        }

        $reservations = $reservationsQuery->orderBy('created_on', 'asc')->get(); 
        return view('reservations.reservationList', compact('users', 'reservations', 'status', 'agentId'));
    }

    public function create(Reservation $reservation)
    {
        $reservation = new Reservation();
        $users = User::select('id','fname', 'lname' ,'email')
                    ->where('isDeleted',0)
                    ->get();
        $isNewReservation = true;

        $customers = Customer::select('id','fname','lname','agent_id','email', 'cellphone')->where('is_deleted',0)->get();

        $products = Product::orderBy('product_name')->where('is_deleted',0)->get();
        $destinations = Destination::orderBy('destination_name')->where('is_deleted',0)->get();
        $resortShips = ResortShip::orderBY('resort_ship_name')->where('is_deleted',0)->get();
        $cruiseItineraries = CruiseItinerary::orderBY('cruise_name')->where('is_deleted',0)->get();


        return view('reservations.reservationDetails', compact('users', 'reservation', 'isNewReservation', 'products', 'customers', 'destinations', 'resortShips','cruiseItineraries'));
    }

    public function edit(Reservation $reservation)
    {
        $users = User::select('id','fname', 'lname' ,'email')
                    ->where('isDeleted',0)
                    ->get();
        $isNewReservation = false;

        $customers = Customer::select('id','fname','lname','agent_id')->where('is_deleted',0)->get();

        $products = Product::orderBy('product_name')->where('is_deleted',0)->get();
        $destinations = Destination::orderBy('destination_name')->where('is_deleted',0)->get();
        $resortShips = ResortShip::orderBY('resort_ship_name')->where('is_deleted',0)->get();
        $cruiseItineraries = CruiseItinerary::orderBY('cruise_name')->where('is_deleted',0)->get();

        $availableForms = CustomersForm::where('is_deleted', 0)
                                        ->where('is_active', 1)
                                        ->whereHas('customersFormRequired', function($q){
                                            $q->where('all_reservations_required', 1);
                                        })->get();

        return view('reservations.reservationDetails', compact('users', 'reservation' ,'isNewReservation','products', 'customers', 'destinations', 'resortShips','cruiseItineraries','availableForms'));
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
            'product_id.required' => 'The Product field is required.',
            'destination_id.required' => 'The Destination field is required',
            'resort_id.required' => 'The Resort/Ship field is required.',
            'onboard_credit_from_cruise_line.numeric' => 'The OBC From Cruise Line field must be numeric and may contain 2 decimal points.',
            'onboard_credit_from_agent' => 'The OBC From Agent field must be numeric and may contain 2 decimal points.'
        ];

        $data = $request->validate([

            //  Required fields
            'agent_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'reservation_number' => 'required|string|max:255',
            'reservation_name' => 'required|string|max:255',
            'reservation_cost' => 'required|numeric',
            'agency_commission' => 'required|numeric',
            'status' => 'required|string|max:255',
            'product_id' => 'required|integer',
            'destination_id' => 'required|integer',
            'resort_id' => 'required|integer',

            //  Optional string fields
            'group_number' => 'nullable|string|max:50',
            'special_offer' => 'nullable|string|max:255',
            'celebrations' => 'nullable|string|max:255',
            'celebration_notes' => 'nullable|string|max:255',
            'room_category' => 'nullable|string|max:255',
            'stateroom_number' => 'nullable|string|max:255',
            'embarkation_port' => 'nullable|string|max:255',
            'ticket_types' => 'nullable|string|max:255',
            'dining_option' => 'nullable|string|max:255',
            'add_on_options' => 'nullable|string|max:255',
            'transportation_options' => 'nullable|string|max:255',
            'cruise_level' => 'nullable|string|max:255',

            //  Optional numeric (money)
            'agent_commission' => 'nullable|numeric',
            'onboard_credit_from_cruise_line' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],
            'onboard_credit_from_agent' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],

            //  Optional mediumtext / text
            'commission_notes' => 'nullable|string',
            'reservation_notes' => 'nullable|string',
            'flight_notes' => 'nullable|string',
            'notes' => 'nullable|string',

            //  Optional dates
            'checkin_date' => 'nullable|date',
            'checkout_date' => 'nullable|date',
            'deposit_due_date' => 'nullable|date',
            'final_payment_due_date' => 'nullable|date',
            'unknown_reservation_date' => 'nullable|date',

            //  Optional integers / flags
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

            //  Lead source flags
            'is_website_lead_knot' => 'nullable|integer',
            'is_website_lead' => 'nullable|integer',
            'is_virtuoso_lead' => 'nullable|integer',
            'is_luxury_magazine_lead' => 'nullable|integer',
            'is_facebook_lead' => 'nullable|integer',
            'is_instagram_lead' => 'nullable|integer',
            'is_radio_lead' => 'nullable|integer',

        ], $messages);

        //  Auto fields
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        Reservation::create($data);

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
            'product_id.required' => 'The Product field is required.',
            'destination_id.required' => 'The Destination field is required',
            'resort_id.required' => 'The Resort/Ship field is required.',
            'onboard_credit_from_cruise_line' => 'The OBC From Cruise Line field must be numeric and may contain 2 decimal points.',
            'onboard_credit_from_agent' => 'The OBC From Agent field must be numeric and may contain 2 decimal points.',
            'days_of_tickets.numeric' => 'Invalid',
        ];

        $data = $request->validate([
            //  Required fields
            'agent_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'reservation_number' => 'required|string|max:255',
            'reservation_name' => 'required|string|max:255',
            'reservation_cost' => 'required|numeric',
            'agency_commission' => 'required|numeric',
            'status' => 'required|string|max:255',
            'product_id' => 'required|integer',
            'destination_id' => 'required|integer',
            'resort_id' => 'required|integer',

            //  Optional string fields
            'group_number' => 'nullable|string|max:50',
            'special_offer' => 'nullable|string|max:255',
            'celebrations' => 'nullable|string|max:255',
            'celebration_notes' => 'nullable|string|max:255',
            'room_category' => 'nullable|string|max:255',
            'stateroom_number' => 'nullable|string|max:255',
            'embarkation_port' => 'nullable|string|max:255',
            'ticket_types' => 'nullable|string|max:255',
            'dining_option' => 'nullable|string|max:255',
            'add_on_options' => 'nullable|string|max:255',
            'transportation_options' => 'nullable|string|max:255',
            'cruise_level' => 'nullable|string|max:255',

            //  Optional numeric
            'agent_commission' => 'nullable|numeric',
            'onboard_credit_from_cruise_line' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],
            'onboard_credit_from_agent' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],

            //  Optional text
            'commission_notes' => 'nullable|string',
            'reservation_notes' => 'nullable|string',
            'flight_notes' => 'nullable|string',
            'notes' => 'nullable|string',

            //  Optional dates
            'checkin_date' => 'nullable|date',
            'checkout_date' => 'nullable|date',
            'deposit_due_date' => 'nullable|date',
            'final_payment_due_date' => 'nullable|date',
            'unknown_reservation_date' => 'nullable|date',

            // Optional integers / flags
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

            //  Lead flags
            'is_website_lead_knot' => 'nullable|integer',
            'is_website_lead' => 'nullable|integer',
            'is_virtuoso_lead' => 'nullable|integer',
            'is_luxury_magazine_lead' => 'nullable|integer',
            'is_facebook_lead' => 'nullable|integer',
            'is_instagram_lead' => 'nullable|integer',
            'is_radio_lead' => 'nullable|integer',
        ], $messages);

        // Always update last modified
        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $reservation->update($data);

        return redirect()
            ->route('reservations.reservationDetails', $reservation->id)
            ->with('success', 'Reservation updated successfully');
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

    public function storeTask(Request $request, Reservation $reservation)
    {
        $messages = [
            'task_name.required' => 'The Task Name field is required.',
            'due_date.required' => 'The Due Date field is required.',
            'priority.required' => 'The Priority field is required.',
        ];

        $data = $request->validate([
            'task_name' => 'required|string|max:255',
            'priority' => 'required|string',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ],$messages);

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationTask::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Task added successfully')
                ->with('activeTab', 'tasks');
    }

    public function toggleCompleteTask(ReservationTask $task)
    {
        $task->update([
            'is_completed' => $task->is_completed ? 0 : 1, 
            'is_completed_by' =>  auth()->id(),
            'is_completed_on' =>  now(),
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
            ->route('reservations.reservationDetails', $task->reservation_id)
            ->with('success', $task->is_completed ? 'Task marked incomplete' : 'Task completed')
            ->with('activeTab', 'tasks');
    }

    public function deleteTask(ReservationTask $task)
    {
        $reservationId = $task->reservation_id;

        $task->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Task deleted successfully')
                ->with('activeTab', 'tasks');
    }

    public function storeDiningNote(Request $request, Reservation $reservation)
    {
        $messages = [
            'notes.required' => 'The Note field is required.',
        ];

        $data = $request->validate([
            'dining_date' => 'nullable|date',
            'dining_time' => 'nullable|date_format:H:i',
            'meal' => 'nullable|string',
            'notes' => 'required|string',
        ],$messages);

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationDiningNote::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Task added successfully')
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

        $data = $request->validate([
            'gift_date' => 'required|date',
            'gift_type' => 'required|string',
            'amount' => 'required|integer',
            'notes' => 'nullable|string'
        ], $messages);

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationGift::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Gift added successfully')
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

        $data = $request->validate([
            'category' => 'nullable|string',
            'caller_name' => 'nullable|string',
            'caller_phone_number' => 'nullable|string',
            'notes' => 'required|string',
        ], $messages);

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationPhoneNote::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Phone Note added successfully')
                ->with('activeTab', 'phoneNotes');
    }

    public function toggleCancelPhoneNote(ReservationPhoneNote $phoneNote)
    {
        $phoneNote->update([
            'is_canceled' => $phoneNote->is_canceled ? 0 : 1,
            'canceled_by' => auth()->id(),
            'canceled_on' => now(),
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
                ->route('reservations.reservationDetails', $phoneNote->reservation_id)
                ->with('success', $phoneNote->is_canceled ? 'Phone Note marked as uncanceled' : 'Phone Note marked as canceled')
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

        $data = $request->validate([
            'fee_type' => 'required|string',
            'amount' => 'required|integer',
            'notes' => 'nullable|string',
        ], $messages);

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();
        
        ReservationCommissionFee::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Commission Fee added successfully')
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

        return redirect()
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Commission Fee deleted successfully')
                ->with('activeTab', 'agentPayments');
    }
}
