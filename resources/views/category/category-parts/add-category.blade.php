<form method="POST" action="{{ route('categories.store') }}" class="space-y-6" id="addCategoryForm">
    @csrf

    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-12 sm:col-span-6">
            <x-input-label
                for="name"
                :value="__('Name')"
                class="mb-1"
            />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="block w-full"
                :value="old('name')"
                required
                autocomplete="name"
            />
            {{-- <x-input-error
                id="errorCategoryName"
                class="mt-2 block"
                :messages="$errors->get('name')"
            /> --}}
            <div id="errorCategoryName" class="text-sm text-red-600 space-y-1 "></div>
        </div>

        <div class="col-span-12 sm:col-span-6">
            <x-input-label for="colorPicker" class="mb-1">Choose a color:</x-input-label>
            <div class="colorBox cursor-pointer w-[23px] h-[23px] rounded shadow-sm"></div>
            <input type="text" id="colorInput" value="#14548d" name="color" hidden />
        </div>
    </div>
</form>
