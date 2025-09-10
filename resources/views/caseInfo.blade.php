
<x-app-layout>
    <x-slot name="header" >
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Case Info') }}
        </h2>
    </x-slot>

    <div class="text-gray-900 px-3 py-3 grid grid-cols-1 2xl:grid-cols-12 gap-2">
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
                    </form>

                </div>
            </div>
        </div>
        <div class="2xl:col-span-2 flex flex-col h-full ">
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
        <div class="2xl:col-span-2 flex flex-col h-full ">
            <div class=" flex flex-col h-full">
                <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                    <div class="p-2">
                        @include('case-info.todo-checklists')

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
