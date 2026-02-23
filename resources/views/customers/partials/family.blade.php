@if($isNewCustomer)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Family Travelers will be available after customer is saved.</span>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">Family</h6>

        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0" onclick="openAddFamilyMemberModal()">
            <i class="fas fa-plus-circle"></i>
        </button>
    </div>

    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="flex items-center justify-between mt-3">
        <div class="flex items-center gap-3">
            <i class="fa fa-user-tie text-base"></i>
            <p class="text-base">test</p>
            <p class="text-sm bg-[#bdbdbd] text-[#fff] px-3">Self</p>
        </div>

        <i class="fas fa-trash text-base"></i>
    </div>
@endif    
<div id="customersAddFamilyMemberModal" class="fixed inset-0 bg-black/30 flex items-center justify-center hidden z-[9999]">
    
    <div class="bg-white w-full max-w-4xl rounded-lg shadow-lg relative max-h-[95vh] overflow-y-auto">

        
        <div class="flex items-center justify-between px-6 py-4 border-b-2 border-t-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="fas fa-plus-circle text-[#f18325] text-base"></i>
                <h2 class="text-base">Add Family Member</h2>
            </div>

            <button type="button" onclick="closeAddFamilyMemberModal()" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        
        <div class="px-6 py-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-x-8 gap-y-8">
                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberFirstName" name="addFamilyMemberFirstName"  />

                    <x-input-label for="addFamilyMemberFirstName">First Name</x-input-label>
                </div>

                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberMiddleName" name="addFamilyMemberMiddleName"  />

                    <x-input-label for="addFamilyMemberMiddleName">Middle Name</x-input-label>
                </div>

                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberLastName" name="addFamilyMemberLastName"  />

                    <x-input-label for="addFamilyMemberLastName">Last Name</x-input-label>
                </div>

                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberNickName" name="addFamilyMemberNickName"  />

                    <x-input-label for="addFamilyMemberNickName">Nick Name</x-input-label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">

                <div x-data="dateDropdown()" class="relative mt-4">
                    <label class="block text-sm mb-1">Birth Date</label>
                    <div class="flex w-full border-b-2 border-[#bdbdbd] overflow-hidden outline-none">
                        <select x-model="year" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                            <option value="">Year</option>
                            <template x-for="y in years" :key="y">
                                <option :value="y" x-text="y"></option>
                            </template>
                        </select>

                        <select x-model="month" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                            <option value="">Month</option>
                            <template x-for="(m, i) in months" :key="i">
                                <option :value="i + 1" x-text="m"></option>
                            </template>
                        </select>

                        <select x-model="day" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                            <option value="">Day</option>
                            <template x-for="d in days" :key="d">
                                <option :value="d" x-text="d"></option>
                            </template>
                        </select>

                    </div>
                    <input type="hidden" name="birthDate" :value="formattedDate">
                </div>

                <div class="relative mt-8">
                    <label for="addFamilyMemberRelation">Relation</label>
                    <select name="addFamilyMemberRelation" id="addFamilyMemberRelation" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                        <option value="-1">-- Select Relation --</option>
                        <option value="adultChild">Adult Child</option>
                        <option value="adultRelative">Adult Relative</option>
                        <option value="aunt">Aunt</option>
                        <option value="boyfriend">Boyfriend</option>
                        <option value="child">Child</option>
                        <option value="client">Client</option>
                        <option value="co-worker">Co-worker</option>
                        <option value="daughterInLaw">Daughter in Law</option>
                        <option value="divorcedSpouse">Divorced Spouse</option>
                        <option value="fatherInLaw">Father in Law</option>
                        <option value="fiancée/fiancé">Fiancée/Fiancé</option>
                        <option value="friend">Friend</option>
                        <option value="girlfriend">Girlfriend</option>
                        <option value="grandchild">Grandchild</option>
                        <option value="grandparent">Grandparent</option>
                        <option value="motherInLaw">Mother in law</option>
                        <option value="nephew">Nephew</option>
                        <option value="niece">Niece</option>
                        <option value="parent">Parent</option>
                        <option value="partner">Partner</option>
                        <option value="relative">Relative</option>
                        <option value="self">Self</option>
                        <option value="sibling">Sibling</option>
                        <option value="sonInLaw">Son in Law</option>
                        <option value="spouse">Spouse</option>
                        <option value="uncle">Uncle</option>
                    </select>
                </div>

                <div class="relative mt-8">
                    <label for="addFamilyMemberGender">Gender</label>
                    <select name="addFamilyMemberGender" id="addFamilyMemberGender" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                        <option value="-1">-- Select Gender --</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberCellPhone" name="addFamilyMemberCellPhone"  />

                    <x-input-label for="addFamilyMemberCellPhone">Cell Phone</x-input-label>
                </div>

                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberHomePhone" name="addFamilyMemberHomePhone"  />

                    <x-input-label for="addFamilyMemberHomePhone">Home Phone</x-input-label>
                </div>

                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberWorkPhone" name="addFamilyMemberWorkPhone"  />

                    <x-input-label for="addFamilyMemberWorkPhone">Work Phone</x-input-label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberEmail" name="addFamilyMemberEmail"  />

                    <x-input-label for="addFamilyMemberEmail">Email</x-input-label>
                </div>

                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberKnownTravelerNumber" name="addFamilyMemberKnownTravelerNumber"  />

                    <x-input-label for="addFamilyMemberKnownTravelerNumber">Known Traveler Number</x-input-label>
                </div>

                <div class="flex items-center justify-end gap-2 mt-8">
                    <input type="checkbox" class="h-4 w-4">
                    <label class="text-sm">Deceased</label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                <div class="relative mt-2">
                    <x-text-input type="text" id="addFamilyMemberPassportNumber" name="addFamilyMemberPassportNumber"  />

                    <x-input-label for="addFamilyMemberPassportNumber">Passport Number</x-input-label>
                </div>

                <div class="relative mt-2">
                    <x-text-input type="date" id="addFamilyMemberPassportIssueDate" name="addFamilyMemberPassportIssueDate"  />

                    <x-input-label for="addFamilyMemberPassportIssueDate">Passport Issue Date</x-input-label>
                </div>

                <div class="relative mt-2">
                    <x-text-input type="date" id="addFamilyMemberNickName" name="addFamilyMemberNickName"  />

                    <x-input-label for="addFamilyMemberNickName">Passport Expiration Date</x-input-label>
                </div>
            </div>

            <div x-data="{ open: false }" class="mt-6">
                <div class="flex flex-row items-center justify-between">
                    <p class="text-lg font-medium">
                        <i
                            class="fas"
                            :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                        ></i>
                        Address
                    </p>

                </div>
            </div>    

            <div x-show="open"  class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberAddressLine1" name="addFamilyMemberAddressLine1"  />

                    <x-input-label for="addFamilyMemberAddressLine1">Address Line 1</x-input-label>
                </div>

                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberAddressLine2" name="addFamilyMemberAddressLine2"  />

                    <x-input-label for="addFamilyMemberAddressLine2">Address Line 2</x-input-label>
                </div>

                <div class="relative">
                    <x-text-input type="text" id="addFamilyMemberCity" name="addFamilyMemberCity"  />

                    <x-input-label for="addFamilyMemberCity">City</x-input-label>
                </div>
            </div>

            <div x-show="open" class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                <div class="relative mt-6">
                    <label for="addFamilyMemberStateProvinceState">State/Province/Region</label>
                    <select name="addFamilyMemberStateProvinceState" id="addFamilyMemberStateProvinceState" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                        <option value="-1">-- Select State/Province/Region --</option>
                    </select>
                </div>

                <div class="relative mt-4">
                    <x-text-input type="text" id="addFamilyMemberZipCode" name="addFamilyMemberZipCode"  />

                    <x-input-label for="addFamilyMemberZipCode">Zip Code</x-input-label>
                </div>

                <div class="relative mt-5">
                    <label for="addFamilyMemberCountry">Country</label>
                    <select name="addFamilyMemberCountry" id="addFamilyMemberCountry" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                        <option value="-1">-- Select Country --</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col mt-4">
                <label for="specialNotes" class="mb-1 text-sm text-gray-700">Special Notes</label>
                <textarea
                    id="specialNotes"
                    name="specialNotes"
                    rows="2"
                    class="w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1">
                </textarea>
            </div>
        </div>

        
        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">
            <x-primary-btn type="submit" class="customerAddFamilyMember">
                <i class="fa fa-paper-plane"></i>
                <span>Save</span>
            </x-primary-btn>

            <x-secondary-btn type="button" onclick="closeAddFamilyMemberModal()">
                <i class="fa fa-times-circle"></i>
                <span>Cancel</span>
            </x-secondary-btn>
        </div>

    </div>
</div>
