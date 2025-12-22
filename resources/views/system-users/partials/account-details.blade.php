<div>
    <h4 class="font-semibold">Account Details</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 mt-6">
        <div>
            <div class="flex items-center gap-2">
                <input type="checkbox" class="h-4 w-4">
                <label class="text-sm">User Account Disabled</label>
            </div>
            <span class="block mt-3 text-sm">
                Lock This User Out Of The System
            </span>
        </div>

        <div>
            <div class="flex items-center gap-2">
                <input type="checkbox" class="h-4 w-4">
                <label class="text-sm">Access To Automated Emails Page</label>
            </div>
        </div>
    </div>


    <hr class="my-5 border-t border-gray-300">


    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-4">
            <label for="role">Role</label>
            <select name="role" id="role" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">Select Role</option>
                <option value="1">Administrator</option>
                <option value="2">Agent</option>
            </select>
        </div>

        <div class="relative mt-3">
            <x-text-input type="text" id="agentTitle" name="agentTitle"  />

            <x-input-label for="agentTitle">Enter Agent Title</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <x-text-input type="text" id="commission" name="commission"  />

            <x-input-label for="commission">Commission %</x-input-label>
        </div>

        <div class="relative mt-3">
            <x-text-input type="text" id="alternateCommission" name="alternateCommission"  />

            <x-input-label for="alternateCommission">Alternate Commission %</x-input-label>
        </div>
    </div>

    <hr class="my-7 border-t border-gray-300">

    <div>
        <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" class="h-4 w-4">
            <label class="text-sm" for="doNotIncludeOnAliasReports">Do Not Include on Alias Reports</label>
        </div>
        <span class="block mt-3 text-sm mb-3">Hide This Agent From All Alias Reports</span>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="alias" name="alias"  />

        <x-input-label for="alias">Alias</x-input-label>
    </div>
</div>