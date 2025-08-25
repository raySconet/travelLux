<table id="dailyViewTable" class="w-full bg-[#eaf1ff] border border-[#fff]">
    <thead>
        <tr>
            <x-calendar-components.th id="dailyHeader" class="p-4 text-start border-0 flex justify-between"></x-calendar-components.th>
        </tr>
    </thead>
    <tbody id="dailyBody" class="pb-4 border-t-2 border-[#fff]">
        <!-- rows dynamically inserted here -->
    </tbody>
</table>


<table id="dailyViewTableHidden" class="w-full bg-[#eaf1ff] border border-[#fff] hidden">
    <thead>
        <tr>
            <x-calendar-components.th id="dailyHeaderHidden" class="p-4 text-start border-0 flex justify-between">

            </x-calendar-components.th>
        </tr>
    </thead>
    <tbody class="border-t-2 border-[#fff]">
    </tbody>
</table>
