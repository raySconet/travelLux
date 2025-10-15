<table class="table-fixed w-full bg-[#eaf1ff] border-[#fff]" id="myTable">
	<thead class="" id="thead">
		<tr> {{-- class="flex w-full" --}}
            <x-calendar-components.th class="w-1/7">Sunday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7">Monday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7">Tuesday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7">Wednesday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7">Thursday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7">Friday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7">Saturday</x-calendar-components.th>
		</tr>
	</thead>
	<tbody id="calendarBody"> {{-- class="flex flex-col" --}}
        @for($i = 0; $i < 6; $i++)
            <tr class="calendarRowData" data-week="{{ $i + 1 }}" dataId=""> {{-- flex w-full  --}}
                <x-calendar-components.td class="sunday h-[270px] align-top" />
                <x-calendar-components.td class="monday h-[270px] align-top" />
                <x-calendar-components.td class="tuesday h-[270px] align-top" />
                <x-calendar-components.td class="wednesday h-[270px] align-top" />
                <x-calendar-components.td class="thursday h-[270px] align-top" />
                <x-calendar-components.td class="friday h-[270px] align-top" />
                <x-calendar-components.td class="saturday h-[270px] align-top" />
            </tr>
        @endfor
    </tbody>
</table>
