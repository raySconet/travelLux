<section class="mt-1">
    <h2 class="text-lg font-medium text-gray-900">
        {{ __('Treating Chart') }}
    </h2>
    <x-case-components.grid >
        <x-case-components.col class="2xl:col-span-3">

            <div >
                <x-input-label for="referralChiro" :value="__('Referral')" />
                <x-text-input id="referralChiro" name="referralChiro" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="referralChiro" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="chiro" :value="__('Chiro')" />
                <x-text-input id="chiro" name="chiro" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="chiro" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </x-case-components.col>

        <x-case-components.col class="2xl:col-span-3">
            <div>
                <x-input-label for="injuries" :value="__('Injuries')" />
                <x-text-area id="injuries" name="injuries" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="injuries" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </x-case-components.col>

        <x-case-components.col class="2xl:col-span-3">
            <div>
                <x-input-label for="facts" :value="__('Facts')" />
                <x-text-area id="facts" name="facts" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="facts" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </x-case-components.col>

        <x-case-components.col class="2xl:col-span-3">
            <div>
                <x-input-label for="doi" :value="__('DOI')" />
                <x-text-input id="doi" name="doi" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="doi" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
             <div>
                <x-input-label for="preExistingInjuries" :value="__('Pre-Existing Injuries')" />
                <x-text-input id="preExistingInjuries" name="preExistingInjuries" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="preExistingInjuries" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </x-case-components.col>
    </x-case-components.grid>


    <x-case-components.grid class="mt-2">
        <x-case-components.col class="2xl:col-span-4 ">
            <x-input-label for="policeReport" :value="__('Police Report')" />
            <x-text-input  name="policeReport" type="text" class="mt-1 " :value="old('policeReport')"  required autofocus autocomplete="Email" />
            <x-input-error class="mt-2" :messages="$errors->get('policeReport')" />
        </x-case-components.col>

        <x-case-components.col class="2xl:col-span-4 ">
            <x-input-label for="photos" :value="__('Photos')" />
            <x-text-input  name="photos" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="photos" />
            <x-input-error class="mt-2" :messages="$errors->get('photos')" />
        </x-case-components.col>
        <x-case-components.col class="2xl:col-span-4 ">
            <x-input-label for="propertyDesctiption" :value="__('PropertyDesctiption')" />
            <x-text-input  name="propertyDesctiption" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="propertyDesctiption" />
            <x-input-error class="mt-2" :messages="$errors->get('propertyDesctiption')" />
        </x-case-components.col>
    </x-case-components.grid>


    <x-case-components.grid class="mt-2">
        <x-case-components.col class="2xl:col-span-12 ">
            <p class="mb-1 text-md text-gray-700 text-center border border-[#CCC] p-2">
                {{ __("Part 2 (first 30 days)") }}
            </p>
        </x-case-components.col>

        <x-case-components.col class="2xl:col-span-3 ">
            <x-input-label for="3pLiability" :value="__('3P Liability')" />
            <x-text-input  name="3pLiability" type="text" class="mt-1 " :value="old('3pLiability')"  required autofocus autocomplete="Email" />
            <x-input-error class="mt-2" :messages="$errors->get('3pLiability')" />
        </x-case-components.col>

        <x-case-components.col class="2xl:col-span-3 ">
            <x-input-label for="3pCoverage" :value="__('3P Coverage')" />
            <x-text-input  name="3pCoverage" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="3pCoverage" />
            <x-input-error class="mt-2" :messages="$errors->get('3pCoverage')" />
        </x-case-components.col>
        <x-case-components.col class="2xl:col-span-3 ">
            <x-input-label for="3pLiabilityLimit" :value="__('3P Liability Limit')" />
            <x-text-input  name="3pLiabilityLimit" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="3pLiabilityLimit" />
            <x-input-error class="mt-2" :messages="$errors->get('3pLiabilityLimit')" />
        </x-case-components.col>
        <x-case-components.col class="2xl:col-span-3 ">
            <x-input-label for="3pCoverageLimits" :value="__('3P Coverage Limits')" />
            <x-text-input  name="3pCoverageLimits" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="3pCoverageLimits" />
            <x-input-error class="mt-2" :messages="$errors->get('3pCoverageLimits')" />
        </x-case-components.col>
    </x-case-components.grid>


</section>
