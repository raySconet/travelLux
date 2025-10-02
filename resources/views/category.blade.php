
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

    <x-general-modal id="deleteCategoryConfirmModal" class="hidden">
        <x-slot name="header">
            <div class="grid grid-cols-[auto_1fr] items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation fa-xl text-red-600"></i>
                <h2 class="text-xl font-semibold text-red-700">Confirm Deletion</h2>
            </div>
            <i
                id="closeCategoryDeleteConfirmModal"
                class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                role="button"
                aria-label="Close"
            ></i>
        </x-slot>

        {{-- Body content --}}
        <div class="text-sm text-gray-700 space-y-4">
            <p>Are you sure you want to delete this item? This action cannot be undone.</p>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <button id="cancelCategoryDeleteBtn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-sm rounded cursor-pointer">
                    Cancel
                </button>
                <x-danger-button id="confirmCategoryDeleteBtn">
                    Yes, Delete
                </x-danger-button>
            </div>
        </x-slot>
    </x-general-modal>
</x-app-layout>
