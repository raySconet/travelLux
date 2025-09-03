<form method="POST" action="" class="space-y-6" id="addEventCaseForm">
    @csrf

    <div class="grid grid-cols-2 items-center">
        <div class="grid grid-cols-[auto_1fr] items-center gap-2">
            <i class="fa-solid fa-calendar-plus fa-xl primary-color" style="color: #14548d;"></i>
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Add Event or Case') }}
            </h2>
        </div>
        <i
            id="closeAddEventModal"
            class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
            role="button"
            data-bs-dismiss="modal"
            aria-label="Close">
        </i>
    </div>


    <div>
        <x-input-label
            for="title"
            :value="__('Title')"
            class="block mb-1"
        />
        <x-text-area
            id="title"
            name="title"
            type="text"
            class="block w-full"
            :value="old('title')"
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
                class="datetimepicker"
                {{-- class="datetimepicker w-full border border-gray-300 rounded-md shadow-sm focus:outline-none" --}}
                :value="old('fromDate')"
                placeholder="Select date and time"
                required
            />
            <x-input-error
                class="mt-2"
                :messages="$errors->get('')"
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
                class="datetimepicker"
                {{-- class="datetimepicker w-full border border-gray-300 rounded-md shadow-sm focus:outline-none" --}}
                :value="old('to')"
                placeholder="Select date and time"
                required
            />
            <x-input-error
                class="mt-2"
                :messages="$errors->get('to')"
            />
        </div>
    </div>

    <div class="">
        <x-input-label
            :value="__('Type')"
            class="block mb-2"
        />
        <div class="grid grid-cols-2 gap-4">
            <label class="inline-flex items-center cursor-pointer">
                <x-input-radio
                    name="type"
                    value="event"
                    checked="{{ old('type') === 'event' ? 'checked' : '' }}"
                    required
                />
                <span class="ml-2 text-gray-700">Event</span>
            </label>

            <label class="inline-flex items-center cursor-pointer">
                <x-input-radio
                    name="type"
                    value="case"
                    checked="{{ old('type') === 'case' ? 'checked' : '' }}"
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

    <div class="grid grid-cols-2 gap-4">
        <x-cancel-btn onclick="closeMainModal()" />
        <x-primary-btn class="justify-self-end">
            {{ __('Save') }}
        </x-primary-btn>
    </div>
</form>
