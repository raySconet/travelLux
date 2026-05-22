<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>{{ $itinerary->name }}</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            color:#212121;
            font-size:13px;
            margin:30px;
        }

        .top-section{
            text-align:center;
            margin-bottom:35px;
        }

        .logo{
            width:180px;
            margin-bottom:15px;
        }

        .trip-title{
            font-size:28px;
            font-weight:bold;
            color:#2C3E50;
            text-align: left;
        }

        .trip-date{
            font-size:14px;
            color:#777;
            margin-top:5px;
            text-align:left;
        }

        .day-block{
            margin-top:35px;
        }

        .day-header{
            border-bottom:2px solid #B6844A;
            padding-bottom:10px;
            margin-bottom:20px;
        }

        .day-number{
            font-size:24px;
            font-weight:bold;
            color:#B6844A;
        }

        .day-date{
            font-size:15px;
            color:#666;
            margin-top:4px;
        }

        .day-title{
            font-size:18px;
            margin-top:6px;
            color:#2C3E50;
        }

        .event-card{
            border:1px solid #ccc;
            padding:18px;
            margin-bottom:18px;
            border-radius:6px;
        }

        .event-top{
            width:100%;
            margin-bottom:12px;
        }

        .event-header{
            width:100%;
        }

        .event-header-table{
            width:100%;
            border-collapse:collapse;
        }

        .event-header-left{
            text-align:left;
            vertical-align:middle;
        }

        .event-header-right{
            text-align:right;
            vertical-align:middle;
        }

        .event-icon{
            width:22px;
            height:22px;
            margin-right:6px;
            vertical-align:middle;
        }

        .event-label{
            font-size:13px;
            letter-spacing:1px;
            vertical-align:middle;
            color:#2C3E50;
            font-weight: 300;
        }

        .event-time{
            font-size:14px;
            font-weight:bold;
            color:#B6844A;
        }

        .event-title{
            font-size:22px;
            margin-bottom:10px;
        }

        .event-note{
            font-size:14px;
            margin-bottom:15px;
        }

        .divider{
            border-top:1px solid #eee;
            margin:15px 0;
        }

        .details{
            width:100%;
        }

        .details td{
            width:25%;
            vertical-align:top;
            padding-right:10px;
            padding-top:8px;
            font-size: 85%;
        }

        .detail-title{
            font-weight:bold;
            margin-bottom:4px;
            color:#888
        }

        .agent-card{
            border:1px solid #ccc;
            border-radius:10px;
            padding:18px;
            margin-top:25px;
            text-align:left;
            position:relative;
        }

        .agent-name{
            font-size:18px;
            font-weight:bold;
            color:#000;
        }

        .agent-company{
            font-size:14px;
            color:#000;
            margin-top:2px;
        }

        .agent-contact-box{
            border:1px solid #ccc;
            border-radius:10px;
            padding:14px;
            margin-top:18px;
        }

        .agent-contact-title{
            font-size:16px;
            font-weight:bold;
            margin-bottom:18px;
            color:#2C3E50;
        }

        .contact-row{
            margin-bottom:14px;
        }

        .contact-label{
            display:inline-block;
            width:24px;
            height:24px;
            border:1px solid #B6844A;
            text-align:center;
            line-height:24px;
            margin-right:8px;
            color:#000;
            font-weight:bold;
        }

        .contact-text{
            font-size:14px;
            color:#B6844A;
        }

        .agent-logo{
            width:150px;
            position:absolute;
            top:20px;
            right:20px;
        }

    </style>
</head>

<body>

    <div class="top-section">

        <img src="{{ public_path('images/archer-logo.png') }}" class="logo">

        <div class="trip-title text-left">
            Trip to {{ $itinerary->name }}
        </div>

        <div class="trip-date">
            {{ \Carbon\Carbon::parse($itinerary->date)->format('F d, Y') }}
        </div>

        <div class="agent-card">

            <img src="{{ public_path('images/archer-logo.png') }}" class="agent-logo">

            <div class="agent-name">
                {{ $itinerary->creator?->fname }}
                {{ $itinerary->creator?->lname }}
            </div>

            <div class="agent-company">
                Archer Luxury Travel
            </div>

            <div class="agent-contact-box">

                <div class="agent-contact-title">
                    How to reach me
                </div>

                <div class="contact-row">
                    <span class="contact-label">
                        <span style="font-size:16px; display:inline-block; width:24px; text-align:center;margin-top:-5px;">
                            ☎
                        </span>
                    </span>

                    <span class="contact-text">
                        {{ $itinerary->creator?->phone_number ?? '' }}
                    </span>
                </div>

                <div class="contact-row">
                    <span class="contact-label" style="font-size:20px; display:inline-block; width:24px; text-align:center; margin-top:-8px;">
                        ✉
                    </span>

                    <span class="contact-text">
                        {{ $itinerary->creator?->email }}
                    </span>
                </div>

            </div>

        </div>
    </div>

    @foreach($itinerary->itineraryDays as $day)

        <div class="day-block">

            <div class="day-header">

                <div class="day-number">
                    Day {{ $day->dayNumber }}
                </div>

                @if($day->dayDate)
                    <div class="day-date">
                        {{ \Carbon\Carbon::parse($day->dayDate)->format('F d, Y') }}
                    </div>
                @endif

                @if($day->dayTitle)
                    <div class="day-title">
                        {{ $day->dayTitle }}
                    </div>
                @endif

            </div>

            @foreach($day->events as $event)

                @php
                    $details = [];
                    $formattedTime = '';

                    if($event->eventTime && $event->eventTime != '-1'){

                        $formattedTime = \Carbon\Carbon::parse($event->eventTime)
                            ->format('g:i A');
                    }

                    $eventTitle = '';
                    $eventNote = '';
                    $bookedThrough = '';
                    $confirmation = '';
                    $provider = '';
                    $price = '';

                    $eventClass = 'activity-color';

                    $eventLabel = 'EVENT';
                    $eventIcon = public_path('images/itinerary/icons/default.png');
                @endphp

                @if($event->eventType == 1)

                    @php
                        $detailsTop = [];
                        $detailsBottom = [];
                        $eventTitle = $event->itineraryActivityFormTitle;
                        $eventNote = $event->itineraryActivityFormNote;

                        $detailsTop[] = [
                            'title' => 'BOOKED THROUGH',
                            'value' => $event->itineraryActivityFormBookedThrough
                        ];

                        $detailsTop[] = [
                            'title' => 'CONFIRMATION',
                            'value' => $event->itineraryActivityFormConfirmation
                        ];

                        $detailsTop[] = [
                            'title' => 'PROVIDER',
                            'value' => $event->itineraryActivityFormProvider
                        ];

                        $detailsTop[] = [
                            'title' => 'PRICE',
                            'value' => ($event->itineraryActivityFormCurrency ?? '') . ' ' . ($event->itineraryActivityFormAmount ?? '')
                        ];

                        if($event->itineraryEventFormActivitySubcategory == 1){

                            $eventLabel = 'ACTIVITY';
                            $eventIcon = public_path('images/itinerary/icons/activity-activity.png');

                        } elseif($event->itineraryEventFormActivitySubcategory == 2){

                            $eventLabel = 'FOOD & DRINK';
                            $eventIcon = public_path('images/itinerary/icons/activity-food-drink.png');

                            $eventClass = 'food-color';
                        }
                    @endphp
                
                @endif

                @if($event->eventType == 2)

                    @php
                        $detailsTop = [];
                        $detailsBottom = [];
                        $eventTitle = $event->itineraryLodgingFormTitle;
                        $eventNote = $event->itineraryLodgingFormNote;

                        $detailsTop[] = [
                            'title' => 'BOOKED THROUGH',
                            'value' => $event->itineraryLodgingFormBookedThrough
                        ];

                        $detailsTop[] = [
                            'title' => 'CONFIRMATION',
                            'value' => $event->itineraryLodgingFormConfirmation
                        ];

                        $detailsTop[] = [
                            'title' => 'ROOM/BED TYPE',
                            'value' => $event->itineraryLodgingFormRoomBedType
                        ];

                        $detailsTop[] = [
                            'title' => 'PRICE',
                            'value' => ($event->itineraryLodgingFormAmountCurrency ?? '') . ' ' . ($event->itineraryLodgingFormAmount ?? '')
                        ];

                        if($event->itineraryEventFormLodgingSubcategory == 1){

                            $eventLabel = 'LODGING: CHECK-IN';
                            $eventIcon = public_path('images/itinerary/icons/lodging.png');

                        } elseif($event->itineraryEventFormLodgingSubcategory == 2){

                            $eventLabel = 'LODGING: CHECK-OUT';
                            $eventIcon = public_path('images/itinerary/icons/lodging.png');

                            $eventClass = 'food-color';
                        }
                    @endphp

                @endif

                @if($event->eventType == 3)

                    @php
                        $detailsTop = [];
                        $detailsBottom = [];
                        $eventTitle = $event->itineraryFlightFormTitle;
                        $eventNote = $event->itineraryFlightFormNote;

                        $detailsTop[] = [
                            'title' => 'BOOKED THROUGH',
                            'value' => $event->itineraryFlightFormBookedThrough
                        ];

                        $detailsTop[] = [
                            'title' => 'CONFIRMATION',
                            'value' => $event->itineraryFlightFormConfirmation
                        ];

                        $detailsTop[] = [
                            'title' => 'ARILINE',
                            'value' => $event->itineraryFlightFormAirline
                        ];

                        $detailsTop[] = [
                            'title' => 'FLIGHT NUMBER',
                            'value' => $event->itineraryFlightFormFlightNumber
                        ];

                        $detailsBottom[] = [
                            'title' => 'GATE',
                            'value' => $event->itineraryFlightFormGate
                        ];

                        $detailsBottom[] = [
                            'title' => 'TERMINAL',
                            'value' => $event->itineraryFlightFormTerminal
                        ];

                        $detailsBottom[] = [
                            'title' => 'SEAT/TICKET DETAILS',
                            'value' => $event->itineraryFlightFormSeatTicketDetails
                        ];

                        $detailsBottom[] = [
                            'title' => 'PRICE',
                            'value' => ($event->itineraryFlightFormAmountCurrency ?? '') . ' ' . ($event->itineraryFlightFormAmount ?? '')
                        ];

                        if($event->itineraryEventFormFlightSubCategory == 1){

                            $eventLabel = 'FLIGHT: DEPARTURE';
                            $eventIcon = public_path('images/itinerary/icons/flight.png');

                        } elseif($event->itineraryEventFormFlightSubCategory == 2){

                            $eventLabel = 'FLIGHT: ARRIVAL';
                            $eventIcon = public_path('images/itinerary/icons/flight.png');

                        } 
                    @endphp

                @endif

                @if($event->eventType == 4)

                    @php
                        $detailsTop = [];
                        $detailsBottom = [];
                        $eventTitle = $event->itineraryTransportationFormTitle;
                        $eventNote = $event->itineraryTransportationFormNote;

                        $detailsTop[] = [
                            'title' => 'BOOKED THROUGH',
                            'value' => $event->itineraryTransportationFormBookedThrough
                        ];

                        $detailsTop[] = [
                            'title' => 'CONFIRMATION',
                            'value' => $event->itineraryTransportationFormConfirmation
                        ];

                        $detailsTop[] = [
                            'title' => 'CARRIER',
                            'value' => $event->itineraryTransportationFormCarrier
                        ];


                        $detailsBottom[] = [
                            'title' => 'TRANSPORTATION NUMBER',
                            'value' => $event->itineraryTransportationFormTransportationNumber
                        ];


                        $detailsBottom[] = [
                            'title' => 'PRICE',
                            'value' => ($event->itineraryTransportationFormAmountCurrency ?? '') . ' ' .($event->itineraryTransportationFormAmount ?? '')
                        ];

                        if($event->itineraryTransportationFormSubCategory == 1){

                            $eventLabel = 'TRANSPORTATION: DEPARTURE';
                            $eventIcon = public_path('images/itinerary/icons/transportation.png');

                        } elseif($event->itineraryTransportationFormSubCategory == 2){

                            $eventLabel = 'TRANSPORTATION: ARRIVAL';
                            $eventIcon = public_path('images/itinerary/icons/transportation.png');

                        } 
                    @endphp

                @endif

                @if($event->eventType == 5)

                    @php
                        $detailsTop = [];
                        $detailsBottom = [];
                        $eventTitle = $event->itineraryCruiseFormTitle;
                        $eventNote = $event->itineraryCruiseFormNote;

                        $detailsTop[] = [
                            'title' => 'CABIN NUMBER',
                            'value' => $event->itineraryCruiseFormCabinNumber
                        ];

                        $detailsTop[] = [
                            'title' => 'CABIN TYPE',
                            'value' => $event->itineraryCruiseFormCabinType
                        ];

                        $detailsTop[] = [
                            'title' => 'CARRIE',
                            'value' => $event->itineraryCruiseFormCarrier
                        ];

                        $detailsTop[] = [
                            'title' => 'CONFIRMATION',
                            'value' => $event->itineraryCruiseFormConfirmation
                        ];

                        $detailsBottom[] = [
                            'title' => 'BOOKED THROUGH',
                            'value' => $event->itineraryCruiseFormBookedThrough
                        ];


                        $detailsBottom[] = [
                            'title' => 'PRICE',
                            'value' => ($event->itineraryCruiseFormAmountCurrency ?? '') . ' ' . ($event->itineraryCruiseFormAmount ?? '')
                        ];

                        if($event->itineraryCruiseFormSubCategory == 1){

                            $eventLabel = 'CRUISE: DEPARTURE';
                            $eventIcon = public_path('images/itinerary/icons/cruise.png');

                        } elseif($event->itineraryCruiseFormSubCategory == 2){

                            $eventLabel = 'CRUISE: ARRIVAL';
                            $eventIcon = public_path('images/itinerary/icons/cruise.png');

                        } 
                    @endphp

                @endif

                @if($event->eventType == 6)

                    @php
                        $detailsTop = [];
                        $detailsBottom = [];
                        $eventTitle = $event->itineraryInfoFormTitle;
                        $eventNote = $event->itineraryInfoFormNote;

                        if($event->itineraryEventFormInfoSubCategory == 1){

                            $eventLabel = 'INFO';
                            $eventIcon = public_path('images/itinerary/icons/info.png');

                        } elseif($event->itineraryEventFormInfoSubCategory == 2){

                            $eventLabel = 'CITY GUIDE';
                            $eventIcon = public_path('images/itinerary/icons/book.png');

                        } 
                    @endphp

                @endif

                <div class="event-card">

                    <div class="event-top">

                        <div class="event-header">

                            <table class="event-header-table">

                                <tr>

                                    <td class="event-header-left">

                                        <img src="{{ $eventIcon }}" class="event-icon">

                                        <span class="event-label {{ $eventClass }}">
                                            {{ $eventLabel }}
                                        </span>

                                    </td>

                                    <td class="event-header-right">

                                        <span class="event-time {{ $eventClass }}">
                                            {{ $formattedTime }}
                                        </span>

                                    </td>

                                </tr>

                            </table>

                        </div>

                    </div>

                    <div class="divider"></div>

                    <div class="event-title">
                        {{ $eventTitle }}
                    </div>

                    @if($eventNote)
                        <div class="event-note">
                            {!! $eventNote !!}
                        </div>
                    @endif

                    <div class="divider"></div>

                    <table class="details">
                        <tr>
                            @foreach($detailsTop as $item)
                                @if(!empty(trim($item['value'] ?? '')))
                                    <td>
                                        <div class="detail-title">
                                            {{ $item['title'] }}
                                        </div>
                                        <div>
                                            {{ $item['value'] }}
                                        </div>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    </table>

                    <div style="height:12px;"></div>

                    <table class="details">
                        <tr>
                            @foreach($detailsBottom as $item)
                                @if(!empty(trim($item['value'] ?? '')))
                                    <td>
                                        <div class="detail-title">
                                            {{ $item['title'] }}
                                        </div>
                                        <div>
                                            {{ $item['value'] }}
                                        </div>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    </table>

                </div>

            @endforeach

        </div>

    @endforeach

</body>
</html>