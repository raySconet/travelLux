<div class="bg-white sm:rounded-lg relative">
    <div class="overflow-y-auto px-7">
        <x-secondary-btn id="openDrawer">
                <i class="fa-solid fa-chevron-right"></i>
        </x-secondary-btn>

        <div id="sidebarDrawer"
            class="absolute top-0 left-0 w-full bg-white z-10 transform -translate-x-full transition-transform duration-300 ease-in-out max-h-[900px] overflow-y-auto px-3">

            <div class="flex justify-end">
                <x-secondary-btn id="closeDrawer">
                    <i class="fa-solid fa-chevron-left"></i>
                </x-secondary-btn>
            </div>

            <div class="px-4 pb-4">
                @include('calendar.calendar-parts.lawyers-sidebar')
            </div>
        </div>
    </div>
</div>

