<section class="mt-1">
     <h2   class="mb-1 mt-1 text-md  text-gray-700 text-center border border-[#CCC] p-2 bg-[#eaf1ffd4]">
        {{ __(' Deposits / Expenses / Advances') }}
    </h2>

    <div class="depositsAppendDuplicates">
        <div class= "grid grid-cols-1 2xl:grid-cols-14 gap-2 w-full " id="depositsToDuplicate">
            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="ems" :value="__('EMS')" />
                    <x-text-input id="ems" name="ems" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="ems" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="hospital" :value="__('Hospital')" />
                    <x-text-input id="hospital" name="hospital" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="hospital" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">

                <div >
                    <x-input-label for="chiropractor" :value="__('Referral')" />
                    <x-text-input id="chiropractor" name="chiropractor" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="chiropractor" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="pcpmd" :value="__('PCP/MD')" />
                    <x-text-input id="pcpmd" name="pcpmd" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="pcpmd" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="mriAndResults" :value="__('MRI & RESULTS')" />
                    <x-text-input id="mriAndResults" name="mriAndResults" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="mriAndResults" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="painManagement" :value="__('Pain Management')" />
                    <x-text-input id="painManagement" name="painManagement" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="painManagement" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="orthoOrSurgery" :value="__('Ortho &/ Surgery')" />
                    <x-text-input id="orthoOrSurgery" name="orthoOrSurgery" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="orthoOrSurgery" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

        </div>
    </div>

</section>
