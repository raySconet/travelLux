<form method="POST" action="{{ route('todos.store') }}" class="space-y-6" id="addManageSectionForm">
    @csrf

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 sm:col-span-6">
            <x-input-label
                for="todoTitle"
                :value="__('Title')"
                class="mb-1"
            />
            <x-text-input
                id="todoSectionTitle"
                name="todoSectionTitle"
                type="text"
                class="block w-full"
                :value="old('todoSectionTitle')"
                required
                autocomplete="todoSectionTitle"
            />

            <div id="errorCategoryName" class="text-sm text-red-600 space-y-1 "></div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <x-input-label
                for="todoDate"
                :value="__('Date')"
                class="mb-1"
            />
            <x-text-input
                id="todoDate"
                name="todoDate"
                type="text"
                class="datetimepicker text-start"
                {{-- class="datetimepicker w-full border border-gray-300 rounded-md shadow-sm focus:outline-none" --}}
                :value="old('todoDate')"
                placeholder="Select date"
                required
            />

            <div id="errorCategoryName" class="text-sm text-red-600 space-y-1 "></div>
        </div>
         <div class="col-span-12 sm:col-span-12">
            <x-input-label
                for="todoDescription"
                :value="__('Description')"
                class="mb-1"
            />
            <x-text-area
                id="todoDescription"
                name="todoDescription"
                type="text"
                class="block w-full"
                :value="old('todoDescription')"
                required
                autocomplete="todoDescription"
            />

            <div id="errorCategoryName" class="text-sm text-red-600 space-y-1 "></div>
        </div>


    </div>
</form>
