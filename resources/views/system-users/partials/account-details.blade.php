<div>
    <p class="text-xl">Account Details</p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 mt-6">
        <div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_disabled" value="0" >
                <input type="checkbox" class="h-4 w-4" name="is_disabled" value="1" {{ old('is_disabled', $user->is_disabled ?? 0) == 1 ? 'checked' : '' }}>
                <label class="text-sm">User Account Disabled</label>
            </div>
            <span class="block mt-3 text-sm">
                Lock This User Out Of The System
            </span>
        </div>

        <div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="automated_emails_page_access" value="0">
                <input type="checkbox" class="h-4 w-4" value="1" {{ old('automated_emails_page_access', $user->automated_emails_page_access ?? 0) == 1 ? 'checked' : '' }}>
                <label class="text-sm">Access To Automated Emails Page</label>
            </div>
        </div>
    </div>


    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-4">
            <label for="role">Role</label>
            <select name="role" id="role" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="">Select Role</option>
                <option value="1" {{ old('role', $user->role ?? '') == '1' ? 'selected' : '' }}>Administrator</option>
                <option value="2" {{ old('role', $user->role ?? '') == '2' ? 'selected' : '' }}>Agent</option>
            </select>

            <x-input-error  :messages="$errors->get('role')" />
        </div>

        <div class="relative mt-3">
            <x-text-input type="text" id="agent_title" name="agent_title"  value="{{ old('agent_title', $user->agent_title ?? '') }}" />

            <x-input-label for="agent_title">Enter Agent Title</x-input-label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <x-text-input type="text" id="commission" name="commission" value="{{ old('commission', $user->commission ?? '') }}" />

            <x-input-label for="commission">Commission %</x-input-label>

            <x-input-error  :messages="$errors->get('commission')" />
        </div>

        <div class="relative mt-3">
            <x-text-input type="text" id="alternate_commission" name="alternate_commission" value="{{ old('alternate_commission', $user->alternate_commission ?? '') }}" />

            <x-input-label for="alternate_commission">Alternate Commission %</x-input-label>
        </div>
    </div>

    <hr class="my-7 border-t border-gray-300">

    <div>
        <div class="flex items-center gap-2 mt-6">
            <input type="hidden" name="include_in_alias_reports" value="0" >
            <input type="checkbox" class="h-4 w-4" name="include_in_alias_reports" value="1" {{ old('include_in_alias_reports', $user->include_in_alias_reports ?? 0) == 1 ? 'checked' : '' }}>
            <label class="text-sm" for="include_in_alias_reports">Do Not Include on Alias Reports</label>
        </div>
        <span class="block mt-3 text-sm mb-3">Hide This Agent From All Alias Reports</span>
    </div>

    <div class="relative mt-3">
        <x-text-input type="text" id="alias" name="alias" value="{{ old('alias', $user->alias ?? '') }}" />

        <x-input-label for="alias">Alias</x-input-label>
    </div>
</div>