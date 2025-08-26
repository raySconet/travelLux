<table id="dailyViewTable" class="col-span-12 xl:col-span-6 w-full bg-[#eaf1ff] border border-b-0 border-[#fff] h-[max-content]">
    <thead>
        <tr>
            <x-calendar-components.th id="dailyHeader" class="p-4 text-start border-0 flex justify-between"></x-calendar-components.th>
        </tr>
    </thead>
    <tbody id="dailyBody" class="border-t-3 border-[#fff]">
        <!-- rows dynamically inserted here -->
    </tbody>
</table>


<table id="dailyViewTableHidden" class="col-span-12 xl:col-span-6 w-full bg-[#eaf1ff] border border-b-0 border-[#fff] hidden h-[max-content]">
    <thead>
        <tr>
            <x-calendar-components.th id="dailyHeaderHidden" class="p-4 text-start border-0 flex justify-between"></x-calendar-components.th>
        </tr>
    </thead>
    <tbody id="dailyBodyHidden" class="border-t-3  border-[#fff]">
    </tbody>
</table>
