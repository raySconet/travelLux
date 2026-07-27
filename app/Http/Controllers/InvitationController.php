<?php

namespace App\Http\Controllers;

use App\Models\CustomerInvitation;
use App\Models\Customer;
use App\Models\State;
use App\Models\Country;
use App\Models\CustomerFamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class InvitationController extends Controller
{
    public function show($token)
    {
        $decoded = base64_decode($token);

        $invitationId = str_replace(config('app.invitation_salt'),'',$decoded);

        if (!is_numeric($invitationId)) {
            abort(404, 'Error Occurred. Please try again later. (Error Code: 300)');
        }

        $invitation = CustomerInvitation::find($invitationId);

        if (!$invitation) {
            abort(404, 'Error Occurred. Please try again later. (Error Code: 301)');
        }

        if ($invitation->expired_flag == 0 && $invitation->created_at->addDay()->isPast()) {
            $invitation->update([
                'expired_flag' => 1,
            ]);
        }

        if ($invitation->status === 'C') {
            abort(403, 'This link has been canceled, please contact your travel agent for a new link.');
        }

        if ($invitation->expired_flag == 1) {
            abort(403, 'This link has expired, please contact your travel agent for a new link.');
        }

        $customer = Customer::find($invitation->customer_id);

        if (!$customer) {
            abort(404, 'Error Occurred. Please try again later. (Error Code: 302)');
        }

        $states = State::orderBy('name')->get();

        $countries = Country::orderBy('name')->get();

        $familyMembers = CustomerFamilyMember::where('customer_id', $customer->id)->where('is_deleted', 0)->get();

        $interests = [];

        $places = [];

        return view('invitations.customerInvitationForm', [
            'customer' => $customer,
            'states' => $states,
            'countries' => $countries,
            'familyMembers' => $familyMembers,
            'interests' => $interests,
            'places' => $places,
            'encryptedInvitationId' => $token,
            'invitation' => $invitation,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email',
            'cellphone' => 'required',
            'address_line1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required',
        ]);

        DB::transaction(function () use ($request) {

            $customer = Customer::findOrFail($request->customer_id);

            $checkboxFields = [
                'interests_all_inclusive',
                'four_wheel_drive_expedition',
                'adult_education',
                'adventure',
                'archaeology',
                'art_theatre',
                'ballooning',
                'birding',
                'climbing',
                'cruise_barge',
                'cruise_expedition',
                'cruise_large_ship',
                'cruise_river',
                'cruise_small_ship',
                'cycling',
                'destination_weddings_romantic',
                'diving',
                'equestrian',
                'escorted_tours',
                'extreme_sports',
                'family_adventures',
                'family_vacations',
                'fishing',
                'food_wine',
                'gaming',
                'gardens',
                'golf',
                'health_fitness',
                'hiking',
                'history',
                'honeymoon_anniversary',
                'hotels',
                'hunting',
                'independent_traveler',
                'indigenous_cultures',
                'life_cycle_lgbtq',
                'multi_generation_travel',
                'music',
                'naturalist',
                'photography',
                'private_jet',
                'rafting_kayak',
                'rail_travel',
                'ranch_lodges',
                'religious_tourism',
                'resorts',
                'safari',
                'sailing',
                'shopping',
                'short_notice_travel',
                'snow_sports',
                'space_travel',
                'spas',
                'sporting_events',
                'sports_enthusiast',
                'sun_sand',
                'sustainable_tourism',
                'tennis',
                'trekking',
                'walking',
                'water_sports',
                'wellness',
                'wildlife',

                'north_america',
                'northern_africa',
                'canada',
                'continental_us',
                'alaska',
                'hawaii',
                'mexico',
                'caribbean',
                'central_america',
                'panama_canal_voyages',
                'south_america',
                'galapagos_islands',
                'europe',
                'western_europe',
                'france',
                'germany',
                'greece',
                'italy',
                'mediterranean_region',
                'spain',
                'turkey',
                'uk_ireland',
                'eastern_europe',
                'russia',
                'scandinavia',
                'seychelles_mauitius_maldives',
                'africa',
                'bermuda',
                'costa_rica',
                'cuba',
                'egypt',
                'middle_east',
                'australia',
                'new_zealand',
                'south_pacific',
                'asia',
                'south_east_asia',
                'hong_kong',
                'pacific_rim_japan',
                'china',
                'india',
                'antarctica',
                'arctic',
                'polar_regions',
                'portugal',
                'trans_atlantic_voyages',
                'us_new_england',
                'us_northwest',
                'us_southeast_fl',
                'us_southwest',
                'us_west_ca',
                'world_grand_voyages',

                'direct_mail_marketing_permission',
                'email_marketing_permission',
            ];

            $checkboxData = [];

            foreach ($checkboxFields as $field) {
                $checkboxData[$field] = $request->boolean($field) ? 'T' : 'F';
            }

            $customer->update(array_merge([

                'fname' => $request->fname,
                'mname' => $request->mname,
                'lname' => $request->lname,
                'nickname' => $request->nickname,
                'email' => $request->email,
                'secondary_email' => $request->secondary_email,
                'cellphone' => $request->cellphone,
                'home_phone' => $request->home_phone,
                'work_phone' => $request->work_phone,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'anniversary_date' => $request->anniversary_date,
                'special_notes' => $request->special_notes,

                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country,

                'status' => 'Active',

                'retired' => $request->boolean('retired'),
                'children_at_home' => $request->boolean('children_at_home'),

            ], $checkboxData));

            $decoded = base64_decode($request->encryptedInvitationId);

            $invitationId = str_replace(config('app.invitation_salt'),'',$decoded);

            $invitation = CustomerInvitation::find($invitationId);

            if ($invitation) {
                $invitation->update([
                    'status' => 'S',
                    'submit_flag' => 1,
                    'submitted_on' => now(),
                ]);
            }

            $customer->load('agent');

            if ($customer->agent?->email) {

                Mail::raw(
                    "Your client {$customer->fname} {$customer->lname} filled in the information on the CRM.",
                    function ($message) use ($customer) {
                        $message
                            ->to($customer->agent->email)
                            ->subject('Client Information - CRM');
                    }
                );
            }
        });

        return response()->json([
            'success' => true
        ]);
    }
}