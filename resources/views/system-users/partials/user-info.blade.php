<div>
    <div class="systemUserIconContainer">
        <img src="{{ asset('images/mickey.png') }}" style="width: 60px;  height: 60px; margin-bottom:10px;" alt="Mickey!">

        <button type="button" class="btn archerBtn" id="editProfilePhoto">Edit</button>
        <input type="file" id="profile_photo" name="profile_photo" style="display:none;" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="fname" name="fname" value="{{ old('fname', $user->fname ?? '') }}" />

            <x-input-label for="fname"> First Name</x-input-label>

            <x-input-error  :messages="$errors->get('fname')" />
        </div>
        <div class="relative mt-5">
            <x-text-input type="text" id="lname" name="lname" value="{{ old('lname', $user->lname ?? '') }}" />

            <x-input-label for="lname"> Last Name</x-input-label>

            <x-input-error  :messages="$errors->get('lname')" />
        </div>
    </div>  

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" />

            <x-input-label for="email">Email</x-input-label>

            <x-input-error  :messages="$errors->get('email')" />
        </div>

        <div class="relative mt-5">
            <x-text-input  type="email" id="backup_email" name="backup_email" value="{{ old('backup_email', $user->backup_email ?? '') }}" />

            <x-input-label for="backup_email">Back Up Email</x-input-label>
        </div>
    </div>  

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="ssn" name="ssn" value="{{ old('ssn', $user->ssn ?? '') }}" />

            <x-input-label for="ssn">SSN</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="ein" name="ein" value="{{ old('ein', $user->ein ?? '') }}" />

            <x-input-label for="ein">EIN</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}" />

            <x-input-label for="phone_number">Phone Number</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="cell_phone_number" name="cell_phone_number" value="{{ old('cell_phone_number', $user->cell_phone_number ?? '') }}" />

            <x-input-label for="cell_phone_number">Cell Phone Number</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

        <div x-data="dateDropdown('{{ old('birth_date', $user->birth_date ?? '') }}')" class="relative mt-5">
            <label class="block text-sm mb-1">Birth Date</label>
            <div class="flex w-full border-b-2 border-[#bdbdbd] overflow-hidden outline-none">
                <select x-model="year" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Year</option>
                    <template x-for="y in years" :key="y">
                        <option :value="y" x-text="y" :selected="year == y"></option>
                    </template>
                </select>

                <select x-model="month" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Month</option>
                    <template x-for="(m, i) in months" :key="i">
                        <option :value="i + 1" x-text="m" :selected="month == i + 1"></option>
                    </template>
                </select>

                <select x-model="day" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Day</option>
                    <template x-for="d in days" :key="d">
                        <option :value="d" x-text="d" :selected="day == d"></option>
                    </template>
                </select>
            </div>

            <input type="hidden" name="birth_date" :value="formattedDate">
        </div>

        <div x-data="dateDropdown('{{ old('hire_date', $user->hire_date ?? '') }}')" class="relative mt-5">
            <label class="block text-sm mb-1">Hire Date</label>
            <div class="flex w-full border-b-2 border-[#bdbdbd] overflow-hidden outline-none">
                <select x-model="year" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Year</option>
                    <template x-for="y in years" :key="y">
                        <option :value="y" x-text="y" :selected="year == y"></option>
                    </template>
                </select>

                <select x-model="month" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Month</option>
                    <template x-for="(m, i) in months" :key="i">
                        <option :value="i + 1" x-text="m" :selected="month == i + 1"></option>
                    </template>
                </select>

                <select x-model="day" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                    <option value="">Day</option>
                    <template x-for="d in days" :key="d">
                        <option :value="d" x-text="d" :selected="day == d"></option>
                    </template>
                </select>
            </div>

            <input type="hidden" name="hire_date" :value="formattedDate">
        </div>

    </div>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 items-center">
        <div class="relative mt-5 md:col-span-2">
            <x-text-input type="text" name="username" class="w-full" value="{{ old('username', $user->username ?? '') }}" />
            <x-input-label for="username">User Name</x-input-label>
        </div>

        <div class="flex items-center justify-end gap-2 mt-13">
            <input type="hidden" name="email_as_username" value="0" >
            <input type="checkbox" class="h-4 w-4" name="email_as_username" value="1" {{ old('email_as_username', $user->email_as_username ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm">Email As Username</label>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="password" id="password" name="password"  />

            <x-input-label for="password">Password</x-input-label>

            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="relative mt-5">
            <x-text-input type="password" id="password_confirmation" name="password_confirmation"  />

            <x-input-label for="password_confirmation">Confirm Password</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="facebook_profile" name="facebook_profile" value="{{ old('facebook_profile', $user->facebook_profile ?? '') }}" />

            <x-input-label for="facebook_profile">Facebook Profile</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="instagram_account" name="instagram_account" value="{{ old('instagram_account', $user->instagram_account ?? '') }}" />

            <x-input-label for="instagram_account">Instagram</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="twitter_account" name="twitter_account" value="{{ old('twitter_account', $user->twitter_account ?? '') }}" />

            <x-input-label for="twitter_account">Twitter</x-input-label>
        </div>

        <div class="relative mt-6">
            <label for="time_zone">Time Zone</label>
            <select name="time_zone" id="time_zone" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                <option value="-1" {{ old('time_zone', $user->time_zone ?? '') == '-1' ? 'selected' : '' }}>-- Select Time Zone --</option>
                <option value="(UTC-12:00) International Date Line West" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-12:00) International Date Line West' ? 'selected' : '' }}>(UTC-12:00) International Date Line West</option>
                <option value="(UTC-11:00) Coordinated Universal Time - 11" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-11:00) Coordinated Universal Time - 11' ? 'selected' : '' }}>(UTC-11:00) Coordinated Universal Time - 11</option>
                <option value="(UTC-10:00) Aleutian Islands" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-10:00) Aleutian Islands' ? 'selected' : '' }}>(UTC-10:00) Aleutian Islands</option>
                <option value="(UTC-10:00) Hawaii" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-10:00) Hawaii' ? 'selected' : '' }}>(UTC-10:00) Hawaii</option>
                <option value="(UTC-09:30) Marquesas Islands" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-09:30) Marquesas Islands' ? 'selected' : '' }}>(UTC-09:30) Marquesas Islands</option>
                <option value="(UTC-09:00) Alaska" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-09:00) Alaska' ? 'selected' : '' }}>(UTC-09:00) Alaska</option>
                <option value="(UTC-09:00) Coordinated Universal Time - 09" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-09:00) Coordinated Universal Time - 09' ? 'selected' : '' }}>(UTC-09:00) Coordinated Universal Time - 09</option>
                <option value="(UTC-08:00) Baja California" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-08:00) Baja California' ? 'selected' : '' }}>(UTC-08:00) Baja California</option>
                <option value="(UTC-08:00) Coordinated Universal Time - 08" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-08:00) Coordinated Universal Time - 08' ? 'selected' : '' }}>(UTC-08:00) Coordinated Universal Time - 08</option>
                <option value="(UTC-08:00) Pacific Time (US &amp; Canada)" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-08:00) Pacific Time (US &amp; Canada)' ? 'selected' : '' }}>(UTC-08:00) Pacific Time (US &amp; Canada)</option>
                <option value="(UTC-07:00) Arizona" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-07:00) Arizona' ? 'selected' : '' }}>(UTC-07:00) Arizona</option>
                <option value="(UTC-07:00) Chihuahua, La Paz, Mazatlan" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-07:00) Chihuahua, La Paz, Mazatlan' ? 'selected' : '' }}>(UTC-07:00) Chihuahua, La Paz, Mazatlan</option>
                <option value="(UTC-07:00) Mountain Time (US &amp; Canada)" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-07:00) Mountain Time (US &amp; Canada)' ? 'selected' : '' }}>(UTC-07:00) Mountain Time (US &amp; Canada)</option>
                <option value="(UTC-06:00) Central America" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-06:00) Central America' ? 'selected' : '' }}>(UTC-06:00) Central America</option>
                <option value="(UTC-06:00) Central Time (US &amp; Canada)" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-06:00) Central Time (US &amp; Canada)' ? 'selected' : '' }}>(UTC-06:00) Central Time (US &amp; Canada)</option>
                <option value="(UTC-06:00) Easter Island" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-06:00) Easter Island' ? 'selected' : '' }}>(UTC-06:00) Easter Island</option>
                <option value="(UTC-06:00) Guadalajara, Mexico City, Monterrey" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-06:00) Guadalajara, Mexico City, Monterrey' ? 'selected' : '' }}>(UTC-06:00) Guadalajara, Mexico City, Monterrey</option>
                <option value="(UTC-06:00) Saskatchewan" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-06:00) Saskatchewan' ? 'selected' : '' }}>(UTC-06:00) Saskatchewan</option>
                <option value="(UTC-05:00) Bogota, Lima, Quito, Rio Branco" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-05:00) Bogota, Lima, Quito, Rio Branco' ? 'selected' : '' }}>(UTC-05:00) Bogota, Lima, Quito, Rio Branco</option>
                <option value="(UTC-05:00) Chetumal" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-05:00) Chetumal' ? 'selected' : '' }}>(UTC-05:00) Chetumal</option>
                <option value="(UTC-05:00) Eastern Time (US &amp; Canada)" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-05:00) Eastern Time (US &amp; Canada)' ? 'selected' : '' }}>(UTC-05:00) Eastern Time (US &amp; Canada)</option>
                <option value="(UTC-05:00) Haiti" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-05:00) Hait' ? 'selected' : '' }}>(UTC-05:00) Haiti</option>
                <option value="(UTC-05:00) Havana" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-05:00) Havana' ? 'selected' : '' }}>(UTC-05:00) Havana</option>
                <option value="(UTC-05:00) Indiana (East)" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-05:00) Indiana (East)' ? 'selected' : '' }}>(UTC-05:00) Indiana (East)</option>
                <option value="(UTC-05:00) Turks And Caicos" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-05:00) Turks And Caicos' ? 'selected' : '' }}>(UTC-05:00) Turks And Caicos</option>
                <option value="(UTC-04:00) Asuncion" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-04:00) Asuncion' ? 'selected' : '' }}>(UTC-04:00) Asuncion</option>
                <option value="(UTC-04:00) Atlantic Time (Canada)" {{ old('time_zone', $user->time_zone ?? '') == '-1' ? 'selected' : '' }}>(UTC-04:00) Atlantic Time (Canada)</option>
                <option value="(UTC-04:00) Caracas" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-04:00) Caracas' ? 'selected' : '' }}>(UTC-04:00) Caracas</option>
                <option value="(UTC-04:00) Cuiaba" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-04:00) Cuiaba' ? 'selected' : '' }}>(UTC-04:00) Cuiaba</option>
                <option value="(UTC-04:00) Georgetown, La Paz, Manaus, San Juan" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-04:00) Georgetown, La Paz, Manaus, San Juan' ? 'selected' : '' }}>(UTC-04:00) Georgetown, La Paz, Manaus, San Juan</option>
                <option value="(UTC-04:00) Santiago" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-04:00) Santiago' ? 'selected' : '' }}>(UTC-04:00) Santiago</option>
                <option value="(UTC-03:30) Newfoundland" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-03:30) Newfoundland' ? 'selected' : '' }}>(UTC-03:30) Newfoundland</option>
                <option value="(UTC-03:00) Araguaina" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-03:00) Araguaina' ? 'selected' : '' }}>(UTC-03:00) Araguaina</option>
                <option value="(UTC-03:00) Brasilia" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-03:00) Brasilia' ? 'selected' : '' }}>(UTC-03:00) Brasilia</option>
                <option value="(UTC-03:00) Cayenne, Fortaleza" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-03:00) Cayenne, Fortaleza' ? 'selected' : '' }}>(UTC-03:00) Cayenne, Fortaleza</option>
                <option value="(UTC-03:00) City of Buenos Aires" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-03:00) City of Buenos Aires' ? 'selected' : '' }}>(UTC-03:00) City of Buenos Aires</option>
                <option value="(UTC-03:00) Greenland" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-03:00) Greenland' ? 'selected' : '' }}>(UTC-03:00) Greenland</option>
                <option value="(UTC-03:00) Montevideo" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-03:00) Montevideo' ? 'selected' : '' }}>(UTC-03:00) Montevideo</option>
                <option value="(UTC-03:00) Punta Arenas" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-03:00) Punta Arenas' ? 'selected' : '' }}>(UTC-03:00) Punta Arenas</option>
                <option value="(UTC-03:00) Saint Pierre and Miquelon" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-03:00) Saint Pierre and Miquelon' ? 'selected' : '' }}>(UTC-03:00) Saint Pierre and Miquelon</option>
                <option value="(UTC-03:00) Salvador" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-03:00) Salvador' ? 'selected' : '' }}>(UTC-03:00) Salvador</option>
                <option value="(UTC-02:00) Coordinated Universal Time - 02" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-02:00) Coordinated Universal Time - 02' ? 'selected' : '' }}>(UTC-02:00) Coordinated Universal Time - 02</option>
                <option value="(UTC-02:00) Mid-Atlantic-Old" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-02:00) Mid-Atlantic-Old' ? 'selected' : '' }}>(UTC-02:00) Mid-Atlantic-Old</option>
                <option value="(UTC-01:00) Azores" {{ old('time_zone', $user->time_zone ?? '') == '(UTC-01:00) Azores' ? 'selected' : '' }}>(UTC-01:00) Azores</option>
                <option value="(UTC-01:00) Cabo Verde Is." {{ old('time_zone', $user->time_zone ?? '') == '(UTC-01:00) Cabo Verde Is.' ? 'selected' : '' }}>(UTC-01:00) Cabo Verde Is.</option>
                <option value="(UTC) Coordinated Universal Time" {{ old('time_zone', $user->time_zone ?? '') == '(UTC) Coordinated Universal Time' ? 'selected' : '' }}>(UTC) Coordinated Universal Time</option>
                <option value="(UTC+00:00) Dublin, Edinburgh, Libson, London" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+00:00) Dublin, Edinburgh, Libson, London' ? 'selected' : '' }}>(UTC+00:00) Dublin, Edinburgh, Libson, London</option>
                <option value="(UTC+00:00) Monrovia, Reykjavik" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+00:00) Monrovia, Reykjavik' ? 'selected' : '' }}>(UTC+00:00) Monrovia, Reykjavik</option>
                <option value="(UTC+00:00) Sao Tome" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+00:00) Sao Tome' ? 'selected' : '' }}>(UTC+00:00) Sao Tome</option>
                <option value="(UTC+01:00) Casablanca" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+01:00) Casablanca' ? 'selected' : '' }}>(UTC+01:00) Casablanca</option>
                <option value="(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna' ? 'selected' : '' }}>(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                <option value="(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague' ? 'selected' : '' }}>(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                <option value="(UTC+01:00) Brussels, Copenhagen, Madrid, Paris" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+01:00) Brussels, Copenhagen, Madrid, Paris' ? 'selected' : '' }}>(UTC+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                <option value="(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb' ? 'selected' : '' }}>(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                <option value="(UTC+01:00) West Central Africa" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+01:00) West Central Africa' ? 'selected' : '' }}>(UTC+01:00) West Central Africa</option>
                <option value="(UTC+02:00) Amman" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Amman' ? 'selected' : '' }}>(UTC+02:00) Amman</option>
                <option value="(UTC+02:00) Athens, Bucharest" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Athens, Bucharest' ? 'selected' : '' }}>(UTC+02:00) Athens, Bucharest</option>
                <option value="(UTC+02:00) Beirut" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Beirut' ? 'selected' : '' }}>(UTC+02:00) Beirut</option>
                <option value="(UTC+02:00) Cairo" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Cairo' ? 'selected' : '' }}>(UTC+02:00) Cairo</option>
                <option value="(UTC+02:00) Chisinau" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Chisinau' ? 'selected' : '' }}>(UTC+02:00) Chisinau</option>
                <option value="(UTC+02:00) Damascus" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Damascus' ? 'selected' : '' }}>(UTC+02:00) Damascus</option>
                <option value="(UTC+02:00) Gaza, Hebron" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Gaza, Hebron' ? 'selected' : '' }}>(UTC+02:00) Gaza, Hebron</option>
                <option value="(UTC+02:00) Harare, Pretoria" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Harare, Pretoria' ? 'selected' : '' }}>(UTC+02:00) Harare, Pretoria</option>
                <option value="(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius' ? 'selected' : '' }}>(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                <option value="(UTC+02:00) Jerusalem" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Jerusalem' ? 'selected' : '' }}>(UTC+02:00) Jerusalem</option>
                <option value="(UTC+02:00) Kaliningrad" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Kaliningrad' ? 'selected' : '' }}>(UTC+02:00) Kaliningrad</option>
                <option value="(UTC+02:00) Khartoum" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Khartoum' ? 'selected' : '' }}>(UTC+02:00) Khartoum</option>
                <option value="(UTC+02:00) Tripoli" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Tripoli' ? 'selected' : '' }}>(UTC+02:00) Tripoli</option>
                <option value="(UTC+02:00) Windhoek" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+02:00) Windhoek' ? 'selected' : '' }}>(UTC+02:00) Windhoek</option>
                <option value="(UTC+03:00) Baghdad" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+03:00) Baghdad' ? 'selected' : '' }}>(UTC+03:00) Baghdad</option>
                <option value="(UTC+03:00) Istanbul" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+03:00) Istanbul' ? 'selected' : '' }}>(UTC+03:00) Istanbul</option>
                <option value="(UTC+03:00) Kuwait, Riyadh" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+03:00) Kuwait, Riyadh' ? 'selected' : '' }}>(UTC+03:00) Kuwait, Riyadh</option>
                <option value="(UTC+03:00) Minsk" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+03:00) Minsk' ? 'selected' : '' }}>(UTC+03:00) Minsk</option>
                <option value="(UTC+03:00) Moscow, St. Petersburg" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+03:00) Moscow, St. Petersburg' ? 'selected' : '' }}>(UTC+03:00) Moscow, St. Petersburg</option>
                <option value="(UTC+03:00) Nairobi" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+03:00) Nairobi' ? 'selected' : '' }}>(UTC+03:00) Nairobi</option>
                <option value="(UTC+03:30) Tehran" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+03:30) Tehran' ? 'selected' : '' }}>(UTC+03:30) Tehran</option>
                <option value="(UTC+04:00) Abu Dhabi, Muscat" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+04:00) Abu Dhabi, Muscat' ? 'selected' : '' }}>(UTC+04:00) Abu Dhabi, Muscat</option>
                <option value="(UTC+04:00) Astrakhan, Ulyanovsk" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+04:00) Astrakhan, Ulyanovsk' ? 'selected' : '' }}>(UTC+04:00) Astrakhan, Ulyanovsk</option>
                <option value="(UTC+04:00) Baku" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+04:00) Baku' ? 'selected' : '' }}>(UTC+04:00) Baku</option>
                <option value="(UTC+04:00) Izhevsk, Samara" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+04:00) Izhevsk, Samara' ? 'selected' : '' }}>(UTC+04:00) Izhevsk, Samara</option>
                <option value="(UTC+04:00) Port Louis" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+04:00) Port Louis' ? 'selected' : '' }}>(UTC+04:00) Port Louis</option>
                <option value="(UTC+04:00) Saratov" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+04:00) Saratov' ? 'selected' : '' }}>(UTC+04:00) Saratov</option>
                <option value="(UTC+04:00) Tbilisi" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+04:00) Tbilisi' ? 'selected' : '' }}>(UTC+04:00) Tbilisi</option>
                <option value="(UTC+04:00) Volgograd" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+04:00) Volgograd' ? 'selected' : '' }}>(UTC+04:00) Volgograd</option>
                <option value="(UTC+04:00) Yerevan" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+04:00) Yerevan' ? 'selected' : '' }}>(UTC+04:00) Yerevan</option>
                <option value="(UTC+04:30) Kabul" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+04:30) Kabul' ? 'selected' : '' }}>(UTC+04:30) Kabul</option>
                <option value="(UTC+05:00) Ashgabat, Tashkent" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+05:00) Ashgabat, Tashkent' ? 'selected' : '' }}>(UTC+05:00) Ashgabat, Tashkent</option>
                <option value="(UTC+05:00) Yekaterinburg" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+05:00) Yekaterinburg' ? 'selected' : '' }}>(UTC+05:00) Yekaterinburg</option>
                <option value="(UTC+05:00) Islamabad, Karachi" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+05:00) Islamabad, Karachi' ? 'selected' : '' }}>(UTC+05:00) Islamabad, Karachi</option>
                <option value="(UTC+05:00) Qyzylorda" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+05:00) Qyzylorda' ? 'selected' : '' }}>(UTC+05:00) Qyzylorda</option>
                <option value="(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi' ? 'selected' : '' }}>(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                <option value="(UTC+05:30) Sri Jayawardenepura" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+05:30) Sri Jayawardenepura' ? 'selected' : '' }}>(UTC+05:30) Sri Jayawardenepura</option>
                <option value="(UTC+05:45) Kathmandu" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+05:45) Kathmandu' ? 'selected' : '' }}>(UTC+05:45) Kathmandu</option>
                <option value="(UTC+06:00) Astana" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+06:00) Astana' ? 'selected' : '' }}>(UTC+06:00) Astana</option>
                <option value="(UTC+06:00) Dhaka" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+06:00) Dhaka' ? 'selected' : '' }}>(UTC+06:00) Dhaka</option>
                <option value="(UTC+06:00) Omsk" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+06:00) Omsk' ? 'selected' : '' }}>(UTC+06:00) Omsk</option>
                <option value="(UTC+06:30) Yangon (Rangoon)" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+06:30) Yangon (Rangoon)' ? 'selected' : '' }}>(UTC+06:30) Yangon (Rangoon)</option>
                <option value="(UTC+07:00) Bangkok, Hanoi, Jakarta" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+07:00) Bangkok, Hanoi, Jakarta' ? 'selected' : '' }}>(UTC+07:00) Bangkok, Hanoi, Jakarta</option>
                <option value="(UTC+07:00) Barnaul, Gorno-Altaysk" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+07:00) Barnaul, Gorno-Altaysk' ? 'selected' : '' }}>(UTC+07:00) Barnaul, Gorno-Altaysk</option>
                <option value="(UTC+07:00) Hovd" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+07:00) Hovd' ? 'selected' : '' }}>(UTC+07:00) Hovd</option>
                <option value="(UTC+07:00) Krasnoyarsk" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+07:00) Krasnoyarsk' ? 'selected' : '' }}>(UTC+07:00) Krasnoyarsk</option>
                <option value="(UTC+07:00) Novosibirsk" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+07:00) Novosibirsk' ? 'selected' : '' }}>(UTC+07:00) Novosibirsk</option>
                <option value="(UTC+07:00) Tomsk" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+07:00) Tomsk' ? 'selected' : '' }}>(UTC+07:00) Tomsk</option>
                <option value="(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi' ? 'selected' : '' }}>(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                <option value="(UTC+08:00) Irkutsk" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+08:00) Irkutsk' ? 'selected' : '' }}>(UTC+08:00) Irkutsk</option>
                <option value="(UTC+08:00) Kuala Lumpur, Singapore" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+08:00) Kuala Lumpur, Singapore' ? 'selected' : '' }}>(UTC+08:00) Kuala Lumpur, Singapore</option>
                <option value="(UTC+08:00) Perth" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+08:00) Perth' ? 'selected' : '' }}>(UTC+08:00) Perth</option>
                <option value="(UTC+08:00) Taipei" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+08:00) Taipei' ? 'selected' : '' }}>(UTC+08:00) Taipei</option>
                <option value="(UTC+08:00) Ulaanbaatar" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+08:00) Ulaanbaatar' ? 'selected' : '' }}>(UTC+08:00) Ulaanbaatar</option>
                <option value="(UTC+08:45) Eucla" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+08:45) Eucla' ? 'selected' : '' }}>(UTC+08:45) Eucla</option>
                <option value="(UTC+09:00) Chita" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+09:00) Chita' ? 'selected' : '' }}>(UTC+09:00) Chita</option>
                <option value="(UTC+09:00) Osaka, Sapporo, Tokyo" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+09:00) Osaka, Sapporo, Tokyo' ? 'selected' : '' }}>(UTC+09:00) Osaka, Sapporo, Tokyo</option>
                <option value="(UTC+09:00) Pyongyang" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+09:00) Pyongyang' ? 'selected' : '' }}>(UTC+09:00) Pyongyang</option>
                <option value="(UTC+09:00) Seoul" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+09:00) Seoul' ? 'selected' : '' }}>(UTC+09:00) Seoul</option>
                <option value="(UTC+09:00) Yakutsk" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+09:00) Yakutsk' ? 'selected' : '' }}>(UTC+09:00) Yakutsk</option>
                <option value="(UTC+09:30) Adelaide" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+09:30) Adelaide' ? 'selected' : '' }}>(UTC+09:30) Adelaide</option>
                <option value="(UTC+09:30) Darwin" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+09:30) Darwin' ? 'selected' : '' }}>(UTC+09:30) Darwin</option>
                <option value="(UTC+10:00) Brisbane" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+10:00) Brisbane' ? 'selected' : '' }}>(UTC+10:00) Brisbane</option>
                <option value="(UTC+10:00) Canberra, Melbourne, Sydney" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+10:00) Canberra, Melbourne, Sydney' ? 'selected' : '' }}>(UTC+10:00) Canberra, Melbourne, Sydney</option>
                <option value="(UTC+10:00) Guam, Port Moresby" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+10:00) Guam, Port Moresby' ? 'selected' : '' }}>(UTC+10:00) Guam, Port Moresby</option>
                <option value="(UTC+10:00) Hobart" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+10:00) Hobart' ? 'selected' : '' }}>(UTC+10:00) Hobart</option>
                <option value="(UTC+10:00) Vladivostok" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+10:00) Vladivostok' ? 'selected' : '' }}>(UTC+10:00) Vladivostok</option>
                <option value="(UTC+10:30) Lord Howe Island" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+10:30) Lord Howe Island' ? 'selected' : '' }}>(UTC+10:30) Lord Howe Island</option>
                <option value="(UTC+11:00) Bougainville Island" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+11:00) Bougainville Island' ? 'selected' : '' }}>(UTC+11:00) Bougainville Island</option>
                <option value="(UTC+11:00) Chokurdakh" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+11:00) Chokurdakh' ? 'selected' : '' }}>(UTC+11:00) Chokurdakh</option>
                <option value="(UTC+11:00) Magadan" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+11:00) Magadan' ? 'selected' : '' }}>(UTC+11:00) Magadan</option>
                <option value="(UTC+11:00) Norfolk Island" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+11:00) Norfolk Island' ? 'selected' : '' }}>(UTC+11:00) Norfolk Island</option>
                <option value="(UTC+11:00) Sakhalin" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+11:00) Sakhalin' ? 'selected' : '' }}>(UTC+11:00) Sakhalin</option>
                <option value="(UTC+11:00) Solomon Is., New Caledonia" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+11:00) Solomon Is., New Caledonia' ? 'selected' : '' }}>(UTC+11:00) Solomon Is., New Caledonia</option>
                <option value="(UTC+12:00) Anadyr, Petropavlovsk-Kamchatsky" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+12:00) Anadyr, Petropavlovsk-Kamchatsky' ? 'selected' : '' }}>(UTC+12:00) Anadyr, Petropavlovsk-Kamchatsky</option>
                <option value="(UTC+12:00) Auckland, Wellington" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+12:00) Auckland, Wellington' ? 'selected' : '' }}>(UTC+12:00) Auckland, Wellington</option>
                <option value="(UTC+12:00) Coordinated Universal Time + 12" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+12:00) Coordinated Universal Time + 12' ? 'selected' : '' }}>(UTC+12:00) Coordinated Universal Time + 12</option>
                <option value="(UTC+12:00) Fiji" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+12:00) Fiji' ? 'selected' : '' }}>(UTC+12:00) Fiji</option>
                <option value="(UTC+12:00) Petropavlovsk-Kamchatsky-Old" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+12:00) Petropavlovsk-Kamchatsky-Old' ? 'selected' : '' }}>(UTC+12:00) Petropavlovsk-Kamchatsky-Old</option>
                <option value="(UTC+12:45) Chatham Islands" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+12:45) Chatham Islands' ? 'selected' : '' }}>(UTC+12:45) Chatham Islands</option>
                <option value="(UTC+13:00) Coordinated Universal Time + 13" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+13:00) Coordinated Universal Time + 13' ? 'selected' : '' }}>(UTC+13:00) Coordinated Universal Time + 13</option>
                <option value="(UTC+13:00) Nuku'alofa" {{ old('time_zone', $user->time_zone ?? '') == "(UTC+13:00) Nuku'alofa" ? 'selected' : '' }}>(UTC+13:00) Nuku'alofa</option>
                <option value="(UTC+13:00) Samoa" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+13:00) Samoa' ? 'selected' : '' }}>(UTC+13:00) Samoa</option>
                <option value="(UTC+14:00) Kiritimati Island" {{ old('time_zone', $user->time_zone ?? '') == '(UTC+14:00) Kiritimati Island' ? 'selected' : '' }}>(UTC+14:00) Kiritimati Island</option>
            </select>
        </div>
    </div>
</div>