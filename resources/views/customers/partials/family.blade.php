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

    @forelse($customer->familyMembers()->where('is_deleted', 0)->get() as $member)
        <div class="flex items-center justify-between mt-3">
            <div class="flex items-center gap-3">
                <i class="fa fa-user-tie text-base"></i>
                <p class="text-base">{{ $member->fname }} {{ $member->lname }}</p>
                <p class="text-sm bg-[#bdbdbd] text-[#fff] px-3">
                    @if($member->relation != -1)
                        {{ $member->relation }}
                    @endif
                </p>
            </div>
            <form method="POST" action="{{ route('familyMembers.delete', $member->id) }}" class="inline delete-form">
                @csrf
                @method('DELETE')

                <button type="button" onclick="openDeleteModal(this)">
                    <i class="fa fa-trash text-base"></i>
                </button>
            </form>
        </div>
    @empty
        <p class="mt-3 text-[#6c757d] text-sm"></p>
    @endforelse
@endif

<!-- Add Family Member to customer modal -->
@if(!$isNewCustomer)
    <form method="POST" action="{{ route('customers.familyMembers.store', $customer->id) }}">
        @csrf

        <div id="customersAddFamilyMemberModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[9999]">
            
            <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg relative">

                
                <div class="flex items-center justify-between px-6 py-4 border-b-2  border-[#dee2e6]">
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
                            <x-text-input type="text" id="fname" name="fname"  />

                            <x-input-label for="fname">First Name</x-input-label>

                            <x-input-error :messages="$errors->get('fname')" />
                        </div>

                        <div class="relative">
                            <x-text-input type="text" id="mname" name="mname"  />

                            <x-input-label for="mname">Middle Name</x-input-label>
                        </div>

                        <div class="relative">
                            <x-text-input type="text" id="lname" name="lname"  />

                            <x-input-label for="lname">Last Name</x-input-label>

                            <x-input-error :messages="$errors->get('lname')" />
                        </div>

                        <div class="relative">
                            <x-text-input type="text" id="nickname" name="nickname"  />

                            <x-input-label for="nickname">Nick Name</x-input-label>
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
                            <input type="hidden" name="birth_date" :value="formattedDate">
                        </div>

                        <div class="relative mt-8">
                            <label for="relation">Relation</label>
                            <select name="relation" id="relation" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                                <option value="-1">-- Select Relation --</option>
                                <option value="Adult Child">Adult Child</option>
                                <option value="Adult Relative">Adult Relative</option>
                                <option value="Aunt">Aunt</option>
                                <option value="Boyfriend">Boyfriend</option>
                                <option value="Child">Child</option>
                                <option value="Client">Client</option>
                                <option value="Co-Worker">Co-worker</option>
                                <option value="Daughter in Law">Daughter in Law</option>
                                <option value="Divorced Spouce">Divorced Spouse</option>
                                <option value="Father in Law">Father in Law</option>
                                <option value="Fiancée/Fiancé">Fiancée/Fiancé</option>
                                <option value="Friend">Friend</option>
                                <option value="Girlfriend">Girlfriend</option>
                                <option value="Grandchild">Grandchild</option>
                                <option value="Grandparent">Grandparent</option>
                                <option value="Mother in law">Mother in law</option>
                                <option value="Nephew">Nephew</option>
                                <option value="Niece">Niece</option>
                                <option value="Parent">Parent</option>
                                <option value="Partner">Partner</option>
                                <option value="Relative">Relative</option>
                                <option value="Self">Self</option>
                                <option value="Sibling">Sibling</option>
                                <option value="Son in Law">Son in Law</option>
                                <option value="Spouse">Spouse</option>
                                <option value="Uncle">Uncle</option>
                            </select>
                        </div>

                        <div class="relative mt-8">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                                <option value="-1">-- Select Gender --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                        <div class="relative">
                            <x-text-input type="text" id="cellphone" name="cellphone"  />

                            <x-input-label for="cellphone">Cell Phone</x-input-label>
                        </div>

                        <div class="relative">
                            <x-text-input type="text" id="home_phone" name="home_phone"  />

                            <x-input-label for="home_phone">Home Phone</x-input-label>
                        </div>

                        <div class="relative">
                            <x-text-input type="text" id="work_phone" name="work_phone"  />

                            <x-input-label for="work_phone">Work Phone</x-input-label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                        <div class="relative">
                            <x-text-input type="text" id="email" name="email"  />

                            <x-input-label for="email">Email</x-input-label>
                        </div>

                        <div class="relative">
                            <x-text-input type="text" id="traveler_number" name="traveler_number"  />

                            <x-input-label for="traveler_number">Known Traveler Number</x-input-label>
                        </div>

                        <div class="flex items-center justify-end gap-2 mt-8">
                            <input type="hidden" name="deceased" value="0">
                            <input type="checkbox" name="deceased"  class="h-4 w-4" value="1">
                            <label class="text-sm">Deceased</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                        <div class="relative mt-2">
                            <x-text-input type="text" id="passport_number" name="passport_number"  />

                            <x-input-label for="passport_number">Passport Number</x-input-label>
                        </div>

                        <div class="relative mt-2">
                            <x-text-input type="date" id="passport_issue_date" name="passport_issue_date"  />

                            <x-input-label for="passport_issue_date">Passport Issue Date</x-input-label>
                        </div>

                        <div class="relative mt-2">
                            <x-text-input type="date" id="passport_expiration_date" name="passport_expiration_date"  />

                            <x-input-label for="passport_expiration_date">Passport Expiration Date</x-input-label>
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="mt-6">
                        <div class="flex flex-row items-center justify-between cursor-pointer" @click="open = !open">
                            <p class="text-lg font-medium">
                                <i
                                    class="fas"
                                    :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                                ></i>
                                Address
                            </p>
                        </div>

                        <div x-show="open"  class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                            <div class="relative">
                                <x-text-input type="text" id="address_line1" name="address_line1"  />

                                <x-input-label for="address_line1">Address Line 1</x-input-label>
                            </div>

                            <div class="relative">
                                <x-text-input type="text" id="address_line2" name="address_line2"  />

                                <x-input-label for="address_line2">Address Line 2</x-input-label>
                            </div>

                            <div class="relative">
                                <x-text-input type="text" id="city" name="city"  />

                                <x-input-label for="city">City</x-input-label>
                            </div>
                        </div>

                        <div x-show="open"  class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                            <div class="relative mt-6">
                                <label for="state">State/Province/Region</label>
                                <select name="state" id="state" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                                    <option value="">-- Select State/Province/Region --</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->name }}">
                                            {{ $state->name}}
                                        </option>    
                                    @endforeach    
                                </select>
                            </div>

                            <div class="relative mt-4">
                                <x-text-input type="text" id="zip_code" name="zip_code"  />

                                <x-input-label for="zip_code">Zip Code</x-input-label>
                            </div>

                            <div class="relative mt-5">
                                <label for="country">Country</label>
                                <select name="country" id="country" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                                    <option value="">-- Select Country --</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->name }}">
                                            {{ $country->name }}
                                        </option>
                                    @endforeach        
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col mt-4">
                        <label for="special_notes" class="mb-1 text-sm text-gray-700">Special Notes</label>
                        <textarea
                            id="special_notes"
                            name="special_notes"
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
    </form>    
@endif