<div class="bg-white sm:rounded-lg relative">
    <div class="overflow-y-auto overflow-x-hidden px-7">
        <x-category-components.category-btn id="openDrawer">
                <i class="fa-solid fa-chevron-right"></i>
        </x-category-components.category-btn>

        <div id="sidebarDrawer"
            class="absolute top-0 left-0 w-full bg-white z-10 transform transition-transform duration-300 ease-in-out max-h-[900px] px-3"> {{-- removed -translate-x-full overflow-y-auto --}}

            <div class="flex justify-end">
                <x-category-components.category-btn id="closeDrawer">
                    <i class="fa-solid fa-chevron-left"></i>
                </x-category-components.category-btn>
            </div>

            <div class="px-4">
                @include('calendar.calendar-parts.lawyers-sidebar')
            </div>
        </div>
    </div>
</div>

