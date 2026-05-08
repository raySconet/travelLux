<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryEvent extends Model
{
    protected $table = 'itinerary_events';

    protected $primaryKey = 'id';

    protected $fillable = [
        'eventType',
        'eventTime',
        'itineraryEventFormActivitySubcategory',
        'itineraryEventFormLodgingSubcategory',
        'itineraryEventFormFlightSubCategory',
        'itineraryTransportationFormSubCategory',
        'itineraryCruiseFormSubCategory',
        'itineraryEventFormInfoSubCategory',
        'itineraryEventDayId',
        'itineraryActivityFormTitle',
        'itineraryActivityFormNote',
        'itineraryActivityFormBookedThrough',
        'itineraryActivityFormConfirmation',
        'itineraryActivityFormProvider',
        'itineraryActivityFormTime',
        'itineraryActivityFormDate',
        'itineraryActivityFormDuration',
        'itineraryActivityFormTimezone',
        'itineraryActivityFormAmount',
        'itineraryActivityFormCurrency', 
        'itineraryLodgingFormTitle',
        'itineraryLodgingFormNote',
        'itineraryLodgingFormBookedThrough',
        'itineraryLodgingFormConfirmation',
        'itineraryLodgingFormRoomBedType',
        'itineraryLodgingFormTime',
        'itineraryLodgingFormDate',
        'itineraryLodgingFormDuration',
        'itineraryLodgingFormTimezone', 
        'itineraryLodgingFormAmount',
        'itineraryLodgingFormAmountCurrency',
        'itineraryFlightFormTitle',
        'itineraryFlightFormNote',
        'itineraryFlightFormTime',
        'itineraryFlightFormDate',
        'itineraryFlightFormDuration',
        'itineraryFlightFormTimezone',
        'itineraryFlightFormBookedThrough',
        'itineraryFlightFormConfirmation',
        'itineraryFlightFormAirline',
        'itineraryFlightFormFlightNumber',
        'itineraryFlightFormTerminal',
        'itineraryFlightFormGate',
        'itineraryFlightFormSeatTicketDetails',
        'itineraryFlightFormAmount',
        'itineraryFlightFormAmountCurrency',
        'itineraryTransportationFormTitle',
        'itineraryTransportationFormNote',
        'itineraryTransportationFormTime',
        'itineraryTransportationFormDate',
        'itineraryTransportationFormDuration',
        'itineraryTransportationFormTimezone',
        'itineraryTransportationFormBookedThrough',
        'itineraryTransportationFormConfirmation',
        'itineraryTransportationFormCarrier',
        'itineraryTransportationFormTransportationNumber',
        'itineraryTransportationFormAmount', 
        'itineraryTransportationFormAmountCurrency',
        'itineraryCruiseFormTitle',
        'itineraryCruiseFormNote',
        'itineraryCruiseFormTime',
        'itineraryCruiseFormDate',
        'itineraryCruiseFormDuration',
        'itineraryCruiseFormTimezone',
        'itineraryCruiseFormBookedThrough',
        'itineraryCruiseFormConfirmation',
        'itineraryCruiseFormCarrier',
        'itineraryCruiseFormCabinType',
        'itineraryCruiseFormCabinNumber',
        'itineraryCruiseFormAmount',
        'itineraryCruiseFormAmountCurrency',
        'itineraryInfoFormTitle',
        'itineraryInfoFormNote',
        'itineraryInfoFormTime',
        'itineraryInfoFormImage',
        'itineraryInfoFormImageExtension',
        'isDeleted' 
    ];

    public function day()
    {
        return $this->belongsTo(ItineraryDay::class, 'itineraryEventDayId', 'id');
    }
}
