import './bootstrap';

$(document).ready(() => {
    // start import bookings
    window.openImportReservationBookings = function(){
        $('#importReservationBookingsModal').removeClass('hidden');
    }

    window.closeImportReservationBookings = function(){
        $('#importReservationBookingsModal').addClass('hidden');
    }
    // end import bookings

    // start duplicate itinerary
    window.openDuplicateItineraryModal = function(name, date){

        $('#duplicateItineraryModal').removeClass('hidden');

        $('#duplicateName').val(name );
        $('#duplicateDate').val(date);
    }

    window.closeDuplicateItineraryModal = function(){
        $('#duplicateItineraryModal').addClass('hidden');
    }

    $(document).on('submit', '#duplicateItineraryForm', function(e){

        e.preventDefault();

        $.ajax({
            url: `/itinerary/${itineraryData.id}/duplicate`,
            type: 'POST',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: $('#duplicateName').val(),
                date: $('#duplicateDate').val(),
            },

            success: function(response){

                closeDuplicateItineraryModal();

                window.location.href = response.redirect;
            },

            error: function(xhr){

                console.log(xhr.responseText);
            }
        });
    });
    // end duplicate itinerary

    // start edit itinerary
    window.openEditItineraryModal = function(){

        $('#editItineraryName').val(itineraryData.name);
        $('#editItineraryDate').val(itineraryData.date);

        $('#editItineraryModal').removeClass('hidden');
    }

    window.closeEditItineraryModal = function(){
        $('#editItineraryModal').addClass('hidden')
    }

    $('#editItineraryForm').on('submit', function(e){

        e.preventDefault();

        $.ajax({
            url: `/itinerary/${itineraryData.id}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT',
                name: $('#editItineraryName').val(),
                date: $('#editItineraryDate').val(),
            },

            success: function(response){

                itineraryData.name = $('#editItineraryName').val();
                itineraryData.date = $('#editItineraryDate').val();

                $('#editItineraryModal').addClass('hidden');

                $('#editItineraryNamePencil').closest('h4').find('span').text(itineraryData.name);
            },

            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
    });
    // end edit itinerary

    // start add day
    $(document).on('click', '#addDayBtn', function () {

        const itineraryId = $(this).data('itinerary-id');

        $.ajax({
            url: `/itinerary/${itineraryId}/add-day`,
            type: 'POST',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function () {

                location.reload();
            }
        });
    });

    window.openDeleteDayModal = function(){
        $('#deleteDayModal').removeClass('hidden');
    }

    let selectedDayId = null;

    $(document).on('click', '.delete-day-btn', function () {

        selectedDayId = $(this).data('day-id');

        $('#deleteDayModal').removeClass('hidden');
    });

    window.closeDeleteDayModal = function () {

        $('#deleteDayModal').addClass('hidden');

        selectedDayId = null;
    }

    $(document).on('click', '#confirmDeleteDayBtn', function () {

        if (!selectedDayId) return;

        $.ajax({
            url: `/itinerary/day/${selectedDayId}`,
            type: 'DELETE',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function () {

                closeDeleteDayModal();

                location.reload();
            }
        });
    });
    // end add day

    // start render itinerary day events
    let selectedDay = null;
    $(document).on('click', '.itinerary-day', function () {

        $('.itinerary-day').removeClass('bg-gray-100');
        $(this).addClass('bg-gray-100');

        selectedDay = $(this).data('day');

        renderDayHeader(selectedDay);
        renderDayEvents(selectedDay);
        toggleDayActions();
    });

    function renderDayEvents(day) {

        let html = '';

        if (!day.events || day.events.length === 0) {
            html = ` <div class="text-gray-400 text-lg text-center mt-20"></div> `;
        } else {

            day.events.forEach(event => {
                let formattedTime = '';

                if ( event.eventTime && event.eventTime !== '-1') {

                    const [hours, minutes] = event.eventTime.split(':');

                    const hour = parseInt(hours);

                    const suffix = hour >= 12 ? 'PM' : 'AM';

                    const formattedHour = hour % 12 || 12;

                    formattedTime = `${formattedHour}:${minutes} ${suffix}`;
                }

                let htmlBlock = '';

                if (event.eventType == 1 && event.itineraryEventFormActivitySubcategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-day text-[#2C3E50]"></i>

                                    <span class="text-sm text-gray-700 font-extrabold">
                                        ${formattedTime}
                                    </span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}

                            </div>

                            <div class="text-2xl">
                                ${event.itineraryActivityFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryActivityFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryActivityFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryActivityFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryActivityFormProvider
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Provider</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormProvider}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryActivityFormAmount && event.itineraryActivityFormCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormCurrency ?? ''}
                                                    ${event.itineraryActivityFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 1 && event.itineraryEventFormActivitySubcategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-utensils text-[#90d5ec]"></i>

                                    <span class="text-sm text-[#009fd5] font-extrabold">
                                        ${formattedTime}
                                    </span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryActivityFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryActivityFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryActivityFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryActivityFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryActivityFormProvider
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Provider</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormProvider}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryActivityFormAmount && event.itineraryActivityFormCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormCurrency ?? ''}
                                                    ${event.itineraryActivityFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 2 && event.itineraryEventFormLodgingSubcategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-bed text-[#fea700]"></i>

                                    <span class="text-sm text-[#fea700] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#fea700] font-extrabold">CHECK-IN</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryLodgingFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryLodgingFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryLodgingFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryLodgingFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryLodgingFormRoomBedType
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Room/Bed Type</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormRoomBedType}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryLodgingFormAmount && event.itineraryLodgingFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormAmountCurrency ?? ''}
                                                    ${event.itineraryLodgingFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 2 && event.itineraryEventFormLodgingSubcategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-bed text-[#fea700]"></i>

                                    <span class="text-sm text-[#fea700] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#fea700] font-extrabold">CHECK-OUT</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>` : '' }
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryLodgingFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryLodgingFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryLodgingFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryLodgingFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryLodgingFormRoomBedType
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Room/Bed Type</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormRoomBedType}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryLodgingFormAmount && event.itineraryLodgingFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormAmountCurrency ?? ''}
                                                    ${event.itineraryLodgingFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 3 && event.itineraryEventFormFlightSubCategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-plane text-[#ca5]"></i>

                                    <span class="text-sm text-[#ca5] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#ca5] font-extrabold">DEPARTURE</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>` : ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryFlightFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryFlightFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryFlightFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormAirline
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Airline</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormAirline}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormFlightNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Airline</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormFlightNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>


                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryFlightFormGate
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Gate</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormGate}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormTerminal
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Terminal</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormTerminal}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormSeatTicketDetails
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Seat/ Ticket Details</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormSeatTicketDetails}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryFlightFormAmount && event.itineraryFlightFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormAmountCurrency ?? ''}
                                                    ${event.itineraryFlightFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 3 && event.itineraryEventFormFlightSubCategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-plane text-[#ca5]"></i>

                                    <span class="text-sm text-[#ca5] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#ca5] font-extrabold">ARRIVAL</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryFlightFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryFlightFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-5 gap-4 text-sm">

                                ${
                                    event.itineraryFlightFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormAirline
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Airline</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormAirline}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormFlightNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Airline</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormFlightNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormGate
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Gate</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormGate}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>


                            <div class="grid grid-cols-3 gap-4 text-sm">

                                ${
                                    event.itineraryFlightFormTerminal
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Terminal</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormTerminal}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormSeatTicketDetails
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Seat/ Ticket Details</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormSeatTicketDetails}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryFlightFormAmount && event.itineraryFlightFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormAmountCurrency ?? ''}
                                                    ${event.itineraryFlightFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 4 && event.itineraryTransportationFormSubCategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-train text-[#f49ac1]"></i>

                                    <span class="text-sm text-[#f49ac1] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#f49ac1] font-extrabold">DEPARTURE</span>
                                </div>

                                ${!VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryTransportationFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryTransportationFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-3 gap-4 text-sm">

                                ${
                                    event.itineraryTransportationFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryTransportationFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryTransportationFormCarrier
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Carrier</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormCarrier}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>

                            <div class="grid grid-cols-2 gap-4 text-sm">

                                ${
                                    event.itineraryTransportationFormTransportationNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Transportation Number</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormTransportationNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryTransportationFormAmount && event.itineraryTransportationFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormAmountCurrency ?? ''}
                                                    ${event.itineraryTransportationFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 4 && event.itineraryTransportationFormSubCategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-train text-[#f49ac1]"></i>

                                    <span class="text-sm text-[#f49ac1] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#f49ac1] font-extrabold">ARRIVAL</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryTransportationFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryTransportationFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-3 gap-4 text-sm">

                                ${
                                    event.itineraryTransportationFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryTransportationFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryTransportationFormCarrier
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Carrier</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormCarrier}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>

                            <div class="grid grid-cols-2 gap-4 text-sm">

                                ${
                                    event.itineraryTransportationFormTransportationNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Transportation Number</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormTransportationNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryTransportationFormAmount && event.itineraryTransportationFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormAmountCurrency ?? ''}
                                                    ${event.itineraryTransportationFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 5 && event.itineraryCruiseFormSubCategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-ship text-[#6c9]"></i>

                                    <span class="text-sm text-[#6c9] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#6c9] font-extrabold">DEPARTURE</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryCruiseFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryCruiseFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryCruiseFormCabinNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Cabin Number</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCabinNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormCabinType
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Cabin Type</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCabinType}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormCarrier
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Carrier</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCarrier}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryCruiseFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryCruiseFormAmount && event.itineraryCruiseFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormAmountCurrency ?? ''}
                                                    ${event.itineraryCruiseFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 5 && event.itineraryCruiseFormSubCategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-ship text-[#6c9]"></i>

                                    <span class="text-sm text-[#6c9] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#6c9] font-extrabold">ARRIVAL</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryCruiseFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryCruiseFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryCruiseFormCabinNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Cabin Number</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCabinNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormCabinType
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Cabin Type</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCabinType}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormCarrier
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Carrier</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCarrier}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryCruiseFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryCruiseFormAmount && event.itineraryCruiseFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormAmountCurrency ?? ''}
                                                    ${event.itineraryCruiseFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 6 && event.itineraryEventFormInfoSubCategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-info-circle text-[#627e8c]"></i>

                                    <span class="text-sm text-[#627e8c] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryInfoFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryInfoFormNote ?? ''}
                            </div>

                        </div>
                    `;
                } else if (event.eventType == 6 && event.itineraryEventFormInfoSubCategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-info-circle text-[#627e8c]"></i>

                                    <span class="text-sm text-[#627e8c] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>` : ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryInfoFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryInfoFormNote ?? ''}
                            </div>

                        </div>
                    `;
                }

                html += htmlBlock;
            });
        }

        $('#dayEventsContainer').html(html);
    }

    // start delete event
    let selectedEventId = null;

    $(document).on('click', '.delete-event-btn', function (e) {
        e.stopPropagation();

        selectedEventId = $(this).data('event-id');

        $.ajax({
            url: `/itinerary/event/${selectedEventId}`,
            type: 'DELETE',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function () {

                if (selectedDay) {

                    selectedDay.events = selectedDay.events.filter(event => {
                        return event.id != selectedEventId;
                    });

                    renderDayEvents(selectedDay);
                }
            }
        });
    });
    // end delete event

    function toggleDayActions() {

        if (selectedDay && selectedDay.id) {

            if (isEditingDay) {

                $('#doneBtn').removeClass('hidden');

                $('#editBtn, #addEventBtn').addClass('hidden');

            } else {

                $('#editBtn, #addEventBtn').removeClass('hidden');

                $('#doneBtn').addClass('hidden');
            }

        } else {

            $('#editBtn, #addEventBtn, #doneBtn').addClass('hidden');
        }
    }

    $(document).on('click', '#editBtn', function () {

        if (!selectedDay) return;

        isEditingDay = true;

        $('#dayViewMode').addClass('hidden');
        $('#dayEditMode').removeClass('hidden');

        $('#editDayDate').val(selectedDay.dayDate ?? '');

        $('#editDayTitle').val(selectedDay.dayTitle ?? '');

        toggleDayActions();
    });

    $(document).on('click', '#doneBtn', function () {

        if (!selectedDay) return;

        $.ajax({
            url: `/itinerary/day/${selectedDay.id}`,
            type: 'PUT',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                date: $('#editDayDate').val(),
                title: $('#editDayTitle').val()
            },

            success: function () {

                selectedDay.dayTitle = $('#editDayTitle').val();

                selectedDay.dayDate = $('#editDayDate').val();

                isEditingDay = false;

                $('#dayEditMode').addClass('hidden');

                $('#dayViewMode').removeClass('hidden');

                renderDayHeader(selectedDay);

                const sidebarDay = $('.itinerary-day').filter(function () {
                    return $(this).data('day').id == selectedDay.id;
                });

                sidebarDay.find('span.text-sm').text(
                    selectedDay.dayDate
                        ? new Date(selectedDay.dayDate + "T00:00:00")
                            .toLocaleDateString('en-US', {
                                month: 'long',
                                day: '2-digit'
                            })
                        : ''
                );

                sidebarDay.attr('data-day', JSON.stringify(selectedDay));

                toggleDayActions();
            },

            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });

    let isEditingDay = false;

    $(document).ready(function () {

        toggleDayActions();

        const firstDay = $('.itinerary-day').first();

        if (firstDay.length) {

            firstDay.trigger('click');
        }
    });

    function renderDayHeader(day) {

        const header = document.getElementById('dayHeader');

        if (!day) {
            header.classList.add('hidden');
            return;
        }

        header.classList.remove('hidden');

        const divider = document.querySelector('#dayViewMode .w-px');

        document.getElementById('dayMonth').innerText = '';
        document.getElementById('dayDate').innerText = '';

        if (day.dayDate) {

            const fullDate = new Date(day.dayDate + "T00:00:00");

            if (!isNaN(fullDate.getTime())) {

                document.getElementById('dayMonth').innerText = fullDate.toLocaleString('en-US', { month: 'long' });

                document.getElementById('dayDate').innerText = fullDate.getDate();

                divider.classList.remove('hidden');
            }

        } else {

            divider.classList.add('hidden');
        }

        document.getElementById('dayNumber').innerText = `Day ${day.dayNumber ?? ''}`;

        document.getElementById('dayTitle').innerText = day.dayTitle ?? '';

        $('#dayEditMode').addClass('hidden');

        $('#dayViewMode').removeClass('hidden');

        isEditingDay = false;

        toggleDayActions();
    }
    // end render itinerary day events

    // start manage event
    window.openManageEventItineraryModal = function () {
        editingEventId = null;

        if (!selectedDay) return;

        $('#itineraryEventDayId').val(selectedDay.id);

        $('#manageEventItineraryModal').removeClass('hidden');

        setTimeout(() => {

            if (!$('.event_note').next('.note-editor').length) {

                $('.event_note').summernote({
                    height: 350,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                        ['view', ['codeview']]
                    ]
                });

            }

        }, 100);
    }

    window.closeManageEventItineraryModal = function () {

        $('#manageEventItineraryModal').addClass('hidden');

        editingEventId = null;

        $('#manageEventForm')[0].reset();

        $('.event_note').summernote('code', '');

        $('.validation-error').text('').addClass('hidden');

        selectedCategory = categories[0];
        selectedSubcategory = categories[0].subcategories[0];

        renderCategories();
        renderSubcategories();
        renderSections();
    };

    const categories = [
        {
            id: 1,
            name: 'Activity',
            slug: 'activity',
            icon: 'fa-solid fa-calendar-days',

            subcategories: [
                {
                    id: 11,
                    name: 'Activity',
                    slug: 'activity',
                    icon: 'fa-solid fa-calendar-days'
                },
                {
                    id: 12,
                    name: 'Food/Drink',
                    slug: 'food-drink',
                    icon: 'fa-solid fa-utensils'
                }
            ]
        },

        {
            id: 2,
            name: 'Lodging',
            slug: 'lodging',
            icon: 'fa-solid fa-bed',

            subcategories: [
                {
                    id: 21,
                    name: 'Check-in',
                    slug: 'check-in',
                },
                {
                    id: 22,
                    name: 'Check-out',
                    slug: 'check-out',
                }
            ]
        },

        {
            id: 3,
            name: 'Flight',
            slug: 'flight',
            icon: 'fa-solid fa-plane',

            subcategories: [
                {
                    id: 31,
                    name: 'Departure',
                    slug: 'departure',
                    icon: 'fa-solid fa-plane-departure'
                },
                {
                    id: 32,
                    name: 'Arrival',
                    slug: 'arrival',
                    icon: 'fa-solid fa-plane-arrival'
                }
            ]
        },

        {
            id: 4,
            name: 'Transportation',
            slug: 'transportation',
            icon: 'fa-solid fa-bus',

            subcategories: [
                {
                    id: 41,
                    name: 'Departure',
                    slug: 'departure-transportation',
                },
                {
                    id: 42,
                    name: 'Arrival',
                    slug: 'arrival-transportation',
                }
            ]
        },

        {
            id: 5,
            name: 'Cruise',
            slug: 'cruise',
            icon: 'fa-solid fa-ship',

            subcategories: [
                {
                    id: 51,
                    name: 'Departure',
                    slug: 'departure-cruise',
                },
                {
                    id: 52,
                    name: 'Arrival',
                    slug: 'arrival-cruise',
                }
            ]
        },

        {
            id: 6,
            name: 'Info',
            slug: 'info',
            icon: 'fa-solid fa-circle-info',

            subcategories: [
                {
                    id: 61,
                    name: 'Info',
                    slug: 'info',
                    icon: 'fa-solid fa-circle-info'
                },
                {
                    id: 62,
                    name: 'City Guide',
                    slug: 'city-guide',
                    icon: 'fa-solid fa-book-open'
                }
            ]
        }
    ];

    let editingEventId = null;
    let selectedCategory = categories[0];
    let selectedSubcategory = categories[0].subcategories[0];

    function renderCategories() {

        let html = '';

        categories.forEach(category => {

            const activeClass = selectedCategory.id === category.id ? 'bg-[#6c757d] text-white' : 'bg-white text-[#495057] hover:bg-[#f1f3f5]';

            html += `
                <button type="button" class="category-btn h-[32px] px-3 border border-[#6c757d] text-[14px] flex items-center gap-2 transition-all ${activeClass} cursor-pointer" data-id="${category.id}" >
                    <i class="${category.icon}"></i>
                    <span>${category.name}</span>
                </button>
            `;
        });

        $('#categoryContainer').html(html);
    }

    function renderSubcategories() {

        let html = '';

        selectedCategory.subcategories.forEach(sub => {

            const activeClass = selectedSubcategory.id === sub.id ? 'bg-[#6c757d] text-white' : 'bg-white text-[#495057] hover:bg-[#f1f3f5]';

            html += `
                <button type="button" class="subcategory-btn h-[32px] px-3 border border-[#6c757d] text-[14px] flex items-center gap-2 transition-all ${activeClass} cursor-pointer" data-id="${sub.id}" >
                    <i class="${sub.icon}"></i>
                    <span>${sub.name}</span>
                </button>
            `;
        });

        $('#subcategoryContainer').html(html);
    }

    function renderSections() {

        $('.dynamic-section').addClass('hidden');

        $('.dynamic-section').find('input, select, textarea').prop('disabled', true);


        const key = `${selectedCategory.slug}-${selectedSubcategory.slug}`;

        const activeSection = $(`.${key}-section`);

        activeSection.removeClass('hidden');

        activeSection.find('input, select, textarea').prop('disabled', false);

        $('#eventType').val(selectedCategory.id);

        $('#subcategoryId').val(selectedSubcategory.id);
    }

    function initEventManager() {

        renderCategories();
        renderSubcategories();
        renderSections();
    }

    $(document).on('click', '.category-btn', function () {

        const id = $(this).data('id');

        selectedCategory = categories.find(c => c.id == id);

        selectedSubcategory = selectedCategory.subcategories[0];

        renderCategories();
        renderSubcategories();
        renderSections();
    });

    $(document).on('click', '.subcategory-btn', function () {

        const id = $(this).data('id');

        selectedSubcategory = selectedCategory.subcategories.find(s => s.id == id);

        renderSubcategories();
        renderSections();
    });

    initEventManager();

    $(document).on('click', '.event-card', function () {

        const event = $(this).data('event');

        editingEventId = event.id;

        // ACTIVITY
        if (event.eventType == 1) {

            selectedCategory = categories.find(c => c.id == 1);

            if (event.itineraryEventFormActivitySubcategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 11);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 12);
            }
        }

        // LODGING
        else if (event.eventType == 2) {

            selectedCategory = categories.find(c => c.id == 2);

            if (event.itineraryEventFormLodgingSubcategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 21);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 22);
            }
        }

        // FLIGHT
        else if (event.eventType == 3) {

            selectedCategory = categories.find(c => c.id == 3);

            if (event.itineraryEventFormFlightSubCategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 31);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 32);
            }
        }

        // TRANSPORTATION
        else if (event.eventType == 4) {

            selectedCategory = categories.find(c => c.id == 4);

            if (event.itineraryTransportationFormSubCategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 41);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 42);
            }
        }

        // CRUISE
        else if (event.eventType == 5) {

            selectedCategory = categories.find(c => c.id == 5);

            if (event.itineraryCruiseFormSubCategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 51);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 52);
            }
        }

        // INFO
        else if (event.eventType == 6) {

            selectedCategory = categories.find(c => c.id == 6);

            if (event.itineraryEventFormInfoSubCategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 61);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 62);
            }
        }

        renderCategories();
        renderSubcategories();
        renderSections();

        $('#manageEventItineraryModal').removeClass('hidden');

        setTimeout(() => {

            // ACTIVITY
            $('[name="itineraryActivityFormTitle"]').val(event.itineraryActivityFormTitle);
            $('[name="itineraryActivityFormBookedThrough"]').val(event.itineraryActivityFormBookedThrough);
            $('[name="itineraryActivityFormConfirmation"]').val(event.itineraryActivityFormConfirmation);
            $('[name="itineraryActivityFormProvider"]').val(event.itineraryActivityFormProvider);
            $('[name="itineraryActivityFormTime"]').val(event.itineraryActivityFormTime);
            $('[name="itineraryActivityFormDuration"]').val(event.itineraryActivityFormDuration);
            $('[name="itineraryActivityFormTimezone"]').val(event.itineraryActivityFormTimezone);
            $('[name="itineraryActivityFormAmount"]').val(event.itineraryActivityFormAmount);
            $('[name="itineraryActivityFormCurrency"]').val(event.itineraryActivityFormCurrency);

            // LODGING
            $('[name="itineraryLodgingFormTitle"]').val(event.itineraryLodgingFormTitle);
            $('[name="itineraryLodgingFormBookedThrough"]').val(event.itineraryLodgingFormBookedThrough);
            $('[name="itineraryLodgingFormConfirmation"]').val(event.itineraryLodgingFormConfirmation);
            $('[name="itineraryLodgingFormRoomBedType"]').val(event.itineraryLodgingFormRoomBedType);
            $('[name="itineraryLodgingFormTime"]').val(event.itineraryLodgingFormTime);
            $('[name="itineraryLodgingFormDuration"]').val(event.itineraryLodgingFormDuration);
            $('[name="itineraryLodgingFormTimezone"]').val(event.itineraryLodgingFormTimezone);
            $('[name="itineraryLodgingFormAmount"]').val(event.itineraryLodgingFormAmount);
            $('[name="itineraryLodgingFormAmountCurrency"]').val(event.itineraryLodgingFormAmountCurrency);

            // FLIGHT
            $('[name="itineraryFlightFormTitle"]').val(event.itineraryFlightFormTitle);
            $('[name="itineraryFlightFormBookedThrough"]').val(event.itineraryFlightFormBookedThrough);
            $('[name="itineraryFlightFormConfirmation"]').val(event.itineraryFlightFormConfirmation);
            $('[name="itineraryFlightFormAirline"]').val(event.itineraryFlightFormAirline);
            $('[name="itineraryFlightFormFlightNumber"]').val(event.itineraryFlightFormFlightNumber);
            $('[name="itineraryFlightFormTerminal"]').val(event.itineraryFlightFormTerminal);
            $('[name="itineraryFlightFormGate"]').val(event.itineraryFlightFormGate);
            $('[name="itineraryFlightFormSeatTicketDetails"]').val(event.itineraryFlightFormSeatTicketDetails);
            $('[name="itineraryFlightFormTime"]').val(event.itineraryFlightFormTime);
            $('[name="itineraryFlightFormDuration"]').val(event.itineraryFlightFormDuration);
            $('[name="itineraryFlightFormTimezone"]').val(event.itineraryFlightFormTimezone);
            $('[name="itineraryFlightFormAmount"]').val(event.itineraryFlightFormAmount);
            $('[name="itineraryFlightFormAmountCurrency"]').val(event.itineraryFlightFormAmountCurrency);

            // TRANSPORTATION
            $('[name="itineraryTransportationFormTitle"]').val(event.itineraryTransportationFormTitle);
            $('[name="itineraryTransportationFormBookedThrough"]').val(event.itineraryTransportationFormBookedThrough);
            $('[name="itineraryTransportationFormConfirmation"]').val(event.itineraryTransportationFormConfirmation);
            $('[name="itineraryTransportationFormCarrier"]').val(event.itineraryTransportationFormCarrier);
            $('[name="itineraryTransportationFormTransportationNumber"]').val(event.itineraryTransportationFormTransportationNumber);
            $('[name="itineraryTransportationFormTime"]').val(event.itineraryTransportationFormTime);
            $('[name="itineraryTransportationFormDuration"]').val(event.itineraryTransportationFormDuration);
            $('[name="itineraryTransportationFormTimezone"]').val(event.itineraryTransportationFormTimezone);
            $('[name="itineraryTransportationFormAmount"]').val(event.itineraryTransportationFormAmount);
            $('[name="itineraryTransportationFormAmountCurrency"]').val(event.itineraryTransportationFormAmountCurrency);

            // CRUISE
            $('[name="itineraryCruiseFormTitle"]').val(event.itineraryCruiseFormTitle);
            $('[name="itineraryCruiseFormBookedThrough"]').val(event.itineraryCruiseFormBookedThrough);
            $('[name="itineraryCruiseFormConfirmation"]').val(event.itineraryCruiseFormConfirmation);
            $('[name="itineraryCruiseFormCarrier"]').val(event.itineraryCruiseFormCarrier);
            $('[name="itineraryCruiseFormCabinType"]').val(event.itineraryCruiseFormCabinType);
            $('[name="itineraryCruiseFormCabinNumber"]').val(event.itineraryCruiseFormCabinNumber);
            $('[name="itineraryCruiseFormTime"]').val(event.itineraryCruiseFormTime);
            $('[name="itineraryCruiseFormDuration"]').val(event.itineraryCruiseFormDuration);
            $('[name="itineraryCruiseFormTimezone"]').val(event.itineraryCruiseFormTimezone);
            $('[name="itineraryCruiseFormAmount"]').val(event.itineraryCruiseFormAmount);
            $('[name="itineraryCruiseFormAmountCurrency"]').val(event.itineraryCruiseFormAmountCurrency);

            // INFO
            $('[name="itineraryInfoFormTitle"]').val(event.itineraryInfoFormTitle);
            $('[name="itineraryInfoFormTime"]').val(event.itineraryInfoFormTime);

            // NOTES
            let note = '';

            if (event.eventType == 1) {
                note = event.itineraryActivityFormNote ?? '';
            }

            else if (event.eventType == 2) {
                note = event.itineraryLodgingFormNote ?? '';
            }

            else if (event.eventType == 3) {
                note = event.itineraryFlightFormNote ?? '';
            }

            else if (event.eventType == 4) {
                note = event.itineraryTransportationFormNote ?? '';
            }

            else if (event.eventType == 5) {
                note = event.itineraryCruiseFormNote ?? '';
            }

            else if (event.eventType == 6) {
                note = event.itineraryInfoFormNote ?? '';
            }

            $('.event_note').summernote('code', note);

        }, 100);
    });

    $('#manageEventForm').on('submit', function (e) {

        e.preventDefault();

        const formData = new FormData(this);

        if (editingEventId) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
           url: editingEventId ? `/itinerary/event/${editingEventId}` : '/itinerary/event/store',

            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function (response) {

                if (editingEventId) {

                    const index = selectedDay.events.findIndex(e => e.id == editingEventId);

                    if (index !== -1) {
                        selectedDay.events[index] = response.event;
                    }

                } else {

                    selectedDay.events.push(response.event);
                }

                selectedDay.events.sort((a, b) => {
                    return (a.eventTime || '').localeCompare(b.eventTime || '');
                });

                renderDayEvents(selectedDay);

                closeManageEventItineraryModal();

                $('#manageEventForm')[0].reset();

                $('.event_note').summernote('code', '');

                editingEventId = null;
            },

            error: function (xhr) {

            $('.validation-error').text('').addClass('hidden');

            if (xhr.status === 422) {

                let errors = xhr.responseJSON.errors;

                Object.keys(errors).forEach(function(field) {

                    const input = $(`[name="${field}"]`);

                    input.closest('.relative').find('.validation-error').text(errors[field][0]).removeClass('hidden');
                });
            }
        }
        });
    });
    // end manage event

    // start itinerary attachments
    function renderAttachmentsSection() {

        let attachmentsHtml = '';

        if (itineraryAttachments.length > 0) {

            itineraryAttachments.forEach(file => {

                attachmentsHtml += `
                    <div class="border border-[#ccc] bg-white p-2 flex flex-col gap-3 cursor-pointer attachment-row">

                        <div class="flex justify-between gap-3">

                            <div class="flex flex-col">
                                <a href="/storage/attachments/itineraries/${file.id}.${file.extension}" target="_blank" class="text-base text-[#007bff]">
                                    ${file.name}.${file.extension}
                                </a>
                            </div>

                            ${
                                !VIEW_ONLY
                                ? `
                                    <i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-attachment" data-id="${file.id}"></i>
                                `
                                : ''
                            }

                        </div>

                    </div>
                `;
            });

        } else {

            attachmentsHtml = `
                <div class="text-gray-400 text-center mt-8">

                </div>
            `;
        }

        $('#dayHeader').addClass('hidden');

        $('#dayEventsContainer').html(`
            <div class="flex flex-col gap-5">

                ${
                    !VIEW_ONLY
                    ? `
                        <div>
                            <button id="addItineraryAttachmentBtn" class="space-x-2 bg-[#B6844A] text-white font-semibold py-2 px-7 rounded cursor-pointer border border-transparent hover:bg-white hover:border-[#B6844A] hover:text-[#B6844A] transition-all duration-200 ">
                                <i class="fas fa-paperclip mr-2"></i>
                                Attachments
                            </button>
                        </div>
                    `
                    : ''
                }

                <div id="itineraryAttachmentsList">
                    ${attachmentsHtml}
                </div>

            </div>
        `);

        $('.itinerary-day').removeClass('bg-gray-100');

        selectedDay = null;

        toggleDayActions();
    }

    $(document).on('click', '#attachmentsBtn', function () {

        renderAttachmentsSection();
    });

    $(document).on('click', '#addItineraryAttachmentBtn', function () {

        $('#itineraryAttachmentsInput').trigger('click');
    });

    $(document).on('change', '#itineraryAttachmentsInput', function () {

        const files = this.files;

        if (!files.length) return;

        const formData = new FormData();

        Array.from(files).forEach(file => {
            formData.append('attachments[]', file);
        });

        $.ajax({
            url: `/itinerary/${itineraryData.id}/attachments`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(response) {

                response.attachments.forEach(file => {

                    itineraryAttachments.push(file);
                });

                renderAttachmentsSection();

                $('#itineraryAttachmentsInput').val('');
            },

            error: function(xhr) {

                console.log(xhr.responseText);
            }
        });
    });

    $(document).on('click', '.delete-attachment', function () {

        const id = $(this).data('id');

        $.ajax({
            url: `/itinerary/attachments/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {

                const index = itineraryAttachments.findIndex(a => a.id == id);
                if (index !== -1) {
                    itineraryAttachments.splice(index, 1);
                }

                renderAttachmentsSection();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    });
    // end itinerary attachments

    // start change cover photo
    $('#changeItineraryCoverPhotoBtn').on('click', function () {
        $('#coverPhotoInput').click();
    });

    $('#coverPhotoInput').on('change', function () {

        const file = this.files[0];

        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);

        $.ajax({
            url: `/itinerary/${itineraryData.id}/cover-photo`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                location.reload();
            }
        });
    });
    // end change cover photo

    // start pdf copy link
    window.copyItineraryLink = function (url) {

        navigator.clipboard.writeText(url)
            .then(function () {

                $('#copySuccessOverlay').removeClass('hidden').addClass('flex');

                setTimeout(function () {

                    $('#copySuccessOverlay').addClass('hidden').removeClass('flex');

                }, 1500);

            })
            .catch(function () {

                alert('Failed to copy link');

            });
    };
    // end pdf copy link

});
