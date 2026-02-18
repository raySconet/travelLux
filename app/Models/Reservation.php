<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';

    protected $primaryKey = 'id';

    protected $fillable = [
        'customer_id',
        'reservation_number',
        'group_number',
        'special_offer',
        'reservation_name',
        'status',
        'paid_in_full_date',
        'celebrations',
        'celebration_notes',
        'checkin_date',
        'checkout_date',
        'reservation_cost',
        'agency_commission',
        'agent_commission',
        'commission_received',
        'commission_claimed',
        'look_up',
        'document_fee',
        'agent_commission_percentage',
        'commission_notes',
        'commission_to_agent',
        'non_commissionable',
        'is_surprise',
        'agent_personal_travel',
        'is_website_lead_knot',
        'is_website_lead',
        'is_virtuoso_lead',
        'is_luxury_magazine_lead',
        'is_facebook_lead',
        'is_instagram_lead',
        'is_radio_lead',
        'secondary_agent',
        'product_id',
        'destination_id',
        'resort_id',
        'cruise_itinerary_id',
        'reservation_notes',
        'room_category',
        'stateroom_number',
        'embarkation_port',
        'days_of_tickets',
        'ticket_types',
        'dining_option',
        'add_on_options',
        'transportation_options',
        'cruise_level',
        'concierge_ship',
        'club_level_resort',
        'submitted_to_rewards',
        'deposit_due_date',
        'final_payment_due_date',
        'onboard_credit_from_cruise_line',
        'onboard_credit_from_agent',
        'flight_notes',
        'notes',
        'agent_commission_received',
        'mentor_commission_received',
        'remove_mentor_commission',
        'unknown_reservation_date',
        'unknown_reservation_checked_flag',
        'stop_auto_emails',
        'radio_station_ads',
        'agent_id',
        'itinerary_trip_id',
        'created_by',
        'created_on',
        'last_modified_by',
        'last_modified_on',
        'is_deleted' 
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id'); 
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id'); 
    }
}
