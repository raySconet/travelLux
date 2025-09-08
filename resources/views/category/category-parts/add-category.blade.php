<form method="POST" action="" class="space-y-6" id="addCategoryForm">
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
            <x-input-error
                class="mt-2"
                :messages="$errors->get('Name')"
            />
        </div>

        <div class="col-span-12 sm:col-span-6">
            <x-input-label for="colorPicker" class="mb-1">Choose a color:</x-input-label>
            <div id="colorBox" class="cursor-pointer w-[20px] h-[20px] rounded"></div>
            <input type="text" id="colorInput" hidden />
        </div>
    </div>
</form>
