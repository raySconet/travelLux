<table class="table-fixed w-full bg-[#eaf1ff] border-[#fff]" id="weeklyTable">
	<thead class="" id="thead">
		<tr class="flex w-full">
            <x-calendar-components.th>Sunday</x-calendar-components.th>
            <x-calendar-components.th>Monday</x-calendar-components.th>
            <x-calendar-components.th>Tuesday</x-calendar-components.th>
            <x-calendar-components.th>Wednesday</x-calendar-components.th>
            <x-calendar-components.th>Thursday</x-calendar-components.th>
            <x-calendar-components.th>Friday</x-calendar-components.th>
            <x-calendar-components.th>Saturday</x-calendar-components.th>
		</tr>
	</thead>
	<tbody id="calendarBody" class="flex flex-col">
        @for($i = 0; $i < 6; $i++)
            <tr class="flex w-full calendarRowData" data-week="{{ $i + 1 }}" dataId="">
                <x-calendar-components.td class="sunday h-[210px]" />
                <x-calendar-components.td class="monday h-[210px]" />
                <x-calendar-components.td class="tuesday h-[210px]" />
                <x-calendar-components.td class="wednesday h-[210px]" />
                <x-calendar-components.td class="thursday h-[210px]" />
                <x-calendar-components.td class="friday h-[210px]" />
                <x-calendar-components.td class="saturday h-[210px]" />
            </tr>
        @endfor
    </tbody>
</table>
