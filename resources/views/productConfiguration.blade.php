<x-app-layout>
    <x-slot name="header" >
        <div class="p-4 bg-white shadow sm:rounded-lg">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-ship mr-2 text-[#f18325]"></i>{{ __('Product Configuration') }}
            </h2>
        </div>
    </x-slot>

        <div class=" mx-auto py-4 px-4">
            <div class=" p-4 bg-white shadow sm:rounded-lg">
                <x-grid   class="2xl:grid-cols-12">
                    <x-col class="2xl:col-span-3 px-3">
                        <h2 class=" text-lg  text-gray-700 text-center p-2 border-t-1 border-b-1 border-gray-200 ">
                            <i class="fa-solid fa-circle-plus text-[#f18325] mr-2"></i>
                            {{ __('Products') }}
                        </h2>
                    </x-col>

                    <x-col class="2xl:col-span-3 px-3">
                        <h2 class=" text-lg  text-gray-700 text-center p-2  border-t-1 border-b-1 border-gray-200">
                            <i class="fa-solid fa-circle-plus text-[#f18325] mr-2"></i>
                            {{ __('Destinations') }}
                        </h2>
                    </x-col>

                    <x-col class="2xl:col-span-3 px-3">
                        <h2 class=" text-lg  text-gray-700 text-center p-2  border-t-1 border-b-1 border-gray-200">
                            <i class="fa-solid fa-circle-plus text-[#f18325] mr-2"></i>
                            {{ __('Resort/Ships') }}
                        </h2>
                    </x-col>
                    <x-col class="2xl:col-span-3  px-3">
                        <h2 class=" text-lg  text-gray-700 text-center p-2  border-t-1 border-b-1 border-gray-200">
                            <i class="fa-solid fa-circle-plus text-[#f18325] mr-2"></i>
                            {{ __('Cruise itineraries') }}
                        </h2>
                    </x-col>
                </x-grid>



            </div>
            {{-- <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div> --}}
        </div>
</x-app-layout>
