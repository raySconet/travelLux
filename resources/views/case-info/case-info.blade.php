<section class="mt-1">
    <h2   class="mb-1 text-2xl mt-1 text-gray-700 text-center border border-[#CCC] p-2 bg-[#eaf1ffd4]">
        {{ __('Case Info') }}
    </h2>
     <p class="mb-1 text-xl text-center text-gray-600">
        {{ __("1st Intake Convesation") }}
    </p>
    <x-case-components.grid   class="2xl:grid-cols-12">
        <div class="2xl:col-span-3 flex flex-col ">
            <div >
                <x-input-label for="referralChiro" :value="__('Referral')" />
                <x-text-input id="referralChiro" name="referralChiro" placeholder="Insert Referral" type="text" class="mt-1  text-center" :value="old('name')"  required autofocus autocomplete="referralChiro" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="chiro" :value="__('Chiro')" />
                <x-text-input id="chiro" name="chiro" placeholder="Insert Chiro" type="text" class="mt-1  text-center" :value="old('name')"  required autofocus autocomplete="chiro" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>
        <div class="2xl:col-span-3 flex flex-col ">
            <div>
                <x-input-label for="doi" :value="__('DOI')" />
                <x-text-input id="doi" name="doi"  placeholder="00/00/00" type="text" class="mt-1  text-center" :value="old('name')"  required autofocus autocomplete="doi" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
             <div>
                <x-input-label for="preExistingInjuries" :value="__('Pre-Existing Injuries')" />
                <x-text-input id="preExistingInjuries" name="preExistingInjuries"  placeholder="Back Surgery" type="text" class="mt-1   text-center" :value="old('name')"  required autofocus autocomplete="preExistingInjuries" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>

        <div class="2xl:col-span-3 flex flex-col ">
            <div>
                <x-input-label for="facts" :value="__('Facts')" />
                <x-text-area id="facts" name="facts" placeholder="How it happened" type="text" class="mt-1 h-[77px]  text-center" :value="old('name')"  required autofocus autocomplete="facts" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>
        <div class="2xl:col-span-3 flex flex-col ">
            <div>
                <x-input-label for="injuries" :value="__('Injuries')" />
                <x-text-area id="injuries" name="injuries" placeholder="Neck & Back" type="text" class="mt-1 h-[77px]  text-center" :value="old('name')"  required autofocus autocomplete="injuries" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>

    </x-case-components.grid>


    <x-case-components.grid class="2xl:grid-cols-12 mt-2">
        <x-case-components.col class="2xl:col-span-4 ">
            <x-input-label for="policeReport" :value="__('Police Report')" />
            <x-text-input  name="policeReport"  placeholder="Depth/Case# / In efile / None" type="text" class="mt-1  text-center"  :value="old('policeReport')"  required autofocus autocomplete="Email" />
            <x-input-error class="mt-2" :messages="$errors->get('policeReport')" />
        </x-case-components.col>

        <x-case-components.col class="2xl:col-span-4 ">
            <x-input-label for="photos" :value="__('Photos')" />
            <x-text-input  name="photos"  placeholder="TBD / In efile" type="text" class="mt-1  text-center"  :value="old('name')"  required autofocus autocomplete="photos" />
            <x-input-error class="mt-2" :messages="$errors->get('photos')" />
        </x-case-components.col>
        <x-case-components.col class="2xl:col-span-4 ">
            <x-input-label for="propertyDesctiption" :value="__('PropertyDesctiption')" />
            <x-text-input  name="propertyDesctiption"  placeholder="Low / Okay / Good / Very Good" type="text" class="mt-1  text-center"  :value="old('name')"  required autofocus autocomplete="propertyDesctiption" />
            <x-input-error class="mt-2" :messages="$errors->get('propertyDesctiption')" />
        </x-case-components.col>
    </x-case-components.grid>


    <x-case-components.grid class="2xl:grid-cols-12 mt-2">
        <x-case-components.col class="2xl:col-span-12 ">
            <p class="mb-1 text-md text-gray-700 text-center border border-[#CCC] p-2">
                {{ __("Part 2 (first 30 days)") }}
            </p>
        </x-case-components.col>

        <x-case-components.col class="2xl:col-span-3 ">
            <x-input-label for="3pLiability" :value="__('3P Liability')" />
            <x-text-input  name="3pLiability" placeholder="TBD" type="text" class="mt-1  text-center"  :value="old('3pLiability')"  required autofocus autocomplete="Email" />
            <x-input-error class="mt-2" :messages="$errors->get('3pLiability')" />
        </x-case-components.col>

        <x-case-components.col class="2xl:col-span-3 ">
            <x-input-label for="3pCoverage" :value="__('3P Coverage')" />
            <x-text-input  name="3pCoverage" placeholder="TDB" type="text" class="mt-1  text-center"  :value="old('name')"  required autofocus autocomplete="3pCoverage" />
            <x-input-error class="mt-2" :messages="$errors->get('3pCoverage')" />
        </x-case-components.col>
        <x-case-components.col class="2xl:col-span-3 ">
            <x-input-label for="3pLiabilityLimit" :value="__('3P Liability Limits')" />
            <x-text-input  name="3pLiabilityLimit" placeholder="$00K/ $00K OR WND" type="text" class="mt-1  text-center"  :value="old('name')"  required autofocus autocomplete="3pLiabilityLimit" />
            <x-input-error class="mt-2" :messages="$errors->get('3pLiabilityLimit')" />
        </x-case-components.col>
        <x-case-components.col class="2xl:col-span-3 ">
            <x-input-label for="1pCoverageLimits" :value="__('1P Coverage Limits')" />
            <x-text-input  name="1pCoverageLimits" placeholder="$00,000.00 or REJ" type="text" class="mt-1  text-center"  :value="old('name')"  required autofocus autocomplete="1pCoverageLimits" />
            <x-input-error class="mt-2" :messages="$errors->get('1pCoverageLimits')" />
        </x-case-components.col>
    </x-case-components.grid>

</section>
