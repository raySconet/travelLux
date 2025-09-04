<div id="categoryViewSection" class="text-gray-900 px-5 py-5 grid grid-cols-1 xl:grid-cols-[100px_1fr] gap-2">
    <div id="categorySidebar" class="flex flex-col h-full">
        <div class="bg-white overflow-visible shadow-xs sm:rounded-lg py-6">
            <div class="h-full xl:min-h-[900px] overflow-y-auto">
                @include('category.category-parts.category-sidebar')
            </div>
        </div>
    </div>
    <div id="categoryLayout" class="flex flex-col h-full overflow-x-auto">
        <div class="min-w-[1075px] flex flex-col h-full">
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6">
                    @include('category.category-parts.category-header')
                </div>
            </div>
            <div class="mt-2 flex-1 flex flex-col bg-white xl:overflow-visible overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6">
                    @include('category.category-parts.category-layout')
                </div>
            </div>
        </div>
    </div>
</div>
