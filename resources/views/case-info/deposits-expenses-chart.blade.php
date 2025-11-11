<section class="mt-1 depositsExpensesSection">
    <h2 class="text-2xl font-medium text-center text-gray-900">
        {{ __(' Deposits / Expenses / Advances') }}
        <div class="flex float-right mt-[-5px] mb-[5px]">
            <x-primary-btn id="submitMainForm" style="height:36px; width:60px; font-size:14px; margin-top:2px;" class="submitMainForm">{{ __('Save') }}</x-primary-btn>
        </div>
    </h2>

    <x-case-components.grid class="2xl:grid-cols-12">
        {{--Client Section  --}}
        <x-case-components.col class="2xl:col-span-4 ">
            <p  class="mb-1 text-md font-bold  text-center border border-[#CCC] p-1 bg-[#eaf1ffd4]">
                {{ __("Deposits") }}
            </p>
            <div class="appendDuplicatesForDeposits">
                <x-case-components.grid class="2xl:grid-cols-12 DepositsToDuplicate" id="">
                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="depositsAmount" :value="__('Amount')" /> --}}
                        <x-text-input  name="depositsAmount"  placeholder="Amount"  type="text" class="mt-1 depositsAmount" :value="old('depositsAmount')"   autofocus autocomplete="depositsAmount" />
                        <x-input-error class="mt-2" :messages="$errors->get('depositsAmount')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="deposistsName" :value="__('Name')" /> --}}
                        <x-text-input  name="deposistsName"  placeholder="Name"  type="text" class="mt-1 deposistsName" :value="old('deposistsName')"   autofocus autocomplete="deposistsName" />
                        <x-input-error class="mt-2" :messages="$errors->get('deposistsName')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="depositsDate" :value="__('Date')" /> --}}
                        <x-text-input  name="depositsDate"  placeholder="Date"  type="text" class="mt-1 depositsDate" :value="old('depositsDate')"   autofocus autocomplete="depositsDate" />
                        <x-input-error class="mt-2" :messages="$errors->get('depositsDate')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="depositsCheckNumber" :value="__('CheckNumber')" /> --}}
                        <x-text-input  name="depositsCheckNumber" placeholder="CheckNumber"   type="text" class="mt-1 depositsCheckNumber" :value="old('depositsCheckNumber')"   autofocus autocomplete="depositsCheckNumber" />
                        <x-input-error class="mt-2" :messages="$errors->get('depositsCheckNumber')" />
                    </x-case-components.col>


                    <x-case-components.col class="2xl:col-span-12 items-center  ">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addButtonForDeposits" style="margin-top:2px; width:22px; height:22px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>

                </x-case-components.grid>
            </div>
        </x-case-components.col>
        {{-- End Client Section  --}}


        <div class="2xl:col-span-4 flex flex-col ">
            <p class="mb-1 text-md  font-bold text-gray-700 text-center border border-[#CCC] p-1 bg-[#eaf1ffd4]">
                {{ __("Expenses") }}
            </p>
            <div class="appendDuplicatesForExpenses">
                <x-case-components.grid class="2xl:grid-cols-13 expensesToDuplicate" id="">
                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="expensesMethod" :value="__('Method')" /> --}}
                        <x-text-input  name="expensesName"   placeholder="Name"  type="text" class="mt-1 expensesName" :value="old('expensesName')"   autofocus autocomplete="expensesName" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="expensesAmount" :value="__('Amount')" /> --}}
                        <x-text-input  name="expensesPrice"   placeholder="Amount"  type="text" class="mt-1 expensesPrice" :value="old('name')"   autofocus autocomplete="expensesPrice" />
                        <x-input-error class="mt-2" :messages="$errors->get('expensesPrice')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="expensesDate" :value="__('Date')" /> --}}
                        <x-text-input  name="expensesDate"  placeholder="Date"   type="text" class="mt-1 expensesDate" :value="old('name')"   autofocus autocomplete="expensesDate" />
                        <x-input-error class="mt-2" :messages="$errors->get('expensesDate')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="expensesCheck" :value="__('Name')" /> --}}
                        <x-text-input  name="expensesCheck"  placeholder="Check #"   type="text" class="mt-1 expensesCheck" :value="old('name')"   autofocus autocomplete="expensesCheck" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 items-center  ">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addButtonForExpenses" style="margin-top:2px; width:22px; height:22px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>

                </x-case-components.grid>
            </div>
        </div>


        <div class="2xl:col-span-4 flex flex-col ">
            <p class="mb-1 text-md font-bold  text-gray-700 text-center border border-[#CCC] p-1 bg-[#eaf1ffd4]">
                {{ __("Advances") }}
            </p>
            <div class="appendDuplicatesForAdvances">
                <x-case-components.grid  class="2xl:grid-cols-13 advancesToDuplicate" id="">
                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="advancesAmount" :value="__('Amount')" /> --}}
                        <x-text-input  name="advancesAmount" type="text"  placeholder="Amount"  class="mt-1 advancesAmount" :value="old('advancesAmount')"   autofocus autocomplete="advancesAmount" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="advancesName" :value="__('Name')" /> --}}
                        <x-text-input  name="advancesName" type="text"  placeholder="Name"  class="mt-1 advancesName" :value="old('name')"   autofocus autocomplete="advancesName" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="advancesDate" :value="__('Date')" /> --}}
                        <x-text-input  name="advancesDate" type="text"  placeholder="Date"  class="mt-1 advancesDate" :value="old('name')"   autofocus autocomplete="advancesDate" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12">
                        {{-- <x-input-label for="advancesCheckNumber" :value="__('Check Number')" /> --}}
                        <x-text-input  name="advancesCheckNumber" type="text"  placeholder="Check Number"  class="mt-1 advancesCheckNumber" :value="old('name')"   autofocus autocomplete="advancesCheckNumber" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </x-case-components.col>

                    <x-case-components.col class="2xl:col-span-12 items-center  ">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addButtonForAdvances" style="margin-top:2px; width:22px; height:22px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>

                </x-case-components.grid>
            </div>
        </div>
    </x-case-components.grid>

</section>

