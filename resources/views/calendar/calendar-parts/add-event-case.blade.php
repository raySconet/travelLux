<form method="POST" action="" class="space-y-6" id="addEventCaseForm">
    @csrf

    <div>
        <x-input-label
            for="title"
            :value="__('Title')"
            class="block mb-1"
        />
        <x-text-input
            id="title"
            name="title"
            type="text"
            class="block w-full text-start"
            :value="old('title')"
            placeholder="Enter here..."
            required
            autocomplete="title"
        />
        <x-input-error
            class="mt-2"
            :messages="$errors->get('title')"
        />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <x-input-label
                for="fromDate"
                :value="__('From Date')"
                class="block mb-1"
            />
            <x-text-input
                id="fromDate"
                name="fromDate"
                type="text"
                class="datetimepicker text-start"
                {{-- class="datetimepicker w-full border border-gray-300 rounded-md shadow-sm focus:outline-none" --}}
                :value="old('fromDate')"
                placeholder="Select date and time"
                required
            />
            <x-input-error
                class="mt-2"
                :messages="$errors->get('fromDate')"
            />
        </div>

        <div>
            <x-input-label
                for="toDate"
                :value="__('To Date')"
                class="block mb-1"
            />
            <x-text-input
                id="toDate"
                name="toDate"
                type="text"
                class="datetimepicker text-start"
                {{-- class="datetimepicker w-full border border-gray-300 rounded-md shadow-sm focus:outline-none" --}}
                :value="old('to')"
                placeholder="Select date and time"
                required
            />
            <x-input-error
                class="mt-2"
                :messages="$errors->get('toDate')"
            />
        </div>
    </div>

    <div id="eventCaseType">
        <x-input-label
            :value="__('Type')"
            class="block mb-2"
        />
        <div class="grid grid-cols-2 gap-4">
            <label class="inline-flex items-center cursor-pointer">
                <x-input-radio
                    name="type"
                    value="event"
                    required
                    checked
                />
                <span class="ml-2 text-gray-700">Event</span>
            </label>

            <label class="inline-flex items-center cursor-pointer">
                <x-input-radio
                    name="type"
                    value="case"
                    required
                />
                <span class="ml-2 text-gray-700">Case</span>
            </label>
        </div>
        <x-input-error
            class="mt-2"
            :messages="$errors->get('type')"
        />
    </div>

    <div>
        <x-input-label
            for="category"
            :value="__('Category')"
            class="block mb-1"
        />
        <select
            id="categorySelect"
            name="category"
            class="
                block w-full rounded-md bg-white border border-gray-300
                px-1.5 text-gray-900 cursor-pointer appearance-none bg-no-repeat bg-right bg-[length:1.25em_1.25em]
            "
        >
        </select>
        <x-input-error
            class="mt-2"
            :messages="$errors->get('category')"
        />
    </div>

    <div id="userFieldsContainer" class="hidden">
        <x-input-label
            for="user"
            :value="__('Users')"
            class="block mb-1"
        />
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <select
                    id="userSelect"
                    name="user"
                    class="
                        block w-full h-[200px] rounded-md bg-white border border-gray-300
                        px-1.5 text-gray-900 cursor-pointer appearance-none bg-no-repeat bg-right bg-[length:1.25em_1.25em]
                        selectArrowDown
                    "
                    required
                >
                </select>
                <x-input-error
                    class="mt-2"
                    :messages="$errors->get('user') ?: $errors->get('user.*')"
                />
            </div>
            <div id="selectedUsers" class="min-w-[120px] h-[200px] gap-2 border border-gray-300 rounded-md overflow-y-auto"></div>
        </div>
    </div>
</form>
