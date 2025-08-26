<table id="weeklyViewTable" class="table-fixed w-full h-full bg-[#eaf1ff] border-0 border-[#fff]">
    <thead class="h-[60px]">
        <tr>
            <x-calendar-components.th id="userHeader"></x-calendar-components.th>
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
        <tr class="grid grid-cols-7 h-full calendarRowData" data-week="" dataId="">
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

<table id="weeklyViewTableHidden" class="table-fixed w-full h-full bg-[#eaf1ff] border-0 border-[#fff] hidden">
    <thead class="h-[60px]">
        <tr>
            <x-calendar-components.th id="userHeaderHidden">Username</x-calendar-components.th>
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
        <tr class="grid grid-cols-7 h-full calendarRowData" data-week="" dataId="">
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
