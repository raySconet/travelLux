<section class="mt-1">
    <h2   class="mb-1 mt-1 text-md  text-gray-700 text-center border border-[#CCC] p-2 bg-[#eaf1ffd4]">
        {{ __('Affidavit Chart') }}
    </h2>
    <h2 class=" text-lg">
        DO THE FOLLOWING AFTER DEFENDANT FILES ORIGINAL ANSWER “OA”:
    </h2>
    <p>
        -Immediately Calendar the Following Deadlines: <br>
	    30 Days from Defendant’s OA - “*CLIENT NAME” – Plaintiff’s Initial Disclosures Due – Use Lit Form L3” <br>
	    60 Days from Defendant’s OA – “*CLIENT NAME – 30 Days Deadline to Serve 18.001 Affidavit <br>
        90 Days from Defendant’s OA –“**CLIENT NAME” – 90 Day Deadline to Serve 18.001 Affs” <br>
	    120 Days from Defendant’s OA –“CLIENT NAME – Defendant’s 120 Day Controverting Affidavit Deadline” <br>
        -<i>Please see the affidavit chart below on how to handle service of affidavits under the law that went into effect Sept. 1, 2019.</i>
    </p>

    <h1 class="text-center text-lg">
        18.001 Affidavit Process
    </h1>

    <h2 class="text-center text-lg">
        18.001 Process/Guides for Lawsuit Filed Post Sept. 1, 2019
    </h2>
    <p class="text-center mt-1">
        The rule of thumb on service of 18.001 affidavits is to serve all billing affidavits on the 90th day from the date Defendant filed their original answer.
        However, due to our process in filing suit early, occasions may arise where this rule of thumb will not be able to be met. If that is the case,
        for the current file or any other, at the 30 Day until the Deadline to serve our 18.001 Affidavits,
        you will meet with your supervising attorney and pyramid leader
         to come up with a plan of execution for this particular file in accordance with the new 18.001 Statute and circumstances in this case.
    </p>

    <div class="affidavitAppendDuplicates mt-2">
        <div class= "grid grid-cols-1 2xl:grid-cols-13 gap-2 w-full ">
            <x-case-components.col class="2xl:col-span-13 text-center">
                    <p>Norma - Affidavit Chart </p>
            </x-case-components.col>
        </div>
        <div class= "grid grid-cols-1 2xl:grid-cols-13 gap-2 w-full " id="affidavitToDuplicate">


            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="providerName" :value="__('Provider Name')" />
                    <x-text-input id="providerName" name="providerName" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="providerName" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="dateOrdered" :value="__('Date Ordered')" />
                    <x-text-input id="dateOrdered" name="dateOrdered" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="dateOrdered" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="dateReceivedMr" :value="__('Date Received Mr')" />
                    <x-text-input id="dateReceivedMr" name="dateReceivedMr" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="dateReceivedMr" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div >
                    <x-input-label for="dateReceivedBr" :value="__('Date Received Br')" />
                    <x-text-input id="dateReceivedBr" name="dateReceivedBr" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="dateReceivedBr" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="affidavitDateServed" :value="__('Date Served')" />
                    <x-text-input id="affidavitDateServed" name="affidavitDateServed" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="affidavitDateServed" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="affidavitNoticeFiled" :value="__('Notice Filled')" />
                    <x-text-input id="affidavitNoticeFiled" name="affidavitNoticeFiled" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="affidavitNoticeFiled" />
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

            <x-case-components.col class="2xl:col-span-1 items-center">
                <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addAffidavitButton" style="margin-top:22px; width:26px; height:26px;">
                    <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                </div>
            </x-case-components.col>
        </div>
    </div>

</section>
