<x-invitation-layout>
    <div id="creditCardForm" class="max-w-9xl mx-auto bg-white shadow p-8 mt-5 mb-5" style="box-shadow: 0 5px 5px -11px rgba(0,0,0,0.2),0 1px 4px -22px rgba(0,0,0,0.14),0 3px 14px 2px rgba(0,0,0,0.12);" >

        <div class="text-center mb-8">
            <img src="{{ asset('images/archer-logo.png') }}" alt="Travelux" class="w-56 mx-auto mb-4">
            <h1 class="text-3xl">
                Credit Card Authorization Form
            </h1>
        </div>

        <h2 class="text-sm font-extrabold mb-4">CARDHOLDER INFORMATION</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="font-semibold">First Name</label>
                <input type="text" value="{{ $reservation->customer->fname }}" readonly class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

            <div>
                <label class="font-semibold">Last Name</label>
                <input type="text" value="{{ $reservation->customer->lname }}" readonly class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

            <div>
                <label class="font-semibold">Address</label>
                <input type="text" value="{{ $reservation->customer->address_line1 }}" readonly class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

            <div>
                <label class="font-semibold">City</label>
                <input type="text" value="{{ $reservation->customer->city }}" readonly class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

            <div>
                <label class="font-semibold">State</label>
                <input type="text" value="{{ $reservation->customer->state }}" readonly class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

            <div>
                <label class="font-semibold">Zip Code</label>
                <input type="text" value="{{ $reservation->customer->postal_code }}" readonly class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mb-8">
            <div>
                <label class="font-semibold">Phone Number</label>
                <input type="text" value="{{ $reservation->customer->cellphone }}" readonly class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

            <div>
                <label class="font-semibold">Email</label>
                <input type="text" value="{{ $reservation->customer->email }}" readonly class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

            <div>
                <label class="font-semibold">Reservation Number</label>
                <input type="text" value="{{ $reservation->reservation_number }}" readonly class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>
        </div>

        <h2 class="text-base font-extrabold">Credit Card Information</h2>

        <div class="max-w-9xl mx-auto mt-3 text-sm mb-6">
            <p>Credit Card Type:</p>

            <div class="flex justify-between">
                <div class="flex items-center gap-2">
                    <input class="h-4 w-4" type="radio" name="cardType" id="mastercard" value="Mastercard" />
                    <label for="mastercard">Mastercard</label>
                </div>

                <div class="flex items-center gap-2">
                    <input class="h-4 w-4" type="radio" name="cardType" id="visa" value="Visa" />
                    <label for="visa">Visa</label>
                </div>

                <div class="flex items-center gap-2">
                    <input class="h-4 w-4" type="radio" name="cardType" id="americanExpress" value="American Express" />
                    <label for="americanExpress">American Express</label>
                </div>

                <div class="flex items-center gap-2">
                    <input class="h-4 w-4" type="radio" name="cardType" id="discoverCard" value="Discover Card" />
                    <label for="discoverCard">Discover Card</label>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="font-semibold">Card Number</label>
                <input type="text" name="card_number" id="card_number" class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

            <div>
                <label class="font-semibold">Security Code</label>
                <input type="text" name="security_code" id="security_code" class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

            <div>
                <label class="font-semibold">Expiration Month</label>
                <input type="text" name="expiration_month" id="expiration_month" class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

            <div>
                <label class="font-semibold">Expiration Year</label>
                <input type="text" name="expiration_year" id="expiration_year" class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
            </div>

            <div class="md:col-span-2">
                <label class="font-semibold">Authorized Amount ($)</label>
                <input type="text" name="authorized_amount" id="authorized_amount" class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 mb-1 -mt-5">
            </div>
        </div>

        <div class="mt-10">
            <h2 class="text-xl font-bold mb-4">Traveler Information</h2>

            @foreach($reservation->travelers->where('is_included',1) as $traveler)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="font-semibold">Traveler Name</label>
                        <input type="text" value="{{ $traveler->familyMember->fname }} {{ $traveler->familyMember->lname }}" readonly class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
                    </div>

                    <div>
                        <label class="font-semibold">Birth Date</label>
                        <input type="text" value="{{ $traveler->familyMember->birth_date }}" readonly class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1 -mt-5">
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            <label class="flex items-start gap-2">
                <input type="checkbox" class="mt-1">
                <span>
                    I authorize Travelux to charge my card for the reservation and provider noted above.I have read and agree to the terms and conditions of the Tour Operator/Supplier/Cruise Line and Travelux.
                </span>
            </label>
        </div>

        <div class="mt-8">
            <div class="flex items-center gap-3 mb-2">
                <label class="font-semibold mb-0">Signature:</label>

                <button type="button" id="clearSignature" class="text-sm text-red-600 hover:underline noprint ml-85">
                    Clear
                </button>
            </div>

            <canvas id="travelInsuranceWaiverSignature" width="450" height="150" class="border border-gray-300 rounded bg-white"></canvas>
        </div>

        <div class="flex justify-start">
            <button type="button" id="printCreditCardForm" class="space-x-2 bg-[#B6844A] text-white font-semibold py-2 px-12 rounded cursor-pointer border border-transparent hover:bg-white hover:border-[#B6844A] hover:text-[#B6844A] transition-all duration-200 mt-2 noprint">
                <i class="fas fa-print"></i>
                Print
            </button>
        </div>

    </div>
</x-invitation-layout>