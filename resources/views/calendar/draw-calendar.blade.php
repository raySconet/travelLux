<table class="table-fixed w-full" id="myTable">
	<thead class="" id="thead">
		<tr class="flex w-full">
            <x-th-calendar>Sunday</x-th-calendar>
            <x-th-calendar>Monday</x-th-calendar>
            <x-th-calendar>Tuesday</x-th-calendar>
            <x-th-calendar>Wednesday</x-th-calendar>
            <x-th-calendar>Thursday</x-th-calendar>
            <x-th-calendar>Friday</x-th-calendar>
            <x-th-calendar>Saturday</x-th-calendar>
		</tr>
	</thead>
	<tbody id="calendarBody" class="flex flex-col">
        @for($i = 0; $i < 6; $i++)
            <tr class="flex w-full calendarRowData border-[#fff]" data-week="{{ $i + 1 }}" dataId="">
                <x-td-calendar class="sunday" />
                <x-td-calendar class="monday" />
                <x-td-calendar class="tuesday" />
                <x-td-calendar class="wednesday" />
                <x-td-calendar class="thursday" />
                <x-td-calendar class="friday" />
                <x-td-calendar class="saturday" />
            </tr>
        @endfor
    </tbody>
</table>
