<section>

    <h2 class="text-2xl  text-center text-yellow-700">
        {{ __('Contact Info') }}
    </h2>

    <x-case-components.grid class="2xl:grid-cols-12">
        {{--Client Section  --}}
        <x-case-components.col class="2xl:col-span-3 ">
            <p  class="mb-1 text-md  font-bold text-yellow-600 text-center border border-[#CCC] p-0.5 bg-[#eaf1ffd4]">
                {{ __("Client") }}
            </p>
            <div class="appendDuplicates items-center justify-center h-full  grid">
                <x-case-components.grid class="2xl:grid-cols-12  items-center justify-center" id="clientToDuplicate">
                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="name" :value="__('Name')" /> --}}
                        <x-text-input  name="name" type="text" placeholder="Name" class="mt-1 placeholder:text-yellow-600 text-center"  :value="old('name')"  required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="tel" :value="__('Tel')" /> --}}
                        <x-text-input  name="tel" type="text" placeholder="Tel" class="mt-1 placeholder:text-yellow-600 text-center"  :value="old('tel')"  required autofocus autocomplete="tel" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="Email" :value="__('Email')" /> --}}
                        <x-text-input  name="Email" type="text" class="mt-1 placeholder:text-yellow-600 text-center"  placeholder="Email" :value="old('email')"  required autofocus autocomplete="Email" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="Address" :value="__('Address')" /> --}}
                        <x-text-area  name="Address" type="text" class="mt-1  placeholder:text-yellow-600 text-center  h-[56px]"    placeholder="Address" :value="old('name')"  required autofocus autocomplete="Address" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="DOB" :value="__('DOB')" /> --}}
                        <x-text-input  name="DOB" type="text" class="mt-1 placeholder:text-yellow-600 text-center"  placeholder="DOB" :value="old('dob')"  required autofocus autocomplete="DOB" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="ssNumber" :value="__('SS#')" /> --}}
                        <x-text-input  name="ssNumber" type="text" class="mt-1 placeholder:text-yellow-600 text-center"  placeholder="SS#" :value="old('ssNumber')"  required autofocus autocomplete="ssNumber" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 items-center  ">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButton" style="margin-top:2px; width:22px; height:22px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>

                </x-case-components.grid>
            </div>
        </x-case-components.col>
        {{-- End Client Section  --}}


        <div class="2xl:col-span-3 flex flex-col ">
            <p class="   text-md  font-bold text-yellow-600 text-center border border-[#CCC] p-0.5 bg-[#eaf1ffd4]">
                {{ __("3P") }}
            </p>
            <div class="appendDuplicatesFor3p items-center justify-center h-full  grid" >
                <x-case-components.grid class="2xl:grid-cols-12 mt-2  items-center justify-center" id="clientToDuplicate">
                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="name" :value="__('Name')" /> --}}
                        <x-text-input  name="threePName" type="text" class="mt-1 placeholder:text-yellow-600 text-center"  placeholder="Name" :value="old('threePName')"  required autofocus autocomplete="threePName" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="tel" :value="__('Tel')" /> --}}
                        <x-text-input  name="threePTel" type="text"  placeholder="Tel" class="mt-1 placeholder:text-yellow-600 text-center"  :value="old('threePTel')"  required autofocus autocomplete="threePTel" />
                        <x-input-error class="mt-2" :messages="$errors->get('tel')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="threeEmail" :value="__('Email')" /> --}}
                        <x-text-input  name="threeEmail" type="text" class="mt-1 placeholder:text-yellow-600 text-center"   placeholder="Email" :value="old('name')"  required autofocus autocomplete="threeEmail" />
                        <x-input-error class="mt-2" :messages="$errors->get('Email')" />
                    </x-case-components.col>


                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="Address" :value="__('Address')" /> --}}
                        <x-text-input  name="threeClaim" type="text" class="mt-1 placeholder:text-yellow-600 text-center"  placeholder="Claim #" :value="old('name')"  required autofocus autocomplete="threeClaim" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>


                    <x-case-components.col class="2xl:col-span-12 items-center  ">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButtonFor3p" style="margin-top:2px; width:22px; height:22px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>

                </x-case-components.grid>
            </div>
        </div>


        <div class="2xl:col-span-3 flex flex-col ">
            <p class="mb-1 text-md  font-bold  text-yellow-600 text-center border border-[#CCC] p-0.5 bg-[#eaf1ffd4]">
                {{ __("1P") }}
            </p>
           <div class="appendDuplicatesFor1p  items-center justify-center h-full  grid gap-0 space-y-0">
                <x-case-components.grid  class="2xl:grid-cols-12  items-center justify-center gap-0 space-y-0" id="clientToDuplicate">
                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="name" :value="__('Name')" /> --}}
                        <x-text-input  name="onePName"  placeholder="Name"  type="text" class="mt-1 placeholder:text-yellow-600 text-center"  :value="old('name')"  required autofocus autocomplete="onePName" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="tel" :value="__('Tel')" /> --}}
                        <x-text-input  name="onePTel"  placeholder="Tel"  type="text" class="mt-1 placeholder:text-yellow-600 text-center"  :value="old('name')"  required autofocus autocomplete="onePTel" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="Email" :value="__('Email')" /> --}}
                        <x-text-input  name="onePEmail"  placeholder="Email"  type="text" class="mt-1 placeholder:text-yellow-600 text-center"  :value="old('name')"  required autofocus autocomplete="onePEmail" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>


                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="Address" :value="__('Address')" /> --}}
                        <x-text-input  name="onePClaim" type="text" class="mt-1 placeholder:text-yellow-600 text-center"  placeholder="Claim #" :value="old('name')"  required autofocus autocomplete="onePClaim" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>
                    {{-- <x-case-components.col class="2xl:col-span-2">
                        <x-input-label for="DOB" :value="__('DOB')" />
                        <x-text-input  name="DOB" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="DOB" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-2">
                        <x-input-label for="SS#" :value="__('SS#')" />
                        <x-text-input  name="SS#" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="SS#" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col> --}}

                    <x-case-components.col class="2xl:col-span-12 items-center  ">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButtonFor1p" style="margin-top:2px; width:22px; height:22px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>

                </x-case-components.grid>
            </div>
        </div>

        <div class="2xl:col-span-3 flex flex-col ">
            <p class="mb-1 text-md font-bold  text-yellow-600 text-center border border-[#CCC] p-0.5 bg-[#eaf1ffd4]">
                {{ __("Defense Counsel") }}
            </p>
            <div class="appendDuplicatesForDefense  items-center justify-center h-full grid">
                <x-case-components.grid   class="2xl:grid-cols-12 items-center justify-center" id="clientToDuplicate">

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="name" :value="__('Name')" /> --}}
                        <x-text-input  name="defenseCounselName"  placeholder="Name"  type="text" class="mt-1 placeholder:text-yellow-600 text-center"  :value="old('defenseCounselName')"  required autofocus autocomplete="defenseCounselName" />
                        {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="tel" :value="__('Tel')" /> --}}
                        <x-text-input  name="defenseCounselTel"  placeholder="Tel"  type="text" class="mt-1 placeholder:text-yellow-600 text-center"  :value="old('name')"  required autofocus autocomplete="defenseCounselTel" />
                        {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="Email" :value="__('Email')" /> --}}
                        <x-text-input  name="defenseCounselEmail"  placeholder="Email"  type="text" class="mt-1 placeholder:text-yellow-600 text-center"  :value="old('defenseCounselEmail')"  required autofocus autocomplete="defenseCounselEmail" />
                        {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="Address" :value="__('Address')" /> --}}
                        <x-text-input  name="defenseCounselAddress" placeholder="Address"   type="text" class="mt-1 placeholder:text-yellow-600 text-center"  :value="old('defenseCounselAddress')"  required autofocus autocomplete="defenseCounselAddress" />
                        {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="DOB" :value="__('DOB')" /> --}}
                        <x-text-input  name="defenseCounselCell" placeholder="Cell"  type="text" class="mt-1 placeholder:text-yellow-600 text-center"  :value="old('defenseCounselCell')"  required autofocus autocomplete="defenseCounselCell" />
                        {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 items-center">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButtonForDefense" style="margin-top:2px; width:22px; height:22px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>
                </x-case-components.grid>
            </div>
        </div>
    </x-case-components.grid>

</section>
