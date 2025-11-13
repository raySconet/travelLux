<section>

    <h2 class="text-2xl  text-center text-yellow-700">
        {{ __('Contact Info') }}
        <div class="flex float-right mt-[-5px] mb-[5px]">
            <x-primary-btn id=""  type="button" style="height:36px; width:60px; font-size:14px; margin-right:20px; background-color:#066606 !important;" class="editContactInfo">{{ __('Edit') }}</x-primary-btn>
        </div>
    </h2>

    <x-case-components.grid class="2xl:grid-cols-12 gap-x-3">
        {{--Client Section  --}}
        <x-case-components.col class="2xl:col-span-3 ">
            <p  class="mb-1 text-md  font-bold text-yellow-600 text-center border border-[#CCC] p-0.5 bg-[#eaf1ffd4]">
                {{ __("Client") }}
            </p>
            <div class="appendDuplicates  grid ">
                <x-case-components.grid class="2xl:grid-cols-12 clientToDuplicate" id=""  data-client="1">
                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="name" :value="__('Name')" /> --}}
                        <x-text-input  name="name" type="text" placeholder="Name" class="mt-1 clientNameInput placeholder:text-yellow-600 text-center"  :value="old('name')"   autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="tel" :value="__('Tel')" /> --}}
                        <x-text-input  name="tel" type="text" placeholder="Tel" class="mt-1 clientTel placeholder:text-yellow-600 text-center"  :value="old('tel')"   autofocus autocomplete="tel" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="Email" :value="__('Email')" /> --}}
                        <x-text-input  name="Email" type="text" class="mt-1 clientEmail placeholder:text-yellow-600 text-center"  placeholder="Email" :value="old('email')"   autofocus autocomplete="Email" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="Address" :value="__('Address')" /> --}}
                        <x-text-area  name="Address" type="text" class="mt-1 clientAddress placeholder:text-yellow-600 text-center  h-[56px]"    placeholder="Address" :value="old('name')"   autofocus autocomplete="Address" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="DOB" :value="__('DOB')" /> --}}
                        <x-text-input  name="DOB" type="text" class="mt-1 clientDob placeholder:text-yellow-600 text-center"  placeholder="DOB" :value="old('dob')"   autofocus autocomplete="DOB" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="ssNumber" :value="__('SS#')" /> --}}
                        <x-text-input  name="ssNumber" type="text" class="mt-1 clientSsn placeholder:text-yellow-600 text-center"  placeholder="SS#" :value="old('ssNumber')"   autofocus autocomplete="ssNumber" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <div class="flex flex-row 2xl:col-span-12 items-center clientButtons mt-2 gap-2" style="justify-content:center;">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButton" style="margin-top:2px; width:22px; height:22px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </span>
                    </div>

                </x-case-components.grid>
            </div>
        </x-case-components.col>
        {{-- End Client Section  --}}


        <div class="2xl:col-span-3 flex flex-col ">
            <p class="   text-md  font-bold text-yellow-600 text-center border border-[#CCC] p-0.5 bg-[#eaf1ffd4]">
                {{ __("3P") }}
            </p>
            <div class="appendDuplicatesFor3p    grid" >
                <x-case-components.grid class="2xl:grid-cols-12   threePToDuplicate" id="">
                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="name" :value="__('Name')" /> --}}
                        <select
                            id="threePName"
                            name="threePName"
                            class="
                                block w-full text-center threePName rounded-md mt-2 bg-white placeholder:text-yellow-600 text-center border border-gray-200
                                cursor-pointer
                            "
                            style="height:26px;"
                        >
                        </select>
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="Address" :value="__('Address')" /> --}}
                        <x-text-input  name="threeClaim" type="text" class="mt-1 threeClaim placeholder:text-yellow-600 text-center"  placeholder="Claim #" :value="old('name')"   autofocus autocomplete="threeClaim" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="tel" :value="__('Tel')" /> --}}
                        <x-text-input  name="threePBiAdjuster" type="text"  placeholder="BI Adjuster" class="mt-1 threePBiAdjuster placeholder:text-yellow-600 text-center"  :value="old('threePBiAdjuster')"   autofocus autocomplete="threePTel" />
                        <x-input-error class="mt-2" :messages="$errors->get('tel')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="tel" :value="__('Tel')" /> --}}
                        <x-text-input  name="threePTel" type="text"  placeholder="Tel" class="mt-1 threePTel placeholder:text-yellow-600 text-center"  :value="old('threePTel')"   autofocus autocomplete="threePTel" />
                        <x-input-error class="mt-2" :messages="$errors->get('tel')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="threeEmail" :value="__('Email')" /> --}}
                        <x-text-input  name="threeFax" type="text" class="mt-1 threeFax placeholder:text-yellow-600 text-center"   placeholder="Fax" :value="old('name')"   autofocus autocomplete="threeFax" />
                        <x-input-error class="mt-2" :messages="$errors->get('Email')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="threeEmail" :value="__('Email')" /> --}}
                        <x-text-input  name="threeEmail" type="text" class="mt-1 threeEmail placeholder:text-yellow-600 text-center"   placeholder="Email" :value="old('name')"   autofocus autocomplete="threeEmail" />
                        <x-input-error class="mt-2" :messages="$errors->get('Email')" />
                    </x-case-components.col>



                    <x-case-components.col class="2xl:col-span-12 items-center mt-2 clientButtonsThreeP ">
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
           <div class="appendDuplicatesFor1p    grid gap-0 space-y-0">
                <x-case-components.grid  class="2xl:grid-cols-12   gap-0 space-y-0 firstPToDuplicate" id="">
                    <x-case-components.col class="2xl:col-span-12 hidden">

                        <select
                            id="onePName"
                            name="onePName"
                            class="
                                block w-full text-center onePName rounded-md mt-2 bg-white placeholder:text-yellow-600 text-center border border-gray-200
                                cursor-pointer
                            "
                            style="height:26px;"
                        >
                        </select>
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="Address" :value="__('Address')" /> --}}
                        <x-text-input  name="onePClaim" type="text" class="mt-1 onePClaim placeholder:text-yellow-600 text-center"  placeholder="Claim #" :value="old('name')"   autofocus autocomplete="onePClaim" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        <x-text-input  name="onePBiAdjuster"  placeholder="Bi Adjuster"  type="text" class="mt-1 onePBiAdjuster placeholder:text-yellow-600 text-center"  :value="old('name')"   autofocus autocomplete="onePBiAdjuster" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="tel" :value="__('Tel')" /> --}}
                        <x-text-input  name="onePTel"  placeholder="Tel"  type="text" class="mt-1 onePTel placeholder:text-yellow-600 text-center"  :value="old('name')"   autofocus autocomplete="onePTel" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="Email" :value="__('Email')" /> --}}
                        <x-text-input  name="onePFax"  placeholder="Fax"  type="text" class="mt-1 onePFax placeholder:text-yellow-600 text-center"  :value="old('name')"   autofocus autocomplete="onePFax" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="Email" :value="__('Email')" /> --}}
                        <x-text-input  name="onePEmail"  placeholder="Email"  type="text" class="mt-1 onePEmail placeholder:text-yellow-600 text-center"  :value="old('name')"   autofocus autocomplete="onePEmail" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>



                    {{-- <x-case-components.col class="2xl:col-span-2">
                        <x-input-label for="DOB" :value="__('DOB')" />
                        <x-text-input  name="DOB" type="text" class="mt-1 " :value="old('name')"   autofocus autocomplete="DOB" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-2">
                        <x-input-label for="SS#" :value="__('SS#')" />
                        <x-text-input  name="SS#" type="text" class="mt-1 " :value="old('name')"   autofocus autocomplete="SS#" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col> --}}

                    <x-case-components.col class="2xl:col-span-12 items-center clientButtonsOneP mt-2 ">
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
            <div class="appendDuplicatesForDefense  grid">
                <x-case-components.grid   class="2xl:grid-cols-12 defenseToDuplicate" id="">

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="name" :value="__('Name')" /> --}}
                        <x-text-input  name="defenseCounselName"  placeholder="Defense Law Firm"  type="text" class="mt-1 defenseCounselName placeholder:text-yellow-600 text-center"  :value="old('defenseCounselName')"   autofocus autocomplete="defenseCounselName" />
                        {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="name" :value="__('Name')" /> --}}
                        <x-text-input  name="defenseCounselAttorney"  placeholder="Defense Attorney"  type="text" class="mt-1 defenseCounselAttorney placeholder:text-yellow-600 text-center"  :value="old('defenseCounselName')"   autofocus autocomplete="defenseCounselName" />
                        {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="Address" :value="__('Address')" /> --}}
                        <x-text-area  name="defenseCounselAddress" placeholder="Address"   type="text" class="mt-1 defenseCounselAddress placeholder:text-yellow-600 text-center"  :value="old('defenseCounselAddress')"   autofocus autocomplete="defenseCounselAddress" />
                        {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="tel" :value="__('Tel')" /> --}}
                        <x-text-input  name="defenseCounselTel"  placeholder="Tel"  type="text" class="mt-1 defenseCounselTel placeholder:text-yellow-600 text-center"  :value="old('name')"   autofocus autocomplete="defenseCounselTel" />
                        {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="Email" :value="__('Email')" /> --}}
                        <x-text-input  name="defenseCounselEmail"  placeholder="Email"  type="text" class="mt-1 defenseCounselEmail placeholder:text-yellow-600 text-center"  :value="old('defenseCounselEmail')"   autofocus autocomplete="defenseCounselEmail" />
                        {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 hidden">
                        {{-- <x-input-label for="Email" :value="__('Email')" /> --}}
                        <x-text-input  name="defenseCounselFax"  placeholder="Fax"  type="text" class="mt-1 defenseCounselFax placeholder:text-yellow-600 text-center"  :value="old('defenseCounselFax')"   autofocus autocomplete="defenseCounselEmail" />
                        {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 items-center clientButtonsDefense mt-2">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButtonForDefense" style="margin-top:2px; width:22px; height:22px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>
                </x-case-components.grid>
            </div>
        </div>
    </x-case-components.grid>

</section>
