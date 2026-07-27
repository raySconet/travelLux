<p>Dear {{ $reservation->customer->fname }},</p>

<p>Please find attached the itinerary for your trip on {{ $reservation->checkin_date ? \Carbon\Carbon::parse($reservation->checkin_date)->format('m/d/Y') : ' ' }}</p>

<p>Thank you for choosing Travelux!</p>