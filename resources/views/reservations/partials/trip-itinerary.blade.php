<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        color: #282560;
        font-size: 16px;
        line-height: 1.5;
    }

    .logo {
        display: block;
        width: 250px;
        height: auto;
        margin: 0 auto 30px auto;
    }

    .center-text {
        text-align: center;
        color: #282560;
        margin: 8px 0;
    }

    .title {
        font-size: 22px;
        font-weight: bold;
        letter-spacing: 1px;
        margin-bottom: 25px;
    }

    .trip-title {
        font-size: 20px;
        margin: 15px 0;
    }

    .trip-title strong {
        font-weight: bold;
    }

    .date {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .reservation-number {
        font-size: 18px;
        margin-bottom: 35px;
    }

    .prepared-by {
        margin-top: 50px;
        text-align: right;
        font-size: 16px;
    }

    .prepared-by strong {
        font-weight: bold;
    }

    .logo-container {
        text-align: center;
        margin-bottom: 30px;
    }

    .logo {
        width: 250px;
        height: auto;
    }
</style>

<div class="logo-container">
    <img src="{{ base_path('public/images/archer-logo.png') }}" class="logo" alt="Logo">
</div>

<p class="center-text title">
    PRESENTS...
</p>

<p class="center-text trip-title">
    <strong>A</strong> {{ $reservation->destination->destination_name }}
    <strong>for the</strong>
    {{ $reservation->customer->lname }} Family
</p>

<p class="center-text date">
    {{ $reservation->checkin_date ? \Carbon\Carbon::parse($reservation->checkin_date)->format('m/d/Y') : '' }}
    -
    {{ $reservation->checkout_date ? \Carbon\Carbon::parse($reservation->checkout_date)->format('m/d/Y') : '' }}
</p>

<p class="center-text reservation-number">
    Reservation # {{ $reservation->reservation_number }}
</p>

<p>
    <strong>Customer:</strong>
    {{ $reservation->customer->fname }}
    {{ $reservation->customer->lname }}
</p>

<p>
    <strong>Resort/Ship:</strong>
    {{ $reservation->resort->resort_ship_name }}
</p>

@if(!empty($reservation->celebrations))
    <p>
        <strong>Celebrations:</strong>
        {{ $reservation->celebrations }}
    </p>
@endif

<p><strong>Customer Family Travelers:</strong></p>
@foreach($reservation->travelers->where('is_included', 1) as $traveler)
    <div>
        {{ $traveler->familyMember->fname }}
        {{ $traveler->familyMember->lname }}
        -
        {{ $traveler->familyMember->relation }}
    </div>
@endforeach

@if(!empty($reservation->celebrations))
    <p>
        <strong>Celebrations:</strong>
        {{ $reservation->celebrations }}
    </p>
@endif

@if($reservation->embarkation_port != -1)
    <p>
        <strong>Embarkation Port:</strong>
        {{ $reservation->embarkation_port }}
    </p>
@endif

@php
    $diningNotes = $reservation->diningNotes->where('is_canceled', 0)->where('is_deleted', 0);
@endphp

@if($diningNotes->isNotEmpty())
    <p><strong>Dining Information:</strong></p>

    @foreach($diningNotes as $index => $note)
        <div style="margin-bottom:12px;">
            <strong>Dining {{ $index + 1 }}:</strong>
            {{ $note->meal }}<br>

            {{ \Carbon\Carbon::parse($note->dining_date)->format('m/d/Y') }}
            @if(!empty($note->dining_time))
                - {{ $note->dining_time }}
            @endif

            @if(!empty($note->notes))
                <br>
                Note: {{ $note->notes }}
            @endif
            
        </div>
    @endforeach
@endif

<p class="prepared-by">
    <strong>PREPARED BY:</strong><br>
    {{ $reservation->agent->fname }} {{ $reservation->agent->lname }}<br>
    {{ $reservation->agent->phone_number }}
</p>