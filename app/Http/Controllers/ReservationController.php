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

class ReservationController extends Controller
{
    public function index(Request $request)
    {
       $status = $request->input('status', 'Active');

       $agentId = $request->input('users', auth()->id());

       $users = User::select('id','name', 'email')->get();

       $reservationsQuery = Reservation::with('agent', 'customer', 'product', 'destination')
                                        ->select('id', 'status', 'created_on', 'reservation_number', 'reservation_name', 'customer_id', 'agent_id', 'product_id', 'destination_id', 'checkin_date', 'final_payment_due_date')
                                        ->where('status',$status);

        if($agentId !=-1){
            $reservationsQuery->where('agent_id', $agentId);
        }

        $reservations = $reservationsQuery->orderBy('created_on', 'asc')->get(); 
        return view('reservations.reservationList', compact('users', 'reservations', 'status', 'agentId'));
    }

    public function create(Reservation $reservation)
    {
        $reservation = new Reservation();
        $users = User::select('id','name','email')->get();
        $isNewReservation = true;

        $customers = Customer::select('id','fname','lname','agent_id','email', 'cellphone')->get();

        $products = Product::orderBy('product_name')->get();
        $destinations = Destination::orderBy('destination_name')->get();
        $resortShips = ResortShip::orderBY('resort_ship_name')->get();
        $cruiseItineraries = CruiseItinerary::orderBY('cruise_name')->get();


        return view('reservations.reservationDetails', compact('users', 'reservation', 'isNewReservation', 'products', 'customers', 'destinations', 'resortShips','cruiseItineraries'));
    }

    public function edit(Reservation $reservation)
    {
        $users = User::select('id','name','email')->get();
        $isNewReservation = false;

        $customers = Customer::select('id','fname','lname','agent_id')->get();

        $products = Product::orderBy('product_name')->get();
        $destinations = Destination::orderBy('destination_name')->get();
        $resortShips = ResortShip::orderBY('resort_ship_name')->get();
        $cruiseItineraries = CruiseItinerary::orderBY('cruise_name')->get();

        return view('reservations.reservationDetails', compact('users', 'reservation' ,'isNewReservation','products', 'customers', 'destinations', 'resortShips','cruiseItineraries'));
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
            'resort_id.required' => 'The Resort/Ship field is required.'
        ];

        $data = $request->validate([

            //  Required fields
            'customer_id' => 'required|integer',
            'reservation_number' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'agent_id' => 'required|integer',

            //  Optional string fields
            'group_number' => 'nullable|string|max:50',
            'special_offer' => 'nullable|string|max:255',
            'reservation_name' => 'required|string|max:255',
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
            'reservation_cost' => 'required|numeric',
            'agency_commission' => 'required|numeric',
            'agent_commission' => 'nullable|numeric',
            'onboard_credit_from_cruise_line' => 'nullable|numeric',
            'onboard_credit_from_agent' => 'nullable|numeric',

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
            'product_id' => 'required|integer',
            'destination_id' => 'required|integer',
            'resort_id' => 'required|integer',
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
        $data = $request->validate([

            //  Required fields
            'customer_id' => 'required|integer',
            'reservation_number' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'agent_id' => 'required|integer',

            //  Optional string fields
            'group_number' => 'nullable|string|max:50',
            'special_offer' => 'nullable|string|max:255',
            'reservation_name' => 'nullable|string|max:255',
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
            'reservation_cost' => 'nullable|numeric',
            'agency_commission' => 'nullable|numeric',
            'agent_commission' => 'nullable|numeric',
            'onboard_credit_from_cruise_line' => 'nullable|numeric',
            'onboard_credit_from_agent' => 'nullable|numeric',

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
            'product_id' => 'nullable|integer',
            'destination_id' => 'nullable|integer',
            'resort_id' => 'nullable|integer',
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
        ]);

        // Always update last modified
        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $reservation->update($data);

        return redirect()
            ->route('reservations.reservationDetails', $reservation->id)
            ->with('success', 'Reservation updated successfully');
    }


}
