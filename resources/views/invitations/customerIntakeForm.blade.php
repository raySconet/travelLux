<x-invitation-layout bodyClass="bg-white" containerClass="bg-white px-4">
    <div id="customerIntakeFormContent">
    <div class="flex justify-center mt-5">
        <img src="{{ asset('images/archer-logo.png') }}" class="w-[250px]" alt="Logo">
    </div>

    <input type="hidden" id="encryption" value="{{ $token }}">

    <div class="max-w-5xl mx-auto mt-5 text-base">
        <h5>Hello, Future Traveler! Head ups: if you're on a phone, you may want to scurry on over to your computer to fill this out. It seems to be a better fit.</h5>
        <h5>Yay, I'm so excited to help you plan a luxury, epic, and unforgettable vacation!</h5>

        <br/>

        <h5>-- STEP (1) --</h5>
        <h5>First things first, let's make sure we're on the same page. Please take a few minutes to read the following info as it has some key details to ensure a smooth planning process.</h5>

        <br/>

        <h5>-- STEP (2) --</h5>
        <h5>Fill out the form below with as many juicy details as possible so I can start crafting your dream vacay! Depending on your travel plans, we might need to hop on a quick call - but don't worry, I'll be in touch to set it up if that's the case.</h5>
        <h5>Speaking of budgets, I totally get it if you're not sure what yours is. But keep in mind, everyone's financial situation is different, and budgets are totally subjective. Even a ballpark range helps me out a ton! And don't worry, I'll let you know if your budget is totally bonkers.</h5>
        <h5>Oh and btw, my services are completely FREE for you! No hidden fees or charges - I'm here to help you out, no strings attached. Just keep in mind that planning a trip can take several hours, and I only get paid if you actually take the trip. So please be mindful of that as we work together to plan your perfect getaway.</h5>

        <br/>

        <h5>-- STEP (3) --</h5>
        <h5>Let's do this thing! Allow me 1-2 business days to get back to you with some fun options. Get ready for the time of your life!</h5>
    </div>

    <div class="max-w-5xl mx-auto mt-2 text-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4">
            <div class="relative">
                <x-text-input type="text" id="name" name="name"  />

                <x-input-label for="name" class="font-bold">Name of person filling this out*</x-input-label>

                <div id="name-error"></div>
            </div>

            <div class="relative">
                <x-text-input type="email" id="email" name="email"  />

                <x-input-label for="email" class="font-bold">Email Address*</x-input-label>   

                <div id="email-error"></div>
            </div>

            <div class="relative">
                <x-text-input type="text" id="phone" name="phone" />

                <x-input-label for="phone" class="font-bold">What is your phone number?*</x-input-label>

                <div id="phone-error"></div>
            </div>

            <div class="relative">
                <x-text-input type="text" id="timezone" name="timezone" />

                <x-input-label for="timezone" class="font-bold">What time zone are you located in?</x-input-label>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto text-sm mt-3">
        Trip Insurance is a critical step in the planning process based on my experience. I loosely require it due to the amount of issues and 
        loss that can take place without it. It's something that you hope you never have to cash in on, but if you do... you're glad you have it! 
        The cost can run on average between $60-$100 for a cruise and $300-$1000 for a long, expensive vacation. It is based on the cost of the trip.
    </div>

    <div class="max-w-5xl mx-auto text-sm mt-3">
        <p class="font-bold">Do you agree to purchasing trip insurance?</p>

        <div class="flex items-center gap-2 mt-3">
            <input type="radio" name="insuranceRadio" id="insuranceRadio1" class="h-4 w-4 text-[#B6844A] border-gray-300 focus:ring-[#B6844A]" value="Yes">
            <label for="insuranceRadio1">Yes</label>
        </div>

        <div class="flex items-center gap-2">
            <input type="radio" name="insuranceRadio" id="insuranceRadio2" class="h-4 w-4 text-[#B6844A] border-gray-300 focus:ring-[#B6844A]" value="No">
            <label for="insuranceRadio2">No</label>
        </div>

        <div class="flex items-center gap-2">
            <input type="radio" name="insuranceRadio" id="insuranceRadio3" class="h-4 w-4 text-[#B6844A] border-gray-300 focus:ring-[#B6844A]" value="Maybe - I need more info">
            <label for="insuranceRadio3">Maybe - I need more info</label>
        </div>

        <div class="flex items-center gap-3 mt-5">
            <input type="radio" name="insuranceRadio" id="insuranceRadioOther" class="h-4 w-4 text-[#B6844A] border-gray-300 focus:ring-[#B6844A]" value="Other">
            <label for="insuranceRadioOther">Other</label>
            <input type="text" name="insuranceOtherText" id="insuranceOtherText" class="flex-1 border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none -mt-2">
        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-8 text-sm">
        <b>What type of a trip do you need assistance with?</b>

        <div class="mt-3 space-y-2">

            <label class="flex items-center gap-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadio1" class="h-4 w-4" value="Adventures by Disney">
                <span>Adventures by Disney</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadio2" class="h-4 w-4" value="All Inclusive Resort">
                <span>All Inclusive Resort</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadio3" class="h-4 w-4" value="Cruise">
                <span>Cruise</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadio4" class="h-4 w-4" value="Disney Cruise">
                <span>Disney Cruise</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadio5" class="h-4 w-4" value="Disney Land">
                <span>Disney Land</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadio6" class="h-4 w-4" value="Disney World">
                <span>Disney World</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadio7" class="h-4 w-4" value="European Vacation">
                <span>European Vacation</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadio8" class="h-4 w-4" value="Luxury Resort Stay within the US">
                <span>Luxury Resort Stay within the US</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadio9" class="h-4 w-4" value="Luxury Resort Stay outside the US">
                <span>Luxury Resort Stay outside the US</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadio10" class="h-4 w-4" value="Ski Trip">
                <span>Ski Trip</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadio11" class="h-4 w-4" value="Small Tour Group">
                <span>Small Tour Group</span>
            </label>

            <div class="flex items-center gap-3 pt-3 mb-2">
                <input type="checkbox" name="assistanceRadio[]" id="assistanceRadioOther" class="h-4 w-4" value="Other">
                <label for="assistanceRadioOther">Other</label>
                <input type="text" name="assistanceOtherText" id="assistanceOtherText" class="flex-1 border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none -mt-2">
            </div>

        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-8 text-sm">
        <b>What is important to you on this specific vacation?</b>

        <div class="mt-3 space-y-2">

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="importanceRadio[]" id="importanceRadio1" class="h-4 w-4" value="Adventure">
                <span>Adventure</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="importanceRadio[]" id="importanceRadio2" class="h-4 w-4" value="The Beach!">
                <span>The Beach!</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="importanceRadio[]" id="importanceRadio3" class="h-4 w-4" value="Excursions (or ports)">
                <span>Excursions (or ports)</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="importanceRadio[]" id="importanceRadio4" class="h-4 w-4" value="Night Life">
                <span>Night Life</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="importanceRadio[]" id="importanceRadio5" class="h-4 w-4" value="Relaxing only - no matter what that looks like">
                <span>Relaxing only - no matter what that looks like</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="importanceRadio[]" id="importanceRadio6" class="h-4 w-4" value="The Spa">
                <span>The Spa</span>
            </label>

            <div class="flex items-center gap-3 pt-3 mb-2">
                <input type="checkbox" name="importanceRadio[]" id="importanceRadioOther" class="h-4 w-4" value="Other">
                <label for="importanceRadioOther">Other</label>
                <input type="text" name="importanceOtherText" id="importanceOtherText" class="flex-1 border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none -mt-2">
            </div>

        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-5 text-sm">
        <div class="relative">
            <x-text-input type="text" id="locations" name="locations" />

            <x-input-label for="locations" class="font-bold">What location(s) are you hoping to visit on your trip (including a cruise)?</x-input-label>
        </div>

        <div class="relative mt-8">
            <x-text-input type="text" id="cruiseLine" name="cruiseLine" />

            <x-input-label for="cruiseLine" class="font-bold">If you are looking to book a cruise, do you prefer a specific cruise line(s)? Please put N/A if you are not looking to book a cruise or don't have a specific line.</x-input-label>
        </div>

        <div class="relative mt-8">
            <x-text-input type="text" id="favoriteHotel" name="favoriteHotel" />

            <x-input-label for="favoriteHotel" class="font-bold">My favorite hotel I have ever been or dream of going to is?</x-input-label>
        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-5 text-sm">
        <b>What is your budget for this vacation?</b>

        <div class="mt-3 space-y-2">

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="budgetRadio[]" id="budgetRadio1" class="h-4 w-4" value="$1000-$2000">
                <span>$1000-$2000</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="budgetRadio[]" id="budgetRadio2" class="h-4 w-4" value="$2000-$3000">
                <span>$2000-$3000</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="budgetRadio[]" id="budgetRadio3" class="h-4 w-4" value="$3000-$4000">
                <span>$3000-$4000</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="budgetRadio[]" id="budgetRadio4" class="h-4 w-4" value="$4000-$5000">
                <span>$4000-$5000</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="budgetRadio[]" id="budgetRadio5" class="h-4 w-4" value="$5000+">
                <span>$5000+</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="budgetRadio[]" id="budgetRadio6" class="h-4 w-4" value="I don't know">
                <span>I don't know</span>
            </label>

            <div class="flex items-center gap-3 pt-3">
                <input type="checkbox" name="budgetRadio[]" id="budgetRadioOther" class="h-4 w-4" value="Other">
                <label for="budgetRadioOther">Other</label>
                <input type="text" name="budgetOtherText" id="budgetOtherText" class="flex-1 border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none -mt-2">
            </div>

        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-8 text-sm">
        <b>If you are looking to book a cruise, what room types/categories are you interested in?</b>

        <div class="mt-3 space-y-2">

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="roomTypeRadio[]" id="roomTypeRadio1" class="h-4 w-4" value="Interior (No Windows at all)">
                <span>Interior (No Windows at all)</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="roomTypeRadio[]" id="roomTypeRadio2" class="h-4 w-4" value="Oceanview (No balcony)">
                <span>Oceanview (No balcony)</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="roomTypeRadio[]" id="roomTypeRadio3" class="h-4 w-4" value="Balcony (Oceanview or Central Park inside view)">
                <span>Balcony (Oceanview or Central Park inside view)</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="roomTypeRadio[]" id="roomTypeRadio4" class="h-4 w-4" value="Suite">
                <span>Suite</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="roomTypeRadio[]" id="roomTypeRadio5" class="h-4 w-4" value="Not interested in a cruise at this time">
                <span>Not interested in a cruise at this time</span>
            </label>

        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-8 text-sm" id="passportValidityContainer">
        <b>If you are traveling by plane out of the US, does everyone have a passport that will be current through 6 months post travel? *</b>

        <div class="mt-3 space-y-2">

            <label class="flex items-center gap-2 -mt-2">
                <input type="radio" name="passportsRadio"id="passportsRadio1" class="h-4 w-4 passportsRadio" value="Yes">
                <span>Yes</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="radio" name="passportsRadio" id="passportsRadio2" class="h-4 w-4 passportsRadio" value="Yes">
                <span>Yes</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="radio" name="passportsRadio" id="passportsRadio3" class="h-4 w-4 passportsRadio" value="Passport(s) in Process!">
                <span>Passport(s) in Process!</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="radio" name="passportsRadio" id="passportsRadio4" class="h-4 w-4 passportsRadio" value="N/A">
                <span>N/A</span>
            </label>

            <div class="flex items-center gap-3 pt-3">
                <input type="radio" name="passportsRadio" id="passportsRadioOther" class="h-4 w-4" value="Other">
                <label for="passportsRadioOther">Other</label>
                <input type="text" name="passportsOtherText" id="passportsOtherText" class="flex-1 border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none -mt-2">
            </div>

            <div id="passportsRadio-error"></div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-5 text-sm">
        <div class="relative">
            <label for="dateRange" class="font-bold block">
                Please offer me a specific date range (I have to input whatever dates you provide, so this is important). 
                If you want to provide a few dates options, go for it, but I do need them to be specific 
                (ie. July 1st - July 8th, {{ date("Y") }}).
            </label>
            <input type="text" name="dateRange" id="dateRange" class="w-full border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none">
        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-5 text-sm">
        <b>What year do you plan to travel?</b>

        <div class="mt-3 space-y-2">

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="yearRadio[]" id="yearRadio1" class="h-4 w-4" value="{{ date('Y') }}">
                <span>{{ date("Y") }}</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="yearRadio[]" id="yearRadio2" class="h-4 w-4" value="{{ date('Y', strtotime('+1 year')) }}">
                <span>{{ date("Y", strtotime("+1 year")) }}</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="yearRadio[]" id="yearRadio3" class="h-4 w-4" value="{{ date('Y', strtotime('+2 years')) }}">
                <span>{{ date("Y", strtotime("+2 years")) }}</span>
            </label>

            <div class="flex items-center gap-3 pt-3">
                <input type="checkbox" name="yearRadio[]" id="yearRadioOther" class="h-4 w-4" value="Other">
                <label for="yearRadioOther">Other</label>
                <input type="text" name="yearOtherText" id="yearOtherText" class="flex-1 border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none -mt-2">
            </div>

        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-8 text-sm">
        <b>How many nights do you want to travel?</b>

        <div class="mt-3 space-y-2">

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="nightsRadio[]" id="nightsRadio1" class="h-4 w-4" value="1 Night">
                <span>1 Night</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="nightsRadio[]" id="nightsRadio2" class="h-4 w-4" value="2 Nights">
                <span>2 Nights</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="nightsRadio[]" id="nightsRadio3" class="h-4 w-4" value="3 Nights">
                <span>3 Nights</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="nightsRadio[]" id="nightsRadio4" class="h-4 w-4" value="4 Nights">
                <span>4 Nights</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="nightsRadio[]" id="nightsRadio5" class="h-4 w-4" value="5 Nights">
                <span>5 Nights</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="nightsRadio[]" id="nightsRadio6" class="h-4 w-4" value="6 Nights">
                <span>6 Nights</span>
            </label>

            <label class="flex items-center gap-2 -mt-2">
                <input type="checkbox" name="nightsRadio[]" id="nightsRadio7" class="h-4 w-4" value="7 Nights">
                <span>7 Nights</span>
            </label>

            <div class="flex items-center gap-3 pt-3">
                <input type="checkbox" name="nightsRadio[]" id="nightsRadioOther" class="h-4 w-4" value="Other">
                <label for="nightsRadioOther">Other</label>
                <input type="text" name="nightsOtherText" id="nightsOtherText" class="flex-1 border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none -mt-2">
            </div>

        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-8 text-sm">
        <b>Will you need to purchase flights for your trip?</b>

        <div class="flex items-center gap-2 mt-3">
            <input class="h-4 w-4"  type="radio" name="purchaseFlightsRadio"  id="purchaseFlightsRadio1" value="Yes"/>
            <label for="purchaseFlightsRadio1">Yes</label>
        </div>

        <div class="flex items-center gap-2">
            <input class="h-4 w-4" type="radio" name="purchaseFlightsRadio"  id="purchaseFlightsRadio2" value="No"/>
            <label for="purchaseFlightsRadio2">No</label>
        </div>

        <div class="flex items-center gap-2">
            <input class="h-4 w-4" type="radio" name="purchaseFlightsRadio" id="purchaseFlightsRadio3" value="Maybe"/>
            <label for="purchaseFlightsRadio3">Maybe</label>
        </div>

        <div class="flex items-center gap-2">
            <input class="h-4 w-4" type="radio" name="purchaseFlightsRadio" id="purchaseFlightsRadio4" value="N/A"/>
            <label for="purchaseFlightsRadio4">N/A</label>
        </div>

        <div class="flex items-center gap-3 mt-5">
            <input class="h-4 w-4" type="radio" name="purchaseFlightsRadio" id="purchaseFlightsRadioOther" value="Other"/>
            <label for="purchaseFlightsRadioOther">Other</label>
            <input type="text" name="purchaseFlightsOtherText" id="purchaseFlightsOtherText" class="border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none flex-1 -mt-2"/>
        </div>

    </div>

    <div class="max-w-5xl mx-auto mt-7 text-sm">
        <div class="relative">
            <label for="flightsPreferences" class="font-bold block">
                What airport(s) do you prefer to fly out of? Also pls note preferences for flights if you have any (ie. I will not fly out before 8am, I prefer to fly on United, etc). Pls put N/A if you don't need flights.
            </label>
            <input type="text" name="flightsPreferences" id="flightsPreferences" class="w-full border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none">
        </div>
    </div>
    
    <div class="max-w-5xl mx-auto mt-7 text-sm">
        <p class="mt-4 mb-3 text-sm">If you haven't put this in the CRM from the previous email please add here, if you have just first names of those traveling</p>
        <div class="relative">
            <label for="travelersInfo" class="font-bold block">
                Please list all traveler NAMES (as issued on their govt IDs) + BIRTHDAYS.
            </label>
            <input type="text" name="travelersInfo" id="travelersInfo" class="w-full border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none">
        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-7 text-sm">
        <div class="relative">
            <label for="cruiseRooms" class="font-bold block">
                If you are planning to cruise, please list out who will be in what room. Example: Mary + Joe in one, John + Sally in another. If you are not cruising, pls note N/A.
            </label>
            <input type="text" name="cruiseRooms" id="cruiseRooms" class="w-full border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none">
        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-7 text-sm">
        <div class="relative">
            <label for="otherText" class="font-bold block">
                What else do you want to tell me?  Please list that here. :)
            </label>
            <input type="text" name="otherText" id="otherText" class="w-full border-0 border-b-2 border-gray-300 focus:border-[#B6844A] focus:ring-0 outline-none">
        </div>
    </div>
    
    <div class="max-w-5xl mx-auto mt-7 mb-7">
        <div class="flex justify-end mt-2 gap-2">
            <x-primary-btn type="button" id="customerIntakeFormSubmitBtn">
                <i class="fas fa-paper-plane mr-2"></i>Submit
            </x-primary-btn>
        </div>
    </div>
</div>
</x-invitation-layout>