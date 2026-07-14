<div class="space-y-5 mt-6">

    <div class="bg-white shadow p-6">
        <h2 class="text-xl font-bold mb-6">
            1. MAIN INFORMATION
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-y-6 gap-x-12">

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">First Name</div>
                <div id="review_fname"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Middle Name</div>
                <div id="review_mname"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Last Name</div>
                <div id="review_lname"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Nick Name</div>
                <div id="review_nickname"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Email</div>
                <div id="review_email"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Secondary Email</div>
                <div id="review_secondary_email"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Cell Phone</div>
                <div id="review_cellphone"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Home Phone</div>
                <div id="review_home_phone"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Work Phone</div>
                <div id="review_work_phone"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Gender</div>
                <div id="review_gender"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Birth Date</div>
                <div id="review_birth_date"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Anniversary Date</div>
                <div id="review_anniversary_date"></div>
            </div>

            <div class="md:col-span-3">
                <div class="text-base font-bold max-[992px]:mt-[5px]">Special Notes</div>
                <div id="review_special_notes"></div>
            </div>

        </div>
    </div>

    <div class="bg-white shadow p-6">

        <h2 class="text-xl font-bold mb-6">
            2. HOME ADDRESS
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-y-6 gap-x-12">

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Address Line 1</div>
                <div id="review_address_line1"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Address Line 2</div>
                <div id="review_address_line2"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">City</div>
                <div id="review_city"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">State/Province/Region</div>
                <div id="review_state"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Postal Code</div>
                <div id="review_postal_code"></div>
            </div>

            <div>
                <div class="text-base font-bold max-[992px]:mt-[5px]">Country</div>
                <div id="review_country"></div>
            </div>

        </div>

    </div>

    <div class="bg-white shadow p-6">

        <h2 class="text-xl font-bold mb-6">
            3. FAMILY MEMBERS
        </h2>


        @forelse($customer->familyMembers ?? [] as $member)
            <div x-data="{ open: false }"
                class="flex flex-col mt-3 bg-[#fff] px-5 py-3"
                style="box-shadow: 0 5px 5px -11px rgba(0, 0, 0, 0.2),0 1px 4px -22px rgba(0, 0, 0, 0.14),0 3px 14px 2px rgba(0, 0, 0, 0.12);">

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <i class="fa fa-user-tie text-xl"></i>

                        <p class="text-xl">
                            {{ $member->fname ?? '' }}
                            {{ $member->lname ?? '' }}
                        </p>

                        <p class="text-base bg-[#bdbdbd] text-white px-3">
                            @if($member->relation != -1)
                                {{ $member->relation ?? '' }}
                            @endif    
                        </p>
                    </div>

                    <i 
                        class="fas text-xl cursor-pointer"
                        :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                        @click="open = !open"
                    ></i>
                </div>

                <div x-show="open" x-transition class="mt-5 flex flex-col gap-3">

                    <div class="flex text-base font-bold">
                        <p class="w-1/3">Gender</p>
                        <p class="w-1/3">Birthdate</p>
                        <p class="w-1/3">Email</p>
                    </div>

                    <div class="flex justify-between text-sm">
                        
                        <p class="w-1/3">
                            @if(!empty($member->gender))
                                {{ $member->gender }}
                            @endif
                        </p>

                        <p class="w-1/3">
                            @if(!empty($member->birth_date))
                                {{ \Carbon\Carbon::parse($member->birth_date)->format('m/d/Y') }}
                            @endif
                        </p>

                        <p class="w-1/3">
                            @if(!empty($member->email))
                                {{ $member->email }}
                            @endif
                        </p>

                    </div>

                    <div class="flex text-base font-bold">
                        <p class="w-1/3">Cell Phone</p>
                        <p class="w-1/3">Home Phone</p>
                        <p class="w-1/3">Work Phone</p>
                    </div>

                    <div class="flex justify-between text-sm">
                        
                        <p class="w-1/3">
                            @if(!empty($member->cellphone))
                                {{ $member->cellphone }}
                            @endif
                        </p>

                        <p class="w-1/3">
                            @if(!empty($member->home_phone))
                                {{ $member->home_phone }}
                            @endif
                        </p>

                        <p class="w-1/3">
                            @if(!empty($member->work_phone))
                                {{ $member->work_phone }}
                            @endif
                        </p>
                    </div>

                    <div class="flex text-base font-bold">
                        <p class="w-1/3">Passport Number</p>
                        <p class="w-1/3">Passport Issue Date</p>
                        <p class="w-1/3">Passport Expiration Date</p>
                    </div>

                    <div class="flex justify-between text-sm">
                        <p class="w-1/3">
                            @if(!empty($member->passport_number))
                                {{ $member->passport_number }}
                            @endif
                        </p>

                        <p class="w-1/3">
                            @if(!empty($member->passport_issue_date))
                                {{ $member->passport_issue_date }}
                            @endif
                        </p>

                        <p class="w-1/3">
                            @if(!empty($member->passport_expiration_date))
                                {{ $member->passport_expiration_date }}
                            @endif
                        </p>
                    </div>

                    <div class="flex text-base font-bold">
                        <p class="w-1/3">Address Line 1</p>
                        <p class="w-1/3">Address Line 2</p>
                        <p class="w-1/3">City</p>
                    </div>

                    <div class="flex justify-between text-sm">
                        <p class="w-1/3">
                            @if(!empty($member->address_line1))
                                {{ $member->address_line1 }}
                            @endif
                        </p>

                        <p class="w-1/3">
                            @if(!empty($member->address_line2))
                                {{ $member->address_line2 }}
                            @endif
                        </p>

                        <p class="w-1/3">
                            @if(!empty($member->city))
                                {{ $member->city }}
                            @endif
                        </p>
                    </div>

                    <div class="flex text-base font-bold">
                        <p class="w-1/3">State/Province/Region</p>
                        <p class="w-1/3">Postal Code</p>
                        <p class="w-1/3">Country</p>
                    </div>

                    <div class="flex justify-between text-sm">
                        <p class="w-1/3">
                            @if(!empty($member->state))
                                {{ $member->state }}
                            @endif
                        </p>

                        <p class="w-1/3">
                            @if(!empty($member->zip_code))
                                {{ $member->zip_code }}
                            @endif
                        </p>

                        <p class="w-1/3">
                            @if(!empty($member->country))
                                {{ $member->country }}
                            @endif
                        </p>
                    </div>

                    <div class="flex text-base font-bold">
                        <p class="w-1/3">Known Traveler Number</p>
                        <p class="w-1/3">Notes</p>
                    </div>

                    <div class="flex text-sm">
                        <p class="w-1/3">
                            @if(!empty($member->traveler_number))
                                {{ $member->traveler_number }}
                            @endif
                        </p>

                        <p class="w-1/3">
                            @if(!empty($member->special_notes))
                                {{ $member->special_notes }}
                            @endif
                        </p>
                    </div>
                </div>

            </div>
        @empty
            <p class="text-base text-center mt-2">
                No Family Members for this Customer
            </p>
        @endforelse

    </div>

</div>