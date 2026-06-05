<div id="manageEventItineraryModal" class="fixed inset-0 bg-black/50 hidden z-[9999] overflow-y-auto -py-12" >

    <div class="min-h-full flex items-start justify-center">

        <div class="bg-white w-full max-w-5xl rounded shadow-lg relative my-10">

            <div class="flex items-center justify-between px-5 py-3 border-b border-[#dee2e6]">
                <h2 class="text-[18px] font-normal text-[#212529]">Manage Event</h2>

                <button type="button" onclick="closeManageEventItineraryModal()" class="text-[#545b62] hover:text-[#343a40] text-[18px] cursor-pointer">
                    ✕
                </button>
            </div>

            <div class="p-5 bg-[#f8f9fa]">

                <div class="bg-white p-5 border border-[#dee2e6]" style="box-shadow: 0 2px 8px -1px rgba(0, 0, 0, .2), 0 1px 1px 0 rgba(0, 0, 0, .14), 0 1px 3px 0 rgba(0, 0, 0, .12);">

                    <div class="mb-1">

                        <h3 class="text-[16px] text-[#212529] mb-2">Category</h3>

                        <div  id="categoryContainer" class="flex flex-wrap">

                            <template>

                                <button type="button" class="h-[32px] px-3 border border-[#545b62] text-[14px] flex items-center gap-2 transition-all"
                                        :class="selectedCategory.id === category.id
                                        ? 'bg-[#545b62] text-white'
                                        : 'bg-white text-[#495057] hover:bg-[#f1f3f5]'">

                                    <i :class="category.icon"></i>

                                    <span x-text="category.name"></span>

                                </button>

                            </template>

                        </div>

                    </div>

                    <div>

                        <h3 class="text-[16px] text-[#212529] mb-2">Sub-category</h3>

                        <div id="subcategoryContainer" class="flex flex-wrap">

                            <template>

                                <button type="button" class="h-[32px] px-3 border border-[#545b62] text-[14px] flex items-center gap-2 transition-all"
                                    :class="selectedSubcategory.id === sub.id
                                        ? 'bg-[#545b62] text-white'
                                        : 'bg-white text-[#495057] hover:bg-[#f1f3f5]'">

                                    <i :class="sub.icon"></i>

                                    <span x-text="sub.name"></span>

                                </button>

                            </template>

                        </div>

                    </div>

                    <form id="manageEventForm">

                        <input type="hidden" id="eventType" name="eventType">
                        <input type="hidden" id="subcategoryId" name="subcategoryId">
                        <input type="hidden" id="itineraryEventDayId" name="itineraryEventDayId">

                        <div class="pt-5">

                            <div>

                                <!-- ACTIVITY + ACTIVITY -->
                                <div class="dynamic-section activity-activity-section">
                                    @include('itinerary.partials.activity-activity')
                                </div>

                                <!-- ACTIVITY + FOOD/DRINK -->
                                <div class="dynamic-section activity-food-drink-section hidden">
                                    @include('itinerary.partials.activity-food-drink')
                                </div>

                                <!-- LODGING + CHECK-IN -->
                                <div class="dynamic-section lodging-check-in-section hidden">
                                    @include('itinerary.partials.lodging-check-in')
                                </div>

                                <!-- LODGING + CHECK-OUT -->
                                <div class="dynamic-section lodging-check-out-section hidden">
                                    @include('itinerary.partials.lodging-check-out')
                                </div>

                                <!-- FLIGHT + DEPARTURE -->
                                <div class="dynamic-section flight-departure-section hidden">
                                    @include('itinerary.partials.flight-departure')
                                </div>
                                
                                <!-- FLIGHT + ARRIVAL -->
                                <div class="dynamic-section flight-arrival-section hidden">
                                    @include('itinerary.partials.flight-arrival')
                                </div>

                                <!-- TRANSPORTATION + DEPARTURE -->
                                <div class="dynamic-section transportation-departure-transportation-section hidden">
                                    @include('itinerary.partials.transportation-departure')
                                </div>

                                <!-- TRANSPORTATION + ARRIVAL -->
                                <div class="dynamic-section transportation-arrival-transportation-section hidden">
                                    @include('itinerary.partials.transportation-arrival')
                                </div>

                                <!-- CRUISE + DEPARTURE -->
                                <div class="dynamic-section cruise-departure-cruise-section hidden">
                                    @include('itinerary.partials.cruise-departure')
                                </div>

                                <!-- CRUISE + ARRIVAL -->
                                <div class="dynamic-section cruise-arrival-cruise-section hidden">
                                    @include('itinerary.partials.cruise-arrival')
                                </div>

                                <!-- INFO + INFO -->
                                <div class="dynamic-section info-info-section hidden">
                                    @include('itinerary.partials.info-info')
                                </div>

                                <!-- INFO + CITY GUIDE -->
                                <div class="dynamic-section info-city-guide-section hidden">
                                    @include('itinerary.partials.info-city-guide')
                                </div>
                            </div>

                        </div>

                        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6] space-x-2">

                            <x-primary-btn type="submit">
                                <span>Save</span>
                            </x-primary-btn>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>
