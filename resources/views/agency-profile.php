<x-app-layout>
    <x-slot name="header" >
        <div class="p-4 bg-white shadow sm:rounded-lg">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-ship mr-2 text-[#f18325]"></i>{{ __('Agency Profile') }}
            </h2>
        </div>
    </x-slot>

        <div class=" mx-auto py-4 px-4">
            <div class=" p-4 bg-white shadow sm:rounded-lg">
                <x-grid   class="2xl:grid-cols-12">
                    <x-col class="2xl:col-span-6 px-3">
                        <div>
                            <x-input-label for="policeReport" :value="__('Police Report')" />
                            <x-text-input  id="policeReport" name="policeReport"  placeholder="Depth/Case# / In efile / None" type="text" class="mt-1  text-center"  :value="old('policeReport')"   autofocus autocomplete="Email" />
                            <x-input-error class="mt-2" :messages="$errors->get('policeReport')" />
                        </div>
                        <div>
                            <x-input-label for="policeReport" :value="__('Police Report')" />
                            <x-text-input  id="policeReport" name="policeReport"  placeholder="Depth/Case# / In efile / None" type="text" class="mt-1  text-center"  :value="old('policeReport')"   autofocus autocomplete="Email" />
                            <x-input-error class="mt-2" :messages="$errors->get('policeReport')" />
                        </div>
                        <div>
                            <x-input-label for="policeReport" :value="__('Police Report')" />
                            <x-text-input  id="policeReport" name="policeReport"  placeholder="Depth/Case# / In efile / None" type="text" class="mt-1  text-center"  :value="old('policeReport')"   autofocus autocomplete="Email" />
                            <x-input-error class="mt-2" :messages="$errors->get('policeReport')" />
                        </div>
                    </x-col>

                    <x-col class="2xl:col-span-6 px-3">
                        <div>
                            <x-input-label for="policeReport" :value="__('Police Report')" />
                            <x-text-input  id="policeReport" name="policeReport"  placeholder="Depth/Case# / In efile / None" type="text" class="mt-1  text-center"  :value="old('policeReport')"   autofocus autocomplete="Email" />
                            <x-input-error class="mt-2" :messages="$errors->get('policeReport')" />
                        </div>
                        <div>
                            <x-input-label for="policeReport" :value="__('Police Report')" />
                            <x-text-input  id="policeReport" name="policeReport"  placeholder="Depth/Case# / In efile / None" type="text" class="mt-1  text-center"  :value="old('policeReport')"   autofocus autocomplete="Email" />
                            <x-input-error class="mt-2" :messages="$errors->get('policeReport')" />
                        </div>
                        <div>
                            <x-input-label for="policeReport" :value="__('Police Report')" />
                            <x-text-input  id="policeReport" name="policeReport"  placeholder="Depth/Case# / In efile / None" type="text" class="mt-1  text-center"  :value="old('policeReport')"   autofocus autocomplete="Email" />
                            <x-input-error class="mt-2" :messages="$errors->get('policeReport')" />
                        </div>
                    </x-col>

                </x-grid>
            </div>
        </div>
</x-app-layout>
