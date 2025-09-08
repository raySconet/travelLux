<section>

    <h2 class="text-lg font-medium text-gray-900">
        {{ __('Contact Info') }}
    </h2>

    <x-case-components.grid>

        {{--Client Section  --}}
        <x-case-components.col class="2xl:col-span-12 ">

            <p class="mb-1 text-md  text-gray-700 text-center border border-[#CCC] p-2">
                {{ __("Client") }}
            </p>
            <div class="appendDuplicates">
                <x-case-components.grid id="clientToDuplicate">
                    <x-case-components.col class="2xl:col-span-2">
                        <x-input-label for="tel" :value="__('Tel')" />
                        <x-text-input  name="tel" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="tel" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-2">
                        <x-input-label for="Email" :value="__('Email')" />
                        <x-text-input  name="Email" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Email" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="Address" :value="__('Address')" />
                        <x-text-input  name="Address" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Address" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-2">
                        <x-input-label for="DOB" :value="__('DOB')" />
                        <x-text-input  name="DOB" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="DOB" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-2">
                        <x-input-label for="SS#" :value="__('SS#')" />
                        <x-text-input  name="SS#" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="SS#" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-1 items-center justify-center ">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButton" style="margin-top:20px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>

                </x-case-components.grid>
            </div>
        </x-case-components.col>
        {{-- End Client Section  --}}


        <div class="2xl:col-span-12 flex flex-col ">
            <p class="mb-1 text-md text-gray-700 text-center border border-[#CCC] p-2">
                {{ __("3P") }}
            </p>
            <div>
                <x-input-label for="tel" :value="__('Tel')" />
                <x-text-input  name="tel" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

                <div>
                <x-input-label for="Email" :value="__('Email')" />
                <x-text-input  name="Email" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Email" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

                <div>
                <x-input-label for="Address" :value="__('Address')" />
                <x-text-input  name="Address" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Address" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="DOB" :value="__('DOB')" />
                <x-text-input  name="DOB" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="DOB" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="SS#" :value="__('SS#')" />
                <x-text-input  name="SS#" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="SS#" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>

        <div class="2xl:col-span-12 flex flex-col ">
            <p class="mb-1 text-md text-gray-700  text-center border border-[#CCC] p-2">
                {{ __("1P") }}
            </p>
            <div>
                <x-input-label for="tel" :value="__('Tel')" />
                <x-text-input  name="tel" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

                <div>
                <x-input-label for="Email" :value="__('Email')" />
                <x-text-input  name="Email" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Email" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

                <div>
                <x-input-label for="Address" :value="__('Address')" />
                <x-text-input  name="Address" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Address" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="DOB" :value="__('DOB')" />
                <x-text-input  name="DOB" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="DOB" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="SS#" :value="__('SS#')" />
                <x-text-input  name="SS#" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="SS#" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>

        <div class="2xl:col-span-3 flex flex-col ">
            <p class="mb-1 text-md text-gray-700  text-center border border-[#CCC] p-2">
                {{ __("Defense Counsel") }}
            </p>
            <div>
                <x-input-label for="tel" :value="__('Tel')" />
                <x-text-input  name="tel" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="Email" :value="__('Email')" />
                <x-text-input  name="Email" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Email" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="Address" :value="__('Address')" />
                <x-text-input  name="Address" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Address" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="DOB" :value="__('DOB')" />
                <x-text-input  name="DOB" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="DOB" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="SS#" :value="__('SS#')" />
                <x-text-input  name="SS#" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="SS#" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>

    </x-case-components.grid>
</section>
