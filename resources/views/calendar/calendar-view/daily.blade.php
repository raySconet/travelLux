<table class="table-fixed w-full bg-[#eaf1ff] border border-[#fff]" id="dailyTable">
	<thead class="grid grid-cols-12 gap-4" id="">
		<tr class="flex w-full col-span-6">
            <x-calendar-components.th class="p-4 text-start border-0 flex justify-between">
                Sunday
                <div class="">~John Doe</div>
            </x-calendar-components.th>
		</tr>
        <tr class="flex w-full col-span-6">
            <x-calendar-components.th class="p-4 text-start border-0 flex justify-between">
                Sunday
                <div class="">~John Doe</div>
            </x-calendar-components.th>
		</tr>
	</thead>
	<tbody id="" class="flex flex-col flex-1 border-t-2 border-[#fff]">
        <tr class="flex size-full calendarRowData" dataId="">
            <x-calendar-components.td class="flex-1 h-full px-4 border-0">
                <div class="text-gray-900 dailyEventInfo bg-[#30d80fb3]"
                    id="event"
                    draggable="true"
                    title="">
                    <span>AAM -NEW REFFERAL - SIMONE ALEXANDER;</span>
                </div>
            </x-calendar-components.td>
        </tr>
        <tr class="flex size-full calendarRowData" dataId="">
            <x-calendar-components.td class="flex-1 h-full px-4 border-0">
                <div class="text-gray-900 dailyEventInfo bg-[#30d80fb3]"
                    id="event"
                    draggable="true"
                    title="">
                    <span>AAM -NEW REFFERAL - SIMONE ALEXANDER;</span>
                </div>
            </x-calendar-components.td>
        </tr>
    </tbody>
</table>
