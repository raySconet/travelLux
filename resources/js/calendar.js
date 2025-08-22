let currentMonth, currentYear;

$(document).ready(() => {
    ({ month: currentMonth, year: currentYear } = parseMonthYear($('#calendarMonthYearSelected').text()));

    generateCalendarDays();

    $(document).on('click', '.sidebar-day-btn', function() {
        const dateStr = $(this).data('date');
        const [year, month, day] = dateStr.split('-').map(Number);

        const selected = new Date(year, month - 1, day);
        window.selectedDate = selected;

        currentMonth = selected.getMonth();
        currentYear = selected.getFullYear();

        updateCalendarHeader(currentMonth, currentYear);
        buildMonthlyCalendarDays(currentMonth, currentYear);
        buildDailyView(day, currentMonth, currentYear);
        updateDailyHeader(selected);

        showView('Daily');
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
    })

    $(document).on('click', '#calendarNextDay', function() {
        if (!window.selectedDate) window.selectedDate = new Date();
        window.selectedDate.setDate(window.selectedDate.getDate() + 1);
        buildDailyView(window.selectedDate.getDate(), window.selectedDate.getMonth(), window.selectedDate.getFullYear());
        updateDailyHeader(window.selectedDate);
    });

    $(document).on('click', '#calendarPrevMonth, #sidebarCalendarPrevMonth', function() {
        goToPreviousMonth();
    });

    $(document).on('click', '#calendarNextMonth, #sidebarCalendarNextMonth', function() {
        goToNextMonth();
    });

    $(document).on('click', '#calendarDayViewOption, #calendarWeekViewOption, #calendarMonthViewOption', function() {
        const id = this.id

        const viewMap = {
            'calendarDayViewOption': 'Daily',
            'calendarWeekViewOption': 'Weekly',
            'calendarMonthViewOption': 'Monthly'
        };

        const view = viewMap[id];
        showView(view);

        const el = $('#viewOptionsDropdown [popover]')[0];
        el?.hidePopover?.();
    });
});

function toggleHeaderForView(view) {
    if(view === 'Monthly') {
        $('#monthToggleSection').removeClass('hidden');
        $('#dayToggleSection').addClass('hidden');
    } else if(view === 'Daily') {
        $('#monthToggleSection').addClass('hidden');
        $('#dayToggleSection').removeClass('hidden');

        if(window.selectedDate) { // storage day
            updateDailyHeader(window.selectedDate);
        } else {
            updateDailyHeader(new Date());
        }
    } else {
        // For Week view or others, you can decide similarly
        $('#monthToggleSection').removeClass('hidden');
        $('#dayToggleSection').addClass('hidden');
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

function showView(view) {
    $('#viewMonthly, #viewWeekly, #viewDaily').addClass('hidden');
    $('#view' + view).removeClass('hidden');

    toggleHeaderForView(view);

    if (view === 'Daily') {
        if (window.selectedDate) {
            buildDailyView(
                window.selectedDate.getDate(),
                window.selectedDate.getMonth(),
                window.selectedDate.getFullYear()
            );
        } else {
            buildDailyView();
        }
    }
}

function buildDailyView(inputDay = null, inputMonth = null, inputYear = null) {
    const today = new Date();

    const year = inputYear !== null ? inputYear : today.getFullYear();
    const month = inputMonth !== null ? inputMonth : today.getMonth();
    const day = inputDay !== null ? inputDay : today.getDate();

    const selectedDate = new Date(year, month, day);
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const dayName = daysOfWeek[selectedDate.getDay()];
    const userName = "~Rony Chammai";

    // Set day name and user in header
    const $dailyHeader = $('#dailyHeader');
    const $dailyBody = $('#dailyBody');

    $dailyHeader.html(`${dayName} ${day} <div>${userName}</div>`);

    $dailyBody.empty();

    const dailyEvents = getEventsForDate(selectedDate);

    if (dailyEvents.length === 1) {
        $('#dailyViewTable').removeClass('hidden');
        $('#dailyViewTableHidden').addClass('hidden');

        const user = dailyEvents[0];
        $('#dailyHeader').html(`${dayName} ${day} <div>${user.user}</div>`);

        const $dailyBody = $('#dailyBody');
        $dailyBody.empty();

        user.events.forEach(event => {
            const $row = $(`
                <tr class="calendarRowData mt-2">
                    <td class="min-w-[500px] h-full px-4 border-0">
                        <div class="text-gray-900 dailyEventInfo bg-[#30d80fb3]" draggable="true">
                            <span>${event.title}</span>
                        </div>
                    </td>
                </tr>
            `);
            $dailyBody.append($row);
        });

    } else if (dailyEvents.length >= 2) {
        $('#dailyViewTable').removeClass('hidden');
        $('#dailyViewTableHidden').removeClass('hidden');

        // Fill first table
        const user1 = dailyEvents[0];
        $('#dailyHeader').html(`${dayName} ${day} <div>${user1.user}</div>`);
        const $dailyBody = $('#dailyBody');
        $dailyBody.empty();
        user1.events.forEach(event => {
            const $row = $(`
                <tr class="calendarRowData mt-2">
                    <td class="min-w-[500px] h-full px-4 border-0">
                        <div class="text-gray-900 dailyEventInfo bg-[#30d80fb3]" draggable="true">
                            <span>${event.title}</span>
                        </div>
                    </td>
                </tr>
            `);
            $dailyBody.append($row);
        });

        // Fill second table
        const user2 = dailyEvents[1];
        const $headerHidden = $('#dailyHeaderHidden');
        $headerHidden.html(`${dayName} ${day} <div>${user2.user}</div>`);

        const $bodyHidden = $('#dailyViewTableHidden tbody');
        $bodyHidden.empty();
        user2.events.forEach(event => {
            const $row = $(`
                <tr class="calendarRowData mt-2">
                    <td class="min-w-[500px] h-full px-4 border-0">
                        <div class="text-gray-900 dailyEventInfo bg-[#30d80fb3]" draggable="true">
                            <span>${event.title}</span>
                        </div>
                    </td>
                </tr>
            `);
            $bodyHidden.append($row);
        });
    }


    console.log("Daily view rendered for:", selectedDate.toDateString());
}

function getEventsForDate(date) {
    // Stub: replace this with your actual fetch logic
    return [
        {
            user: 'Simone Alexander',
            events: [
                { title: 'AAM - NEW REFERRAL - SIMONE ALEXANDER' },
                { title: 'Morning Briefing' }
            ]
        },
        // {
        //     user: 'John Doe',
        //     events: [
        //         { title: 'Team Sync - Project Phoenix' },
        //         { title: 'Follow-up Call with Client' }
        //     ]
        // }
    ];
}


function buildMonthlyCalendarDays(inputMonth = null, inputYear = null) {
    const today = new Date();
    const year = inputYear !== null ? inputYear : today.getFullYear();
    const month = inputMonth !== null ? inputMonth : today.getMonth();

    const weeksNeeded = getWeeksCountForCalendar(month, year);
    console.log("Weeks needed:", weeksNeeded);

    // console.log(inputMonth, ",", inputYear);
    const firstDay = new Date(year, month, 1).getDay(); // 0 = Sunday
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Get previous month info
    const prevMonth = month === 0 ? 11 : month - 1;
    const prevYear = month === 0 ? year - 1 : year;
    const daysInPrevMonth = new Date(prevYear, prevMonth + 1, 0).getDate();

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
            $td.removeClass('currentDay'); // Always clear before setting
            if (calendarId === '#sidebarCalendarBody') {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                $td.html(`<button class="sidebar-day-btn font-bold text-black" data-date="${dateStr}">${day}</button>`);
            } else {
                $td.html('<span class="font-bold">' + day + '</span>');
            }

            if (isCurrentMonth && day === today.getDate()) {
                $td.addClass(currentDayClass);
                if (calendarId === '#calendarBody') {
                    $td.append(`<div class=" text-gray-900 EventInfo bg-[#b71c1cb3]"
                                    id="event"
                                    draggable="true"
                                    title="">
                                    <span>Rony Chammai</span>
                                </div>
                                <div class=" text-gray-900 EventInfo bg-[#b71c1cb3]"
                                    id="event"
                                    draggable="true"
                                    title="">
                                    <span>Rony Chammai</span>
                                </div>
                                <div class=" text-gray-900 EventInfo bg-[#b71c1cb3]"
                                    id="event"
                                    draggable="true"
                                    title="">
                                    <span>Rony Chammai</span>
                                </div>
                                <div class=" text-gray-900 EventInfo bg-[#b71c1cb3]"
                                    id="event"
                                    draggable="true"
                                    title="">
                                    <span>Rony Chammai</span>
                                </div>
                                <div class=" text-gray-900 EventInfo bg-[#b71c1cb3]"
                                    id="event"
                                    draggable="true"
                                    title="">
                                    <span>Rony Chammai</span>
                                </div>
                                <div class=" text-gray-900 EventInfo bg-[#b71c1cb3]"
                                    id="event"
                                    draggable="true"
                                    title="">
                                    <span>Rony Chammai</span>
                                </div>
                                `)
                }
                // console.log("ping!");
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
