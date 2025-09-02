<form method="POST" action="" class="space-y-6" id="addEventCaseForm">
    @csrf

    <h2 class="text-xl font-semiBold text-gray-800 mb-4">
        {{ __('Add Event or Case') }}
    </h2>

    <div>
        <x-input-label for="title" :value="__('Title')" class="block mb-1" />
        <x-text-input id="title" name="title" type="text" class="block w-full" :value="old('title')" required autocomplete="title" />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="date" :value="__('Date')" class="block mb-1" />
        <x-text-input id="date" name="date" type="date" class="block w-full" :value="old('date')" required />
        <x-input-error class="mt-2" :messages="$errors->get('date')" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <x-input-label for="from" :value="__('From')" class="block mb-1" />
            <x-text-input id="from" name="from" type="time" class="block w-full" :value="old('from')" required />
            <x-input-error class="mt-2" :messages="$errors->get('from')" />
        </div>

        <div>
            <x-input-label for="to" :value="__('To')" class="block mb-1" />
            <x-text-input id="to" name="to" type="time" class="block w-full" :value="old('to')" required />
            <x-input-error class="mt-2" :messages="$errors->get('to')" />
        </div>
    </div>

    <div>
        <x-input-label :value="__('Type')" class="block font-semibold mb-2" />
        <div class="grid grid-cols-2 gap-4">
            <label class="inline-flex items-center py-1">
                <x-input-radio
                    name="type"
                    value="event"
                    checked="{{ old('type') === 'event' ? 'checked' : '' }}"
                    required
                />
                <span class="ml-2 text-gray-700">Event</span>
            </label>

            <label class="inline-flex items-center py-1">
                <x-input-radio
                    name="type"
                    value="case"
                    checked="{{ old('type') === 'case' ? 'checked' : '' }}"
                    required
                />
                <span class="ml-2 text-gray-700">Case</span>
            </label>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('type')" />
    </div>

    <div class="grid grid-cols-2 gap-4 pt-6">
        <x-cancel-btn onclick="closeMainModal()" />
        <x-primary-btn class="justify-self-end">
            {{ __('Save') }}
        </x-primary-btn>
    </div>
</form>
