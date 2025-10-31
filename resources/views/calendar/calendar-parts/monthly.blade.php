<table class="table-fixed w-full border-[#fff]" id="myTable">
	<thead class="" id="thead">
		<tr> {{-- class="flex w-full" --}}
            <x-calendar-components.th class="w-1/7 bg-[#eaf1ff]">Sunday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7 bg-[#eaf1ff]">Monday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7 bg-[#eaf1ff]">Tuesday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7 bg-[#eaf1ff]">Wednesday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7 bg-[#eaf1ff]">Thursday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7 bg-[#eaf1ff]">Friday</x-calendar-components.th>
            <x-calendar-components.th class="w-1/7 bg-[#eaf1ff]">Saturday</x-calendar-components.th>
		</tr>
	</thead>
	<tbody id="calendarBody"> {{-- class="flex flex-col" --}}
        @for($i = 0; $i < 6; $i++)
            <tr class="calendarRowData" data-week="{{ $i + 1 }}" dataId=""> {{-- flex w-full  --}}
                <x-calendar-components.td class="sunday bg-[#eaf1ff] h-[270px] align-top" />
                <x-calendar-components.td class="monday bg-[#eaf1ff] h-[270px] align-top" />
                <x-calendar-components.td class="tuesday bg-[#eaf1ff] h-[270px] align-top" />
                <x-calendar-components.td class="wednesday bg-[#eaf1ff] h-[270px] align-top" />
                <x-calendar-components.td class="thursday bg-[#eaf1ff] h-[270px] align-top" />
                <x-calendar-components.td class="friday bg-[#eaf1ff] h-[270px] align-top" />
                <x-calendar-components.td class="saturday bg-[#eaf1ff] h-[270px] align-top" />
            </tr>
        @endfor
    </tbody>
</table>
