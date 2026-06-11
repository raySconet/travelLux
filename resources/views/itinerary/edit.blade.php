@php
    $viewOnly = $viewOnly ?? false;
    $signedUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
        'itinerary.view',
        now()->addDays(30),
        ['itinerary' => $itinerary->id]
    );
@endphp
<x-app-layout :viewOnly="$viewOnly ?? false">
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-none flex items-center justify-between">
                <h2 class=" text-xl text-gray-500 leading-tight">
                    <i class="fa-solid fa-plane mr-2 text-[#B6844A]"></i>{{ __('Itinerary') }}
                </h2>

                @if(!$viewOnly)
                <div class="space-x-2">
                    <x-primary-btn  onclick="openImportReservationBookings()"><i class="far fa-plus-square"></i><span>Import Bookings</span></x-primary-btn>
                    <x-secondary-btn onclick="copyItineraryLink('{{ $signedUrl }}')">
                        <i class="fas fa-link"></i>
                        <span>Copy Link</span>
                    </x-secondary-btn>
                    <x-secondary-btn onclick="openPdf('{{ route('itinerary.pdf', $itinerary->id) }}')">
                        <i class="fas fa-file-pdf"></i>
                        <span>Pdf</span>
                    </x-secondary-btn>
                    <x-secondary-buttonToDelete onclick="openDuplicateItineraryModal('{{ $itinerary->name }}','{{ $itinerary->date }}')">
                        <i class="fas fa-copy"></i>
                        <span>Duplicate</span>
                    </x-secondary-buttonToDelete>
                    <form method="POST" action="{{ route('itinerary.destroy', $itinerary->id)}}" class="inline delete-form">
                        @csrf
                        @method('DELETE')

                        <x-secondary-buttonToDelete type="button" onclick="openDeleteModal(this)">
                            <i class="fas fa-trash"></i><span>Delete</span>
                        </x-secondary-buttonToDelete>
                    </form>
                    <x-primary-btn onclick="window.location='{{ route('itinerary.index') }}'"><i class="far fa-minus-square"></i><span>Close Trip</span></x-primary-btn>
                </div>
                @endif
        </div>
    </x-slot>

    <div class="cover-wrapper ml-3 mr-3">
       
        <input type="file" id="coverPhotoInput" accept="image/*" hidden />

        <div class="cover-image-container">
            <a href="/">
                @php
                    $cover = $itinerary->itineraryImages()->where('is_deleted', 0)->latest()->first();

                    $coverUrl = $cover ? asset('storage/attachments/itineraries/covers/' . $cover->id . '.' . $cover->extension) : asset('images/archer-logo.png');
                @endphp

                <img src="{{ $coverUrl }}" class="cover-image" />
            </a>

            <div class="cover-overlay">
                <h4 class="flex items-center gap-2 text-lg">
                    <span>{{ $itinerary->name }}</span>
                    @if(!$viewOnly)
                        <i class="fas fa-pencil-alt text-base cursor-pointer" onclick="openEditItineraryModal()" id="editItineraryNamePencil"></i>
                    @endif
                </h4>

                @if(!$viewOnly)
                <h5 class="flex items-center gap-2 text-base cursor-pointer"  id="changeItineraryCoverPhotoBtn">
                    <i class="far fa-image"></i>
                    Change cover photo
                </h5>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-none mt-4 p-4 ml-3">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-2 flex flex-col gap-2 border-r border-[#ccc] pr-2">
                @if(!$viewOnly)
                <x-primary-btn id="addDayBtn" data-itinerary-id="{{ $itinerary->id }}" class="flex items-center justify-center gap-2 w-full px-8">
                    <i class="far fa-plus-square"></i>
                    Add Day
                </x-primary-btn>
                @endif


                <div id="attachmentsBtn" class="flex items-center justify-between border-t-2 border-b-2 border-[#dee2e6] px-2 py-1 hover:bg-gray-50 cursor-pointer">
                    <input type="file" id="itineraryAttachmentsInput" multiple hidden>
                    <span class="font-medium text-base">Attachments</span>

                    <button class="text-[#ccc] text-lg">
                        <i class="fas fa-paperclip"></i>
                    </button>
                </div>

                @foreach ($itinerary->itineraryDays as $day)

                    <div class="flex flex-col border-b-2 border-[#dee2e6] px-2 py-2 hover:bg-gray-50 cursor-pointer itinerary-day" data-day='@json($day->toArray())'>

                        <span class="font-medium text-sm text-gray-500">
                            {{ $day->dayDate ? \Carbon\Carbon::parse($day->dayDate)->format('F d') : '' }}
                        </span>

                        <div class="flex items-center justify-between">

                            <div class="flex flex-col">
                                
                                <span class="font-medium text-base">
                                    Day {{ $day->dayNumber }}
                                </span>

                            </div>

                            <button class="text-[#ccc] text-base delete-day-btn cursor-pointer" data-day-id="{{ $day->id }}">
                                <i class="fas fa-times"></i>
                            </button>

                        </div>
                    </div>

                @endforeach
            </div>

                <div class="col-span-10 grid grid-cols-12 gap-6" x-data="{ section: 'yourItems' }">             
                    <div class="col-span-8">

                    <div id="dayHeader" class="hidden">

                        <div id="dayViewMode">
                            <div class="flex items-center gap-4">

                                <div class="flex flex-col text-center min-w-[80px]">
                                    <p id="dayMonth" class="text-base"></p>
                                    <p id="dayDate" class="text-base"></p>
                                </div>

                                <div class="w-px h-12 bg-[#ccc]"></div>

                                <div class="flex flex-col">
                                    <p id="dayNumber" class="text-lg font-medium text-gray-800"></p>
                                </div>

                            </div>

                            <p id="dayTitle" class="text-lg mt-1 mb-2"></p>
                        </div>

                        <div id="dayEditMode" class="hidden">
                            <div class="flex gap-4 items-center mb-2">

                                <div class="relative mt-2 w-[450px]">
                                    <x-text-input type="date" id="editDayDate" name="dayDate" class="w-full" />

                                    <x-input-label for="dayDate">Date</x-input-label>
                                </div>

                                <div class="relative mt-2 w-[450px]">
                                    <x-text-input type="text" id="editDayTitle" name="dayTitle" class="w-full" />

                                    <x-input-label for="dayTitle">Title</x-input-label>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div id="dayEventsContainer" class="flex flex-col gap-4">
                        
                    

                    </div>

                </div>
                
                @if(!$viewOnly)
                <div class="col-span-4 flex flex-col gap-1 items-end">
                    
                    <div class="flex gap-2">

                        <x-primary-btn id="editBtn" class="flex gap-2 hidden">
                            <i class="fas fa-edit mt-1"></i>
                            Edit
                        </x-primary-btn>

                        <x-primary-btn id="doneBtn" class="flex gap-2 hidden px-19">
                            Done
                        </x-primary-btn>

                        <x-primary-btn id="addEventBtn" onclick="openManageEventItineraryModal()" class="flex gap-2 hidden">
                            <i class="far fa-plus-square mt-1"></i>
                            Add Event
                        </x-primary-btn>

                    </div>

                    <div class="flex gap-2 mt-2 w-full">
                        <button class="flex-1 text-white px-4 xl:px-8 py-2 rounded text-sm transition cursor-pointer"
                            :class="section === 'yourItems' ? 'bg-[#B6844A]': 'bg-[#696969]'" @click="section = 'yourItems'">
                            Your Items
                        </button>

                        <button  class="flex-1 text-white px-4 xl:px-8 py-2 rounded text-sm transition cursor-pointer"
                            :class="section === 'guides' ? 'bg-[#B6844A]' : 'bg-[#696969]'" @click="section = 'guides'">
                            Guides
                        </button>
                    </div>
                    <div class="mt-1 w-full text-center">
                        <div class="flex flex-col" x-show="section === 'yourItems'" x-cloak>
                            <label class="text-red-700 text-sm">Search in "Your Items" first.</label>
                            <label class="text-lg">Your Items</label>
                            <div class="relative mt-4">
                                <select name="yourItems" id="yourItems" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#B6844A]">
                                    <option value="-1">Nothing selected</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col" x-show="section === 'guides'" x-cloak>
                            <label class="text-red-700 text-sm">Search in "Your Items" first.</label>
                            <label class="text-lg">Guide Search</label>
                            <div x-data="{ guideSearchSection: 'searchByCity' }" class="flex flex-col gap-2">
                                <div class="flex gap-2 mt-2">
                                    <button class="text-white px-3 py-2 rounded text-sm transition cursor-pointer"
                                        :class="guideSearchSection === 'searchByCity' ? 'bg-[#B6844A]' : 'bg-[#696969]'"
                                        @click="guideSearchSection = 'searchByCity'">
                                        Search By City
                                    </button>

                                    <button class="text-white px-3 py-2 rounded text-sm transition cursor-pointer"
                                        :class="guideSearchSection === 'searchByCountry' ? 'bg-[#B6844A]' : 'bg-[#696969]'"
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
                                            <select name="citySelect" id="citySelect" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#B6844A]">
                                                <option value="-1">Nothing selected</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div x-show="guideSearchSection === 'searchByCountry'" x-cloak class="flex flex-col gap-4">
                                        <div class="relative mt-3">
                                            <select name="countrySelect" id="countrySelect" class="w-full border-b-2 border-[#bdbdbd] mb-4 focus:outline-none focus:border-[#B6844A]">
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
                @endif

            </div>
        </div>
    </div>

    <div id="copySuccessOverlay" class="fixed inset-0 hidden items-center justify-center bg-black/40 z-[9999]">
        <i class="fas fa-check-square text-[#B6844A] text-8xl"></i>
    </div>

</x-app-layout>

<x-import-reservation-bookings />
<x-delete-modal />
<x-duplicate-itinerary-modal />
<x-manage-event-modal />
<x-delete-day-modal />
<x-edit-itinerary-modal />
<script>
    const ITINERARY_BASE_DATE = @json($itinerary->date);

    const itineraryData = {
        id: @json($itinerary->id),
        name: @json($itinerary->name),
        date: @json($itinerary->date),
    };

    const itineraryAttachments = @json($itinerary->attachments);
    
    const VIEW_ONLY = @json($viewOnly ?? false);
</script>