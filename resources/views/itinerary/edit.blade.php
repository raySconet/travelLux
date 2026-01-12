<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
                <h2 class=" text-2xl text-gray-500 leading-tight">
                    <i class="fa-solid fa-plane mr-2 text-[#f18325]"></i>{{ __('Itinerary') }}
                </h2>

                <div class="space-x-2">
                    <x-primary-btn><i class="far fa-plus-square"></i><span>Import Bookings</span></x-primary-btn>
                    <x-secondary-btn><i class="fas fa-link"></i><span>Copy Link</span></x-secondary-btn>
                    <x-secondary-btn><i class="fas fa-file-pdf"></i><span>Pdf</span></x-secondary-btn>
                    <x-secondary-buttonToDelete><i class="fas fa-copy"></i><span>Duplicate</span></x-secondary-buttonToDelete>
                    <x-secondary-buttonToDelete><i class="fas fa-trash"></i><span>Delete</span></x-secondary-buttonToDelete>
                    <x-primary-btn><i class="far fa-minus-square"></i><span>Close Trip</span></x-primary-btn>
                </div>
        </div>
    </x-slot>

    <div class="cover-wrapper">
       
        <input type="file" id="coverPhotoInput" accept="image/*" hidden />

        <div class="cover-image-container">
            <a href="/">
                <img src="{{ asset('images/archer-logo.png') }}" alt="Logo" class="cover-image" />
            </a>

            <div class="cover-overlay">
                <h4 class="flex items-center gap-2 text-lg">
                    <span>Itinerary</span>
                    <i class="fas fa-pencil-alt text-base" id="editItineraryNamePencil"></i>
                </h4>

                <h5 class="flex items-center gap-2 text-base" id="changeItineraryCoverPhotoBtn">
                    <i class="far fa-image"></i>
                    Change cover photo
                </h5>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg mt-4 p-4">
        <div class="grid grid-cols-4 gap-6">
            <div class="col-span-1 flex flex-col gap-4">
                <x-primary-btn class="flex justify-center gap-2 w-auto max-w-[200px] px-4">
                    <i class="far fa-plus-square mt-1"></i>
                    Add Day
                </x-primary-btn>

               
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium border-b-2 border-t-2 border-[#dee2e6]">Attachment</span>
                    <button class="text-[#ccc] text-lg">
                        <i class="fas fa-paperclip"></i>
                    </button>
                </div>
            </div>

            <div class="col-span-3 grid grid-cols-4 gap-6" x-data="{ section: 'yourItems' }">             
                <div class="col-span-3"></div>
                <div class="col-span-1 flex flex-col gap-1 items-end">
                    <div class="flex gap-2">
                        <x-primary-btn class="flex gap-2">
                            <i class="fas fa-edit mt-1"></i>
                            Edit
                        </x-primary-btn>

                        <x-primary-btn class="flex gap-2">
                            <i class="far fa-plus-square mt-1"></i>
                            Add Event
                        </x-primary-btn>
                    </div>
                    
                    <div class="flex gap-2 mt-2">
                        <button class="text-white px-10 py-2 rounded text-sm transition" :class="section === 'yourItems' ? 'bg-[#f18325]': 'bg-[#696969]'" @click="section = 'yourItems'">
                            Your Items
                        </button>

                        <button class="text-white px-10 py-2 rounded text-sm transition" :class="section === 'guides' ? 'bg-[#f18325]' : 'bg-[#696969]'" @click="section = 'guides'" >
                            Guides
                        </button>
                    </div>
                    <div class="mt-1 w-full text-center">
                        <div class="flex flex-col" x-show="section === 'yourItems'" x-cloak>
                            <label class="text-red-700 text-sm">Search in "Your Items" first.</label>
                            <label class="text-lg">Your Items</label>
                            <div class="relative mt-4">
                                <select name="yourItems" id="yourItems" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                                    <option value="-1">Nothing selected</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col" x-show="section === 'guides'" x-cloak>
                            <label class="text-red-700 text-sm">Search in "Your Items" first.</label>
                            <label class="text-lg">Guide Search</label>
                            <div x-data="{ guideSearchSection: 'searchByCity' }" class="flex flex-col gap-2">
                                <div class="flex gap-2 mt-2">
                                    <button class="text-white px-3 py-2 rounded text-sm transition"
                                        :class="guideSearchSection === 'searchByCity' ? 'bg-[#f18325]' : 'bg-[#696969]'"
                                        @click="guideSearchSection = 'searchByCity'">
                                        Search By City
                                    </button>

                                    <button class="text-white px-3 py-2 rounded text-sm transition"
                                        :class="guideSearchSection === 'searchByCountry' ? 'bg-[#f18325]' : 'bg-[#696969]'"
                                        @click="guideSearchSection = 'searchByCountry'">
                                        Search By Country
                                    </button>
                                </div>

                                <div class="">
                                    <div x-show="guideSearchSection === 'searchByCity'" x-cloak class="flex flex-col gap-4">
                                        <div class="relative ">
                                            <x-text-input type="text" id="cityNameInput" name="cityName"  />
                                            <x-input-label for="cityNameInput" class="text-gray-500">Enter City Name</x-input-label>
                                        </div>

                                        <div class="relative mt-3">
                                            <select name="citySelect" id="citySelect" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                                                <option value="-1">Nothing selected</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div x-show="guideSearchSection === 'searchByCountry'" x-cloak class="flex flex-col gap-4">
                                        <div class="relative mt-3">
                                            <select name="countrySelect" id="countrySelect" class="w-full border-b mb-4 focus:outline-none focus:border-[#f18325]">
                                                <option value="-1">Nothing selected</option>
                                            </select>
                                        </div>
                                        <div class="relative mt-3">
                                            <x-text-input type="date" id="StartingDate" name="StartingDate"  />

                                            <x-input-label for="StartingDate">Starting Date</x-input-label>
                                        </div>

                                        <div class="relative mt-3">
                                            <x-text-input type="date" id="EndingDate" name="EndingDate"  />

                                            <x-input-label for="EndingDate">Ending Date</x-input-label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>


</x-app-layout>