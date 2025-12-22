<div>
    <div class="systemUserIconContainer">
        <img src="{{ asset('images/mickey.png') }}" style="width: 60px;  height: 60px; margin-bottom:10px;" alt="Mickey!">

        <button type="button" class="btn archerBtn" id="editProfilePhoto">Edit</button>
        <input type="file" id="userProfilePhoto" name="userProfilePhoto" style="display:none;" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="firstName" name="firstName" value="{{ $user->name }}" />

            <x-input-label for="firstName"> First Name</x-input-label>
        </div>
        <div class="relative mt-5">
            <x-text-input type="text" id="lastName" name="lastName" />

            <x-input-label for="lastName"> Last Name</x-input-label>
        </div>
    </div>  

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="email" id="email" name="email"  value="{{ $user->email }}" />

            <x-input-label for="email">Email</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input  type="email" id="email" name="email"/>

            <x-input-label for="backupEmail">Back Up Email</x-input-label>
        </div>
    </div>  

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="ssn" name="ssn"  />

            <x-input-label for="ssn">SSN</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="ein" name="ein"  />

            <x-input-label for="ein">EIN</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="phoneNumber" name="phoneNumber"  />

            <x-input-label for="phoneNumber">Phone Number</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="cellPhoneNumber" name="cellPhoneNumber"  />

            <x-input-label for="cellPhoneNumber">Cell Phone Number</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="birthDate" name="birthDate"  />

            <x-input-label for="birthDate">Birth Date</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="hireDate" name="hireDate"  />

            <x-input-label for="hireDate">Hire Date</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" />

            <x-input-label type="text" for="userName">User Name</x-input-label>
        </div>
        <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Email As Username</label>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="password" id="password" name="password"  />

            <x-input-label for="password">Password</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="confirmPassword" id="confirmPassword" name="confirmPassword"  />

            <x-input-label for="confirmPassword">Confirm Password</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="facebookProfile" name="facebookProfile"  />

            <x-input-label for="facebookProfile">Facebook Profile</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="instagramAccount" name="instagramAccount"  />

            <x-input-label for="instagramAccount">Instagram</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="twitterAccount" name="twitterAccount"  />

            <x-input-label for="twitterAccount">Twitter</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="timeZone" name="timeZone"  />

            <x-input-label for="timeZone">Time Zone</x-input-label>
        </div>
    </div>
</div>