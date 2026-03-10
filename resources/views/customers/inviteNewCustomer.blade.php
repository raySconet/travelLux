<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fas fa-envelope-open-text mr-2 text-[#f18325]"></i>{{ __('Invite Customer') }}
            </h2>

        </div>
    </x-slot>

    <div class="bg-white shadow rounded-none p-6 ml-3 mr-3">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
            <div class="relative mt-3">
                <x-text-input type="text" id="fname" name="fname"  />

                <x-input-label for="fname">First Name</x-input-label>
            </div>

            <div class="relative mt-3">
                <x-text-input type="text" id="lname" name="lname"  />

                <x-input-label for="lname">Last Name</x-input-label>
            </div>

            <div class="relative mt-3">
                <x-text-input type="email" id="email" name="email"  />

                <x-input-label for="email">Email</x-input-label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
            <div class="flex items-center gap-2 mt-8">
                <input type="hidden" name="is_website_lead_knot" value="0">
                <input type="checkbox" class="h-4 w-4" name="is_website_lead_knot" value="1">
                <label class="text-sm">Website Lead Knot</label>
            </div>

            <div class="flex items-center gap-2 mt-8">
                <input type="hidden" name="is_website_lead" value="0">
                <input type="checkbox" class="h-4 w-4" name="is_website_lead" value="1">
                <label class="text-sm">Website Lead</label>
            </div>

            <div class="flex items-center gap-2 mt-8">
                <input type="hidden" name="is_virtuoso_lead" value="0">
                <input type="checkbox" class="h-4 w-4" name="is_virtuoso_lead" value="1">
                <label class="text-sm">Virtuoso Lead</label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
            <div class="flex items-center gap-2 mt-3">
                <input type="hidden" name="is_luxury_magazine_lead" value="0">
                <input type="checkbox" class="h-4 w-4" name="is_luxury_magazine_lead" value="0">
                <label class="text-sm">Luxury Magazine Lead</label>
            </div>

            <div class="flex items-center gap-2 mt-3">
                <input type="hidden" name="is_facebook_lead" value="0">
                <input type="checkbox" class="h-4 w-4" name="is_facebook_lead" value="1">
                <label class="text-sm">Facebook Lead</label>
            </div>

            <div class="flex items-center gap-2 mt-3">
                <input type="hidden" name="is_instagram_lead" value="0">
                <input type="checkbox" class="h-4 w-4" name="is_instagram_lead" value="1">
                <label class="text-sm">Instagram Lead</label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4">
            <div class="flex items-center gap-2 mt-3">
                <input type="hidden" name="is_radio_lead" value="0">
                <input type="checkbox" class="h-4 w-4" name="is_radio_lead" value="1">
                <label class="text-sm">Radio Lead</label>
            </div>
        </div>

        <div class="space-x-2 flex justify-end">
            <x-secondary-btn><span>Cancel</span></x-secondary-btn>
            <x-primary-btn><span>Invite</span></x-primary-btn>
        </div>
    </div>
    
</x-app-layout>
