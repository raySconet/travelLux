<p>Dear {{ $reservation->customer->fname }},</p>

<p>
We need you to complete this form so we can continue planning your vacation.<br>
Please click
<a href="{{ route('credit-card.form', ['token' => encrypt($reservation->id)]) }}">
here
</a>
to complete the form. Please print the form, complete and sign it.
Once finished, kindly send me a picture of it via text or email.
</p>

<p>Thanks for choosing Archer Luxury Travel for your vacation planning!</p>

<p>
Thank you,<br>
{{ $reservation->agent->fname }} {{ $reservation->agent->lname }}
</p>

<p>Travelux</p>