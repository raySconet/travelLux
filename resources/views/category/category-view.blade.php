<div class="text-gray-900 px-5 py-5 flex h-screen gap-4" x-data="{ open: false }">
  <!-- Sidebar -->
  <div
  :class="open ? 'w-100' : 'w-20'"
  class="bg-white overflow-visible shadow-xs sm:rounded-lg mt-2 py-4 px-4 transition-width duration-500 flex flex-col"
>

    <!-- Toggle Button -->
    <button
      @click="open = !open"
      class="self-end mb-4 px-4 py-1 bg-[#eaf1ff] rounded text-xl font-bold shadow-md transition-transform duration-300 cursor-pointer"
      :class="{ 'rotate-180': open }"
      aria-label="Toggle sidebar"
    >
        <span>&gt;</span>
    </button>

    <!-- Sidebar Content -->
    <div
        class="h-full max-h-[calc(100vh-100px)] overflow-y-auto lawyer-sidebar-category"
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-4"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-4"
    >
        @include('calendar.calendar-parts.lawyers-sidebar')
    </div>
  </div>

  <!-- Main content -->
  <div class="flex-1 flex flex-col h-full overflow-x-auto">
    <div class="min-w-[1075px] flex flex-col h-full">
      <div class="mt-2 flex-1 flex flex-col bg-white xl:overflow-visible overflow-hidden shadow-xs sm:rounded-lg">
        <div id="viewMonthly" class="p-6">
          @include('calendar.calendar-parts.monthly')
        </div>
      </div>
    </div>
  </div>
</div>
