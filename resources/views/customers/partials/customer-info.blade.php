<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="firstName" name="firstName" value="{{ $user->name }}" />

            <x-input-label for="firstName"> First Name</x-input-label>
        </div>
        <div class="relative mt-5">
            <x-text-input type="text" id="middleName" name="middleName" />

            <x-input-label for="middleName"> Middle Name</x-input-label>
        </div>
    </div>  

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="lastName" name="lastName" value="{{ $user->name }}" />

            <x-input-label for="lastName"> Last Name</x-input-label>
        </div>
        <div class="relative mt-5">
            <x-text-input type="text" id="nickName" name="nickName" />

            <x-input-label for="nickName"> Nick Name</x-input-label>
        </div>
    </div> 

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-5 flex items-center gap-3">
            <x-text-input type="email" id="email" name="email" value="{{ $user->email }}" class="flex-1" />
            <x-input-label for="email" class="absolute -top-4 left-0">Email</x-input-label>


            <button type="button" class="text-[#f18325] text-2xl flex-shrink-0 mt-9">
                <i class="fas fa-plus-circle"></i>
            </button>
        </div>
    </div>
 

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="cellPhoneNumber" name="cellPhoneNumber"  />

            <x-input-label for="cellPhoneNumber">Cell Phone Number</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="homePhone" name="homePhone"  />

            <x-input-label for="homePhone">Home Phone</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="workPhone" name="workPhone"  />

            <x-input-label for="workPhone">Work Phone</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-6">
            <label for="status">Status</label>
            <select name="status" id="status" class="w-full border-b-1 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Invited">Invited</option>
                <option value="Paused">Paused</option>
                <option value="Prospect">Prospect</option>
            </select>
        </div>

        <div class="relative mt-6">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" class="w-full border-b-1 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Gender --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
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
            <label class="block text-sm mb-1">Anniversary Date</label>
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
        <div class="relative mt-6">
            <label for="profileType">Profile Type</label>
            <select name="profileType" id="profileType" class="w-full border-b-1 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Profile Type--</option>
                <option value="Corporate">Corporate</option>
                <option value="Leisure">Leisure</option>
            </select>
        </div>

        <div class="relative mt-6">
            <label for="marialStatus">Marial Status</label>
            <select name="marialStatus" id="marialStatus" class="w-full border-b-1 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Marial Status --</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="DomesticPatner">Domestic Partner</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Retired</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Children at Home</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Virtuoso Life</label>
        </div>
    </div>

    <div class="flex flex-col mt-6">
        <label for="specialNotes" class="mb-1 text-sm text-gray-700">Special Notes</label>
        <textarea
            id="specialNotes"
            name="specialNotes"
            rows="3"
            class="w-full border-b border-gray-300 focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1">
        </textarea>
    </div>

</div>