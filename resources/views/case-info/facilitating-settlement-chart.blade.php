<section class="mt-1">
    <h2   class="mb-1 mt-1 text-md  text-gray-700 text-center border border-[#CCC] p-2 bg-[#eaf1ffd4]">
        {{ __(' Facilitating Settlement Chart ') }}
    </h2>

    <div class="facilitatingAppendDuplicates">
        <div class= "grid grid-cols-1 2xl:grid-cols-14 gap-2 w-full " id="facilitatingToDuplicate">
            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="firstpPermissionToRelease" :value="__('1P Permission To Release')" />
                    <x-text-input id="firstpPermissionToRelease" name="firstpPermissionToRelease" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="firstpPermissionToRelease" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="thirdpRelease" :value="__('3P Release')" />
                    <x-text-input id="thirdpRelease" name="thirdpRelease" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="thirdpRelease" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">

                <div >
                    <x-input-label for="firstpRelease" :value="__('1P Release')" />
                    <x-text-input id="firstpRelease" name="firstpRelease" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="firstpRelease" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="pip" :value="__('PIP')" />
                    <x-text-input id="pip" name="pip" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="pip" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="statutoryLiens" :value="__('Statutory Liens')" />
                    <x-text-input id="statutoryLiens" name="statutoryLiens" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="statutoryLiens" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="otherLiensSubrogationInterests" :value="__('Other Liens / Subrogation Interests')" />
                    <x-text-input id="otherLiensSubrogationInterests" name="otherLiensSubrogationInterests" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="otherLiensSubrogationInterests" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="disbursal" :value="__('Disbursal')" />
                    <x-text-input id="disbursal" name="disbursal" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="disbursal" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>
        </div>

        <div class= "grid grid-cols-1 2xl:grid-cols-14 gap-2 w-full " id="facilitatingToDuplicate">
            <x-case-components.col class="2xl:col-span-14">
                <div >
                    <x-input-label for="checks" :value="__('Check(s)')" />
                    <x-text-area id="checks" name="checks" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="checks" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>
        </div>
    </div>

</section>
