let currentMonth, currentYear;

$(document).ready(() => {
    console.log("Calendar initialized");

    ({ month: currentMonth, year: currentYear } = parseMonthYear($('#calendarMonthYearSelected').text()));

    generateCalendarDays();


    $(document).on('click', '#calendarDayViewOption', function() {
        $('#selectedDayWeekMonthOption').text('Day View');
    });

    $(document).on('click', '#calendarWeekViewOption', function() {
        $('#selectedDayWeekMonthOption').text('Week View');
    });

    $(document).on('click', '#calendarMonthViewOption', function() {
        $('#selectedDayWeekMonthOption').text('Month View');
    });

    $(document).on('click', '#calendarPrevMonth', function() {
        goToPreviousMonth();
    });

    $(document).on('click', '#calendarNextMonth', function() {
        goToNextMonth();
    });
});

function buildCalendarDays(inputMonth = null, inputYear = null) {
    $('#calendarBody td').removeClass('currentDay').empty();

    const today = new Date();
    const year = inputYear !== null ? inputYear : today.getFullYear();
    const month = inputMonth !== null ? inputMonth : today.getMonth();

    const weeksNeeded = getWeeksCountForCalendar(month, year);
    console.log("Weeks needed:", weeksNeeded);

    // console.log(inputMonth, ",", inputYear);
    const firstDay = new Date(year, month, 1).getDay(); // 0 = Sunday
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const $tds = $('#calendarBody td');
    let tdIndex = 0;
    let day = 1;

    // Get previous month info
    const prevMonth = month === 0 ? 11 : month - 1;
    const prevYear = month === 0 ? year - 1 : year;
    const daysInPrevMonth = new Date(prevYear, prevMonth + 1, 0).getDate();

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
        $td.html('<span class="font-bold">' + day + '</span>');

        if (isCurrentMonth && day === today.getDate()) {
            $td.addClass('currentDay');
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

    $('#calendarBody tr').each(function(index) {
        if (index < weeksNeeded) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });


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
    buildCalendarDays(currentMonth, currentYear);
}

function goToNextMonth() {
    if (currentMonth === 11) {
        currentMonth = 0;
        currentYear++;
    } else {
        currentMonth++;
    }

    updateCalendarHeader(currentMonth, currentYear);
    buildCalendarDays(currentMonth, currentYear);

}

function goToPreviousMonth() {
    if (currentMonth === 0) {
        currentMonth = 11;
        currentYear--;
    } else {
        currentMonth--;
    }

    updateCalendarHeader(currentMonth, currentYear);
    buildCalendarDays(currentMonth, currentYear);
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
}
