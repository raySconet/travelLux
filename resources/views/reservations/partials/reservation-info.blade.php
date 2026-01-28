<div>
    <div class="flex flex-col gap-1">
        <label for="agents" class="text-sm">
            Select Agent
        </label>
        <select name="agents" id="agents" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
            <option value="-1">All Agents</option>
            
        </select>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-5 flex items-end gap-3">
            <button type="button" class="text-[#f18325] text-2xl flex-shrink-0">
                <i class="fas fa-user-plus"></i>
            </button>

            <div class="flex-1">
                <label for="customer" class="text-sm block mb-1">Customer</label>
                <select
                    name="customer"
                    id="customer"
                    class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1"
                >
                    <option value="-1">All Customers</option>
                </select>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="reservationNumber" name="reservationNumber"  />

            <x-input-label for="reservationNumber">Reservation Number</x-input-label>
            <span class="text-sm">WDW room only travel plan only</span>
        </div>

        <div class="relative mt-9">
            <x-text-input type="text" id="groupPhone" name="groupPhone"  />

            <x-input-label for="groupPhone">Group Phone</x-input-label>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="offers" class="text-sm block mb-1">Special Offers</label>
            <select name="offers"id="offers" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Offer--</option>
                <option value="Free at Sea">Free at Sea</option>
                <option value="Future Cruise Credit">Future Cruise Credit</option>
                <option value="Summer Recovery Dine Offer">Summer Recovery Dine Offer</option>
                <option value="Sun and Fun">Sun and Fun</option>
                <option value="Annual Passholder">Annual Passholder</option>
                <option value="Bounceback Offer">Bounceback Offer</option>
                <option value="Disney Visa Cardholder">Disney Visa Cardholder</option>
                <option value="Disney Wedding Group">Disney Wedding Group</option>
                <option value="Event Voucher">Event Voucher</option>
                <option value="FL Resident">FL Resident</option>
                <option value="Free Dining">Free Dining</option>
                <option value="Gift of Magic">Gift of Magic</option>
                <option value="Gift Vacation Package">Gift Vacation Package</option>
                <option value="Group">Group</option>
                <option value="Kids Sail Free">Kids Sail Free</option>
                <option value="Military">Military</option>
                <option value="No Sale">No Sale</option>
                <option value="Onboard Booking">Onboard Booking</option>
                <option value="Package Sale">Package Sale</option>
                <option value="PIN Code">PIN Code</option>
                <option value="Room Only/Broad Offer">Room Only/Broad Offer</option>
                <option value="Run Disney">Run Disney</option>
                <option value="RunDisney Charity">RunDisney Charity</option>
                <option value="Stay, Play and Dine">Stay, Play and Dine</option>
                <option value="Travel Agent Rate">Travel Agent Rate</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="reservationName" name="reservationName"  />

            <x-input-label for="reservationName">Reservation Name</x-input-label>
            <span class="text-sm">For hotel only put hotel name first</span>
        </div>

        <div class="flex-1 relative mt-8">
            <label for="status" class="text-sm block mb-1">Status</label>
            <select name="status"id="status" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="Active">Active</option>
                <option value="Prospect">Prospect</option>
                <option value="Duplicate">Duplicate</option>
                <option value="Canceled">Canceled</option>
                <option value="Paid in Full">Paid in Full</option>
                <option value="Claimed">Claimed</option>
                <option value="On Hold">On Hold</option>
                <option value="Canceled w/ Insurance Payout">Canceled w/ Insurance Payout</option>
                <option value="Canceled - Commission Protected">Canceled - Commission Protected</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="flex-1 relative mt-8">
            <label for="celebrations" class="text-sm block mb-1">Celebrations</label>
            <select name="celebrations"id="celebrations" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                <option value="-1">--Select Celebrations--</option>
                <option value="Anniversary">Anniversary</option>
                <option value="Birthday">Birthday</option>
                <option value="Family Reunion">Family Reunion</option>
                <option value="First Visit">First Visit</option>
                <option value="Graduation">Graduation</option>
                <option value="Honeymoon">Honeymoon</option>
                <option value="Life Celebration">Life Celebration</option>
                <option value="Retirement">Retirement</option>
                <option value="Summer Family Vacation">Summer Family Vacation</option>
                <option value="Wedding">Wedding</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="celebrationNotes" name="celebrationNotes"  />

            <x-input-label for="celebrationNotes">Celebrations Notes</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

        <div x-data="dateDropdown()" class="relative mt-5">
            <label class="block text-sm mb-1">Checkin Date</label>
            <div class="flex gap-2">
                <select x-model="year" class="form-input">
                    <option value="">Year</option>
                    <template x-for="y in years" :key="y">
                        <option :value="y" x-text="y"></option>
                    </template>
                </select>

                <select x-model="month" class="form-input">
                    <option value="">Month</option>
                    <template x-for="(m, i) in months" :key="i">
                        <option :value="i + 1" x-text="m"></option>
                    </template>
                </select>

                <select x-model="day" class="form-input">
                    <option value="">Day</option>
                    <template x-for="d in days" :key="d">
                        <option :value="d" x-text="d"></option>
                    </template>
                </select>

            </div>
            <input type="hidden" name="birthDate" :value="formattedDate">
        </div>


        <div x-data="dateDropdown()" class="relative mt-5">
            <label class="block text-sm mb-1">Checkout Date</label>
            <div class="flex gap-2">
                <select x-model="year" class="form-input">
                    <option value="">Year</option>
                    <template x-for="y in years" :key="y">
                        <option :value="y" x-text="y"></option>
                    </template>
                </select>

                <select x-model="month" class="form-input">
                    <option value="">Month</option>
                    <template x-for="(m, i) in months" :key="i">
                        <option :value="i + 1" x-text="m"></option>
                    </template>
                </select>

                <select x-model="day" class="form-input">
                    <option value="">Day</option>
                    <template x-for="d in days" :key="d">
                        <option :value="d" x-text="d"></option>
                    </template>
                </select>

            </div>
            <input type="hidden" name="hireDate" :value="formattedDate">
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-5">
            <label for="reservationCost" class="block text-sm">Total Reservation Cost</label>
            
            <div class="relative">
                <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

                <x-text-input type="text" id="reservationCost" name="reservationCost" class="pl-7" />
            </div>
        </div>


        <div class="relative mt-5">
            <label for="totalAgencyCommission" class="block text-sm">Total Agency Commission</label>
            
            <div class="relative">
                <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

                <x-text-input type="text" id="totalAgencyCommission" name="totalAgencyCommission" class="pl-7" />
            </div>
        </div>

        <div class="relative mt-5">
            <label for="agentCommission" class="block text-sm">Agent Commission</label>
            
            <div class="relative">
                <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

                <x-text-input type="text" id="agentCommission" name="agentCommission" class="pl-7" />
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
        <div class="relative mt-9">
            <x-text-input type="text" id="commissionNotes" name="commissionNotes"  />

            <x-input-label for="commissionNotes">Commission Notes</x-input-label>
        </div>
    </div>

    <div class="flex flex-col gap-1 mt-6">
        <label for="secondaryAgents" class="text-sm">
            Secondary Agent
        </label>
        <select name="secondaryAgents" id="secondaryAgents" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
            <option value="-1">--Select Secondary Agent--</option>
            
        </select>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">None Commissionable</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Reservation Is a Surprise</label>
        </div>

        <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Agent Personal Travel</label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-4">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Website Lead Knot</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Website Lead</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Virtuoso Lead</label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-4">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Luxury Magazine Lead</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Facebook Lead</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Instagram Lead</label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="flex items-center gap-2 mt-4">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Radio Lead</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Stop Auto Emails</label>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm">Radio Station Ads</label>
        </div>
    </div>
</div>