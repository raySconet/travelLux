<div class="px-6 pb-4 border-b flex items-center justify-between">
    <h2 class="text-lg font-semibold">August 2025</h2>
    <div class="space-x-2">
        <button class="text-gray-600 hover:text-black">&larr;</button>
        <button class="text-gray-600 hover:text-black">&rarr;</button>
    </div>
</div>

<table class="w-full table-fixed text-center">
    <thead class="sidebarThead bg-[#eaf1ff]">
		<tr>
            <x-calendar-components.th class="border-0">Sun</x-calendar-components.th>
            <x-calendar-components.th class="border-0">Mon</x-calendar-components.th>
            <x-calendar-components.th class="border-0">Tue</x-calendar-components.th>
            <x-calendar-components.th class="border-0">Wed</x-calendar-components.th>
            <x-calendar-components.th class="border-0">Thu</x-calendar-components.th>
            <x-calendar-components.th class="border-0">Fri</x-calendar-components.th>
            <x-calendar-components.th class="border-0">Sat</x-calendar-components.th>
		</tr>
	</thead>
	<tbody id="sidebarCalendarBody">
        @for($i = 0; $i < 6; $i++)
            <tr class="" data-week="{{ $i + 1 }}" dataId="">
                <x-calendar-components.td class="sunday" />
                <x-calendar-components.td class="monday " />
                <x-calendar-components.td class="tuesday" />
                <x-calendar-components.td class="wednesday" />
                <x-calendar-components.td class="thursday" />
                <x-calendar-components.td class="friday" />
                <x-calendar-components.td class="saturday" />
            </tr>
        @endfor
    </tbody>
</table>

