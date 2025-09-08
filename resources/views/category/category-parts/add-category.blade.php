<form method="POST" action="" class="space-y-6" id="addCategoryForm">
    @csrf

    <div>
        <x-input-label
            for="name"
            :value="__('Name')"
            class="block mb-1"
        />
        <x-text-area
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

    <div class="flex gap-2">
        <x-input-label for="colorPicker">Choose a color:</x-input-label>
        <input type="color" id="colorPicker" name="colorPicker" class="cursor-pointer w-[20px] h-[20px]" />
    </div>
</form>
