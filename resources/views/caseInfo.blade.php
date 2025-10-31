
<x-app-layout>
    <x-slot name="header" >
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Case Info') }}
        </h2>
    </x-slot>

    <div class="text-gray-900 px-3 py-3 grid grid-cols-1 2xl:grid-cols-14 gap-2">
        <div class="2xl:col-span-8 flex flex-col h-full">
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-3 text-gray-900">

                    <form method="post" action="{{ route('profile.update') }}" class=" ">
                        @csrf
                        <div class="flex float-right mt-[-5px] mb-[5px]">
                            <x-primary-btn class="">{{ __('Save') }}</x-primary-btn>

                            @if (session('status') === 'profile-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600"
                                >{{ __('Saved.') }}</p>
                            @endif
                        </div>
                        @include('case-info.contact-info')
                        @include('case-info.case-info')
                        @include('case-info.treating-chart')
                        @include('case-info.negotiation-chart')
                        @include('case-info.litigation-chart')
                        @include('case-info.affidavit-chart')
                        @include('case-info.facilitating-settlement-chart')
                        @include('case-info.deposits-expenses-chart')
                    </form>

                </div>
            </div>
        </div>
        <div class="2xl:col-span-3 flex flex-col h-full ">
            <div class=" flex flex-col h-full">
                <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                    <div class="p-2">
                        @include('case-info.todo')
                        <br>
                        @include('case-info.old-todo')

                    </div>
                </div>
            </div>
        </div>
        <div class="2xl:col-span-3 flex flex-col h-full ">
            <div class=" flex flex-col h-full">
                <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                    <div class="p-2">
                        @include('case-info.todo-checklists')

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="height: 20px;">
    </div>
</x-app-layout>


<x-general-modal id="addManageSectionsModal">
    <x-slot name="header">
        <div class="grid grid-cols-[auto_1fr] items-center gap-2">
            <i class="fas fa-list fa-xl primary-color" style="color: #14548d;"></i>
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Manage Sections') }}
            </h2>
        </div>
        <i
            id="closeAddManageSectionsModal"
            class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
            role="button"
            data-bs-dismiss="modal"
            aria-label="Close">
        </i>
    </x-slot>

    @include('case-info.case-info-parts.add-section')

    <x-slot name="footer">
        <x-primary-btn class="ml-auto" id="submitManageSectionBtn">
            {{ __('Save Section') }}
        </x-primary-btn>
    </x-slot>
</x-general-modal>



<x-general-modal id="addTodoModal">
    <x-slot name="header">
        <div class="grid grid-cols-[auto_1fr] items-center gap-2">
            <i class="fas fa-list fa-xl primary-color" style="color: #14548d;"></i>
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Manage Todos') }}
            </h2>
        </div>
        <i
            id="closeAddTodoModal"
            class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
            role="button"
            data-bs-dismiss="modal"
            aria-label="Close">
        </i>
    </x-slot>

    @include('case-info.case-info-parts.add-todo')

    <x-slot name="footer">
        <x-primary-btn class="ml-auto" dataId="" id="submitTodoBtn">
            {{ __('Save Section') }}
        </x-primary-btn>
    </x-slot>
</x-general-modal>
