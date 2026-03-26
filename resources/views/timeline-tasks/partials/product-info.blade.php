<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="">--Select Product--</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                        {{ old('product_id', $timelineTask->product_id) == $product->id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach        
            </select>

            <x-input-error :messages="$errors->get('product_id')" />
        </div>

        <div class="relative mt-3">
            <label for="destination_id">Destination</label>
            <select name="destination_id" id="destination_id" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="">--Select Destination--</option>
                @foreach($destinations as $destination)
                    <option value="{{ $destination->id }}" data-product="{{ $destination->product_id }}"
                        {{ old('destination_id', $timelineTask->destination_id) == $destination->id ? 'selected' : '' }}>
                        {{ $destination->destination_name }}
                    </option>
                @endforeach        
            </select>

            <x-input-error :messages="$errors->get('destination_id')" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <x-text-input type="text" id="task_name" name="task_name" value="{{ old('task_name', $timelineTask->task_name ?? '') }}" />

            <x-input-label for="task_name">Task Name</x-input-label>

            <x-input-error :messages="$errors->get('task_name')" />
        </div>

        <div class="relative mt-4">
            <label for="priority">Priority</label>
            <select name="priority" id="priority" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="">--Select Priority--</option>
                <option value="Low" {{ old('priority', $timelineTask->priority ?? '') == 'Low' ? 'selected' : '' }}>Low</option>
                <option value="Medium" {{ old('priority', $timelineTask->priority ?? '') == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="High" {{ old('priority', $timelineTask->priority ?? '') == 'High' ? 'selected' : '' }}>High</option>
            </select>

            <x-input-error :messages="$errors->get('priority')" /> 
        </div>
    </div>

    <h1 class="text-xl">Task Due</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
        <div class="relative mt-3">
            <label>Day(s)</label>
            <input
                type="number"
                name="due_days"
                min="0"
                step="1"
                value="{{ old('due_days', $timelineTask->due_days ?? 0) }}"
                class="w-full border-b-2 border-[#bdbdbd] py-1 focus:outline-none focus:border-[#f18325]"
            >
        </div>

        <div class="relative mt-5">
            <label for="before_after">Before/After</label>
            <select name="before_after" id="before_after" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="">-- Select Before/After--</option>
                <option value="Before" {{ old('before_after', $timelineTask->before_after ?? '') == 'Before' ? 'selected' : '' }}>Before</option>
                <option value="After" {{ old('before_after', $timelineTask->before_after ?? '') == 'After' ? 'selected' : '' }}>After</option>
            </select>

            <x-input-error :messages="$errors->get('before_after')" />
        </div>

        <div class="relative mt-5">
            <label for="date_type">Date Type</label>
            <select name="date_type" id="date_type" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#f18325]">
                <option value="">-- Select Date Type</option>
                <option value="Check In Date" {{ old('date_type', $timelineTask->date_type ?? '') == 'Check In Date' ? 'selected' : '' }}>Check In Date</option>
                <option value="Check Out Date" {{ old('date_type', $timelineTask->date_type ?? '') == 'Check Out Date' ? 'selected' : '' }}>Check Out Date</option>
                <option value="Create Date" {{ old('date_type', $timelineTask->date_type ?? '') == 'Create Date' ? 'selected' : '' }}>Create Date</option>
                <option value="Deposit Due Date" {{ old('date_type', $timelineTask->date_type ?? '') == 'Deposit Due Date' ? 'selected' : '' }}>Deposit Due Date</option>
                <option value="Final Payment Due Date" {{ old('date_type', $timelineTask->date_type ?? '') == 'Final Payment Due Date' ? 'selected' : '' }}>Final Payment Due Date</option>
            </select>

            <x-input-error :messages="$errors->get('date_type')" />
        </div>
    </div>
</div>