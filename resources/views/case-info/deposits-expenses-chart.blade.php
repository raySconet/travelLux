<section class="mt-1">
    <h2 class="text-lg font-medium text-gray-900">
        {{ __(' Deposits / Expenses / Advances') }}
    </h2>

    <x-case-components.grid class="2xl:grid-cols-12">
        {{--Client Section  --}}
        <x-case-components.col class="2xl:col-span-12 ">
            <p  class="mb-1 text-md  text-gray-700 text-center border border-[#CCC] p-2 bg-[#eaf1ffd4]">
                {{ __("Deposits") }}
            </p>
            <div class="appendDuplicates">
                <x-case-components.grid class="2xl:grid-cols-13" id="clientToDuplicate">
                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="depositsAmount" :value="__('Amount')" />
                        <x-text-input  name="depositsAmount" type="text" class="mt-1" :value="old('depositsAmount')"  required autofocus autocomplete="depositsAmount" />
                        <x-input-error class="mt-2" :messages="$errors->get('depositsAmount')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="deposistsName" :value="__('Name')" />
                        <x-text-input  name="deposistsName" type="text" class="mt-1" :value="old('deposistsName')"  required autofocus autocomplete="deposistsName" />
                        <x-input-error class="mt-2" :messages="$errors->get('deposistsName')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="depositsDate" :value="__('Date')" />
                        <x-text-input  name="depositsDate" type="text" class="mt-1 " :value="old('depositsDate')"  required autofocus autocomplete="depositsDate" />
                        <x-input-error class="mt-2" :messages="$errors->get('depositsDate')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="depositsCheckNumber" :value="__('CheckNumber')" />
                        <x-text-input  name="depositsCheckNumber" type="text" class="mt-1 " :value="old('depositsCheckNumber')"  required autofocus autocomplete="depositsCheckNumber" />
                        <x-input-error class="mt-2" :messages="$errors->get('depositsCheckNumber')" />
                    </x-case-components.col>


                    <x-case-components.col class="2xl:col-span-1 items-center  ">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButton" style="margin-top:22px; width:26px; height:26px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>

                </x-case-components.grid>
            </div>
        </x-case-components.col>
        {{-- End Client Section  --}}
    </x-case-components.grid>


    <x-case-components.grid  class="2xl:grid-cols-12 mt-2">
        <div class="2xl:col-span-12 flex flex-col ">
            <p class="mb-1 text-md mt-1 text-gray-700 text-center border border-[#CCC] p-2 bg-[#eaf1ffd4]">
                {{ __("Expenses") }}
            </p>
            <div class="appendDuplicatesFor3p">
                <x-case-components.grid class="2xl:grid-cols-13 mt-2" id="clientToDuplicate">
                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="expensesMethod" :value="__('Method')" />
                        <x-text-input  name="expensesMethod" type="text" class="mt-1" :value="old('expensesMethod')"  required autofocus autocomplete="expensesMethod" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="expensesAmount" :value="__('Amount')" />
                        <x-text-input  name="expensesAmount" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="expensesAmount" />
                        <x-input-error class="mt-2" :messages="$errors->get('expensesAmount')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="expensesDate" :value="__('Date')" />
                        <x-text-input  name="expensesDate" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="expensesDate" />
                        <x-input-error class="mt-2" :messages="$errors->get('expensesDate')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="expensesName" :value="__('Name')" />
                        <x-text-input  name="expensesName" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="expensesName" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-1 items-center  ">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButtonFor3p" style="margin-top:22px; width:26px; height:26px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>

                </x-case-components.grid>
            </div>
        </div>
    </x-case-components.grid>


    <x-case-components.grid  class="2xl:grid-cols-12 mt-2">
        <div class="2xl:col-span-12 flex flex-col ">
            <p class="mb-1 text-md mt-1 text-gray-700 text-center border border-[#CCC] p-2 bg-[#eaf1ffd4]">
                {{ __("Advances") }}
            </p>
           <div class="appendDuplicatesFor1p">
                <x-case-components.grid  class="2xl:grid-cols-13" id="clientToDuplicate">
                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="advancesAmount" :value="__('Amount')" />
                        <x-text-input  name="advancesAmount" type="text" class="mt-1" :value="old('advancesAmount')"  required autofocus autocomplete="advancesAmount" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="advancesName" :value="__('Name')" />
                        <x-text-input  name="advancesName" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="advancesName" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="advancesDate" :value="__('Date')" />
                        <x-text-input  name="advancesDate" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="advancesDate" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-3">
                        <x-input-label for="advancesCheckNumber" :value="__('Check Number')" />
                        <x-text-input  name="advancesCheckNumber" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="advancesCheckNumber" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-1 items-center  ">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButtonFor1p" style="margin-top:22px; width:26px; height:26px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>

                </x-case-components.grid>
            </div>
        </div>
    </x-case-components.grid>

</section>
