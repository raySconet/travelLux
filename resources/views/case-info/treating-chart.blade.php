<section class="mt-1">
    <h2   class="mb-1 mt-1 text-2xl  text-orange-600 text-center border border-[#CCC] p-2 bg-[#eaf1ffd4]">
        {{ __('Treating Chart') }}
    </h2>

    <div class="treatingAppendDuplicates">
        <div class= "grid grid-cols-1 2xl:grid-cols-14 gap-2 w-full text-orange-600" id="treatingToDuplicate">
            <x-case-components.col class="2xl:col-span-14 text-xl text-orange-600 text-center">
                <p> Norma Treating Chart </p>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div class=" text-orange-600">
                    <x-input-label for="ems"  class=" text-orange-600" :value="__('EMS')" />
                    <x-text-input id="ems" name="ems" type="text" placeholder="TBD / (00/00/00)" class="mt-1 placeholder:text-orange-600  text-center" :value="old('name')"  required autofocus autocomplete="ems" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div class=" text-orange-600">
                    <x-input-label for="hospital" class=" text-orange-600"  :value="__('Hospital')" />
                    <x-text-input id="hospital" name="hospital" type="text" placeholder="TBD / (00/00/00)" class="mt-1 placeholder:text-orange-600  text-center" :value="old('name')"  required autofocus autocomplete="hospital" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">

                <div class=" text-orange-600" >
                    <x-input-label for="chiropractor" class=" text-orange-600"  :value="__('Referral')" />
                    <x-text-input id="chiropractor" name="chiropractor" type="text" placeholder="TBD / (00/00/00)" class="mt-1 placeholder:text-orange-600  text-center" :value="old('name')"  required autofocus autocomplete="chiropractor" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div class=" text-orange-600" >
                    <x-input-label for="pcpmd" class=" text-orange-600"  :value="__('PCP/MD')" />
                    <x-text-input id="pcpmd" name="pcpmd" type="text" placeholder="TBD / (00/00/00)" class="mt-1 placeholder:text-orange-600  text-center" :value="old('name')"  required autofocus autocomplete="pcpmd" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div class=" text-orange-600" >
                    <x-input-label for="mriAndResults" class=" text-orange-600"  :value="__('MRI & RESULTS')" />
                    <x-text-input id="mriAndResults" name="mriAndResults" type="text" placeholder="TBD / (00/00/00)" class="mt-1 placeholder:text-orange-600  text-center" :value="old('name')"  required autofocus autocomplete="mriAndResults" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div class=" text-orange-600" >
                    <x-input-label for="painManagement" class=" text-orange-600"  :value="__('Pain Management')" />
                    <x-text-input id="painManagement" name="painManagement" type="text" placeholder="TBD / (00/00/00)" class="mt-1 placeholder:text-orange-600  text-center" :value="old('name')"  required autofocus autocomplete="painManagement" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div class=" text-orange-600" >
                    <x-input-label for="orthoOrSurgery" class=" text-orange-600"  :value="__('Ortho &/ Surgery')" />
                    <x-text-input id="orthoOrSurgery" name="orthoOrSurgery" type="text" placeholder="TBD / (00/00/00)" class="mt-1 placeholder:text-orange-600  text-center" :value="old('name')"  required autofocus autocomplete="orthoOrSurgery" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

        </div>
    </div>

</section>
