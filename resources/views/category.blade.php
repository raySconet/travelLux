
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Category') }}
            </h2>
            <x-primary-btn id="openAddCategoryModal">
                <i class="fas fa-layer-group"></i>
                <span class="ml-2">{{ __('Add Category') }}</span>
            </x-primary-btn>
        </div>
    </x-slot>

    @include('category.category-view')

    <x-general-modal id="addCategoryModal">
        <x-slot name="header">
            <div class="grid grid-cols-[auto_1fr] items-center gap-2">
                <i class="fas fa-list fa-xl primary-color" style="color: #14548d;"></i>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('Add Category') }}
                </h2>
            </div>
            <i
                id="closeAddCategoryModal"
                class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                role="button"
                data-bs-dismiss="modal"
                aria-label="Close">
            </i>
        </x-slot>

        @include('category.category-parts.add-category')

        <x-slot name="footer">
            <x-primary-btn class="ml-auto" id="submitCategoryBtn">
                {{ __('Add Category') }}
            </x-primary-btn>
        </x-slot>
    </x-general-modal>

    <x-general-modal id="editCategoryModal">
        <x-slot name="header">
            <div class="grid grid-cols-[auto_1fr] items-center gap-2">
                <i class="fa-solid fa-pen-to-square shadow-lg fa-xl primary-color" style="color: #14548d;"></i>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('Edit Category') }}
                </h2>
            </div>
            <i
                id="closeEditCategoryModal"
                class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                role="button"
                data-bs-dismiss="modal"
                aria-label="Close">
            </i>
        </x-slot>

        @include('category.category-parts.edit-category')

        <x-slot name="footer">
            <x-primary-btn class="ml-auto" id="submitEditCategoryBtn">
                <i class="fa-solid fa-paper-plane"></i>
                <span class="ml-1">
                    {{ __('Save') }}
                </span>
            </x-primary-btn>
        </x-slot>
    </x-general-modal>
</x-app-layout>
