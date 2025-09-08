<section>

    <h2 class="text-lg font-medium text-gray-900">
        {{ __('Contact Info') }}
    </h2>

    <x-case-components.grid>
        <x-case-components.col class="2xl:col-span-12">

            <p class="mb-1 text-md  text-gray-700 text-center border border-[#CCC] p-2">
                {{ __("Client") }}
            </p>
            <x-case-components.grid>
                <x-case-components.col class="2xl:col-span-2">
                    <x-input-label for="tel" :value="__('Tel')" />
                    <x-text-input id="tel" name="tel" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="tel" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </x-case-components.col>

                <x-case-components.col class="2xl:col-span-2">
                    <x-input-label for="Email" :value="__('Email')" />
                    <x-text-input id="Email" name="Email" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Email" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </x-case-components.col>

                <x-case-components.col class="2xl:col-span-3">
                    <x-input-label for="Address" :value="__('Address')" />
                    <x-text-input id="Address" name="Address" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Address" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </x-case-components.col>

                <x-case-components.col class="2xl:col-span-2">
                    <x-input-label for="DOB" :value="__('DOB')" />
                    <x-text-input id="DOB" name="DOB" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="DOB" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </x-case-components.col>

                <x-case-components.col class="2xl:col-span-2">
                    <x-input-label for="SS#" :value="__('SS#')" />
                    <x-text-input id="SS#" name="SS#" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="SS#" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </x-case-components.col>

                <x-case-components.col class="2xl:col-span-2">
                    <x-input-label for="tel" :value="__('Tel')" />
                    <x-text-input id="tel" name="tel" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="tel" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </x-case-components.col>

                <x-case-components.col class="2xl:col-span-2">
                    <x-input-label for="Email" :value="__('Email')" />
                    <x-text-input id="Email" name="Email" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Email" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </x-case-components.col>

                <x-case-components.col class="2xl:col-span-3">
                    <x-input-label for="Address" :value="__('Address')" />
                    <x-text-input id="Address" name="Address" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Address" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </x-case-components.col>

                <x-case-components.col class="2xl:col-span-2">
                    <x-input-label for="DOB" :value="__('DOB')" />
                    <x-text-input id="DOB" name="DOB" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="DOB" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </x-case-components.col>

                <x-case-components.col class="2xl:col-span-2">
                    <x-input-label for="SS#" :value="__('SS#')" />
                    <x-text-input id="SS#" name="SS#" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="SS#" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </x-case-components.col>

                <x-case-components.col class="2xl:col-span-1 items-center justify-center ">
                    <div class="flex items-center justify-center w-7 h-7 rounded-full bg-[#14548d] ">
                        <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                    </div>
                </x-case-components.col>

            </x-case-components.grid>
        </x-case-components.col>

        <div class="2xl:col-span-12 flex flex-col ">
            <p class="mb-1 text-md text-gray-700 text-center border border-[#CCC] p-2">
                {{ __("3P") }}
            </p>
            <div>
                <x-input-label for="tel" :value="__('Tel')" />
                <x-text-input id="tel" name="tel" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

                <div>
                <x-input-label for="Email" :value="__('Email')" />
                <x-text-input id="Email" name="Email" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Email" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

                <div>
                <x-input-label for="Address" :value="__('Address')" />
                <x-text-input id="Address" name="Address" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Address" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="DOB" :value="__('DOB')" />
                <x-text-input id="DOB" name="DOB" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="DOB" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="SS#" :value="__('SS#')" />
                <x-text-input id="SS#" name="SS#" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="SS#" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>

        <div class="2xl:col-span-12 flex flex-col ">
            <p class="mb-1 text-md text-gray-700  text-center border border-[#CCC] p-2">
                {{ __("1P") }}
            </p>
            <div>
                <x-input-label for="tel" :value="__('Tel')" />
                <x-text-input id="tel" name="tel" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

                <div>
                <x-input-label for="Email" :value="__('Email')" />
                <x-text-input id="Email" name="Email" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Email" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

                <div>
                <x-input-label for="Address" :value="__('Address')" />
                <x-text-input id="Address" name="Address" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Address" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="DOB" :value="__('DOB')" />
                <x-text-input id="DOB" name="DOB" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="DOB" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="SS#" :value="__('SS#')" />
                <x-text-input id="SS#" name="SS#" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="SS#" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>

        <div class="2xl:col-span-3 flex flex-col ">
            <p class="mb-1 text-md text-gray-700  text-center border border-[#CCC] p-2">
                {{ __("Defense Counsel") }}
            </p>
            <div>
                <x-input-label for="tel" :value="__('Tel')" />
                <x-text-input id="tel" name="tel" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

                <div>
                <x-input-label for="Email" :value="__('Email')" />
                <x-text-input id="Email" name="Email" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Email" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

                <div>
                <x-input-label for="Address" :value="__('Address')" />
                <x-text-input id="Address" name="Address" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="Address" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="DOB" :value="__('DOB')" />
                <x-text-input id="DOB" name="DOB" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="DOB" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="SS#" :value="__('SS#')" />
                <x-text-input id="SS#" name="SS#" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="SS#" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>


    </x-case-components.grid>
</section>
