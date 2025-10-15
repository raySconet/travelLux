<form method="POST" action="{{ route('sections.store') }}" class="space-y-6" id="addManageSectionForm">
    @csrf

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 sm:col-span-6">
            <x-input-label
                for="todoSectionTitle"
                :value="__('Title')"
                class="mb-1"
            />
            <x-text-input
                id="todoSectionTitle"
                name="todoSectionTitle"
                type="text"
                class="block w-full"
                :value="old('todoSectionTitle')"
                required
                autocomplete="todoSectionTitle"
            />

            <div id="errorCategoryName" class="text-sm text-red-600 space-y-1 "></div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <x-input-label
                for="todoSectionCategory"
                :value="__('Category')"
                class="mb-1"
            />
            <select
                id="todoSectionCategory"
                name="todoSectionCategory"
                class="
                    block w-full text-center rounded-md bg-white border border-gray-200
                    cursor-pointer appearance-none bg-no-repeat bg-right
                "
            >
            </select>

            <div id="errorCategoryName" class="text-sm text-red-600 space-y-1 "></div>
        </div>
         <div class="col-span-12 sm:col-span-12">
            <x-input-label
                for="sectionDescription"
                :value="__('Description')"
                class="mb-1"
            />
            <x-text-area
                id="sectionDescription"
                name="sectionDescription"
                type="text"
                class="block w-full"
                :value="old('sectionDescription')"
                required
                autocomplete="sectionDescription"
            />

            <div id="errorCategoryName" class="text-sm text-red-600 space-y-1 "></div>
        </div>


    </div>
</form>
