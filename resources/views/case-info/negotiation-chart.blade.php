<section class="mt-1">
    <h2   class="mb-1 mt-1 text-2xl  text-gray-700 text-center border border-[#CCC] p-2 bg-[#eaf1ffd4]">
        {{ __('Negotiation Chart') }}
    </h2>

    <div class="negotiationAppendDuplicates">
        <div class= "grid grid-cols-1 2xl:grid-cols-12 gap-2 w-full negotiationToDuplicate" id="negotiationToDuplicate" data-client="1">
            <x-case-components.col class="2xl:col-span-12 negotiationName text-xl text-center">
                <p> Norma - Negotiation Chart </p>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-4">
                <div >
                    <x-input-label for="medsTotal" :value="__('Meds Total')" />
                    <x-text-input id="medsTotal" name="medsTotal" type="text" placeholder="$00,000.00" class="mt-1   text-center"  :value="old('name')"  required autofocus autocomplete="medsTotal" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div >
                    <x-input-label for="medsPviTotal" :value="__('Meds Pvi Total')" />
                    <x-text-input id="medsPviTotal" name="medsPviTotal" type="text" placeholder="$00,000.00"  class="mt-1   text-center"  :value="old('name')"  required autofocus autocomplete="medsPviTotal" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-4">
                <div>
                    <x-input-label for="negotiationLastOffer" :value="__('Negotiation Last Offer')" />
                    <x-text-input id="negotiationLastOffer" name="negotiationLastOffer" type="text" placeholder="$00,000.00" class="mt-1   text-center" :value="old('name')"  required autofocus autocomplete="negotiationLastOffer" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="negotiationLastOfferDate" :value="__('Negotiation Last Offer Date')" />
                    <x-text-input id="negotiationLastOfferDate" name="negotiationLastOfferDate" type="text" placeholder="00/00/00" class="mt-1   text-center" :value="old('name')"  required autofocus autocomplete="negotiationLastOfferDate" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-4">

                <div>
                    <x-input-label for="negotiationLastDemand" :value="__('Negotiation Last Demand')" />
                    <x-text-input id="negotiationLastDemand" name="negotiationLastDemand" type="text" placeholder="$00,000.00 " class="mt-1   text-center" :value="old('name')"  required autofocus autocomplete="negotiationLastDemand" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="negotiationLastDemandDate" :value="__('Negotiation Last Demand Date')" />
                    <x-text-input id="negotiationLastDemandDate" name="negotiationLastDemandDate" type="text"  placeholder="00/00/00" class="mt-1   text-center" :value="old('name')"  required autofocus autocomplete="negotiationLastDemandDate" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>


            <x-case-components.col class="2xl:col-span-12">
                <div>
                    <x-input-label for="physicalPainMentalAnguishText" :value="__('Physical Pain / Mental Anguish / Other Value Factors')" />
                    <x-text-area id="physicalPainMentalAnguishText"  name="physicalPainMentalAnguishText" type="text"
                    placeholder="Sprain/Strain, limited movement, broken bones, surgery etc.
                        Can’t pick up kids, afraid of driving now, can’t work out anymore, missed vacation etc.
                        Drunk driver, car totaled, great client."
                        class="mt-1   text-center" :value="old('name')"  required autofocus autocomplete="physicalPainMentalAnguishText" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </x-case-components.col>

            <x-case-components.col class="2xl:col-span-12">

                <div class= "grid grid-cols-1 2xl:grid-cols-12 gap-2 w-full negotiationSubToDuplicate" data-client="1">
                    <x-case-components.col class="2xl:col-span-4">
                        <div>
                            <x-input-label for="negotiationNameBottom" :value="__('Negotiation Name')" />
                            <x-text-input id="negotiationNameBottom" name="negotiationNameBottom" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="negotiationNameBottom" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                    </x-case-components.col>
                    <x-case-components.col class="2xl:col-span-4">
                        <div>
                            <x-input-label for="negotiationDateBottom" :value="__('Negotiation Date')" />
                            <x-text-input id="negotiationDateBottom" name="negotiationDateBottom" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="negotiationDateBottom" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                    </x-case-components.col>
                    <x-case-components.col class="2xl:col-span-3">
                        <div>
                            <x-input-label for="negotiationAmountBottom" :value="__('Negotiation Amount')" />
                            <x-text-input id="negotiationAmountBottom" name="negotiationAmountBottom" type="text" class="mt-1 " :value="old('name')"  required autofocus autocomplete="negotiationAmountBottom" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                    </x-case-components.col>
                    <x-case-components.col class="2xl:col-span-1  items-center justify-center">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addButtonForNegotiation" style="margin-top:22px; width:22px; height:22px;">
                            <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                        </div>
                    </x-case-components.col>
                </div>
            </x-case-components.col>
        </div>
    </div>
</section>
