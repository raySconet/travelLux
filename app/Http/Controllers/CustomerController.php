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
                                    ->where('status', $status)
                                    ->where('is_deleted', 0);
      
        if($agentId != -1){
            $customersQuery->where('agent_id', $agentId);
        }

        $customers = $customersQuery->orderBy('lname', 'asc')->get();

        return view('customers.customerList', compact('users','customers','status','agentId'));
    }

    public function create(Customer $customer)
    {
        $customer = new Customer();
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
            // Required fields
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cellphone' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'status' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',


            // Optional string fields
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

            // Optional boolean flags (stored as varchar(1) F/T)
            'interests_all_inclusive' => 'nullable|in:T,F',
            'four_wheel_drive_expedition' => 'nullable|in:T,F',
            'adult_education' => 'nullable|in:T,F',
            'adventure' => 'nullable|in:T,F',
            'archaeology' => 'nullable|in:T,F',
            'art_theatre' => 'nullable|in:T,F',
            'ballooning' => 'nullable|in:T,F',
            'birding' => 'nullable|in:T,F',
            'climbing' => 'nullable|in:T,F',
            'cruise_barge' => 'nullable|in:T,F',
            'cruise_expedition' => 'nullable|in:T,F',
            'cruise_large_ship' => 'nullable|in:T,F',
            'cruise_river' => 'nullable|in:T,F',
            'cruise_small_ship' => 'nullable|in:T,F',
            'cycling' => 'nullable|in:T,F',
            'destination_weddings_romantic' => 'nullable|in:T,F',
            'diving' => 'nullable|in:T,F',
            'equestrian' => 'nullable|in:T,F',
            'escorted_tours' => 'nullable|in:T,F',
            'extreme_sports' => 'nullable|in:T,F',
            'family_adventures' => 'nullable|in:T,F',
            'family_vacations' => 'nullable|in:T,F',
            'fishing' => 'nullable|in:T,F',
            'food_wine' => 'nullable|in:T,F',
            'gaming' => 'nullable|in:T,F',
            'gardens' => 'nullable|in:T,F',
            'golf' => 'nullable|in:T,F',
            'health_fitness' => 'nullable|in:T,F',
            'hiking' => 'nullable|in:T,F',
            'history' => 'nullable|in:T,F',
            'honeymoon_anniversary' => 'nullable|in:T,F',
            'hotels' => 'nullable|in:T,F',
            'hunting' => 'nullable|in:T,F',
            'independent_traveler' => 'nullable|in:T,F',
            'indigenous_cultures' => 'nullable|in:T,F',
            'life_cycle_lgbtq' => 'nullable|in:T,F',
            'multi_generation_travel' => 'nullable|in:T,F',
            'music' => 'nullable|in:T,F',
            'naturalist' => 'nullable|in:T,F',
            'photography' => 'nullable|in:T,F',
            'private_jet' => 'nullable|in:T,F',
            'rafting_kayak' => 'nullable|in:T,F',
            'rail_travel' => 'nullable|in:T,F',
            'ranch_lodges' => 'nullable|in:T,F',
            'religious_tourism' => 'nullable|in:T,F',
            'resorts' => 'nullable|in:T,F',
            'safari' => 'nullable|in:T,F',
            'sailing' => 'nullable|in:T,F',
            'shopping' => 'nullable|in:T,F',
            'short_notice_travel' => 'nullable|in:T,F',
            'snow_sports' => 'nullable|in:T,F',
            'spas' => 'nullable|in:T,F',
            'sporting_events' => 'nullable|in:T,F',
            'sports_enthusiast' => 'nullable|in:T,F',
            'sun_sand' => 'nullable|in:T,F',
            'sustainable_tourism' => 'nullable|in:T,F',
            'tennis' => 'nullable|in:T,F',
            'trekking' => 'nullable|in:T,F',
            'walking' => 'nullable|in:T,F',
            'water_sports' => 'nullable|in:T,F',
            'wellness' => 'nullable|in:T,F',
            'wildlife' => 'nullable|in:T,F',
            'space_travel' => 'nullable|in:T,F',
            'north_america' => 'nullable|in:T,F',
            'northern_africa' => 'nullable|in:T,F',
            'canada' => 'nullable|in:T,F',
            'continental_us' => 'nullable|in:T,F',
            'alaska' => 'nullable|in:T,F',
            'hawaii' => 'nullable|in:T,F',
            'mexico' => 'nullable|in:T,F',
            'caribbean' => 'nullable|in:T,F',
            'central_america' => 'nullable|in:T,F',
            'panama_canal_voyages' => 'nullable|in:T,F',
            'south_america' => 'nullable|in:T,F',
            'galapagos_islands' => 'nullable|in:T,F',
            'europe' => 'nullable|in:T,F',
            'western_europe' => 'nullable|in:T,F',
            'france' => 'nullable|in:T,F',
            'germany' => 'nullable|in:T,F',
            'greece' => 'nullable|in:T,F',
            'italy' => 'nullable|in:T,F',
            'mediterranean_region' => 'nullable|in:T,F',
            'spain' => 'nullable|in:T,F',
            'turkey' => 'nullable|in:T,F',
            'uk_ireland' => 'nullable|in:T,F',
            'eastern_europe' => 'nullable|in:T,F',
            'russia' => 'nullable|in:T,F',
            'scandinavia' => 'nullable|in:T,F',
            'seychelles_mauitius_maldives' => 'nullable|in:T,F',
            'africa' => 'nullable|in:T,F',
            'bermuda' => 'nullable|in:T,F',
            'costa_rica' => 'nullable|in:T,F',
            'cuba' => 'nullable|in:T,F',
            'egypt' => 'nullable|in:T,F',
            'middle_east' => 'nullable|in:T,F',
            'australia' => 'nullable|in:T,F',
            'new_zealand' => 'nullable|in:T,F',
            'south_pacific' => 'nullable|in:T,F',
            'asia' => 'nullable|in:T,F',
            'south_east_asia' => 'nullable|in:T,F',
            'hong_kong' => 'nullable|in:T,F',
            'pacific_rim_japan' => 'nullable|in:T,F',
            'china' => 'nullable|in:T,F',
            'india' => 'nullable|in:T,F',
            'antarctica' => 'nullable|in:T,F',
            'arctic' => 'nullable|in:T,F',
            'polar_regions' => 'nullable|in:T,F',
            'portugal' => 'nullable|in:T,F',
            'trans_atlantic_voyages' => 'nullable|in:T,F',
            'us_new_england' => 'nullable|in:T,F',
            'us_northwest' => 'nullable|in:T,F',
            'us_southeast_fl' => 'nullable|in:T,F',
            'us_southwest' => 'nullable|in:T,F',
            'us_west_ca' => 'nullable|in:T,F',
            'world_grand_voyages' => 'nullable|in:T,F',
            'direct_mail_marketing_permission' => 'nullable|in:T,F',
            'email_marketing_permission' => 'nullable|in:T,F',

            // Optional int fields
            'retired' => 'nullable|integer',
            'children_at_home' => 'nullable|integer',
            'virtuoso_life' => 'nullable|integer',
            'customer_referral' => 'nullable|integer',
            'is_deleted' => 'nullable|integer',
            'is_website_lead_knot' => 'nullable|integer',
            'is_website_lead' => 'nullable|integer',
            'is_virtuoso_lead' => 'nullable|integer',
            'is_luxury_magazine_lead' => 'nullable|integer',
            'is_facebook_lead' => 'nullable|integer',
            'is_instagram_lead' => 'nullable|integer',
            'is_radio_lead' => 'nullable|integer',

            // Optional date fields
            'birth_date' => 'nullable|date',
            'anniversary_date' => 'nullable|date',
            'last_modified_on' => 'nullable|date',
        ], $messages);

        $data['agent_id'] = auth()->id();
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        Customer::create($data);

        return redirect()
            ->route('customers.customerList')
            ->with('success', 'Customer created successfully');
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            // Required fields
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cellphone' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'status' => 'nullable|string|max:255',

            // Optional string fields
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
            'city' => 'nullable|string|max:255',
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

            // Optional boolean flags (stored as varchar(1) F/T)
            'interests_all_inclusive' => 'nullable|in:T,F',
            'four_wheel_drive_expedition' => 'nullable|in:T,F',
            'adult_education' => 'nullable|in:T,F',
            'adventure' => 'nullable|in:T,F',
            'archaeology' => 'nullable|in:T,F',
            'art_theatre' => 'nullable|in:T,F',
            'ballooning' => 'nullable|in:T,F',
            'birding' => 'nullable|in:T,F',
            'climbing' => 'nullable|in:T,F',
            'cruise_barge' => 'nullable|in:T,F',
            'cruise_expedition' => 'nullable|in:T,F',
            'cruise_large_ship' => 'nullable|in:T,F',
            'cruise_river' => 'nullable|in:T,F',
            'cruise_small_ship' => 'nullable|in:T,F',
            'cycling' => 'nullable|in:T,F',
            'destination_weddings_romantic' => 'nullable|in:T,F',
            'diving' => 'nullable|in:T,F',
            'equestrian' => 'nullable|in:T,F',
            'escorted_tours' => 'nullable|in:T,F',
            'extreme_sports' => 'nullable|in:T,F',
            'family_adventures' => 'nullable|in:T,F',
            'family_vacations' => 'nullable|in:T,F',
            'fishing' => 'nullable|in:T,F',
            'food_wine' => 'nullable|in:T,F',
            'gaming' => 'nullable|in:T,F',
            'gardens' => 'nullable|in:T,F',
            'golf' => 'nullable|in:T,F',
            'health_fitness' => 'nullable|in:T,F',
            'hiking' => 'nullable|in:T,F',
            'history' => 'nullable|in:T,F',
            'honeymoon_anniversary' => 'nullable|in:T,F',
            'hotels' => 'nullable|in:T,F',
            'hunting' => 'nullable|in:T,F',
            'independent_traveler' => 'nullable|in:T,F',
            'indigenous_cultures' => 'nullable|in:T,F',
            'life_cycle_lgbtq' => 'nullable|in:T,F',
            'multi_generation_travel' => 'nullable|in:T,F',
            'music' => 'nullable|in:T,F',
            'naturalist' => 'nullable|in:T,F',
            'photography' => 'nullable|in:T,F',
            'private_jet' => 'nullable|in:T,F',
            'rafting_kayak' => 'nullable|in:T,F',
            'rail_travel' => 'nullable|in:T,F',
            'ranch_lodges' => 'nullable|in:T,F',
            'religious_tourism' => 'nullable|in:T,F',
            'resorts' => 'nullable|in:T,F',
            'safari' => 'nullable|in:T,F',
            'sailing' => 'nullable|in:T,F',
            'shopping' => 'nullable|in:T,F',
            'short_notice_travel' => 'nullable|in:T,F',
            'snow_sports' => 'nullable|in:T,F',
            'spas' => 'nullable|in:T,F',
            'sporting_events' => 'nullable|in:T,F',
            'sports_enthusiast' => 'nullable|in:T,F',
            'sun_sand' => 'nullable|in:T,F',
            'sustainable_tourism' => 'nullable|in:T,F',
            'tennis' => 'nullable|in:T,F',
            'trekking' => 'nullable|in:T,F',
            'walking' => 'nullable|in:T,F',
            'water_sports' => 'nullable|in:T,F',
            'wellness' => 'nullable|in:T,F',
            'wildlife' => 'nullable|in:T,F',
            'space_travel' => 'nullable|in:T,F',
            'north_america' => 'nullable|in:T,F',
            'northern_africa' => 'nullable|in:T,F',
            'canada' => 'nullable|in:T,F',
            'continental_us' => 'nullable|in:T,F',
            'alaska' => 'nullable|in:T,F',
            'hawaii' => 'nullable|in:T,F',
            'mexico' => 'nullable|in:T,F',
            'caribbean' => 'nullable|in:T,F',
            'central_america' => 'nullable|in:T,F',
            'panama_canal_voyages' => 'nullable|in:T,F',
            'south_america' => 'nullable|in:T,F',
            'galapagos_islands' => 'nullable|in:T,F',
            'europe' => 'nullable|in:T,F',
            'western_europe' => 'nullable|in:T,F',
            'france' => 'nullable|in:T,F',
            'germany' => 'nullable|in:T,F',
            'greece' => 'nullable|in:T,F',
            'italy' => 'nullable|in:T,F',
            'mediterranean_region' => 'nullable|in:T,F',
            'spain' => 'nullable|in:T,F',
            'turkey' => 'nullable|in:T,F',
            'uk_ireland' => 'nullable|in:T,F',
            'eastern_europe' => 'nullable|in:T,F',
            'russia' => 'nullable|in:T,F',
            'scandinavia' => 'nullable|in:T,F',
            'seychelles_mauitius_maldives' => 'nullable|in:T,F',
            'africa' => 'nullable|in:T,F',
            'bermuda' => 'nullable|in:T,F',
            'costa_rica' => 'nullable|in:T,F',
            'cuba' => 'nullable|in:T,F',
            'egypt' => 'nullable|in:T,F',
            'middle_east' => 'nullable|in:T,F',
            'australia' => 'nullable|in:T,F',
            'new_zealand' => 'nullable|in:T,F',
            'south_pacific' => 'nullable|in:T,F',
            'asia' => 'nullable|in:T,F',
            'south_east_asia' => 'nullable|in:T,F',
            'hong_kong' => 'nullable|in:T,F',
            'pacific_rim_japan' => 'nullable|in:T,F',
            'china' => 'nullable|in:T,F',
            'india' => 'nullable|in:T,F',
            'antarctica' => 'nullable|in:T,F',
            'arctic' => 'nullable|in:T,F',
            'polar_regions' => 'nullable|in:T,F',
            'portugal' => 'nullable|in:T,F',
            'trans_atlantic_voyages' => 'nullable|in:T,F',
            'us_new_england' => 'nullable|in:T,F',
            'us_northwest' => 'nullable|in:T,F',
            'us_southeast_fl' => 'nullable|in:T,F',
            'us_southwest' => 'nullable|in:T,F',
            'us_west_ca' => 'nullable|in:T,F',
            'world_grand_voyages' => 'nullable|in:T,F',
            'direct_mail_marketing_permission' => 'nullable|in:T,F',
            'email_marketing_permission' => 'nullable|in:T,F',

            // Optional int fields
            'retired' => 'nullable|integer',
            'children_at_home' => 'nullable|integer',
            'virtuoso_life' => 'nullable|integer',
            'customer_referral' => 'nullable|integer',
            'agent_id' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'is_deleted' => 'nullable|integer',
            'is_website_lead_knot' => 'nullable|integer',
            'is_website_lead' => 'nullable|integer',
            'is_virtuoso_lead' => 'nullable|integer',
            'is_luxury_magazine_lead' => 'nullable|integer',
            'is_facebook_lead' => 'nullable|integer',
            'is_instagram_lead' => 'nullable|integer',
            'is_radio_lead' => 'nullable|integer',

            // Optional date fields
            'birth_date' => 'nullable|date',
            'anniversary_date' => 'nullable|date',
            'created_on' => 'nullable|date',
            'last_modified_on' => 'nullable|date',
        ]);

        // Always set last_modified
        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $customer->update($data);

        return redirect()
            ->route('customers.customerDetails', $customer->id)
            ->with('success', 'Customer updated successfully');
    }

    public function destroy(Customer $customer)
    {
        // Soft-delete by setting is_deleted = 1
        $customer->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
            ->route('customers.customerList')
            ->with('success', 'Customer deleted successfully');
    }
}
