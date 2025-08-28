<table id="weeklyViewTable" class="table-fixed h-full bg-[#eaf1ff] border-t-1 border-r-1 border-b-0 border-l-1 border-[#fff]">
    <thead class="h-[60px]">
        <tr>
            <th id="userHeader"></th>
        </tr>
        <tr class="grid grid-cols-7 text-center">
            <x-calendar-components.th>Sunday</x-calendar-components.th>
            <x-calendar-components.th>Monday</x-calendar-components.th>
            <x-calendar-components.th>Tuesday</x-calendar-components.th>
            <x-calendar-components.th>Wednesday</x-calendar-components.th>
            <x-calendar-components.th>Thursday</x-calendar-components.th>
            <x-calendar-components.th>Friday</x-calendar-components.th>
            <x-calendar-components.th>Saturday</x-calendar-components.th>
        </tr>
    </thead>
    <tbody class="grid grid-rows-1 h-full">
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
    <thead class="h-[60px]">
        <tr>
            <th id="userHeaderHidden"></th>
        </tr>
        <tr class="grid grid-cols-7 text-center">
            <x-calendar-components.th>Sunday</x-calendar-components.th>
            <x-calendar-components.th>Monday</x-calendar-components.th>
            <x-calendar-components.th>Tuesday</x-calendar-components.th>
            <x-calendar-components.th>Wednesday</x-calendar-components.th>
            <x-calendar-components.th>Thursday</x-calendar-components.th>
            <x-calendar-components.th>Friday</x-calendar-components.th>
            <x-calendar-components.th>Saturday</x-calendar-components.th>
        </tr>
    </thead>
    <tbody class="grid grid-rows-1 h-full">
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
