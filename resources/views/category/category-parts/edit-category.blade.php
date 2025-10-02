<form method="POST" class="space-y-6" id="editCategoryForm">
    @csrf

    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-12 sm:col-span-6">
            <x-input-label
                for="nameEditCategory"
                :value="__('Name')"
                class="mb-1"
            />
            <x-text-input
                id="nameEditCategory"
                name="nameEditCategory"
                type="text"
                class="block w-full"
                :value="old('nameEditCategory')"
                placeholder="Enter a category name"
                required
                autocomplete="nameEditCategory"
            />
            {{-- <x-input-error
                id="errorCategoryName"
                class="mt-2 block"
                :messages="$errors->get('name')"
            /> --}}
            <div class="text-sm text-red-600 space-y-1 "></div> {{-- id="errorCategoryName" --}}
        </div>

        <div class="col-span-12 sm:col-span-6">
            <x-input-label for="colorPicker" class="mb-1">Choose a color:</x-input-label>
            <div class="colorBox cursor-pointer w-[23px] h-[23px] rounded shadow-sm"></div>
            <input type="text" class="colorInput" value="#14548d" name="color" hidden />
        </div>
    </div>
</form>
