let currentMonth, currentYear, eventsData = [];
let checkedOrder = [];
let currentView = 'Month View'; // global value
let dataType = 'events'; // or 'cases'
let eventCaseEditData = [];

$(document).ready(() => {
    ({ month: currentMonth, year: currentYear } = parseMonthYear($('#calendarMonthYearSelected').text()));

    getUsers(()=> {
        getData(checkedOrder, () => {
            generateCalendarDays();
        });
    });

    populateMonthYearDropdown();

    $('input[name="type"]').on('change', updateUserSelectMode());

    $(document).on('click', '.sidebar-day-btn-parent', function() {
        const dateStr = $(this).children('.sidebar-day-btn').data('date');
        const [year, month, day] = dateStr.split('-').map(Number);

        const selected = new Date(year, month - 1, day);
        window.selectedDate = selected;

        currentMonth = selected.getMonth();
        currentYear = selected.getFullYear();

        updateCalendarHeader(currentMonth, currentYear);
        buildMonthlyCalendarDays(currentMonth, currentYear);
        buildDailyView(day, currentMonth, currentYear);
        updateDailyHeader(selected);

        $('#selectedDayWeekMonthOption').text('Day View');
        showView('Daily');

        highlightSelectedSidebarDay();
    });

    $(document).on('click', '#sidebarCalendarMonthYearSelected', function() {
        if ($('#calendarMonthYearDropdown').is(':visible')) {
            $('#calendarMonthYearDropdown').hide();
        } else {
            populateMonthYearDropdown();
            $('#calendarMonthYearDropdown').show();
        }
    });

    $('#monthSelect').add($('#yearSelect')).on('change', function () {
        currentMonth = parseInt($('#monthSelect').val());
        currentYear = parseInt($('#yearSelect').val());

        updateCalendarHeader(currentMonth, currentYear);
        highlightSelectedSidebarDay();
        getData(checkedOrder, () => {
            buildMonthlyCalendarDays(currentMonth, currentYear);
        });

        $('#calendarMonthYearDropdown').hide();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#sidebarCalendarMonthYearSelected, #calendarMonthYearDropdown').length) {
            $('#calendarMonthYearDropdown').hide();
        }
    });

    $(document).on('click', '#calendarDayViewOption', function() {
        $('#selectedDayWeekMonthOption').text('Day View');
        // showView('Daily');
    });

    $(document).on('click', '#calendarWeekViewOption', function() {
        $('#selectedDayWeekMonthOption').text('Week View');
        // showView('Weekly');
    });

    $(document).on('click', '#calendarMonthViewOption', function() {
        $('#selectedDayWeekMonthOption').text('Month View');
        // showView('Monthly');
    });

    $(document).on('click', '#calendarPrevDay', function() {
        if (!window.selectedDate) window.selectedDate = new Date();

        // Move the selected date back one day
        window.selectedDate.setDate(window.selectedDate.getDate() - 1);

        const newMonth = window.selectedDate.getMonth();
        const newYear = window.selectedDate.getFullYear();

        const doRender = () => {
            buildDailyView(
                window.selectedDate.getDate(),
                window.selectedDate.getMonth(),
                window.selectedDate.getFullYear()
            );
            updateDailyHeader(window.selectedDate);
            highlightSelectedSidebarDay();
        };

        if (newMonth !== currentMonth || newYear !== currentYear) {
            currentMonth = newMonth;
            currentYear = newYear;
            updateCalendarHeader(currentMonth, currentYear);
            buildMonthlyCalendarDays(currentMonth, currentYear);

            getData(checkedOrder, () => {
                doRender();
            });
        } else {
            getData(checkedOrder, () => {
                doRender();
            });
        }
    });

    $(document).on('click', '#calendarNextDay', function() {
        if (!window.selectedDate) window.selectedDate = new Date();

        window.selectedDate.setDate(window.selectedDate.getDate() + 1);

        const newMonth = window.selectedDate.getMonth();
        const newYear = window.selectedDate.getFullYear();

        const doRender = () => {
            buildDailyView(
                window.selectedDate.getDate(),
                window.selectedDate.getMonth(),
                window.selectedDate.getFullYear()
            );
            updateDailyHeader(window.selectedDate);
            highlightSelectedSidebarDay();
        };

        if (newMonth !== currentMonth || newYear !== currentYear) {
            currentMonth = newMonth;
            currentYear = newYear;
            updateCalendarHeader(currentMonth, currentYear);
            buildMonthlyCalendarDays(currentMonth, currentYear);

            getData(checkedOrder, () => {
                doRender();
            });
        } else {
            getData(checkedOrder, () => {
                doRender();
            });
        }
    });

    $(document).on('click', '#calendarPrevWeek', function() {
        if (!window.selectedDate) window.selectedDate = new Date();
        if (!window.viewedWeekDate) window.viewedWeekDate = new Date(window.selectedDate.getTime());

        console.log('Current viewedWeekDate111111:', window.viewedWeekDate);
        window.viewedWeekDate.setDate(window.viewedWeekDate.getDate() - 7);
        console.log('Current viewedWeekDate222222:', window.viewedWeekDate);

        const newMonth = window.viewedWeekDate.getMonth();
        const newYear = window.viewedWeekDate.getFullYear();

        const renderWeek = () => {
            buildWeeklyView(
                window.viewedWeekDate.getDate(),
                window.viewedWeekDate.getMonth(),
                window.viewedWeekDate.getFullYear()
            );

            updateWeeklyHeader(window.viewedWeekDate);
        };

        if (newMonth !== currentMonth || newYear !== currentYear) {
            currentMonth = newMonth;
            currentYear = newYear;
            updateCalendarHeader(currentMonth, currentYear);
            buildMonthlyCalendarDays(currentMonth, currentYear);
            $('.sidebar-day-btn-parent').removeClass('selected-day sidebarCalendarCurrentDay');

            highlightSelectedSidebarDay();

            getData(checkedOrder, renderWeek); // ✅ Wait for events
        } else {
            getData(checkedOrder, renderWeek); // ✅ Always wait for events
        }
        // window.selectedDate = new Date(window.viewedWeekDate);
    });

    $(document).on('click', '#calendarNextWeek', function() {
        if (!window.selectedDate) window.selectedDate = new Date();
        if (!window.viewedWeekDate) window.viewedWeekDate = new Date(window.selectedDate.getTime());

        console.log('Current viewedWeekDate111111:', window.viewedWeekDate);
        window.viewedWeekDate.setDate(window.viewedWeekDate.getDate() + 7);
        console.log('Current viewedWeekDate222222:', window.viewedWeekDate);

        const newMonth = window.viewedWeekDate.getMonth();
        const newYear = window.viewedWeekDate.getFullYear();

        const renderWeek = () => {
            buildWeeklyView(
                window.viewedWeekDate.getDate(),
                window.viewedWeekDate.getMonth(),
                window.viewedWeekDate.getFullYear()
            );

            updateWeeklyHeader(window.viewedWeekDate);
        };

        if (newMonth !== currentMonth || newYear !== currentYear) {
            currentMonth = newMonth;
            currentYear = newYear;
            updateCalendarHeader(currentMonth, currentYear);
            buildMonthlyCalendarDays(currentMonth, currentYear);
            $('.sidebar-day-btn-parent').removeClass('selected-day sidebarCalendarCurrentDay');

            highlightSelectedSidebarDay();

            getData(checkedOrder, renderWeek); // ✅ Wait
        } else {
            getData(checkedOrder, renderWeek); // ✅ Wait
        }
        // window.selectedDate = new Date(window.viewedWeekDate);
    });

    $(document).on('click', '#calendarPrevMonth, #sidebarCalendarPrevMonth', function() {
        goToPreviousMonth();
        highlightSelectedSidebarDay();
        getData(checkedOrder, () => {
            buildMonthlyCalendarDays(currentMonth, currentYear);
        });
        // $('.sidebar-day-btn').removeClass('selected-day');
    });

    $(document).on('click', '#calendarNextMonth, #sidebarCalendarNextMonth', function() {
        goToNextMonth();
        highlightSelectedSidebarDay();
        getData(checkedOrder, () => {
            buildMonthlyCalendarDays(currentMonth, currentYear);
        });
        // $('.sidebar-day-btn').removeClass('selected-day');
    });

    $(document).on('click', '#calendarDayViewOption, #calendarWeekViewOption, #calendarMonthViewOption', function() {
        const id = this.id

        const viewMap = {
            'calendarDayViewOption': 'Daily',
            'calendarWeekViewOption': 'Weekly',
            'calendarMonthViewOption': 'Monthly'
        };

        const view = viewMap[id];
        const el = $('#viewOptionsDropdown [popover]')[0];
        el?.hidePopover?.();
        console.log('Current viewedWeekDate33333333:', window.viewedWeekDate);
        showView(view);
    });

    $(document).on('change', 'input[type="checkbox"][data-user-id]', function () {
        const view = $('#selectedDayWeekMonthOption').text().trim();
        const allCheckboxes = $('input[type="checkbox"][data-user-id]');
        const $this = $(this);
        const userId = $this.data('user-id');

        // console.log('View:', view);
        // console.log('User ID (changed):', userId);

        if (view === 'Month View') {
            if (dataType === 'events') {
                // Only one user allowed
                allCheckboxes.prop('checked', false);
                $this.prop('checked', true);
                checkedOrder = [userId];
            } else {
                // Allow multiple for 'cases'
                if ($this.is(':checked')) {
                    if (!checkedOrder.includes(userId)) {
                        checkedOrder.push(userId);
                    }
                } else {
                    checkedOrder = checkedOrder.filter(id => id !== userId);
                }

                // Keep only the currently checked ones
                checkedOrder = checkedOrder.filter(id =>
                    $(`input[type="checkbox"][data-user-id="${id}"]`).is(':checked')
                );

                // Don't allow no user checked
                if (checkedOrder.length === 0) {
                    $this.prop('checked', true);
                    checkedOrder = [userId];
                }
            }

            // console.log('Currently selected user IDs:', checkedOrder);
            getData(checkedOrder, () => {
                buildMonthlyCalendarDays(currentMonth, currentYear);
            });

            return;
        } else {
            if ($this.is(':checked')) {
                if (!checkedOrder.includes(userId)) {
                    checkedOrder.push(userId);
                }

                checkedOrder = checkedOrder.filter(id =>
                    $(`input[type="checkbox"][data-user-id="${id}"]`).is(':checked')
                );


                if (checkedOrder.length > 2 && dataType === 'events') {
                    // Too many checked, remove the first one (oldest)
                    const firstCheckedId = checkedOrder.shift();
                    $(`input[type="checkbox"][data-user-id="${firstCheckedId}"]`).prop('checked', false);
                    // console.log(`Unchecking oldest user ID: ${firstCheckedId}`);
                }
            } else {
                // Checkbox is being unchecked manually
                // checkedOrder = checkedOrder.filter(id => id !== userId);
                // console.log(`User ID manually unchecked: ${userId}`);

                const numStillChecked = allCheckboxes.filter(function () {
                    return this !== $this[0] && $(this).is(':checked');
                }).length;

                if (numStillChecked === 0) {
                    // This was the last checked one — prevent unchecking
                    $this.prop('checked', true);
                    return;
                }

                // Remove from order if it's allowed to uncheck
                checkedOrder = checkedOrder.filter(id => id !== userId);
            }

            checkedOrder = checkedOrder.filter(id =>
                $(`input[type="checkbox"][data-user-id="${id}"]`).is(':checked')
            );
            // console.log('id::', checkedOrder);
        }

        if (view === 'Week View') {
            getData(checkedOrder, () => {
                const dateToUse = window.viewedWeekDate || new Date(); // fallback to today if null

                buildWeeklyView(
                    dateToUse.getDate(),
                    dateToUse.getMonth(),
                    dateToUse.getFullYear()
                );
            });
        } else if (view === 'Day View') {
            getData(checkedOrder, () => {
                if (window.selectedDate) {
                    buildDailyView(
                        window.selectedDate.getDate(),
                        window.selectedDate.getMonth(),
                        window.selectedDate.getFullYear()
                    );
                } else {
                    buildDailyView();
                }

                if(checkedOrder.length === 2) {
                    $('#dailyViewTable').removeClass('max-w-[750px] mx-auto xl:col-span-12').addClass('xl:col-span-6');
                } else {
                    $('#dailyViewTable').addClass('max-w-[750px] mx-auto xl:col-span-12').removeClass('xl:col-span-6');
                }
            });
        }
    });

    $('#openAddEventCaseModal').on('click', function() {
        $('#addEventCaseModal form')[0].reset();
        $('#eventCaseType').removeClass('hidden');
        $('#headerIcon').removeClass('fa-solid fa-pen-to-square').addClass('fa-solid fa-calendar-plus');
        $('.addEventCaseModalTitle').text(`Add Event or Case`);
        $('#addEventCaseModal').removeClass('hidden');
        $('#modalFooterAction').empty().append(`
            <button type="submit" id="submitAddEventCaseBtn" class="self-end bg-[#14548d] text-white font-semibold py-2 px-4 rounded cursor-pointer">
                Add E/C
            </button>
        `);

        getUsersCategoriesAddEditCasesEvents();
    });

    $('#closeAddEventCaseModal').on('click', function() {
        $('#addEventCaseModal').addClass('hidden');
    });

    $('#addEventCaseModal').on('hidden.bs.modal', function () {
        $('#addEventCaseForm')[0].reset();
        $('#userSelect').val([]).trigger('change');
        eventCaseEditData = [];
    });

    // Optional: Close when clicking outside modal content
    $('#addEventCaseModal').on('click', function(e) {
        if ($(e.target).is('#addEventCaseModal')) {
            $(this).addClass('hidden');
        }
    });

    $('input[name="type"]').on('change', function () {
        // $('input, select').removeClass('border-red-500');
        // $('.input-error-text').remove();
        updateUserSelectMode();
    });

    $(document).on('click', '.removeUserSelect', function (e) {
        const $target = $(e.target).closest('.removeUserSelect');
        const userId = $target.attr('data-user-id');
        const $userSelect = $('#userSelect');
        const selectedValues = $userSelect.data('selectedValues');

        if (!userId) {
            console.warn('No userId found on clicked remove icon');
            return;
        }

        $target.parent().remove();

        selectedValues?.delete(userId);

        const options = $userSelect[0].options;
        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            if (option.value === userId) {
                option.classList.remove('option-disabled');
                // option.classList.add('option-reset');
                option.selected = false;
                break;
            }
        }

        // Remove from current value array
        let currentVals = $userSelect.val() || [];
        const newVals = currentVals.filter(val => val !== userId);
        $userSelect.val(newVals);

        // Clear selection to allow re-selecting same item
        setTimeout(() => {
            $userSelect.val(null);
        }, 0);
    });

    $('#closeErrorModal').on('click', function() {
        $('#errorModal').addClass('hidden');
    });

    $('#closeSuccessModal').on('click', function() {
        $('#successModal').addClass('hidden');
    });

    $('#closeDeleteConfirmModal, #cancelDeleteBtn').on('click', function () {
        $('#deleteConfirmModal').addClass('hidden');
    });

    // add event/case steps
    $(document).on('click', '#submitAddEventCaseBtn', function () {
        const type = $('input[name="type"]:checked').val();
        const routes = {
            eventsStore: "events/store",
            casesStore: "cases/store"
        };

        let actionUrl;

        if (type === 'event') {
            actionUrl = routes.eventsStore;
        } else if (type === 'case') {
            actionUrl = routes.casesStore;

            const selectedUserIds = Array.from($('#selectedUsers i[data-user-id]')).map(el => el.dataset.userId);

            $('#userSelect option').each(function () {
                $(this).prop('selected', selectedUserIds.includes(this.value));
            });
        }

        const $form = $('#addEventCaseForm');
        const $button = $('#submitAddEventCaseBtn');

        $('#modalErrorContent').empty();
        $('#errorModal').addClass('hidden');

        $button.prop('disabled', true).text('Saving...');
        $('.input-error-text').remove();
        $('input, select').removeClass('border-red-500');

        $.ajax({
            type: 'POST',
            url: actionUrl,
            data: $form.serialize(),
            dataType: 'json',
            success: function (response) {
                $form[0].reset();
                $('#addEventCaseModal').addClass('hidden');
                $('#modalSuccessContent').html(response.message);
                $('#successModal').removeClass('hidden');
                refreshCalendar();
            },
            error: function (xhr) {
                if (xhr.status) {
                    const errors = xhr.responseJSON.errors;

                    // Loop over errors and display inline
                    $.each(errors, function (field, messages) {
                        let $input = $form.find(`[name="${field}"]`);

                        if ($input.length === 0) {
                            // Handle array-like error field names like user.0, user.1
                            const baseField = field.split('.')[0];
                            $input = $form.find(`[name="${baseField}[]"], [name="${baseField}"]`);
                        }

                        $input.addClass('border-red-500');

                        if ($input.next('.input-error-text').length === 0) {
                            $input.after(`<p class="input-error-text text-red-600 text-sm mt-1">${messages[0]}</p>`);
                        }
                    });
                }
            },
            complete: function () {
                $button.prop('disabled', false).text('Add E/C');
            }
        });
    });

    $(document).on('click', '.refreshCalendar', function() {
        const $icon = $('#refreshCalendarIcon');

        $icon.addClass('fa-spin');

        setTimeout(() => {
            $icon.removeClass('fa-spin');
        }, 2000);

        refreshCalendar();
    });

    $('#calendarEventTypeFilter').on('click', function() {
        dataType = 'events';
        $('#calendarEventTypeFilter, #calendarCases').removeClass('activeEventsCases');
        $(this).addClass('activeEventsCases');

        $('#lawyersHeader').html(`
            <label>Lawyers</label>
        `);

        const allCheckBoxes = $('input[type="checkbox"][data-user-id]');
        checkedOrder = [];

        allCheckBoxes.each(function (index) {
            const $checkbox = $(this);
            if(index === 0) {
                $checkbox.prop('checked', true);
                checkedOrder.push($checkbox.data('user-id'));
            } else {
                $checkbox.prop('checked', false);
            }
        });

        refreshCalendar();
    });

    $('#calendarCases').on('click', function() {
        dataType = 'cases';
        $('#calendarEventTypeFilter, #calendarCases').removeClass('activeEventsCases');
        $(this).addClass('activeEventsCases');

        $('#lawyersHeader').html(`
            <label>Lawyers</label>
            <label id="selectAllUsersCheckbox" class="ml-2 text-sm font-normal cursor-pointer">
                <input type="checkbox" id="selectAllLawyers" class="mr-1">
                <span>All</span>
            </label>
        `);

        refreshCalendar();
    });

    $(document).on('change', '#selectAllLawyers', function () {
        const isChecked = $(this).is(':checked');
        const allCheckboxes = $('input[type="checkbox"][data-user-id]');
        const view = $('#selectedDayWeekMonthOption').text().trim();
        currentView = view;
        const previousCheckedOrder = [...checkedOrder];
        checkedOrder = [];

        if (isChecked) {
            allCheckboxes.each(function () {
                const $checkbox = $(this);
                const userId = $checkbox.data('user-id');

                if (!$checkbox.is(':checked')) {
                    $checkbox.prop('checked', true);
                }

                checkedOrder.push(userId);
            });
        } else {
            const firstChecked = previousCheckedOrder.length > 0 ? previousCheckedOrder[0] : null;
            checkedOrder = firstChecked ? [firstChecked] : [];


            allCheckboxes.each(function () {
                const $checkbox = $(this);
                const userId = $checkbox.data('user-id');
                const shouldCheck = userId === firstChecked;

                $checkbox.prop('checked', shouldCheck);
            });
        }

        getData(checkedOrder, () => {
            if (view === 'Month View') {
                buildMonthlyCalendarDays(currentMonth, currentYear);
            } else if (view === 'Week View') {
                const dateToUse = window.viewedWeekDate || window.selectedDate || new Date();
                buildWeeklyView(dateToUse.getDate(), dateToUse.getMonth(), dateToUse.getFullYear());
            } else if (view === 'Day View') {
                const selectedDate = window.selectedDate || new Date();
                buildDailyView(selectedDate.getDate(), selectedDate.getMonth(), selectedDate.getFullYear());

                const $table = $('#dailyViewTable');
                if (checkedOrder.length === 2) {
                    $table.removeClass('max-w-[750px] mx-auto xl:col-span-12').addClass('xl:col-span-6');
                } else {
                    $table.addClass('max-w-[750px] mx-auto xl:col-span-12').removeClass('xl:col-span-6');
                }
            }
        });
    });

    $(document).on('dblclick', '.eventCase', function () {
        const eventId = $(this).data('id');
        const eventType = $(this).data('type');
        console.log('Clicked event/case ID:', eventId, 'Type:', eventType);
    });

    $(document).on('click', '.iconPencil', function (event) {
        event.stopPropagation();  // Prevents bubbling to .eventCase

        const eventCaseId = $(this).data('id');
        const eventType = $(this).data('type');
        const arrowPlane = '<i class="fa-solid fa-paper-plane"></i>';

        $('#eventCaseType').addClass('hidden');
        $('#headerIcon').removeClass('fa-solid fa-calendar-plus').addClass('fa-solid fa-pen-to-square');
        $('.input-error-text').remove();
        $('input, select').removeClass('border-red-500');

        // getUsersCategoriesAddEditCasesEvents(function () {
        if (eventType === 'event') {
            $('input[name="type"][value="event"]').prop('checked', true);
        } else if (eventType === 'case') {
            $('input[name="type"][value="case"]').prop('checked', true);
        }
        updateUserSelectMode();
        // });

        const type = eventType === 'event' ? 'Event' : 'Case';
        $('.addEventCaseModalTitle').text(`Edit ${type}`);
        $('#modalFooterAction').empty().append(`
            <div class="flex flex-wrap justify-end gap-2">
                <div class="border border-gray-500 text-gray-700 hover:bg-gray-500 hover:text-white font-semibold py-1 px-2 rounded transition cursor-pointer" id="clearEditEventCaseBtn" data-id="${eventCaseId}">
                    <i class="fa-solid fa-ban"></i>
                    <span class="ml-1">
                        Clear
                    </span>
                </div>
                <div class="border border-red-600 text-red-600 hover:bg-red-600 hover:text-white font-semibold py-1 px-2 rounded transition justify-self-end cursor-pointer" id="deleteEditEventCaseBtn" data-id="${eventCaseId}">
                    <i class="fa-solid fa-trash"></i>
                    <span class="ml-1">
                        Delete
                    </span>
                </div>
                <div class="border border-[#14548d] text-[#14548d] hover:bg-[#14548d] hover:text-white font-semibold py-1 px-2 rounded transition justify-self-end cursor-pointer" id="submitEditEventCaseBtn" data-id="${eventCaseId}">
                    ${arrowPlane}
                    <span class="ml-1">
                        Save
                    </span>
                </div>
            </div>
        `);

        // getData(null, function (err, data) {
            //     if (err) {
            //         $('#modalErrorContent').text(err.error);
            //         $('#errorModal').removeClass('hidden');
            //         return;
            //     }

            //     const dateFrom = `${formatDateToMMDDYYYY(eventCaseEditData.date_from)} ${eventType === 'event' ? formatTimeHHMM(eventCaseEditData.date_from) : ''}`;
            //     const dateTo = `${formatDateToMMDDYYYY(eventCaseEditData.date_to)} ${eventType === 'event' ? formatTimeHHMM(eventCaseEditData.date_to) : ''}`;

            //     $('input[name="title"]').val(eventCaseEditData.title);
            //     $('input[name="fromDate"]').val(dateFrom);
            //     $('input[name="toDate"]').val(dateTo);
            //     $('select[name="category"]').val(eventCaseEditData.categoryId);

            //     if(eventType === 'case') {
            //         const userIds = (eventCaseEditData.users || []).map(u => u.id.toString());
            //         $('#userSelect').val(userIds).trigger('change');
            //     }
            //     // console.log($('#addEventCaseForm').serialize());
            //     $('#addEventCaseModal').removeClass('hidden');
        // }, eventCaseId);

        getData(null, function (err, data) {
            if (err) {
                $('#modalErrorContent').text(err.error || 'Error loading data');
                $('#errorModal').removeClass('hidden');
                return;
            }

            console.log("heloo",data);
            const eventCaseEditData = data.eventCase; console.log(eventCaseEditData);
            const users = data.users || [];
            const categories = data.categories || [];

            // ✅ Populate categories dropdown
            const $categorySelect = $('#categorySelect');
            $categorySelect.empty().append('<option value="-1">Select a category</option>');
            categories.forEach(category => {
                $categorySelect.append(`<option value="${category.id}">${category.categoryName}</option>`);
            });

            // ✅ Populate users dropdown
            const $userSelect = $('#userSelect');
            $userSelect.empty();
            users.forEach(user => {
                $userSelect.append(`<option value="${user.id}">${user.name}</option>`);
            });

            // ✅ Select correct type
            if (eventType === 'event') {
                $('input[name="type"][value="event"]').prop('checked', true);
            } else {
                $('input[name="type"][value="case"]').prop('checked', true);
            }

            updateUserSelectMode();

            // ✅ Fill form with existing data
            const dateFrom = `${formatDateToMMDDYYYY(eventCaseEditData.date_from)} ${eventType === 'event' ? formatTimeHHMM(eventCaseEditData.date_from) : ''}`;
            const dateTo = `${formatDateToMMDDYYYY(eventCaseEditData.date_to)} ${eventType === 'event' ? formatTimeHHMM(eventCaseEditData.date_to) : ''}`;

            $('input[name="title"]').val(eventCaseEditData.title);
            $('input[name="fromDate"]').val(dateFrom);
            $('input[name="toDate"]').val(dateTo);
            $categorySelect.val(eventCaseEditData.categoryId);

            if (eventType === 'case') {
                const userIds = (eventCaseEditData.users || []).map(u => u.id.toString());
                $userSelect.val(userIds).trigger('change');
            }

            $('#addEventCaseModal').removeClass('hidden');
        }, eventCaseId);

        console.log('Clicked case ID:', eventCaseId);
    });

    $(document).on('click', '#clearEditEventCaseBtn', function() {
        const eventType = $('input[name="type"]:checked').val();
        console.log('hiiiiii', eventType);
        $('#addEventCaseModal form')[0].reset();
        if (eventType === 'case') {
            $('input[name="type"][value="case"]').prop('checked', true);
            updateUserSelectMode();
        }
        console.log($('#addEventCaseForm').serialize());
    });

    $(document).on('click', '#deleteEditEventCaseBtn', function() {
        const type = $('input[name="type"]:checked').val();
        const id = $(this).data('id');

        const routes = {
            eventDelete: (id) => `eventDelete/${id}`,
            caseDelete: (id) => `caseDelete/${id}`
        };

        let actionUrl = '';
        let method = 'PUT';

        if(type === 'event') {
            actionUrl = routes.eventDelete(id);
        } else if (type === 'case') {
            actionUrl = routes.caseDelete(id);
        }

        $('#confirmDeleteBtn')
        .data('action-url', actionUrl)
        .data('method', method);

        $('#addEventCaseModal').addClass('hidden');
        $('#deleteConfirmModal').removeClass('hidden');
    });

    $(document).on('click', '#confirmDeleteBtn', function() {
        const actionUrl = $(this).data('action-url');
        const method = $(this).data('method');

        deleteEditEventCase(actionUrl, method);
    });

    $(document).on('click', '#submitEditEventCaseBtn', function() {
        const type = $('input[name="type"]:checked').val();
        const id = $(this).data('id');

        const routes = {
            eventUpdate: (id) => `eventUpdate/${id}`,
            caseUpdate: (id) => `caseUpdate/${id}`
        };

        let actionUrl = '';
        let method = 'PUT';

        if(type === 'event') {
            actionUrl = routes.eventUpdate(id);
        } else if (type === 'case') {
            const selectedUserIds = Array.from($('#selectedUsers i[data-user-id')).map(el => el.dataset.userId);
            $('#userSelect option').each(function () {
                $(this).prop('selected', selectedUserIds.includes(this.value));
            });
            actionUrl = routes.caseUpdate(id);
        }

        submitEditEventCase(actionUrl, method);
    });
});

function toggleHeaderForView(view) {
    if(view === 'Monthly') {
        $('#monthToggleSection').removeClass('hidden');
        $('#weekToggleSection').addClass('hidden');
        $('#dayToggleSection').addClass('hidden');
        if(dataType === 'events') {
            const checkboxes = $('input[type="checkbox"][data-user-id]');
            checkboxes.prop('checked', false);
            checkboxes.first().prop('checked', true);
        }
    } else if(view === 'Daily') {
        $('#monthToggleSection').addClass('hidden');
        $('#weekToggleSection').addClass('hidden');
        $('#dayToggleSection').removeClass('hidden');

        if(window.selectedDate) { // storage day
            updateDailyHeader(window.selectedDate);
        } else {
            updateDailyHeader(new Date());
        }
    } else if(view === 'Weekly') {
        $('#monthToggleSection').addClass('hidden');
        $('#dayToggleSection').addClass('hidden');
        $('#weekToggleSection').removeClass('hidden');

        if(window.selectedDate) {
            updateWeeklyHeader(window.selectedDate);
        } else {
            const today = new Date();
            window.selectedDate = today;
            updateWeeklyHeader(today);
        }
    }
}

function updateDailyHeader(date) {
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const dayName = dayNames[date.getDay()];
    const day = date.getDate();
    const month = date.getMonth();
    const year = date.getFullYear();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    $('#calendarDaySelected').html(`${monthNames[month]} ${day}, ${year}`);
    $('#currentDateData')
        .attr('data-month', String(month + 1).padStart(2, '0'))
        .attr('data-year', year);
}

function updateWeeklyHeader(date) {
    const startOfWeek = new Date(date);

    const month = startOfWeek.getMonth();
    const year = startOfWeek.getFullYear();

    startOfWeek.setDate(date.getDate() - date.getDay()); // Sunday

    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6); // Saturday

    const options = { month: 'short', day: 'numeric' };
    const startStr = startOfWeek.toLocaleDateString(undefined, options);
    const endStr = endOfWeek.toLocaleDateString(undefined, options);

    const sameMonth = startOfWeek.getMonth() === endOfWeek.getMonth();
    const sameYear = startOfWeek.getFullYear() === endOfWeek.getFullYear();

    let label;
    if (sameYear) {
        if (sameMonth) {
            label = `${startStr} - ${endStr}, ${startOfWeek.getFullYear()}`;
        } else {
            const endStrFull = endOfWeek.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
            label = `${startStr} - ${endStrFull}, ${startOfWeek.getFullYear()}`;
        }
    } else {
        const startStrFull = startOfWeek.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
        const endStrFull = endOfWeek.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
        label = `${startStrFull} - ${endStrFull}`;
    }

    $('#calendarWeekSelected').text(label);
     $('#currentDateData')
        .attr('data-month', String(month + 1).padStart(2, '0'))
        .attr('data-year', year);
}

function showView(view) {
    $('#viewMonthly, #viewWeekly, #viewDaily').addClass('hidden');
    $('#view' + view).removeClass('hidden');

    // if(dataType === 'events') {
        toggleHeaderForView(view);
    // }

    if (view === 'Daily') {
        if (window.selectedDate) {
            const newMonth = window.selectedDate.getMonth();
            const newYear = window.selectedDate.getFullYear();

            if (newMonth !== currentMonth || newYear !== currentYear) {
                currentMonth = newMonth;
                currentYear = newYear;
                updateCalendarHeader(currentMonth, currentYear);
                buildMonthlyCalendarDays(currentMonth, currentYear);
            }

            buildDailyView(
                window.selectedDate.getDate(),
                window.selectedDate.getMonth(),
                window.selectedDate.getFullYear()
            );
        } else {
            buildDailyView();
        }
        highlightSelectedSidebarDay();

    } else if (view === 'Weekly') {
        window.viewedWeekDate = new Date(window.selectedDate.getTime());
        getData(checkedOrder, () => {
            if (window.selectedDate) {
                const newMonth = window.selectedDate.getMonth();
                const newYear = window.selectedDate.getFullYear();

                if (newMonth !== currentMonth || newYear !== currentYear) {
                    currentMonth = newMonth;
                    currentYear = newYear;
                    updateCalendarHeader(currentMonth, currentYear);
                    buildMonthlyCalendarDays(currentMonth, currentYear);
                }

                buildWeeklyView(
                    window.selectedDate.getDate(),
                    window.selectedDate.getMonth(),
                    window.selectedDate.getFullYear()
                );
            } else {
                buildWeeklyView();
            }
        }); // Preload data for week view


    } else if (view === 'Monthly') {
        // console.log(window.selectedDate);
        // console.log('1: ', checkedOrder);
        // ✅ Keep only last selected user in Month View
        if (checkedOrder.length >= 1 && dataType === 'events') {
            const lastUserId = checkedOrder[checkedOrder.length - 1];
            checkedOrder = [lastUserId];

            // Uncheck all checkboxes, check only the last selected one
            $('input[type="checkbox"][data-user-id]').each(function () {
                const isLast = $(this).data('user-id') === lastUserId;
                $(this).prop('checked', isLast);
            });
        }
        // console.log('2: ', checkedOrder);
        // ✅ Always rebuild month view with current checkedOrder
        getData(checkedOrder, () => {
            buildMonthlyCalendarDays(currentMonth, currentYear);
        });
    }
}

function buildDailyView(inputDay = null, inputMonth = null, inputYear = null) {
    const today = new Date();

    const year = inputYear !== null ? inputYear : today.getFullYear();
    const month = inputMonth !== null ? inputMonth : today.getMonth();
    const day = inputDay !== null ? inputDay : today.getDate();

    const selectedDate = new Date(year, month, day);
    const isoDate = toLocalDateString(selectedDate); // YYYY-MM-DD
    const dayName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][selectedDate.getDay()];

    // const allUsers = getEventsForDate(); // Get full list of users with events
    const allUsers = eventsData;

    // DOM references
    const dailyHeader = $('#dailyHeader');
    const dailyBody = $('#dailyBody tr td');
    const headerHidden = $('#dailyHeaderHidden');
    const bodyHidden = $('#dailyBodyHidden tr td');

    // Reset UI
    dailyHeader.empty();
    dailyBody.empty();
    headerHidden.empty();
    bodyHidden.empty();

    $('#dailyViewTable').addClass('hidden');
    $('#dailyViewTableHidden').addClass('hidden');
    $('#dailyBox3')?.removeClass('xl:col-span-6').addClass('xl:col-span-12');
    $('#dailyBox4')?.addClass('hidden');

    if (allUsers.length === 0) {
        $('#dailyViewTable').removeClass('hidden');
        dailyHeader.html(`${dayName} ${day} <div class="text-gray-400 italic">No users</div>`);
        dailyBody.append(`
            <div class="text-gray-400 italic dailyEventInfo">No events</div>
        `);
        return;
    }

    // === If only 1 user ===
    if (allUsers.length === 1) {
        const user = allUsers[0];
        const eventsToday = user.events.filter(event => event.date === isoDate);

        $('#dailyViewTable').removeClass('hidden xl:col-span-6').addClass('max-w-[750px] xl:col-span-12 mx-auto');
        $('#dailyViewTableHidden').addClass('hidden');
        $('#dailyBox3').removeClass('xl:col-span-6').addClass('w-[750px] xl:col-span-12 mx-auto');

        dailyHeader.html(`${dayName} ${day} <div>${user.user}</div>`);

        if (eventsToday.length === 0) {
            dailyBody.append(`
                <div class="text-gray-400 italic dailyEventInfo">No events</div>
            `);
        } else {
            eventsToday.forEach(event => {
                console.log('event::', event);
                const timeRange = event.from && event.to ? `<span>~${event.from} - ${event.to}</span>` : '';
                const iconPencil = event.editable ? `
                    <div class="iconPencil absolute right-1.5 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" data-id="${event.id}" data-type="${event.type}">
                        <i class="fa-solid fa-pen-to-square shadow-lg" style="color: #eaeef2;"></i>
                    </div>
                ` : '';
                dailyBody.append(`
                    <div class="relative group text-gray-900 font-semibold dailyEventInfo eventCase" style="background-color: ${event.color}" draggable="true" data-id="${event.id}" data-type="${event.type}">
                            <span>${event.title}</span>
                            ${timeRange}
                            ${iconPencil}
                    </div>
                `);
            });
        }

        return;
    }

    // === If 2 users (always show both) ===
    if (allUsers.length === 2) { // was >= 2
        const user1 = allUsers[0];
        const user2 = allUsers[1];

        const eventsUser1 = user1.events.filter(event => event.date === isoDate);
        const eventsUser2 = user2.events.filter(event => event.date === isoDate);

        // $('#dailyViewTable').removeClass('hidden');
        $('#dailyViewTable').removeClass('hidden max-w-[750px] mx-auto xl:col-span-12').addClass('xl:col-span-6');
        $('#dailyViewTableHidden').removeClass('hidden');
        $('#dailyBox3').addClass('xl:col-span-6').removeClass('w-[750px] xl:col-span-12 mx-auto');
        $('#dailyBox4')?.removeClass('hidden');

        // Fill user 1
        dailyHeader.html(`${dayName} ${day} <div>${user1.user}</div>`);
        if (eventsUser1.length === 0) {
            dailyBody.append(`
               <div class="dailyEventInfo text-gray-400 italic">No events</div>
            `);
        } else {
            eventsUser1.forEach(event => {
                const timeRange = event.from && event.to ? `<span>~${event.from} - ${event.to}</span>` : '';
                const iconPencil = event.editable ? `
                    <div class="iconPencil absolute right-1.5 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" data-id="${event.id}" data-type="${event.type}">
                        <i class="fa-solid fa-pen-to-square shadow-lg" style="color: #eaeef2;"></i>
                    </div>
                ` : '';
                dailyBody.append(`
                    <div class="relative group text-gray-900 font-semibold dailyEventInfo eventCase" style="background-color: ${event.color}" draggable="true" data-id="${event.id}" data-type="${event.type}">
                        <span>${event.title}</span>
                        ${timeRange}
                        ${iconPencil}
                    </div>
                `);
            });
        }

        // Fill user 2
        headerHidden.html(`${dayName} ${day} <div>${user2.user}</div>`);
        if (eventsUser2.length === 0) {
            bodyHidden.append(`
                <div class="text-gray-400 italic dailyEventInfo">No events</div>
            `);
        } else {
            eventsUser2.forEach(event => {
                const timeRange = event.from && event.to ? `<span>~${event.from} - ${event.to}</span>` : '';
                const iconPencil = event.editable ? `
                    <div class="iconPencil absolute right-1.5 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" data-id="${event.id}" data-type="${event.type}">
                        <i class="fa-solid fa-pen-to-square shadow-lg" style="color: #eaeef2;"></i>
                    </div>
                ` : '';
                bodyHidden.append(`
                    <div class="relative group text-gray-900 font-semibold dailyEventInfo eventCase" style="background-color: ${event.color}" draggable="true" data-id="${event.id}" data-type="${event.type}">
                        <span>${event.title}</span>
                        ${timeRange}
                        ${iconPencil}
                    </div>
                `);
            });
        }
    }

    if (allUsers.length >= 3) {
        // Flat list of all events on the selected date from all users
        const eventsToday = allUsers
            .flatMap(user => user.events)
            .filter(event => event.date === isoDate);

        eventsToday.sort((a, b) => {
            const dateA = new Date(`${a.date}T${a.from || "00:00"}:00`);
            const dateB = new Date(`${b.date}T${b.from || "00:00"}:00`);

            if (dateA.getTime() === dateB.getTime()) {
                return (a.title || "").localeCompare(b.title || "", undefined, { sensitivity: 'base' });
            }

            return dateA - dateB;
        });

        // UI Setup
        $('#dailyViewTable').removeClass('hidden xl:col-span-6').addClass('max-w-[750px] xl:col-span-12 mx-auto');
        $('#dailyViewTableHidden').addClass('hidden');
        $('#dailyBox3').removeClass('xl:col-span-6').addClass('w-[750px] xl:col-span-12 mx-auto');

        // Only the date in the header
        dailyHeader.html(`
            <span>${dayName} ${day}</span>
            <span>All Users</span>
        `);

        // Clear any leftover content
        dailyBody.empty();

        if (eventsToday.length === 0) {
            dailyBody.append(`
                <div class="text-gray-400 italic dailyEventInfo">No events</div>
            `);
        } else {
            eventsToday.forEach(event => {
                if (event.isDuplicate) {
                    return;
                }

                const timeRange = event.from && event.to ? `<span>~${event.from} - ${event.to}</span>` : '';
                const iconPencil = event.editable ? `
                    <div class="iconPencil absolute right-1.5 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" data-id="${event.id}" data-type="${event.type}">
                        <i class="fa-solid fa-pen-to-square shadow-lg" style="color: #eaeef2;"></i>
                    </div>
                ` : '';
                dailyBody.append(`
                    <div class="relative group text-gray-900 font-semibold dailyEventInfo eventCase" style="background-color: ${event.color}" draggable="true" data-id="${event.id}" data-type="${event.type}">
                        <span>${event.title}</span>
                        ${timeRange}
                        ${iconPencil}
                    </div>
                `);
            });
        }
    }


    // console.log("Daily view rendered for:", selectedDate.toDateString());
}

function buildWeeklyView(inputDay = null, inputMonth = null, inputYear = null) {
    const today = new Date();

    const year = inputYear !== null ? inputYear : today.getFullYear();
    const month = inputMonth !== null ? inputMonth : today.getMonth();
    const day = inputDay !== null ? inputDay : today.getDate();

    const selectedDate = new Date(year, month, day);
    const startOfWeek = new Date(selectedDate);
    startOfWeek.setDate(selectedDate.getDate() - selectedDate.getDay()); // Start from Sunday

    const daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    // Clear all cells before rendering new data
    daysOfWeek.forEach(dayClass => {
        $(`#weeklyViewTable .${dayClass}`).empty();
        $(`#weeklyViewTableHidden .${dayClass}`).empty();
    });

    $('#userHeader').empty();
    $('#userHeaderHidden').empty();

    for (let i = 0; i < 7; i++) {
        const currentDate = new Date(startOfWeek);
        currentDate.setDate(startOfWeek.getDate() + i);

        const dayClass = daysOfWeek[currentDate.getDay()];
        const dayNameCapitalized = dayClass.charAt(0).toUpperCase() + dayClass.slice(1);
        const dayNumber = currentDate.getDate();

        $(`#weeklyViewTable .th-${dayClass}`).text(`${dayNameCapitalized} ${dayNumber}`);
        $(`#weeklyViewTableHidden .th-${dayClass}`).text(`${dayNameCapitalized} ${dayNumber}`);
    }


    // const allEventsData = getEventsForDate();
    const allEventsData = eventsData;

    allEventsData.sort((a, b) => {
        const dateA = new Date(`${a.date}T${a.from || "00:00"}:00`);
        const dateB = new Date(`${b.date}T${b.from || "00:00"}:00`);

        if (dateA.getTime() === dateB.getTime()) {
            return (a.title || "").localeCompare(b.title || "", undefined, { sensitivity: 'base' });
        }

        return dateA - dateB;
    });

    if (allEventsData.length >= 3 && dataType === 'cases') {
        const startOfWeek = new Date(year, month, day);
        startOfWeek.setDate(day - new Date(year, month, day).getDay());

        const daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        // Clear all cells before rendering
        daysOfWeek.forEach(dayClass => {
            $(`#weeklyViewTable .${dayClass}`).empty();
        });

        $('#userHeader').html(`<div class="border border-[#fff] p-2">All Users</div>`);
        $('#weeklyViewTableHidden').addClass('hidden');
        $('#viewWeekly').removeClass('2xl:grid-rows-[40%_60%]').addClass('grid-rows-1');
        $('#weeklyViewTable > tbody').removeClass('2xl:h-[85%]').addClass('2xl:h-[94%]');

        // Build flat event list with user info and filter duplicates
        const flatWeeklyEvents = allEventsData.flatMap(user =>
            (user.events || []).map(event => ({
                ...event,
                user: user.user || "Unknown"
            }))
        ).filter(event => event.date && !event.isDuplicate);

        // Sort events globally by date and from-time
        flatWeeklyEvents.sort((a, b) => {
            const dateA = new Date(`${a.date}T${a.from || '00:00'}`);
            const dateB = new Date(`${b.date}T${b.from || '00:00'}`);
            if (dateA.getTime() === dateB.getTime()) {
                return (a.title || '').localeCompare(b.title || '', undefined, { sensitivity: 'base' });
            }
            return dateA - dateB;
        });

        // Render headers with day names & numbers
        for (let i = 0; i < 7; i++) {
            const currentDate = new Date(startOfWeek);
            currentDate.setDate(startOfWeek.getDate() + i);

            const dayClass = daysOfWeek[currentDate.getDay()];
            const dayNameCapitalized = dayClass.charAt(0).toUpperCase() + dayClass.slice(1);
            const dayNumber = currentDate.getDate();

            $(`#weeklyViewTable .th-${dayClass}`).text(`${dayNameCapitalized} ${dayNumber}`);

            // Filter events for this date
            const eventsForDay = flatWeeklyEvents.filter(e => e.date === toLocalDateString(currentDate));

            const cell = $(`#weeklyViewTable .${dayClass}`);

            if (eventsForDay.length) {
                eventsForDay.forEach(event => {
                    const iconPencil = event.editable ? `
                        <div class="iconPencil absolute right-1 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" data-id="${event.id}" data-type="${event.type}">
                            <i class="fa-solid fa-pen-to-square shadow-lg" style="color: #eaeef2;"></i>
                        </div>
                    ` : '';
                    const eventDiv = $(`
                        <div class="relative group text-gray-900 font-semibold weeklyEventInfo my-1 p-1 rounded cursor-pointer eventCase" style="background-color: ${event.color}" draggable="true" data-id="${event.id}" data-type="${event.type}">
                            <span>${event.title}</span>
                            ${iconPencil}
                        </div>
                    `);
                    cell.append(eventDiv);
                });
            } else {
                cell.append('<div class="text-gray-400 italic weeklyEventInfo">No events</div>');
            }
        }

        // Exit early to avoid running existing logic
        return;
    }

    const showTwoUserLayout = allEventsData.length === 2;

    const userRow1 = allEventsData[0] || null;
    const userRow2 = showTwoUserLayout ? allEventsData[1] : null;

    if (userRow1) {
        $('#userHeader').html(`<div class="border border-[#fff] p-2">${userRow1.user}</div>`);
    } else {
        $('#userHeader').html(`<div class="border border-[#fff] p-2">No user</div>`);
    }

    if (showTwoUserLayout) {
        $('#userHeaderHidden').html(`<div class="border border-[#fff] p-2">${userRow2.user}</div>`);
        $('#weeklyViewTableHidden').removeClass('hidden');
        $('#viewWeekly').removeClass('grid-rows-1').addClass('2xl:grid-rows-[40%_60%]');
        $('#weeklyViewTable > tbody').removeClass('2xl:h-[94%]').addClass('2xl:h-[85%]');
    } else {
        $('#weeklyViewTableHidden').addClass('hidden');
        $('#viewWeekly').removeClass('2xl:grid-rows-[40%_60%]').addClass('grid-rows-1');
        $('#weeklyViewTable > tbody').removeClass('2xl:h-[85%]').addClass('2xl:h-[94%]');
    }

    for (let i = 0; i < 7; i++) {
        const currentDate = new Date(startOfWeek);
        currentDate.setDate(startOfWeek.getDate() + i);

        const isoDate = toLocalDateString(currentDate); // Format YYYY-MM-DD
        const dayClass = daysOfWeek[currentDate.getDay()];

        const cellMain = $(`#weeklyViewTable .${dayClass}`);
        const cellHidden = $(`#weeklyViewTableHidden .${dayClass}`);

        // User 1 events or no events placeholder
        if (userRow1) {
            const eventsForDate = userRow1.events.filter(e => e.date === isoDate);
            if (eventsForDate.length) {
                eventsForDate.forEach(event => {
                    console.log(event.editable);
                    const iconPencil =  event.editable ? `
                        <div class="iconPencil absolute right-1 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" data-id="${event.id}" data-type="${event.type}">
                            <i class="fa-solid fa-pen-to-square shadow-lg" style="color: #eaeef2;"></i>
                        </div>
                    ` : '';
                    const eventDiv = $(`
                        <div class="relative group text-gray-900 font-semibold weeklyEventInfo my-1 p-1 rounded cursor-pointer eventCase" style="background-color: ${event.color}" draggable="true" data-id="${event.id}" data-type="${event.type}">
                            <span>${event.title}</span>
                            ${iconPencil}
                        </div>
                    `);
                    cellMain.append(eventDiv);
                });
            } else {
                cellMain.append('<div class="text-gray-400 italic weeklyEventInfo">No events</div>');
            }
        } else {
            cellMain.append('<div class="text-gray-400 italic weeklyEventInfo">No user</div>');
        }

        // User 2 events or no events placeholder
        // if (userRow2) {
        if (showTwoUserLayout && userRow2) {
            const eventsForDate = userRow2.events.filter(e => e.date === isoDate);
            if (eventsForDate.length) {
                eventsForDate.forEach(event => {
                    const iconPencil = event.editable ? `
                        <div class="iconPencil absolute right-1 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" data-id="${event.id}" data-type="${event.type}">
                            <i class="fa-solid fa-pen-to-square shadow-lg" style="color: #eaeef2;"></i>
                        </div>
                    ` : '';
                    const eventDiv = $(`
                        <div class="relative group text-gray-900 font-semibold weeklyEventInfo my-1 p-1 rounded cursor-pointer eventCase" style="background-color: ${event.color}" draggable="true" data-id="${event.id}" data-type="${event.type}">
                            <span>${event.title}</span>
                            ${iconPencil}
                        </div>
                    `);
                    cellHidden.append(eventDiv);
                });
            } else {
                cellHidden.append('<div class="text-gray-400 italic weeklyEventInfo">No events</div>');
            }
        } else {
            cellHidden.append('<div class="text-gray-400 italic weeklyEventInfo">No user</div>');
        }
    }

    // console.log("Weekly view rendered for:", startOfWeek.toDateString());
}

function getEventsForDate(eventsData) {
    const seenCaseIds = new Set();

    const transformedData = Object.values((eventsData || []).reduce((acc, item) => {
        const isCase = !!item.case;
        const user_id = item.user_id;
        const user = item.user;
        const data = isCase ? item.case : item;

        const id = isCase ? data.id : (item.id || null);
        const title = isCase ? data.caseTitle : item.title;
        const dateFrom = isCase ? data.dateFrom : item.date_from;
        const dateTo = isCase ? data.dateTo : item.date_to;
        const categorie = data.categorie;

        const userName = user?.name || `User ${user_id}`;

        if (!acc[user_id]) {
            acc[user_id] = {
                user: userName,
                events: []
            };
        }

        if (!dateFrom || !dateTo) {
            return acc;
        }

        if (isCase && id !== null) {
            if (seenCaseIds.has(id)) {
                // ✅ Mark as duplicate
                item.isDuplicate = true;
            } else {
                seenCaseIds.add(id);
                item.isDuplicate = false;
            }
        }


        const fromTime = (dateFrom.split(" ")[1] || "").slice(0, 5);
        const toTime = (dateTo.split(" ")[1] || "").slice(0, 5);
        const color = categorie?.color || "#000000";

        // Multi-day logic: push an event for each date in the range
        const current = new Date(dateFrom.split(" ")[0]);
        const end = new Date(dateTo.split(" ")[0]);

        while (current <= end) {
            const yyyy = current.getFullYear();
            const mm = String(current.getMonth() + 1).padStart(2, '0');
            const dd = String(current.getDate()).padStart(2, '0');
            const dateStr = `${yyyy}-${mm}-${dd}`;

            acc[user_id].events.push({
                id: id,
                title: title || "(No title)",
                date: dateStr,
                from: fromTime,
                to: toTime,
                color,
                user: userName,
                type: isCase ? 'case' : 'event',
                editable: item.editable,
                // caseId: caseId,
                isDuplicate: item.isDuplicate || false  // ⬅️ Keep this flag
            });

            current.setDate(current.getDate() + 1);
        }

        return acc;
    }, {}));

    // Optional: sort each user's events by date
    transformedData.forEach(userGroup => {
        userGroup.events.sort((a, b) => {
            const dateA = new Date(`${a.date}T${a.from || "00:00"}:00`);
            const dateB = new Date(`${b.date}T${b.from || "00:00"}:00`);

            if (dateA.getTime() === dateB.getTime()) {
                return (a.title || "").localeCompare(b.title || "", undefined, { sensitivity: 'base' });
            }

            return dateA - dateB;
        });
    });

    return transformedData;
}

function toLocalDateString(date) {
    const yyyy = date.getFullYear();
    const mm = String(date.getMonth() + 1).padStart(2, '0'); // month is 0-based
    const dd = String(date.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
}

function buildMonthlyCalendarDays(inputMonth = null, inputYear = null) {
    const today = new Date();
    const year = inputYear !== null ? inputYear : today.getFullYear();
    const month = inputMonth !== null ? inputMonth : today.getMonth();

    const weeksNeeded = getWeeksCountForCalendar(month, year);
    const firstDay = new Date(year, month, 1).getDay(); // 0 = Sunday
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    //console.log("Weeks needed:", weeksNeeded);
    // console.log(inputMonth, ",", inputYear);

    // Get previous month info
    const prevMonth = month === 0 ? 11 : month - 1;
    const prevYear = month === 0 ? year - 1 : year;
    const daysInPrevMonth = new Date(prevYear, prevMonth + 1, 0).getDate();

    // 🔹 Get events for this user
    // const userData = getEventsForDate(); // Only one user in your example
    const userData = eventsData || []; // All users' events
    // const userEvents = userData.length > 0 ? userData[0].events : [];
    // Merge all users' events into one flat array
    const userEvents = userData.flatMap(user => user.events || []);
    userEvents.sort((a, b) => {
        const dateA = new Date(`${a.date}T${a.from || "00:00"}:00`);
        const dateB = new Date(`${b.date}T${b.from || "00:00"}:00`);

        if (dateA.getTime() === dateB.getTime()) {
            return (a.title || "").localeCompare(b.title || "", undefined, { sensitivity: 'base' });
        }

        return dateA - dateB;
    });

    // console.log("Events for month:", userEvents);

    ['#calendarBody', '#sidebarCalendarBody'].forEach((calendarId) => {
        const $tds = $(`${calendarId} td`);
        let tdIndex = 0;
        let day = 1;

        const currentDayClass = calendarId === '#calendarBody' ? 'currentDay' : 'sidebarCalendarCurrentDay';

        // Clear all td cells
        $tds.removeClass(currentDayClass).empty();

        // Fill days from previous month in the first cells (before firstDay)
        for (; tdIndex < firstDay && tdIndex < $tds.length; tdIndex++) {
            const dayNum = daysInPrevMonth - firstDay + 1 + tdIndex;
            $tds.eq(tdIndex).html(`<span class="font-bold text-gray-500">${dayNum}</span>`);  // muted color for prev month days
        }

        // Fill days for current month
        const isCurrentMonth = month === today.getMonth() && year === today.getFullYear();

        while (day <= daysInMonth && tdIndex < $tds.length) {
            const $td = $tds.eq(tdIndex);
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            $td.removeClass('currentDay'); // Always clear before setting
            if (calendarId === '#sidebarCalendarBody') {
                $td.html(`<button class="font-bold sidebar-day-btn cursor-pointer" data-date="${dateStr}">${day}</button>`);
                $td.addClass('sidebar-day-btn-parent cursor-pointer');
            } else {
                $td.html('<span class="font-bold cursor-pointer">' + day + '</span>');

                // 🔹 Inject events for the day
                const eventsForDay = userEvents.filter(event => event.date === dateStr);
                eventsForDay.forEach(event => {
                    if (event.isDuplicate) {
                        return; // ❌ Skip in UI, but kept in data
                    }
                    // console.log(event);
                    const iconPencil = event.editable ? `
                        <div class="iconPencil absolute right-1 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" data-id="${event.id}" data-type="${event.type}">
                            <i class="fa-solid fa-pen-to-square shadow-lg" style="color: #eaeef2;"></i>
                        </div>
                    ` : '';
                    const eventDiv = $(`
                        <div class="relative group text-gray-900 font-semibold yearlyEventInfo my-1 p-1 rounded cursor-pointer eventCase" style="background-color: ${event.color}" draggable="true" data-id="${event.id}" data-type="${event.type}">
                            <div class="relative">
                                <span>${event.title.length > 22 ? event.title.slice(0, 22) + '...' : event.title}</span>
                                ${iconPencil}
                            </div>
                            <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 hidden group-hover:block z-10">
                                <div class="relative bg-[#14548d] text-white text-xs p-2 rounded shadow-lg whitespace-nowrap">
                                    ${event.title}
                                    <div class="absolute left-1/2 -translate-x-1/2 top-full w-0 h-0 border-6 border-transparent border-t-[#14548d]"></div>
                                </div>
                            </div>
                        </div>
                    `);
                    $td.append(eventDiv);
                });
            }

            // if (!window.selectedDate && isCurrentMonth && day === today.getDate()) {
            //     $td.addClass(currentDayClass);
            // }

            if (!window.selectedDate) {
                if (isCurrentMonth && day === today.getDate()) {
                    $td.addClass(currentDayClass);
                }
                // else no highlight for other months
            } else {
                const selectedDateStr = toLocalDateString(window.selectedDate);
                if (dateStr === selectedDateStr) {
                    $td.addClass(currentDayClass);
                }
            }


            day++;
            tdIndex++;
        }

        // Fill days from next month if any cells left
        let nextMonthDay = 1;
        while (tdIndex < $tds.length) {
            $tds.eq(tdIndex).html('<span class="font-bold text-gray-500">' + nextMonthDay + '</span>'); // muted color for next month days
            nextMonthDay++;
            tdIndex++;
        }

        $(`${calendarId} tr`).each(function(index) {
            if (index < weeksNeeded) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    })

    // fetchEventsByMonthYear(month, year, currentEventTypeIdFilter, status, subStatus);
}

function getWeeksCountForCalendar(month, year) {
    const firstDay = new Date(year, month, 1).getDay();

    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const prevMonth = month === 0 ? 11 : month - 1;
    const prevYear = month === 0 ? year - 1 : year;
    const daysInPrevMonth = new Date(prevYear, prevMonth + 1, 0).getDate();

    const totalCells = firstDay + daysInMonth;

    let weeks = Math.ceil(totalCells / 7);

    const lastDayWeekday = new Date(year, month, daysInMonth).getDay();

    if (weeks === 5 && (firstDay + daysInMonth) > 35) {
        weeks = 6;
    }

    if (weeks < 4) weeks = 4;

    return weeks;
}

function generateCalendarDays() {
    const allCheckboxes = $('input[type="checkbox"][data-user-id]');
    allCheckboxes.prop('checked', false);

    const first = allCheckboxes.first();
    first.prop('checked', true);

    checkedOrder = [first.data('user-id')];
    buildMonthlyCalendarDays(currentMonth, currentYear);
}

function goToNextMonth() {
    if (currentMonth === 11) {
        currentMonth = 0;
        currentYear++;
    } else {
        currentMonth++;
    }

    updateCalendarHeader(currentMonth, currentYear);
    buildMonthlyCalendarDays(currentMonth, currentYear);
}

function goToPreviousMonth() {
    if (currentMonth === 0) {
        currentMonth = 11;
        currentYear--;
    } else {
        currentMonth--;
    }

    updateCalendarHeader(currentMonth, currentYear);
    buildMonthlyCalendarDays(currentMonth, currentYear);
}

function parseMonthYear(monthYear) {
    const [monthName, yearStr] = monthYear.split(' ');
    const year = parseInt(yearStr, 10);
    const month = new Date(`${monthName} 1, ${year}`).getMonth();
    return { month, year };
}

function updateCalendarHeader(month, year) {
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    $('#calendarMonthYearSelected').text(`${monthNames[month]} ${year}`);
    $('#sidebarCalendarMonthYearSelected').text(`${monthNames[month]} ${year}`);
    $('#currentDateData')
        .attr('data-month', String(month + 1).padStart(2, '0')) // 01-12 format
        .attr('data-year', year);
}

function highlightSelectedSidebarDay() {
    $('.sidebar-day-btn-parent').removeClass('selected-day sidebarCalendarCurrentDay');

    if (window.selectedDate) {
        const selectedDateStr = toLocalDateString(window.selectedDate);
        $(`.sidebar-day-btn[data-date="${selectedDateStr}"]`).parent().addClass('selected-day');
    } else {
        const todayStr = toLocalDateString(new Date());
        $(`.sidebar-day-btn[data-date="${todayStr}"]`).parent().addClass('sidebarCalendarCurrentDay');
    }
}

function highlightSelectedWeeklyDay() {
    // First, clear any existing highlight
    $('.weekly-day.selected-day').removeClass('selected-day');

    if (!window.selectedDate) return;

    // Format the selected date to YYYY-MM-DD
    const year = window.selectedDate.getFullYear();
    const month = (window.selectedDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
    const day = window.selectedDate.getDate().toString().padStart(2, '0');

    const selectedDateStr = `${year}-${month}-${day}`;

    // Find the element in the weekly view with that date and add the highlight class
    $(`.weekly-day[data-date="${selectedDateStr}"]`).addClass('selected-day');
}

function updateUserSelectMode() {
    const selectedType = $('input[name="type"]:checked').val();
    const $userSelect = $('#userSelect');
    const $selectedUsers = $('#selectedUsers');
    const $userFieldsContainer = $('#userFieldsContainer');

    console.log("Selected type:", selectedType);

    // for (let i = 0; i < $userSelect[0].options.length; i++) {
    //     const option = $userSelect[0].options[i];

    //     if (!option.dataset.originalText) {
    //         option.dataset.originalText = option.text;
    //     }

    //     if (i === 0) {
    //         option.text = option.dataset.originalText;
    //     } else {
    //         option.text = `${i}. ${option.dataset.originalText}`;
    //     }

    //     option.classList.remove('option-disabled');
    // }

    $('option').removeClass('option-disabled');
    $selectedUsers.empty();
    $userSelect.off('change');

    const selectedValues = new Set();
    $userSelect.data('selectedValues', selectedValues);

    if (selectedType === 'case') {
        $userFieldsContainer.removeClass('hidden');
        $('#userSelect').removeClass('selectArrowDown');
        $userSelect.attr('multiple', 'multiple');
        $userSelect.attr('name', 'user[]');

        flatpickr(".datetimepicker", {
            enableTime: false,
            dateFormat: "m-d-Y",
            // defaultDate: new Date(),
        });

        // Bind new change handler
        $userSelect.on('change', function () {
            const val = $(this).val();
            if (!val) return;

            val.forEach(v => {
                if (v && v !== '-1' && !selectedValues.has(v)) {
                    selectedValues.add(v);

                    let label = '';
                    for (let i = 0; i < $userSelect[0].options.length; i++) {
                        const option = $userSelect[0].options[i];
                        if (option.value === v) {
                            label = option.text;
                            option.classList.add('option-disabled');
                            break;
                        }
                    }

                    const $tag = $('<div class="text-sm cursor-default select-none w-full flex justify-between items-center px-2"></div>')
                        .addClass('')
                        .html(`<span>${label}</span>`)
                        .append($(`
                            <i
                                class="removeUserSelect fa-solid fa-xmark fa-lg text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                                title="Remove"
                                data-user-id="${v}"
                            </i>
                            `).addClass('ml-1 text-blue-600'));

                    $selectedUsers.append($tag);
                }
            });
        });
        $userSelect.val(null);
    } else {
        $userFieldsContainer.addClass('hidden');
        flatpickr(".datetimepicker", {
            enableTime: true,
            dateFormat: "m-d-Y H:i",
            time_24hr: true,
            // defaultDate: new Date(),
        });
    }
    // else {
    //     $('#userSelect').addClass('selectArrowDown');
    //     $userSelect.removeAttr('multiple');
    //     $userSelect.attr('name', 'user');

    //     $userSelect.val('-1');
    // }
}

function getUsers(callback) {
    $.ajax({
        url: '/getUsers',
        method: 'GET',
        success: function (response) {
            const users = response.users || [];
            const authUserId = response.auth_user_id;

            // Move auth user to the top of the list
            const authUserIndex = users.findIndex(user => user.id === authUserId);
            if (authUserIndex > -1) {
                const [authUser] = users.splice(authUserIndex, 1);
                users.unshift(authUser);
            }

            const $lawyersList = $('#lawyersList');
            $lawyersList.empty();

            users.forEach(user => {
                const isChecked = user.id === authUserId || user.isChecked;
                const checked = isChecked ? 'checked' : '';

                const lawyerHtml = `
                    <li>
                        <label class="lawyersCheckboxSection w-full p-3 bg-[#eaf1ff] rounded-lg shadow flex items-center justify-between hover:bg-[#dce9ff] transition duration-200 ease-in-out cursor-pointer">
                            <span class="text-sm font-medium text-gray-900">${user.name}</span>
                            <input type="checkbox" data-user-id="${user.id}" ${checked} />
                        </label>
                    </li>
                `;
                $lawyersList.append(lawyerHtml);
            });
            initializeCheckedOrder();
            if (typeof callback === 'function') callback();
        },
        error: function () {
            $('#lawyersList').empty();
            $('#lawyersList').append(`
                <li>
                    <label class="lawyersCheckboxSection w-full p-3 bg-[#eaf1ff] rounded-lg shadow flex items-center justify-between hover:bg-[#dce9ff] transition duration-200 ease-in-out cursor-pointer">
                        <span class="text-sm font-medium text-gray-900">Error loading users</span>
                    </label>
                </li>
            `);
        }
    });
}

function getData(ids, callback, eventCaseId = null) {
    const month = $('#currentDateData').attr('data-month');
    const year = $('#currentDateData').attr('data-year');
    // console.log("Fetching data for:", ids, month, year);
    const viewMode = $('#selectedDayWeekMonthOption').text().trim();
    const { visibleStartDate, visibleEndDate } = getMonthWeekBoundaries(year, month);
    // console.log("Visible range:", visibleStartDate, "to", visibleEndDate);
    const startDateStr = visibleStartDate.toISOString().split('T')[0];
    const endDateStr = visibleEndDate.toISOString().split('T')[0];
    // console.log("Fetching data from", startDateStr, "to", endDateStr);
    const endpoint = dataType === 'cases' ? '/getCases' : '/getEvents';

    const requestData = eventCaseId
        ? { event_case_id: eventCaseId }
        : {
            user_id: ids,
            start_date: startDateStr,
            end_date: endDateStr,
            view_mode: viewMode
        };

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: endpoint,
        data: requestData,
        dataType: 'json',
        success: function (response) {
            // console.log(response);
            // if(ids) {
            //     eventsData = getEventsForDate(response || []); // You may rename this for generality
            // } else {
            //     eventCaseEditData = response;
            // }
            // // console.log(`${dataType} saved to global:`, eventsData);

            // if (typeof callback === "function") {
            //     callback(null, eventsData);
            // }
            if (typeof callback === "function") {
                if (eventCaseId) {
                    // ✅ Return full response object for single event/case
                    callback(null, response);
                } else {
                    // ✅ Process and return list
                    eventsData = getEventsForDate(response || []);
                    callback(null, eventsData);
                }
            }
        },
        error: function (xhr) {
            console.error(`Error fetching ${dataType}:`, xhr);

            let errorMsg = 'Unknown error occurred';

            // Try to parse error message from JSON response
            try {
                const response = JSON.parse(xhr.responseText);
                if (response && response.error) {
                    errorMsg = response.error;
                }
            } catch (e) {
                // parsing failed, keep default errorMsg
            }

            // if (typeof callback === "function") {
            //     callback(xhr);
            // }
            if (typeof callback === "function") {
                callback({ error: errorMsg }, null);
            }
        }
    });
}

function initializeCheckedOrder() {
    checkedOrder = [];
    $('input[type="checkbox"][data-user-id]:checked').each(function () {
        checkedOrder.push($(this).data('user-id'));
    });
    // console.log('Initialized checkedOrder:', checkedOrder);
}

function refreshCalendar() {
    const view = $('#selectedDayWeekMonthOption').text().trim();
    currentView = view;
    if (dataType === 'events' && view === 'Month View' && checkedOrder.length > 1) {
        const lastUserId = checkedOrder[checkedOrder.length - 1];
        checkedOrder = [lastUserId];

        // Uncheck all checkboxes, check only the last selected one
        $('input[type="checkbox"][data-user-id]').each(function () {
            const isLast = $(this).data('user-id') === lastUserId;
            $(this).prop('checked', isLast);
        });
    } else if (dataType === 'events' && (view === 'Week View' || view === 'Day View') && checkedOrder.length > 2) {
        const frstUserId = checkedOrder[checkedOrder.length - 1];
        const scndUserId = checkedOrder[checkedOrder.length - 2];

        $('input[type="checkbox"][data-user-id]').each(function () {
            const isFirst = $(this).data('user-id') === frstUserId;
            const isSecond = $(this).data('user-id') === scndUserId;
            $(this).prop('checked', isFirst || isSecond);
        });
    }

    getData(checkedOrder, () => {
        if (view === 'Month View') {
            buildMonthlyCalendarDays(currentMonth, currentYear);
        } else if (view === 'Week View') {
            let dateToUse = window.viewedWeekDate || window.selectedDate || new Date();

            buildWeeklyView(
                dateToUse.getDate(),
                dateToUse.getMonth(),
                dateToUse.getFullYear()
            );

        } else if (view === 'Day View') {
            if (window.selectedDate) {
                buildDailyView(
                    window.selectedDate.getDate(),
                    window.selectedDate.getMonth(),
                    window.selectedDate.getFullYear()
                );
            }
            else {
                buildDailyView();
            }

            if (checkedOrder.length === 2) {
                $('#dailyViewTable').removeClass('max-w-[750px] mx-auto xl:col-span-12').addClass('xl:col-span-6');
            } else {
                $('#dailyViewTable').addClass('max-w-[750px] mx-auto xl:col-span-12').removeClass('xl:col-span-6');
            }
        }
    });
}

function getMonthWeekBoundaries(year, month) {
    const firstOfMonth = new Date(year, month - 1, 1); // JS months are 0-indexed
    const lastOfMonth = new Date(year, month, 0);      // Last day of month

    const startOfFirstWeek = new Date(firstOfMonth);
    startOfFirstWeek.setDate(firstOfMonth.getDate() - firstOfMonth.getDay());

    const endOfLastWeek = new Date(lastOfMonth);
    endOfLastWeek.setDate(lastOfMonth.getDate() + (6 - lastOfMonth.getDay()));

    return {
        visibleStartDate: startOfFirstWeek,
        visibleEndDate: endOfLastWeek
    };
}

function populateMonthYearDropdown() {
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    $('#monthSelect').empty();

    monthNames.forEach((month, index) => {
        $('#monthSelect').append(new Option(month, index));
    });

    const thisYear = new Date().getFullYear();
    $('#yearSelect').empty();
    for (let year = thisYear - 40; year <= thisYear + 40; year++) {
        $('#yearSelect').append(new Option(year, year));
    }

    $('#monthSelect').val(currentMonth);
    $('#yearSelect').val(currentYear);
}

function getUsersCategoriesAddEditCasesEvents(callback) {
    const $categorySelect = $('#categorySelect');
    const $userSelect = $('#userSelect');

    $categorySelect.html('<option value="-1" disabled selected>Loading...</option>')
    $userSelect.html('<option value="-1" disabled selected>Loading...</option>')

    // const categoriesAjax = $.ajax({
    //     url: '/getCategories',
    //     method: 'GET',
    //     success: function (categories) {
    //         // console.log(categories);
    //         $categorySelect.empty();
    //         $categorySelect.append('<option value="-1">Select a category</option>');

    //         categories.forEach(function (category) {
    //             $categorySelect.append(`<option value="${category.id}">${category.categoryName}</option>`);
    //         });

    //         updateUserSelectMode();
    //     },
    //     error: function () {
    //         $categorySelect.html('<option value="-1" disabled selected>Error loading categories</option>');
    //     }
    // });

    // const usersAjax = $.ajax({
    //     url: '/getUsers',
    //     method: 'GET',
    //     success: function (response) {
    //         const users = response.users || [];
    //         // console.log(users);
    //         $userSelect.empty();
    //         // $userSelect.append('<option value="-1">Select an user(s)</option>');

    //         users.forEach(function (user) {
    //             // console.log(user);
    //             $userSelect.append(`<option value="${user.id}">${user.name}</option>`);
    //         });

    //         updateUserSelectMode();
    //     },
    //     error: function () {
    //         $userSelect.html('<option value="-1" disabled selected>Error loading users</option>');
    //     }
    // });

    $.ajax({
        url: '/getUsersCategories',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            const categories = response.categories || [];
            const users = response.users || [];

            // ✅ Populate categories
            $categorySelect.empty().append('<option value="-1">Select a category</option>');
            categories.forEach(function (category) {
                $categorySelect.append(`<option value="${category.id}">${category.categoryName}</option>`);
            });

            // ✅ Populate users
            $userSelect.empty();
            users.forEach(function (user) {
                $userSelect.append(`<option value="${user.id}">${user.name}</option>`);
            });

            updateUserSelectMode();

            if (typeof callback === 'function') {
                callback();
            }
        },
        error: function () {
            $categorySelect.html('<option value="-1" disabled selected>Error loading categories</option>');
            $userSelect.html('<option value="-1" disabled selected>Error loading users</option>');
        }
    });

    // $.when(categoriesAjax, usersAjax).done(function () {
    //     if (typeof callback === 'function') {
    //         callback();
    //     }
    // });
}

function formatDateToMMDDYYYY(datetimeStr) {
    const date = new Date(datetimeStr);

    if (isNaN(date.getTime())) {
        console.warn('Invalid date:', datetimeStr);
        return '';
    }

    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day   = String(date.getDate()).padStart(2, '0');
    const year  = date.getFullYear();

    return `${month}-${day}-${year}`;
}

function formatTimeHHMM(datetimeStr) {
    const date = new Date(datetimeStr);

    if (isNaN(date.getTime())) {
        console.warn('Invalid date:', datetimeStr);
        return '';
    }

    const hours = String(date.getHours()).padStart(2, '0');
    const mins  = String(date.getMinutes()).padStart(2, '0');

    return `${hours}:${mins}`;
}

function submitEditEventCase(actionUrl, method) {
    const $form = $('#addEventCaseForm');
    const $button = $('#submitEditEventCaseBtn');

    $('#modalErrorContent').empty();
    $('#errorModal').addClass('hidden');

    $button.prop('disabled', true).html(`
        <i class="fa-solid fa-spinner fa-spin"></i>
        <span class="ml-1">Saving...</span>
    `);

    $('.input-error-text').remove();
    $('input, select').removeClass('border-red-500');

    console.log($form.serialize());
    $.ajax({
        type: method,
        url: actionUrl,
        data: $form.serialize(),
        dataType: 'json',
        success: function (response) {
            $form[0].reset();
            $('#addEventCaseModal').addClass('hidden');
            $('#modalSuccessContent').html(response.message);
            $('#successModal').removeClass('hidden');
            refreshCalendar();
        },
        error: function (xhr) {
            if (xhr.status) {
                const errors = xhr.responseJSON.errors;

                // Loop over errors and display inline
                $.each(errors, function (field, messages) {
                    let $input = $form.find(`[name="${field}"]`);

                    if ($input.length === 0) {
                        // Handle array-like error field names like user.0, user.1
                        const baseField = field.split('.')[0];
                        $input = $form.find(`[name="${baseField}[]"], [name="${baseField}"]`);
                    }

                    $input.addClass('border-red-500');

                    // if(field === "user") {
                    //     $("#selectedUsers").addClass("border-red-500");
                    // }

                    if ($input.next('.input-error-text').length === 0) {
                        $input.after(`<p class="input-error-text text-red-600 text-sm mt-1">${messages[0]}</p>`);
                    }
                });
            }
        },
        complete: function () {
            $button.prop('disabled', false).html(`
                <i class="fa-solid fa-paper-plane"></i>
                <span class="ml-1">
                    Save
                </span>
            `);
        }
    });
}

function deleteEditEventCase(actionUrl, method) {
    const $form = $('#addEventCaseForm');
    const $button = $('#confirmDeleteBtn');

    $('#modalErrorContent').empty();
    $('#errorModal').addClass('hidden');

    $button.prop('disabled', true).html(`
        <i class="fa-solid fa-spinner fa-spin"></i>
        <span class="ml-1">
            Deleting...
        </span>
    `);

    $('.input-error-text').remove();
    $('input, select').removeClass('border-red-500');

    $.ajax({
        type: method,
        url: actionUrl,
        data: $form.serialize(),
        dataType: 'json',
        success: function (response) {
            $form[0].reset();
            $('#deleteConfirmModal').addClass('hidden');
            $('#modalSuccessContent').html(response.message);
            $('#successModal').removeClass('hidden');
            refreshCalendar();
        },
        error: function (xhr) {
            if (xhr.status) {
                const errors = xhr.responseJSON.errors;

                // Loop over errors and display inline
                $.each(errors, function (field, messages) {
                    let $input = $form.find(`[name="${field}"]`);

                    if ($input.length === 0) {
                        // Handle array-like error field names like user.0, user.1
                        const baseField = field.split('.')[0];
                        $input = $form.find(`[name="${baseField}[]"], [name="${baseField}"]`);
                    }

                    $input.addClass('border-red-500');

                    // if(field === "user") {
                    //     $("#selectedUsers").addClass("border-red-500");
                    // }

                    if ($input.next('.input-error-text').length === 0) {
                        $input.after(`<p class="input-error-text text-red-600 text-sm mt-1">${messages[0]}</p>`);
                    }
                });
            }
        },
        complete: function () {
            $button.prop('disabled', false).html(`
                <span class="ml-1">
                    YES, DELETE
                </span>
            `);
        }
    });
}
