<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="fname" name="fname" value="{{ old('fname', $customer->fname ?? '') }}" />

            <x-input-label for="fname"> First Name</x-input-label>
        </div>
        <div class="relative mt-5">
            <x-text-input type="text" id="middleName" name="middleName" value="{{ old('mname', $customer->mname ?? '') }}" />

            <x-input-label for="middleName"> Middle Name</x-input-label>
        </div>
    </div>  

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="lastName" name="lastName" value="{{ old('lname', $customer->lname ?? '') }}" />

            <x-input-label for="lastName"> Last Name</x-input-label>
        </div>
        <div class="relative mt-5">
            <x-text-input type="text" id="nickName" name="nickName" value="{{ old('nickname', $customer->nickname ?? '') }}" />

            <x-input-label for="nickName"> Nick Name</x-input-label>
        </div>
    </div> 

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-5 flex items-center gap-3">
            <x-text-input type="email" id="email" name="email"  class="flex-1" value="{{ old('email', $customer->email ?? '') }}" />
            <x-input-label for="email" class="absolute -top-4 left-0">Email</x-input-label>


            <button type="button" class="text-[#f18325] text-2xl flex-shrink-0 mt-9">
                <i class="fas fa-plus-circle"></i>
            </button>
        </div>
    </div>
 

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <x-text-input type="text" id="cellPhoneNumber" name="cellPhoneNumber" value="{{ old('cellphone', $customer->cellphone ?? '') }}" />

            <x-input-label for="cellPhoneNumber">Cell Phone Number</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="homePhone" name="homePhone" value="{{ old('home_phone', $customer->home_phone ?? '') }}" />

            <x-input-label for="homePhone">Home Phone</x-input-label>
        </div>

        <div class="relative mt-5">
            <x-text-input type="text" id="workPhone" name="workPhone" value="{{ old('work_phone', $customer->work_phone ?? '') }}"  />

            <x-input-label for="workPhone">Work Phone</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-6">
            <label for="status">Status</label>
            <select name="status" id="status" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="Active" {{ old('status', $customer->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ old('status', $customer->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="Invited" {{ old('status', $customer->status ?? '') == 'Invited' ? 'selected' : '' }}>Invited</option>
                <option value="Paused" {{ old('status', $customer->status ?? '') == 'Paused' ? 'selected' : '' }}>Paused</option>
                <option value="Prospect" {{ old('status', $customer->status ?? '') == 'Prospect' ? 'selected' : '' }}>Prospect</option>
            </select>
        </div>

        <div class="relative mt-6">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1" {{ old('gender', $customer->gender ?? '') == '-1' ? 'selected' : '' }}>--Select Gender--</option>
                <option value="Male" {{ old('gender', $customer->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $customer->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

        <div x-data="dateDropdown('{{ old('birth_date', $customer->birth_date ?? '') }}')" class="relative mt-5">
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

            <input type="hidden" name="birthDate" :value="formattedDate">
        </div>

        <div x-data="dateDropdown('{{ old('anniversary_date', $customer->anniversary_date ?? '') }}')" class="relative mt-5">
            <label class="block text-sm mb-1">Anniversary Date</label>
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

            <input type="hidden" name="anniversary_date" :value="formattedDate">
        </div>

    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-6">
            <label for="profile_type">Profile Type</label>
            <select name="profile_type" id="profile_type" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1" {{ old('profile_type', $customer->profile_type ?? '') == '-1' ? 'selected' : '' }}>--Select Profile Type--</option>
                <option value="Corporate" {{ old('profile_type', $customer->profile_type ?? '') == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                <option value="Leisure" {{ old('profile_type', $customer->profile_type ?? '') == 'Leisure' ? 'selected' : '' }}>Leisure</option>
            </select>
        </div>


        <div class="relative mt-6">
            <label for="marital_status">Marial Status</label>
            <select name="marital_status" id="marital_status" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1" {{ old('marital_status', $customer->marital_status ?? '') == '-1' ? 'selected' : '' }}>--Select Marial Status --</option>
                <option value="Single" {{ old('marital_status', $customer->marital_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                <option value="Married" {{ old('marital_status', $customer->marital_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                <option value="Widowed" {{ old('marital_status', $customer->marital_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                <option value="DomesticPatner" {{ old('marital_status', $customer->marital_status ?? '') == 'DomesticPatner' ? 'selected' : '' }}>Domestic Partner</option>
            </select>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-6">
            <input 
                type="hidden" 
                name="retired" 
                value="0"
            >
            <input 
                type="checkbox" 
                class="h-4 w-4" 
                name="retired" 
                value="1" 
                {{ old('retired', $customer->retired ?? 0) == 1 ? 'checked' : '' }}
            >
            <label class="text-sm">Retired</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="hidden" name="children_at_home" value="0">
            <input 
                type="checkbox" 
                class="h-4 w-4" 
                name="children_at_home" 
                value="1" 
                {{ old('children_at_home', $customer->children_at_home ?? 0) == 1 ? 'checked' : '' }}
            >
            <label class="text-sm">Children at Home</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="hidden" name="virtuoso_life" value="0">
            <input 
                type="checkbox" 
                class="h-4 w-4" 
                name="virtuoso_life" 
                value="1" 
                {{ old('virtuoso_life', $customer->virtuoso_life ?? 0) == 1 ? 'checked' : '' }}
            >
            <label class="text-sm">Virtuoso Life</label>
        </div>
    </div>


    <div class="flex flex-col mt-6">
        <label for="special_notes" class="mb-1 text-sm text-gray-700">Special Notes</label>
        <textarea
            id="special_notes"
            name="special_notes"
            rows="3"
            class="w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1"
        >{{ old('special_notes', $customer->special_notes ?? '') }}</textarea>
    </div>

</div>