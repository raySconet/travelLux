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
            <label for="marketing_method">Marketing Method</label>
            <select name="marketing_method" id="marketing_method" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1" {{ old('marketing_method', $customer->marketing_method ?? '') == '-1' ? 'selected' : '' }}>--Select Marketing Status --</option>
                <option value="Drive By Office" {{ old('marketing_method', $customer->marketing_method ?? '') == 'Drive By Office' ? 'selected' : '' }}>Drive By Office</option>
                <option value="Email Marketing" {{ old('marketing_method', $customer->marketing_method ?? '') == 'Email Marketing' ? 'selected' : '' }}>Email Marketing</option>
                <option value="Facebook Marketing" {{ old('marketing_method', $customer->marketing_method ?? '') == 'Facebook Marketing' ? 'selected' : '' }}>Facebook Marketing</option>
                <option value="Family/Friend" {{ old('marketing_method', $customer->marketing_method ?? '') == 'Family/Friend' ? 'selected' : '' }}>Family/Friend</option>
                <option value="Internet Search" {{ old('marketing_method', $customer->marketing_method ?? '') == 'Internet Search' ? 'selected' : '' }}>Internet Search</option>
                <option value="Networking" {{ old('marketing_method', $customer->marketing_method ?? '') == 'Networking' ? 'selected' : '' }}>Networking</option>
                <option value="Website Marketing" {{ old('marketing_method', $customer->marketing_method ?? '') == 'Website Marketing' ? 'selected' : '' }}>Website Marketing</option>
                <option value="Website Review Page" {{ old('marketing_method', $customer->marketing_method ?? '') == 'Website Review Page' ? 'selected' : '' }}>Website Review Page</option>
            </select>
        </div>
    </div>  

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="referralCompany" name="referralCompany" value="{{ old('referral_company', $customer->referral_company ?? '') }}"/>

            <x-input-label for="referralCompany">Referral Company</x-input-label>
        </div>
        <div class="relative mt-5">
            <x-text-input type="text" id="firstName" name="firstName" value="{{ old('referred_by_fname', $customer->referred_by_fname ?? '') }}"/>

            <x-input-label for="firstName">First Name</x-input-label>
        </div>
    </div> 

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
         <div class="relative mt-5">
            <x-text-input type="text" id="lastName" name="lastName" value="{{ old('referred_by_lname', $customer->referred_by_lname ?? '') }}"/>

            <x-input-label for="lastName">Last Name</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="email" id="email" name="email"  class="w-90" value="{{ old('referred_by_email', $customer->referred_by_email ?? '') }}"/>

            <x-input-label for="email">Email</x-input-label>
        </div>
    </div>  

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input class="w-1/2" type="text" id="phone" name="phone"  value="{{ old('referred_by_phone', $customer->referred_by_phone ?? '') }}"/>

            <x-input-label for="phone">Phone</x-input-label>
        </div>
    </div>
</div>