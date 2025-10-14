<section class="mt-1 litigationChartSection">
    <h2   class="mb-1 mt-1 text-2xl  text-gray-700 text-center border border-[#CCC] p-2 bg-[#eaf1ffd4]">
        {{ __('Litigation Chart') }}
    </h2>

    <div class="">
        <div class= "grid grid-cols-1 2xl:grid-cols-10 gap-2 w-full " id="">
            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="caseInfoStyle"  :value="__('Case Info Style')" />
                    <x-text-input id="caseInfoStyle"  placeholder="Jane Doe"  name="caseInfoStyle" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="caseInfoStyle" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="causeNumber" :value="__('Cause No.')" />
                    <x-text-input id="causeNumber" placeholder="0101010101"  name="causeNumber" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="causeNumber" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="courtCounty" :value="__('Court / County')" />
                    <x-text-input id="courtCounty" name="courtCounty"  placeholder="Harris125 / District" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="courtCounty" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="filed" :value="__('Filed')" />
                    <x-text-input id="filed"  placeholder="00/00/00"  name="filed" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="filed" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="served" :value="__('Served')" />
                    <x-text-input id="served"  placeholder="00/00/00"  name="served" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="served" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="answer" :value="__('Answer')" />
                    <x-text-input id="answer"  placeholder="00/00/00"  name="answer" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="answer" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="discoveryResponsesP" :value="__('Discovery Responses P')" />
                    <x-text-input id="discoveryResponsesP"  placeholder="00/00/00"  name="discoveryResponsesP" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="discoveryResponsesP" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="discoveryResponsesD" :value="__('Discovery Responses D')" />
                    <x-text-input id="discoveryResponsesD"  placeholder="00/00/00"  name="discoveryResponsesD" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="discoveryResponsesD" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div >
                    <x-input-label for="discoveryPeriodEnds" :value="__('Discovery Period Ends')" />
                    <x-text-input id="discoveryPeriodEnds" placeholder="00/00/00"  name="discoveryPeriodEnds" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="discoveryPeriodEnds" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="deposP" :value="__('Depos P')" />
                    <x-text-input id="deposP" name="deposP"  placeholder="00/00/00"  type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="deposP" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <p class="text-center">Do not schedule P's Depo until D has properly answered our discovery. </p>
                </div>
                <div>
                    <x-input-label for="deposD" :value="__('Depos D')" />
                    <x-text-input id="deposD" name="deposD"  placeholder="00/00/00"  type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="deposD" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="billingServedDate" :value="__('Billing Served')" />
                    <x-text-input id="billingServedDate"  placeholder="00/00/00"  name="billingServedDate" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="billingServedDate" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="noticeFiledDate" :value="__('Notice Served')" />
                    <x-text-input id="noticeFiledDate"  placeholder="00/00/00"  name="noticeFiledDate" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="noticeFiledDate" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="trial" :value="__('Trial')" />
                    <x-text-input id="trial"  placeholder="00/00/00"  name="trial" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="trial" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>
        </div>

        <div class= "grid grid-cols-1 2xl:grid-cols-10 gap-2 w-full " id="">
            <x-case-components.col class="2xl:col-span-2">
                <div>
                    <x-input-label for="recordsServed" :value="__('Records Served')" />
                    <x-text-input id="recordsServed"  placeholder="00/00/00"  name="recordsServed" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="recordsServed" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="expertDesignationDeadlinesP" :value="__('Expert Designation Deadlines P ')" />
                    <x-text-input id="expertDesignationDeadlinesP"  placeholder="00/00/00"  name="expertDesignationDeadlinesP" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="expertDesignationDeadlinesP" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="expertDesignationDeadlinesP" :value="__('Expert Designation Deadlines P ')" />
                    <x-text-input id="expertDesignationDeadlinesP"  placeholder="00/00/00"  name="expertDesignationDeadlinesP" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="expertDesignationDeadlinesP" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">
                <div >
                    <x-input-label for="docketCall" :value="__('Docket Call')" />
                    <x-text-input id="docketCall"  placeholder="00/00/00"  name="docketCall" type="text" class="mt-1" :value="old('name')"  required autofocus autocomplete="docketCall" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-2">

            </x-case-components.col>

        </div>
    </div>
    <h2 class=" text-lg mt-2">
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
</section>
