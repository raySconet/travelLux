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

        <div x-data="dateDropdown()" class="relative mt-5">
            <label class="block text-sm mb-1">Birth Date</label>
            <div class="flex gap-2">
                <select x-model="year" class="form-input">
                    <option value="">Year</option>
                    <template x-for="y in years" :key="y">
                        <option :value="y" x-text="y"></option>
                    </template>
                </select>

                <select x-model="month" class="form-input">
                    <option value="">Month</option>
                    <template x-for="(m, i) in months" :key="i">
                        <option :value="i + 1" x-text="m"></option>
                    </template>
                </select>

                <select x-model="day" class="form-input">
                    <option value="">Day</option>
                    <template x-for="d in days" :key="d">
                        <option :value="d" x-text="d"></option>
                    </template>
                </select>

            </div>
            <input type="hidden" name="birthDate" :value="formattedDate">
        </div>


        <div x-data="dateDropdown()" class="relative mt-5">
            <label class="block text-sm mb-1">Hire Date</label>
            <div class="flex gap-2">
                <select x-model="year" class="form-input">
                    <option value="">Year</option>
                    <template x-for="y in years" :key="y">
                        <option :value="y" x-text="y"></option>
                    </template>
                </select>

                <select x-model="month" class="form-input">
                    <option value="">Month</option>
                    <template x-for="(m, i) in months" :key="i">
                        <option :value="i + 1" x-text="m"></option>
                    </template>
                </select>

                <select x-model="day" class="form-input">
                    <option value="">Day</option>
                    <template x-for="d in days" :key="d">
                        <option :value="d" x-text="d"></option>
                    </template>
                </select>

            </div>
            <input type="hidden" name="hireDate" :value="formattedDate">
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

        <div class="relative mt-6">
            <label for="timeZone">Time Zone</label>
            <select name="timeZone" id="timeZone" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">-- Select Time Zone --</option>
                <option value="(UTC-12:00) International Date Line West">(UTC-12:00) International Date Line West</option>
                <option value="(UTC-11:00) Coordinated Universal Time - 11">(UTC-11:00) Coordinated Universal Time - 11</option>
                <option value="(UTC-10:00) Aleutian Islands">(UTC-10:00) Aleutian Islands</option>
                <option value="(UTC-10:00) Hawaii">(UTC-10:00) Hawaii</option>
                <option value="(UTC-09:30) Marquesas Islands">(UTC-09:30) Marquesas Islands</option>
                <option value="(UTC-09:00) Alaska">(UTC-09:00) Alaska</option>
                <option value="(UTC-09:00) Coordinated Universal Time - 09">(UTC-09:00) Coordinated Universal Time - 09</option>
                <option value="(UTC-08:00) Baja California">(UTC-08:00) Baja California</option>
                <option value="(UTC-08:00) Coordinated Universal Time - 08">(UTC-08:00) Coordinated Universal Time - 08</option>
                <option value="(UTC-08:00) Pacific Time (US &amp; Canada)">(UTC-08:00) Pacific Time (US &amp; Canada)</option>
                <option value="(UTC-07:00) Arizona">(UTC-07:00) Arizona</option>
                <option value="(UTC-07:00) Chihuahua, La Paz, Mazatlan">(UTC-07:00) Chihuahua, La Paz, Mazatlan</option>
                <option value="(UTC-07:00) Mountain Time (US &amp; Canada)">(UTC-07:00) Mountain Time (US &amp; Canada)</option>
                <option value="(UTC-06:00) Central America">(UTC-06:00) Central America</option>
                <option value="(UTC-06:00) Central Time (US &amp; Canada)">(UTC-06:00) Central Time (US &amp; Canada)</option>
                <option value="(UTC-06:00) Easter Island">(UTC-06:00) Easter Island</option>
                <option value="(UTC-06:00) Guadalajara, Mexico City, Monterrey">(UTC-06:00) Guadalajara, Mexico City, Monterrey</option>
                <option value="(UTC-06:00) Saskatchewan">(UTC-06:00) Saskatchewan</option>
                <option value="(UTC-05:00) Bogota, Lima, Quito, Rio Branco">(UTC-05:00) Bogota, Lima, Quito, Rio Branco</option>
                <option value="(UTC-05:00) Chetumal">(UTC-05:00) Chetumal</option>
                <option value="(UTC-05:00) Eastern Time (US &amp; Canada)">(UTC-05:00) Eastern Time (US &amp; Canada)</option>
                <option value="(UTC-05:00) Haiti">(UTC-05:00) Haiti</option>
                <option value="(UTC-05:00) Havana">(UTC-05:00) Havana</option>
                <option value="(UTC-05:00) Indiana (East)">(UTC-05:00) Indiana (East)</option>
                <option value="(UTC-05:00) Turks And Caicos">(UTC-05:00) Turks And Caicos</option>
                <option value="(UTC-04:00) Asuncion">(UTC-04:00) Asuncion</option>
                <option value="(UTC-04:00) Atlantic Time (Canada)">(UTC-04:00) Atlantic Time (Canada)</option>
                <option value="(UTC-04:00) Caracas">(UTC-04:00) Caracas</option>
                <option value="(UTC-04:00) Cuiaba">(UTC-04:00) Cuiaba</option>
                <option value="(UTC-04:00) Georgetown, La Paz, Manaus, San Juan">(UTC-04:00) Georgetown, La Paz, Manaus, San Juan</option>
                <option value="(UTC-04:00) Santiago">(UTC-04:00) Santiago</option>
                <option value="(UTC-03:30) Newfoundland">(UTC-03:30) Newfoundland</option>
                <option value="(UTC-03:00) Araguaina">(UTC-03:00) Araguaina</option>
                <option value="(UTC-03:00) Brasilia">(UTC-03:00) Brasilia</option>
                <option value="(UTC-03:00) Cayenne, Fortaleza">(UTC-03:00) Cayenne, Fortaleza</option>
                <option value="(UTC-03:00) City of Buenos Aires">(UTC-03:00) City of Buenos Aires</option>
                <option value="(UTC-03:00) Greenland">(UTC-03:00) Greenland</option>
                <option value="(UTC-03:00) Montevideo">(UTC-03:00) Montevideo</option>
                <option value="(UTC-03:00) Punta Arenas">(UTC-03:00) Punta Arenas</option>
                <option value="(UTC-03:00) Saint Pierre and Miquelon">(UTC-03:00) Saint Pierre and Miquelon</option>
                <option value="(UTC-03:00) Salvador">(UTC-03:00) Salvador</option>
                <option value="(UTC-02:00) Coordinated Universal Time - 02">(UTC-02:00) Coordinated Universal Time - 02</option>
                <option value="(UTC-02:00) Mid-Atlantic-Old">(UTC-02:00) Mid-Atlantic-Old</option>
                <option value="(UTC-01:00) Azores">(UTC-01:00) Azores</option>
                <option value="(UTC-01:00) Cabo Verde Is.">(UTC-01:00) Cabo Verde Is.</option>
                <option value="(UTC) Coordinated Universal Time">(UTC) Coordinated Universal Time</option>
                <option value="(UTC+00:00) Dublin, Edinburgh, Libson, London">(UTC+00:00) Dublin, Edinburgh, Libson, London</option>
                <option value="(UTC+00:00) Monrovia, Reykjavik">(UTC+00:00) Monrovia, Reykjavik</option>
                <option value="(UTC+00:00) Sao Tome">(UTC+00:00) Sao Tome</option>
                <option value="(UTC+01:00) Casablanca">(UTC+01:00) Casablanca</option>
                <option value="(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna">(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                <option value="(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague">(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                <option value="(UTC+01:00) Brussels, Copenhagen, Madrid, Paris">(UTC+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                <option value="(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb">(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                <option value="(UTC+01:00) West Central Africa">(UTC+01:00) West Central Africa</option>
                <option value="(UTC+02:00) Amman">(UTC+02:00) Amman</option>
                <option value="(UTC+02:00) Athens, Bucharest">(UTC+02:00) Athens, Bucharest</option>
                <option value="(UTC+02:00) Beirut">(UTC+02:00) Beirut</option>
                <option value="(UTC+02:00) Cairo">(UTC+02:00) Cairo</option>
                <option value="(UTC+02:00) Chisinau">(UTC+02:00) Chisinau</option>
                <option value="(UTC+02:00) Damascus">(UTC+02:00) Damascus</option>
                <option value="(UTC+02:00) Gaza, Hebron">(UTC+02:00) Gaza, Hebron</option>
                <option value="(UTC+02:00) Harare, Pretoria">(UTC+02:00) Harare, Pretoria</option>
                <option value="(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius">(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                <option value="(UTC+02:00) Jerusalem">(UTC+02:00) Jerusalem</option>
                <option value="(UTC+02:00) Kaliningrad">(UTC+02:00) Kaliningrad</option>
                <option value="(UTC+02:00) Khartoum">(UTC+02:00) Khartoum</option>
                <option value="(UTC+02:00) Tripoli">(UTC+02:00) Tripoli</option>
                <option value="(UTC+02:00) Windhoek">(UTC+02:00) Windhoek</option>
                <option value="(UTC+03:00) Baghdad">(UTC+03:00) Baghdad</option>
                <option value="(UTC+03:00) Istanbul">(UTC+03:00) Istanbul</option>
                <option value="(UTC+03:00) Kuwait, Riyadh">(UTC+03:00) Kuwait, Riyadh</option>
                <option value="(UTC+03:00) Minsk">(UTC+03:00) Minsk</option>
                <option value="(UTC+03:00) Moscow, St. Petersburg">(UTC+03:00) Moscow, St. Petersburg</option>
                <option value="(UTC+03:00) Nairobi">(UTC+03:00) Nairobi</option>
                <option value="(UTC+03:30) Tehran">(UTC+03:30) Tehran</option>
                <option value="(UTC+04:00) Abu Dhabi, Muscat">(UTC+04:00) Abu Dhabi, Muscat</option>
                <option value="(UTC+04:00) Astrakhan, Ulyanovsk">(UTC+04:00) Astrakhan, Ulyanovsk</option>
                <option value="(UTC+04:00) Baku">(UTC+04:00) Baku</option>
                <option value="(UTC+04:00) Izhevsk, Samara">(UTC+04:00) Izhevsk, Samara</option>
                <option value="(UTC+04:00) Port Louis">(UTC+04:00) Port Louis</option>
                <option value="(UTC+04:00) Saratov">(UTC+04:00) Saratov</option>
                <option value="(UTC+04:00) Tbilisi">(UTC+04:00) Tbilisi</option>
                <option value="(UTC+04:00) Volgograd">(UTC+04:00) Volgograd</option>
                <option value="(UTC+04:00) Yerevan">(UTC+04:00) Yerevan</option>
                <option value="(UTC+04:30) Kabul">(UTC+04:30) Kabul</option>
                <option value="(UTC+05:00) Ashgabat, Tashkent">(UTC+05:00) Ashgabat, Tashkent</option>
                <option value="(UTC+05:00) Yekaterinburg">(UTC+05:00) Yekaterinburg</option>
                <option value="(UTC+05:00) Islamabad, Karachi">(UTC+05:00) Islamabad, Karachi</option>
                <option value="(UTC+05:00) Qyzylorda">(UTC+05:00) Qyzylorda</option>
                <option value="(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi">(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                <option value="(UTC+05:30) Sri Jayawardenepura">(UTC+05:30) Sri Jayawardenepura</option>
                <option value="(UTC+05:45) Kathmandu">(UTC+05:45) Kathmandu</option>
                <option value="(UTC+06:00) Astana">(UTC+06:00) Astana</option>
                <option value="(UTC+06:00) Dhaka">(UTC+06:00) Dhaka</option>
                <option value="(UTC+06:00) Omsk">(UTC+06:00) Omsk</option>
                <option value="(UTC+06:30) Yangon (Rangoon)">(UTC+06:30) Yangon (Rangoon)</option>
                <option value="(UTC+07:00) Bangkok, Hanoi, Jakarta">(UTC+07:00) Bangkok, Hanoi, Jakarta</option>
                <option value="(UTC+07:00) Barnaul, Gorno-Altaysk">(UTC+07:00) Barnaul, Gorno-Altaysk</option>
                <option value="(UTC+07:00) Hovd">(UTC+07:00) Hovd</option>
                <option value="(UTC+07:00) Krasnoyarsk">(UTC+07:00) Krasnoyarsk</option>
                <option value="(UTC+07:00) Novosibirsk">(UTC+07:00) Novosibirsk</option>
                <option value="(UTC+07:00) Tomsk">(UTC+07:00) Tomsk</option>
                <option value="(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi">(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                <option value="(UTC+08:00) Irkutsk">(UTC+08:00) Irkutsk</option>
                <option value="(UTC+08:00) Kuala Lumpur, Singapore">(UTC+08:00) Kuala Lumpur, Singapore</option>
                <option value="(UTC+08:00) Perth">(UTC+08:00) Perth</option>
                <option value="(UTC+08:00) Taipei">(UTC+08:00) Taipei</option>
                <option value="(UTC+08:00) Ulaanbaatar">(UTC+08:00) Ulaanbaatar</option>
                <option value="(UTC+08:45) Eucla">(UTC+08:45) Eucla</option>
                <option value="(UTC+09:00) Chita">(UTC+09:00) Chita</option>
                <option value="(UTC+09:00) Osaka, Sapporo, Tokyo">(UTC+09:00) Osaka, Sapporo, Tokyo</option>
                <option value="(UTC+09:00) Pyongyang">(UTC+09:00) Pyongyang</option>
                <option value="(UTC+09:00) Seoul">(UTC+09:00) Seoul</option>
                <option value="(UTC+09:00) Yakutsk">(UTC+09:00) Yakutsk</option>
                <option value="(UTC+09:30) Adelaide">(UTC+09:30) Adelaide</option>
                <option value="(UTC+09:30) Darwin">(UTC+09:30) Darwin</option>
                <option value="(UTC+10:00) Brisbane">(UTC+10:00) Brisbane</option>
                <option value="(UTC+10:00) Canberra, Melbourne, Sydney">(UTC+10:00) Canberra, Melbourne, Sydney</option>
                <option value="(UTC+10:00) Guam, Port Moresby">(UTC+10:00) Guam, Port Moresby</option>
                <option value="(UTC+10:00) Hobart">(UTC+10:00) Hobart</option>
                <option value="(UTC+10:00) Vladivostok">(UTC+10:00) Vladivostok</option>
                <option value="(UTC+10:30) Lord Howe Island">(UTC+10:30) Lord Howe Island</option>
                <option value="(UTC+11:00) Bougainville Island">(UTC+11:00) Bougainville Island</option>
                <option value="(UTC+11:00) Chokurdakh">(UTC+11:00) Chokurdakh</option>
                <option value="(UTC+11:00) Magadan">(UTC+11:00) Magadan</option>
                <option value="(UTC+11:00) Norfolk Island">(UTC+11:00) Norfolk Island</option>
                <option value="(UTC+11:00) Sakhalin">(UTC+11:00) Sakhalin</option>
                <option value="(UTC+11:00) Solomon Is., New Caledonia">(UTC+11:00) Solomon Is., New Caledonia</option>
                <option value="(UTC+12:00) Anadyr, Petropavlovsk-Kamchatsky">(UTC+12:00) Anadyr, Petropavlovsk-Kamchatsky</option>
                <option value="(UTC+12:00) Auckland, Wellington">(UTC+12:00) Auckland, Wellington</option>
                <option value="(UTC+12:00) Coordinated Universal Time + 12">(UTC+12:00) Coordinated Universal Time + 12</option>
                <option value="(UTC+12:00) Fiji">(UTC+12:00) Fiji</option>
                <option value="(UTC+12:00) Petropavlovsk-Kamchatsky-Old">(UTC+12:00) Petropavlovsk-Kamchatsky-Old</option>
                <option value="(UTC+12:45) Chatham Islands">(UTC+12:45) Chatham Islands</option>
                <option value="(UTC+13:00) Coordinated Universal Time + 13">(UTC+13:00) Coordinated Universal Time + 13</option>
                <option value="(UTC+13:00) Nuku'alofa">(UTC+13:00) Nuku'alofa</option>
                <option value="(UTC+13:00) Samoa">(UTC+13:00) Samoa</option>
                <option value="(UTC+14:00) Kiritimati Island">(UTC+14:00) Kiritimati Island</option>
            </select>
        </div>
    </div>
</div>

{{-- <script>
    function dateDropdown(minYear = 1920, maxYear = 2040) {
        return {
            day: '',
            month: '',
            year: '',

            days: Array.from({ length: 31 }, (_, i) => i + 1),

            months: [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ],

            years: Array.from(
                { length: maxYear - minYear + 1 },
                (_, i) => minYear + i
            ),

            get formattedDate() {
                if (!this.day || !this.month || !this.year) return '';
                return `${String(this.month).padStart(2, '0')}/` +
                       `${String(this.day).padStart(2, '0')}/` +
                       `${this.year}`;
            }
        };
    }
</script> --}}
