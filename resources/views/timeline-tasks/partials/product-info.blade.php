<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <label for="product">Product</label>
            <select name="product" id="product" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Product--</option>
            </select>
        </div>

        <div class="relative mt-3">
            <label for="destination">Destination</label>
            <select name="destination" id="destination" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Destination--</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <x-text-input type="text" id="taskName" name="taskName"  />

            <x-input-label for="taskName">Task Name</x-input-label>
        </div>

        <div class="relative mt-4">
            <label for="priority">Priority</label>
            <select name="priority" id="priority" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">--Select Priority--</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <label>Day(s)</label>
            <input
                type="number"
                name="days"
                min="0"
                step="1"
                value="0"
                class="w-full border-b py-1 focus:outline-none focus:border-[#f18325]"
            >
        </div>

        <div class="relative mt-5">
            <label for="timelineTaskBeforeAfter">Before/After</label>
            <select name="timelineTaskBeforeAfter" id="timelineTaskBeforeAfter" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">-- Select Before/After--</option>
                <option value="Before">Before</option>
                <option value="After">After</option>
            </select>
        </div>

        <div class="relative mt-5">
            <label for="timelineTaskDateType">Date Type</label>
            <select name="timelineTaskDateType" id="timelineTaskDateType" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="-1">-- Select Date Type</option>
                <option value="Check In Date">Check In Date</option>
                <option value="Check Out Date">Check Out Date</option>
                <option value="Create Date">Create Date</option>
                <option value="Deposit Due Date">Deposit Due Date</option>
                <option value="Final Payment Due Date">Final Payment Due Date</option>
            </select>
        </div>
    </div>
</div>