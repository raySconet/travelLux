<div>
    <p class="text-xl">Referred By</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-6">
            <label for="customerReferral">Customer Referral</label>
            <select name="customerReferral" id="customerReferral" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">-- Select Customer --</option>
            </select>
        </div>

        <div class="relative mt-6">
            <label for="marketingMethod">Marketing Method</label>
            <select name="marketingMethod" id="marketingMethod" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">-- Select Method --</option>
                <option value="driveByOffice">Drive By Office</option>
                <option value="emailMarketing">Email Marketing</option>
                <option value="facebookMarketing">Facebook Marketing</option>
                <option value="family/friend">Family/Friend</option>
                <option value="internetSearch">Internet Search</option>
                <option value="networking">Networking</option>
                <option value="websiteMarketing">Website Marketing</option>
                <option value="websiteReviewPage">Website Review Page</option>
            </select>
        </div>
    </div>  

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="referralCompany" name="referralCompany" value="{{ $user->name }}" />

            <x-input-label for="referralCompany">Referral Company</x-input-label>
        </div>
        <div class="relative mt-5">
            <x-text-input type="text" id="firstName" name="firstName" />

            <x-input-label for="firstName">First Name</x-input-label>
        </div>
    </div> 

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
         <div class="relative mt-5">
            <x-text-input type="text" id="lastName" name="lastName" />

            <x-input-label for="lastName">Last Name</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="email" id="email" name="email"  class="w-90" value="{{ $user->email }}" />

            <x-input-label for="email">Email</x-input-label>
        </div>
    </div>  

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input class="w-1/2" type="text" id="phone" name="phone"  />

            <x-input-label for="phone">Phone</x-input-label>
        </div>
    </div>
</div>