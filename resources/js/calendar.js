let currentMonth, currentYear;
let checkedOrder = [];

$(document).ready(() => {
    ({ month: currentMonth, year: currentYear } = parseMonthYear($('#calendarMonthYearSelected').text()));

    generateCalendarDays();

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


    $(document).on('click', '#calendarDayViewOption', function() {
        $('#selectedDayWeekMonthOption').text('Day View');
    });

    $(document).on('click', '#calendarWeekViewOption', function() {
        $('#selectedDayWeekMonthOption').text('Week View');
    });

    $(document).on('click', '#calendarMonthViewOption', function() {
        $('#selectedDayWeekMonthOption').text('Month View');
    });

    $(document).on('click', '#calendarPrevDay', function() {
        if(!window.selectedDate) window.selectedDate = new Date();

        window.selectedDate.setDate(window.selectedDate.getDate() - 1);

        const newMonth = window.selectedDate.getMonth();
        const newYear = window.selectedDate.getFullYear();

        if (newMonth !== currentMonth || newYear !== currentYear) {
            currentMonth = newMonth;
            currentYear = newYear;
            updateCalendarHeader(currentMonth, currentYear);
            buildMonthlyCalendarDays(currentMonth, currentYear);
        }

        buildDailyView(window.selectedDate.getDate(), window.selectedDate.getMonth(), window.selectedDate.getFullYear());
        updateDailyHeader(window.selectedDate);
        console.log(window.selectedDate);

        highlightSelectedSidebarDay();
    });

    $(document).on('click', '#calendarNextDay', function() {
        if (!window.selectedDate) window.selectedDate = new Date();
        window.selectedDate.setDate(window.selectedDate.getDate() + 1);

        const newMonth = window.selectedDate.getMonth();
        const newYear = window.selectedDate.getFullYear();

        if (newMonth !== currentMonth || newYear !== currentYear) {
            currentMonth = newMonth;
            currentYear = newYear;
            updateCalendarHeader(currentMonth, currentYear);
            buildMonthlyCalendarDays(currentMonth, currentYear);
        }

        buildDailyView(window.selectedDate.getDate(), window.selectedDate.getMonth(), window.selectedDate.getFullYear());
        updateDailyHeader(window.selectedDate);

        highlightSelectedSidebarDay();
    });

    $(document).on('click', '#calendarPrevWeek', function() {
        if (!window.selectedDate) window.selectedDate = new Date();
        if (!window.viewedWeekDate) window.viewedWeekDate = new Date(window.selectedDate.getTime());

        window.viewedWeekDate.setDate(window.viewedWeekDate.getDate() - 7);

        const newMonth = window.viewedWeekDate.getMonth();
        const newYear = window.viewedWeekDate.getFullYear();

        if (newMonth !== currentMonth || newYear !== currentYear) {
            currentMonth = newMonth;
            currentYear = newYear;
            updateCalendarHeader(currentMonth, currentYear);
            buildMonthlyCalendarDays(currentMonth, currentYear);
            $('.sidebar-day-btn-parent').removeClass('selected-day sidebarCalendarCurrentDay');

            highlightSelectedSidebarDay();  // <-- Call your function here
        }

        buildWeeklyView(
            window.viewedWeekDate.getDate(),
            window.viewedWeekDate.getMonth(),
            window.viewedWeekDate.getFullYear()
        );

        updateWeeklyHeader(window.viewedWeekDate);
    });

    $(document).on('click', '#calendarNextWeek', function() {
        if (!window.selectedDate) window.selectedDate = new Date();
        if (!window.viewedWeekDate) window.viewedWeekDate = new Date(window.selectedDate.getTime());

        window.viewedWeekDate.setDate(window.viewedWeekDate.getDate() + 7);

        const newMonth = window.viewedWeekDate.getMonth();
        const newYear = window.viewedWeekDate.getFullYear();

        if (newMonth !== currentMonth || newYear !== currentYear) {
            currentMonth = newMonth;
            currentYear = newYear;
            updateCalendarHeader(currentMonth, currentYear);
            buildMonthlyCalendarDays(currentMonth, currentYear);
            $('.sidebar-day-btn-parent').removeClass('selected-day sidebarCalendarCurrentDay');

            highlightSelectedSidebarDay();  // <-- And here too
        }

        buildWeeklyView(
            window.viewedWeekDate.getDate(),
            window.viewedWeekDate.getMonth(),
            window.viewedWeekDate.getFullYear()
        );

        updateWeeklyHeader(window.viewedWeekDate);
    });

    $(document).on('click', '#calendarPrevMonth, #sidebarCalendarPrevMonth', function() {
        goToPreviousMonth();
        highlightSelectedSidebarDay();
        // $('.sidebar-day-btn').removeClass('selected-day');
    });

    $(document).on('click', '#calendarNextMonth, #sidebarCalendarNextMonth', function() {
        goToNextMonth();
        highlightSelectedSidebarDay();
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
        showView(view);
    });

    $(document).on('change', 'input[type="checkbox"][data-user-id]', function () {
        const view = $('#selectedDayWeekMonthOption').text().trim();
        const allCheckboxes = $('input[type="checkbox"][data-user-id]');
        const $this = $(this);
        const userId = $this.data('user-id');

        console.log('View:', view);
        console.log('User ID (changed):', userId);

        if (view === 'Month View') {
            allCheckboxes.prop('checked', false);
            $this.prop('checked', true);

            checkedOrder = [$this.data('user-id')];
        } else {
            if ($this.is(':checked')) {
                if (!checkedOrder.includes(userId)) {
                    checkedOrder.push(userId);
                }

                checkedOrder = checkedOrder.filter(id =>
                    $(`input[type="checkbox"][data-user-id="${id}"]`).is(':checked')
                );


                if (checkedOrder.length > 2) {
                    // Too many checked, remove the first one (oldest)
                    const firstCheckedId = checkedOrder.shift();
                    $(`input[type="checkbox"][data-user-id="${firstCheckedId}"]`).prop('checked', false);
                    console.log(`Unchecking oldest user ID: ${firstCheckedId}`);
                }
            } else {
                // Checkbox is being unchecked manually
                checkedOrder = checkedOrder.filter(id => id !== userId);
                console.log(`User ID manually unchecked: ${userId}`);
            }

            // Optional: Log current checked IDs
            const currentChecked = checkedOrder;
            console.log('Currently selected user IDs:', currentChecked);
        }
    });

    $('#openAddEventCaseModal').on('click', function() {
        $('#addEventCaseModal').removeClass('hidden');

        const $select = $('#categorySelect');
        $select.html('<option>Loading...</option>')

        $.ajax({
            url: '/getCategories',
            method: 'GET',
            success: function (categories) {
                console.log(categories);
                $select.empty();
                $select.append('<option value="-1">Select a category</option>');

                categories.forEach(function (category) {
                    $select.append(`<option value="${category.id}">${category.categoryName}</option>`);
                });
            },
            error: function () {
                $select.html('<option>Error loading categories</option>');
            }
        });
    });

    // Close modal
    $('#closeAddEventCaseModal').on('click', function() {
        $('#addEventCaseModal').addClass('hidden');
    });

    // Optional: Close when clicking outside modal content
    $('#addEventCaseModal').on('click', function(e) {
        if ($(e.target).is('#addEventCaseModal')) {
            $(this).addClass('hidden');
        }
    });

    // add event/case steps

    // step 1
    $('#submitAddEventCaseBtn').on('click', function () {
        $('#addEventCaseForm').submit();
    });

    // step 2
    $('#addEventCaseForm').on('submit', function (e) {
        e.preventDefault();

        const type = $('input[name="type"]:checked').val();
        let actionUrl = '';

        if (type === 'event') {
            actionUrl = "{{ route('events.store') }}";
        } else if (type === 'case') {
            actionUrl = "{{ route('cases.store') }}";
        }

        const $form = $(this);
        const $button = $('#submitAddEventCaseBtn');

        // Clear previous inline errors & modal errors
        $('#modalErrorContent').empty();
        $('#errorModal').addClass('hidden');

        $button.prop('disabled', true).text('Saving...');
        // console.log($form.serialize());
        $.ajax({
            type: 'POST',
            url: actionUrl,
            data: $form.serialize(),
            dataType: 'json',
            success: function (response) {
                $form[0].reset();
                $('#addCategoryModal').addClass('hidden');
                $('#modalSuccessContent').html(response.message);
                $('#successModal').removeClass('hidden');
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};

                    if (errors.name) {
                        let errorHtml = '<ul class="text-sm text-red-600 space-y-1">';
                        errors.name.forEach(function (error) {
                            errorHtml += `<li>${error}</li>`;
                        });
                        errorHtml += '</ul>';
                        $('#errorCategoryName').html(errorHtml);
                    }
                } else {
                    $('#modalErrorContent').html('An unexpected error occurred. Please try again.');
                    $('#errorModal').removeClass('hidden');
                }
            },
            complete: function (response) {
                $button.prop('disabled', false).text('Add Category');
            }
        });
    });
});

function toggleHeaderForView(view) {
    if(view === 'Monthly') {
        $('#monthToggleSection').removeClass('hidden');
        $('#weekToggleSection').addClass('hidden');
        $('#dayToggleSection').addClass('hidden');

        const checkboxes = $('input[type="checkbox"][data-user-id]');
        checkboxes.prop('checked', false);
        checkboxes.first().prop('checked', true);
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
}

function updateWeeklyHeader(date) {
    const startOfWeek = new Date(date);
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
}

function showView(view) {
    $('#viewMonthly, #viewWeekly, #viewDaily').addClass('hidden');
    $('#view' + view).removeClass('hidden');

    toggleHeaderForView(view);

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
        // $('.sidebar-day-btn').removeClass('selected-day');
    } else if (view === 'Monthly') {
        // $('.sidebar-day-btn').removeClass('selected-day');
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

    const allUsers = getEventsForDate(); // Get full list of users with events

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
                dailyBody.append(`
                    <div class="text-gray-900 dailyEventInfo bg-[#00CED1]" draggable="true">
                        <span>${event.title}</span>
                        <span>~${event.from} - ${event.to}</span>
                    </div>
                `);
            });
        }

        return;
    }

    // === If 2 users (always show both) ===
    if (allUsers.length >= 2) {
        const user1 = allUsers[0];
        const user2 = allUsers[1];

        const eventsUser1 = user1.events.filter(event => event.date === isoDate);
        const eventsUser2 = user2.events.filter(event => event.date === isoDate);

        $('#dailyViewTable').removeClass('hidden');
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
                dailyBody.append(`
                    <div class="text-gray-900 dailyEventInfo bg-[#00CED1]" draggable="true">
                        <span>${event.title}</span>
                        <span>~${event.from} - ${event.to}</span>
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
                bodyHidden.append(`
                    <div class="text-gray-900 dailyEventInfo bg-[#00CED1]" draggable="true">
                        <span>${event.title}</span>
                        <span>~${event.from} - ${event.to}</span>
                    </div>
                `);
            });
        }
    }

    console.log("Daily view rendered for:", selectedDate.toDateString());
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


    const allEventsData = getEventsForDate();

    const userRow1 = allEventsData[0] || null;
    const userRow2 = allEventsData[1] || null;

    if (userRow1) {
        $('#userHeader').html(`<div class="border border-[#fff] p-2">${userRow1.user}</div>`);
    } else {
        $('#userHeader').html(`<div class="border border-[#fff] p-2">"No user"</div>`);
    }

    if (userRow2) {
        $('#userHeaderHidden').html(`<div class="border border-[#fff] p-2">${userRow2.user}</div>`);
        $('#weeklyViewTableHidden').removeClass('hidden');
        $('#viewWeekly').removeClass('grid-rows-1').addClass('2xl:grid-rows-[40%_60%]');
    } else {
        $('#weeklyViewTableHidden').addClass('hidden');
        $('#viewWeekly').removeClass('2xl:grid-rows-[40%_60%]').addClass('grid-rows-1');
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
                    const eventDiv = $(`
                        <div class="text-gray-900 weeklyEventInfo bg-[#00CED1] my-1 p-1 rounded cursor-pointer" draggable="true">
                            <span>${event.title}</span>
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
        if (userRow2) {
            const eventsForDate = userRow2.events.filter(e => e.date === isoDate);
            if (eventsForDate.length) {
                eventsForDate.forEach(event => {
                    const eventDiv = $(`
                        <div class="text-gray-900 weeklyEventInfo bg-[#00CED1] my-1 p-1 rounded cursor-pointer" draggable="true">
                            <span>${event.title}</span>
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

    console.log("Weekly view rendered for:", startOfWeek.toDateString());
}


function getEventsForDate(date) {
    // Stub: replace this with your actual fetch logic
    return [
        {
            user: 'Simone Alexander',
            events: [
                { title: 'AAM - NEW REFERRAL', date: '2025-08-25', from: '09:00', to: '09:30' },
                { title: 'Morning Briefing', date: '2025-08-26', from: '08:30', to: '09:00' },
                { title: 'Client Meeting - Project Alpha', date: '2025-08-28', from: '11:00', to: '12:00' },
                { title: 'Lunch with Team', date: '2025-08-29', from: '12:30', to: '13:30' },
                { title: 'Quarterly Report Review', date: '2025-08-30', from: '15:00', to: '16:00' },
                { title: 'Strategy Planning Session', date: '2025-09-01', from: '10:00', to: '12:00' },
                { title: 'Follow-up Call with Partner', date: '2025-09-02', from: '14:00', to: '14:30' },
                { title: 'Product Demo', date: '2025-09-04', from: '10:00', to: '11:00' },
                { title: 'Team Building Activity', date: '2025-09-04', from: '13:00', to: '17:00' },
                { title: 'End of Week Wrap-up', date: '2025-09-04', from: '17:00', to: '17:30' }
            ]
        },
        {
            user: 'John Doe',
            events: [
                { title: 'Team Sync - Project Phoenix', date: '2025-08-28', from: '10:00', to: '10:30' },
                { title: 'Follow-up Call with Client', date: '2025-08-25', from: '15:00', to: '15:30' }
            ]
        }
    ];
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
    const userData = getEventsForDate(); // Only one user in your example
    const userEvents = userData.length > 0 ? userData[0].events : [];

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
                    const eventDiv = $(`
                        <div class="text-gray-900 yearlyEventInfo bg-[#00CED1] my-1 p-1 rounded cursor-pointer" draggable="true" title="${event.title}">
                            <span>${event.title}</span>
                        </div>
                    `);
                    $td.append(eventDiv);
                });
            }

            if (!window.selectedDate && isCurrentMonth && day === today.getDate()) {
                $td.addClass(currentDayClass);
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
