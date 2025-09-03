<section class="mt-1">
    <h2 class="text-lg font-medium text-gray-900">
        {{ __('Case Info') }}
    </h2>
     <p class="mb-1 text-md text-gray-600">
        {{ __("1st Intake Convesation") }}
    </p>
    <div class="grid grid-cols-1 2xl:grid-cols-12 gap-4 w-full">
        <div class="2xl:col-span-3 flex flex-col ">
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
        </div>

        <div class="2xl:col-span-3 flex flex-col ">
            <div>
                <x-input-label for="doi" :value="__('DOI')" />
                <x-text-input id="doi" name="doi" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="doi" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

        </div>

        <div class="2xl:col-span-3 flex flex-col ">
            <div>
                <x-input-label for="facts" :value="__('Facts')" />
                <x-text-area id="facts" name="facts" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="facts" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>

        <div class="2xl:col-span-3 flex flex-col ">
            <div>
                <x-input-label for="injuries" :value="__('Injuries')" />
                <x-text-input id="injuries" name="injuries" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="injuries" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

             <div>
                <x-input-label for="preExistingInjuries" :value="__('Pre-Existing Injuries')" />
                <x-text-input id="preExistingInjuries" name="preExistingInjuries" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="preExistingInjuries" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>
    </div>
</section>
