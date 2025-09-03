<div class="text-gray-900 relative">
    <!-- Sidebar Wrapper -->
    <div class="relative bg-white overflow-visible shadow-xs sm:rounded-lg mt-2 w-full max-w-md">
        <!-- Toggle Button -->
        <button
            @click="open = !open"
            class="absolute top-1 right-0 z-10 px-2 py-1 bg-[#eaf1ff] rounded text-xl font-bold shadow-md transition-transform duration-300 cursor-pointer"
            :class="{ 'rotate-180': open }"
            aria-label="Toggle sidebar"
        >
            <span>&lt;</span>
        </button>

        <!-- Slide Panel -->
        <div
            x-show="open"
            x-transition
            class="transition-transform duration-500 ease-in-out transform mt-4"
        >
            @include('calendar.calendar-parts.lawyers-sidebar')
        </div>
    </div>
</div>
