<form method="POST" action="{{ route('todos.store') }}" class="space-y-6" id="addTodoForm">
    @csrf

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 sm:col-span-12">
            <x-input-label
            for="completedBy"
            :value="__('Completed By')"
            class="mb-1"
            />
            <x-text-input
                id="completedBy"
                name="completedBy"
                type="text"
                class="block w-full"
                :value="old('Completed By')"
                required
                autocomplete="completedBy"
            />
            <div id="errorCategoryName" class="text-sm text-red-600 space-y-1 "></div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <x-input-label
            for="todoTitle"
            :value="__('Title')"
            class="mb-1"
            />
            <input type="hidden" name="sectionId" id="sectionId" value="">
            <input type="hidden" name="todoId" id="todoId" value="">
            <x-text-input
                id="todoTitle"
                name="todoTitle"
                type="text"
                class="block w-full"
                :value="old('todoTitle')"
                required
                autocomplete="todoTitle"
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

        <div class="col-span-12 sm:col-span-12">
            <x-input-label
                for="toDoNoteBox"
                :value="__('Note Box')"
                class="mb-1"
            />
            <x-text-area
                id="toDoNoteBox"
                name="toDoNoteBox"
                type="text"
                class="block w-full"
                :value="old('toDoNoteBox')"
                required
                autocomplete="toDoNoteBox"
            />
            <div id="errorCategoryName" class="text-sm text-red-600 space-y-1 "></div>
        </div>

    </div>
</form>
