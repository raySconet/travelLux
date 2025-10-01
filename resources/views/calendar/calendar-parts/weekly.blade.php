<table id="weeklyViewTable" class="table-fixed h-full bg-[#eaf1ff] border-t-1 border-r-1 border-b-0 border-l-1 border-[#fff]">
    <thead class="2xl:h-[60px]">
        <tr>
            <th id="userHeader"></th>
        </tr>
        <tr class="grid grid-cols-7 text-center">
            <x-calendar-components.th class="th-sunday">Sunday</x-calendar-components.th>
            <x-calendar-components.th class="th-monday">Monday</x-calendar-components.th>
            <x-calendar-components.th class="th-tuesday">Tuesday</x-calendar-components.th>
            <x-calendar-components.th class="th-wednesday">Wednesday</x-calendar-components.th>
            <x-calendar-components.th class="th-thursday">Thursday</x-calendar-components.th>
            <x-calendar-components.th class="th-friday">Friday</x-calendar-components.th>
            <x-calendar-components.th class="th-saturday">Saturday</x-calendar-components.th>
        </tr>
    </thead>
    <tbody class="2xl:h-[85%]">
        <tr class="grid grid-cols-7 h-full calendarRowData min-h-[200px]" data-week="" dataId="">
            <x-calendar-components.td class="sunday" />
            <x-calendar-components.td class="monday" />
            <x-calendar-components.td class="tuesday" />
            <x-calendar-components.td class="wednesday" />
            <x-calendar-components.td class="thursday" />
            <x-calendar-components.td class="friday" />
            <x-calendar-components.td class="saturday" />
        </tr>
    </tbody>
</table>

<table id="weeklyViewTableHidden" class="table-fixed h-full bg-[#eaf1ff] border-t-0 border-r-1 border-b-1 border-l-1 border-[#fff] hidden">
    <thead class="2xl:h-[60px]">
        <tr>
            <th id="userHeaderHidden"></th>
        </tr>
        <tr class="grid grid-cols-7 h-full text-center">
            <x-calendar-components.th class="th-sunday">Sunday</x-calendar-components.th>
            <x-calendar-components.th class="th-monday">Monday</x-calendar-components.th>
            <x-calendar-components.th class="th-tuesday">Tuesday</x-calendar-components.th>
            <x-calendar-components.th class="th-wednesday">Wednesday</x-calendar-components.th>
            <x-calendar-components.th class="th-thursday">Thursday</x-calendar-components.th>
            <x-calendar-components.th class="th-friday">Friday</x-calendar-components.th>
            <x-calendar-components.th class="th-saturday">Saturday</x-calendar-components.th>
        </tr>
    </thead>
    <tbody class="2xl:h-[90%]">
        <tr class="grid grid-cols-7 h-full calendarRowData min-h-[200px]" data-week="" dataId="">
            <x-calendar-components.td class="sunday" />
            <x-calendar-components.td class="monday" />
            <x-calendar-components.td class="tuesday" />
            <x-calendar-components.td class="wednesday" />
            <x-calendar-components.td class="thursday" />
            <x-calendar-components.td class="friday" />
            <x-calendar-components.td class="saturday" />
        </tr>
    </tbody>
</table>
