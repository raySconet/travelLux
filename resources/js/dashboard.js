$(document).ready(function() {
    var $expandButtons = $('.expand-btn');
    var $cards = $('.dashboard-card');
    var $grid = $('#dashboard-grid');

    $expandButtons.each(function() {
        $(this).on('click', function() {
            var $btn = $(this);
            var $card = $btn.closest('.dashboard-card');
            var isExpanded = $card.hasClass('expanded');

            $cards.each(function() {
                $(this).removeClass('expanded col-span-full hidden');
                $(this).find('.expand-btn').removeClass('fa-compress').addClass('fa-expand').attr('title', 'Fullscreen');
            });

            $grid.removeClass('lg:grid-cols-2');

            if (!isExpanded) {
                $cards.not($card).addClass('hidden');
                $card.addClass('expanded col-span-full');
                $btn.removeClass('fa-expand').addClass('fa-compress').attr('title', 'Exit Fullscreen');
                $grid.addClass('grid-cols-1');
            } else {
                $grid.addClass('lg:grid-cols-2');
            }
        });
    });

    let ajaxSilentCount = 0;

    function runSilentAjax(fn) {
        ajaxSilentCount++;
        window.disableGlobalLoader = true;

        return fn().always(function () {
            ajaxSilentCount--;

            if (ajaxSilentCount <= 0) {
                ajaxSilentCount = 0;
                window.disableGlobalLoader = false;
            }
        });
    }

    // ----------------------------------------------------------- //
    // start overall dashboard //
    // ----------------------------------------------------------- //
    $('#agents').on('change', function () {

        let agentId = $(this).val();

        runSilentAjax(() => {
            return $.get('/overallTaskDashboard/stats/' + agentId, function (data) {

                $('#high-past-due').text(data.highPriority?.past_due ?? 0);
                $('#high-due-today').text(data.highPriority?.due_today ?? 0);
                $('#high-two-weeks').text(data.highPriority?.two_weeks ?? 0);
                $('#high-thirty-days').text(data.highPriority?.thirty_days ?? 0);

                $('#medium-past-due').text(data.mediumPriority?.past_due ?? 0);
                $('#medium-due-today').text(data.mediumPriority?.due_today ?? 0);
                $('#medium-two-weeks').text(data.mediumPriority?.two_weeks ?? 0);
                $('#medium-thirty-days').text(data.mediumPriority?.thirty_days ?? 0);

                $('#low-past-due').text(data.lowPriority?.past_due ?? 0);
                $('#low-due-today').text(data.lowPriority?.due_today ?? 0);
                $('#low-two-weeks').text(data.lowPriority?.two_weeks ?? 0);
                $('#low-thirty-days').text(data.lowPriority?.thirty_days ?? 0);

                $('#all-past-due').text(data.allTasks?.past_due ?? 0);
                $('#all-due-today').text(data.allTasks?.due_today ?? 0);
                $('#all-two-weeks').text(data.allTasks?.two_weeks ?? 0);
                $('#all-thirty-days').text(data.allTasks?.thirty_days ?? 0);
            });
        });
    });

    $(document).on('click', '.task-link-overall', function(e) {
        e.preventDefault();

        let priority = $(this).data('priority');
        let period = $(this).data('period');

        let card = $(this).closest('.dashboard-card');
        let details = card.find('.task-details');

        let agentId = $('#agents').val();

        runSilentAjax(() => {
            return $.get('/overallTaskDashboard/tasks/' + priority + '/' + period,{ agent_id: agentId }, function(tasks) {

                let currentPage = 1;
                const perPage = 5;

                function renderTable() {
                    let start = (currentPage - 1) * perPage;
                    let end = start + perPage;

                    let pageTasks = tasks.slice(start, end);
                    

                    let html = `
                        <div class="mb-4 text-center">
                            <button class="close-table text-lg cursor-pointer">Go Back</button>
                        </div>

                        <table class="w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">Actions</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">Due Date</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">Customer</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]"></th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    if (pageTasks.length === 0) {
                        html += `
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500 border-t-2 border-[#dee2e6]">
                                    No data available in this table
                                </td>
                            </tr>
                        `;
                    } else {
                        pageTasks.forEach(task => {

                            let customer = '';

                            if (task.reservation && task.reservation.customer) {
                                if (task.reservation && task.reservation.customer) {

                                    let c = task.reservation.customer;

                                    customer = c.lname + ', ' + c.fname;

                                    if (c.mname) {
                                        customer += ' ' + c.mname;
                                    }
                                }
                            }

                            html += `
                                <tr>
                                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                                        <i class="far fa-check-circle complete-task cursor-pointer text-[#bdbdbd] text-base mr-2" data-id="${task.id}"></i>
                                        <i class="fa fa-trash delete-task cursor-pointer text-[#bdbdbd] text-base" data-id="${task.id}"></i>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${task.task_name}</td>
                                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${task.due_date}</td>
                                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${customer}</td>
                                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                                        <a href="/reservation-list/${task.reservation_id}">
                                            <i title="Go To Reservation" class="fas fa-tag text-[#B6844A] text-base"></i>
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    html += `
                            </tbody>
                        </table>
                    `;

                    if (tasks.length > 0) {
                        const totalPages = Math.ceil(tasks.length / perPage);

                        html += `
                            <div class="flex justify-end gap-3 mt-4">
                                <button class="prev-btn px-3 py-1 bg-gray-200 rounded cursor-pointer"
                                    ${currentPage === 1 ? 'disabled' : ''}>
                                    Prev
                                </button>

                                <span class="mt-1">
                                    Page ${currentPage} of ${totalPages}
                                </span>

                                <button class="next-btn px-3 py-1 bg-gray-200 rounded cursor-pointer"
                                    ${currentPage === totalPages ? 'disabled' : ''}>
                                    Next
                                </button>
                            </div>
                        `;
                    }

                    details.html(html);
                }

                renderTable();
                

                details.off('click', '.next-btn').on('click', '.next-btn', function () {
                    if (currentPage * perPage < tasks.length) {
                        currentPage++;
                        renderTable();
                    }
                });

                details.off('click', '.prev-btn').on('click', '.prev-btn', function () {
                    if (currentPage > 1) {
                        currentPage--;
                        renderTable();
                    }
                });
                

                card.find('.grid').hide();
                card.find('.viewAll').closest('div').hide();
                details.removeClass('hidden');

            });
        });
    });
   // ----------------------------------------------------------- //
    // end overall dashboard //
    // ----------------------------------------------------------- //

    // ----------------------------------------------------------- //
    // start my overall task dashboard //
    // ----------------------------------------------------------- //
    $(document).on('click', '.task-link', function(e) {
        e.preventDefault();

        let priority = $(this).data('priority');
        let period = $(this).data('period');

        let card = $(this).closest('.dashboard-card');
        let details = card.find('.task-details');

        runSilentAjax(() => {
            return $.get('/myOverallTaskDashboard/tasks/' + priority + '/' + period, function(tasks) {

                let currentPage = 1;
                const perPage = 5;

                function renderTable() {
                    let start = (currentPage - 1) * perPage;
                    let end = start + perPage;

                    let pageTasks = tasks.slice(start, end);
                    

                    let html = `
                        <div class="mb-4 text-center">
                            <button class="close-table text-lg cursor-pointer">Go Back</button>
                        </div>

                        <table class="w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">Actions</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">Due Date</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">Customer</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]"></th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    if (pageTasks.length === 0) {
                        html += `
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500 border-t-2 border-[#dee2e6]">
                                    No data available in this table
                                </td>
                            </tr>
                        `;
                    } else {
                        pageTasks.forEach(task => {

                            let customer = '';

                            if (task.reservation && task.reservation.customer) {
                               if (task.reservation && task.reservation.customer) {

                                    let c = task.reservation.customer;

                                    customer = c.lname + ', ' + c.fname;

                                    if (c.mname) {
                                        customer += ' ' + c.mname;
                                    }
                                }
                            }

                            html += `
                                <tr>
                                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                                        <i class="far fa-check-circle complete-task cursor-pointer text-[#bdbdbd] text-base mr-2" data-id="${task.id}"></i>
                                        <i class="fa fa-trash delete-task cursor-pointer text-[#bdbdbd] text-base" data-id="${task.id}"></i>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${task.task_name}</td>
                                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${task.due_date}</td>
                                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${customer}</td>
                                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                                        <a href="/reservation-list/${task.reservation_id}">
                                            <i title="Go To Reservation" class="fas fa-tag text-[#B6844A] text-base"></i>
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    html += `
                            </tbody>
                        </table>
                    `;

                    if (tasks.length > 0) {
                        const totalPages = Math.ceil(tasks.length / perPage);

                        html += `
                            <div class="flex justify-end gap-3 mt-4">
                                <button class="prev-btn px-3 py-1 bg-gray-200 rounded cursor-pointer"
                                    ${currentPage === 1 ? 'disabled' : ''}>
                                    Prev
                                </button>

                                <span class="mt-1">
                                    Page ${currentPage} of ${totalPages}
                                </span>

                                <button class="next-btn px-3 py-1 bg-gray-200 rounded cursor-pointer"
                                    ${currentPage === totalPages ? 'disabled' : ''}>
                                    Next
                                </button>
                            </div>
                        `;
                    }

                    details.html(html);
                }

                renderTable();
                

                details.off('click', '.next-btn').on('click', '.next-btn', function () {
                    if (currentPage * perPage < tasks.length) {
                        currentPage++;
                        renderTable();
                    }
                });

                details.off('click', '.prev-btn').on('click', '.prev-btn', function () {
                    if (currentPage > 1) {
                        currentPage--;
                        renderTable();
                    }
                });
                

                card.find('.grid').hide();
                card.find('.viewAll').closest('div').hide();
                details.removeClass('hidden');

            });
        });
    });

    $(document).on('click', '.close-table', function() {

        let card = $(this).closest('.dashboard-card');

        card.find('.task-details').addClass('hidden').empty();
        card.find('.grid').show();
        card.find('.viewAll').closest('div').show();
    });

    $(document).on('click', '.complete-task', function () {
        let taskId = $(this).data('id');

        $.post('/tasks/' + taskId + '/complete-only', {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }, function () {
            location.reload(); 
        });
    });

    let deleteTaskId = null;

    $(document).on('click', '.delete-task', function () {
        deleteTaskId = $(this).data('id');
        $('#deleteModal').removeClass('hidden');
    });

    $(document).on('click', '#confirmDeleteBtn', function () {
        if (!deleteTaskId) return;

        $.ajax({
            url: '/myOverallDashboard/tasks/' + deleteTaskId,
            type: 'POST',
            data: {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                _method: 'DELETE'
            },
            success: function () {
                $('#deleteModal').addClass('hidden');
                deleteTaskId = null;

                location.reload(); 
            }
        });
    });
    // ----------------------------------------------------------- //
    // end my overall task dashboard //
    // ----------------------------------------------------------- //

    // ----------------------------------------------------------- //
    // start agent dashboard //
    // ----------------------------------------------------------- //

    // start upcoming reservations(90 days)
    $(document).on('click', '.upcoming-link', function(e) {
        e.preventDefault();

        let card = $(this).closest('.dashboard-card');
        let details = card.find('.upcoming-details');

        runSilentAjax(() => {
            return $.get('/agentDashboard/upcoming-reservations', function(response) {

                let reservations = response.reservations;

                let currentPage = 1;
                const perPage = 5;

                function renderTable() {

                    let start = (currentPage - 1) * perPage;
                    let end = start + perPage;

                    let pageData = reservations.slice(start, end);

                    let html = `
                        <div class="mb-4 text-center">
                            <button class="close-upcoming text-lg cursor-pointer">Go Back</button>
                        </div>

                        <table class="w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">Checkin Date</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">Customer</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">Reservation Number</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]"></th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    if (pageData.length === 0) {
                        html += `
                            <tr>
                                <td colspan="4" class="text-center text-gray-400 py-6">
                                    No data available in this table
                                </td>
                            </tr>
                        `;
                    } else {
                        pageData.forEach(r => {
                            html += `
                                <tr>
                                    <td class="px-4 py-2 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${r.checkin_date}</td>
                                    <td class="px-4 py-2 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${r.customer}</td>
                                    <td class="px-4 py-2 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${r.reservation_number}</td>
                                    <td class="px-4 py-2 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                                        <a href="/reservation-list/${r.id}">
                                            <i title="Go To Reservation" class="fas fa-tag text-[#B6844A] text-base"></i>
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    html += `</tbody></table>`;

                    if (reservations.length > 0) {
                        let totalPages = Math.ceil(reservations.length / perPage);

                        html += `
                            <div class="flex justify-end gap-3 mt-4">
                                <button class="prev-upcoming px-3 py-1 bg-gray-200 rounded"
                                    ${currentPage === 1 ? 'disabled' : ''}>
                                    Prev
                                </button>

                                <span class="mt-1">Page ${currentPage} of ${totalPages}</span>

                                <button class="next-upcoming px-3 py-1 bg-gray-200 rounded"
                                    ${currentPage === totalPages ? 'disabled' : ''}>
                                    Next
                                </button>
                            </div>
                        `;
                    }

                    details.html(html);
                }

                renderTable();

                details.off('click', '.next-upcoming').on('click', '.next-upcoming', function () {
                    if (currentPage * perPage < reservations.length) {
                        currentPage++;
                        renderTable();
                    }
                });

                details.off('click', '.prev-upcoming').on('click', '.prev-upcoming', function () {
                    if (currentPage > 1) {
                        currentPage--;
                        renderTable();
                    }
                });

                $(document).on('click', '.close-upcoming', function () {
                    details.addClass('hidden').empty();

                    card.find('#upcomingReservationsContainer').show();
                });

                card.find('#upcomingReservationsContainer').hide();
                details.removeClass('hidden');
            });

        });
    });

    $(document).ready(function () {

        if (!$('#upcomingReservationsChartContainer').length) {
            return;
        }

        runSilentAjax(() => {
            return $.get('/agentDashboard/upcoming-reservations', function(response) {

                if (response.count === 0) {
                    $('.no-upcoming').removeClass('hidden');
                    return;
                }

                $('.upcoming-link').removeClass('hidden');

                Highcharts.chart('upcomingReservationsChartContainer', {
                    chart: {
                        type: 'xrange'
                    },
                    title: {
                        text: null
                    },
                    legend: {
                        enabled: false
                    },
                    credits: {
                        enabled: false
                    },
                    xAxis: {
                        type: 'datetime'
                    },
                    yAxis: {
                        categories: response.categories,
                        reversed: true
                    },
                    series: [{
                        borderColor: 'gray',
                        pointWidth: 20,
                        data: response.chartData
                    }]
                });
            });
        });

    });
    // end upcoming reservations(90 days)

    // start total sales
    if ($('#totalSalesChartContainer').length) {

        runSilentAjax(() => {
            return $.get('/agentDashboard/total-sales', function(totalSales) {

                Highcharts.chart('totalSalesChartContainer', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        type: 'category'
                    },
                    yAxis: {
                        title: {
                            text: 'Total Sales'
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        name: 'Sales',
                        colorByPoint: false,
                        data: totalSales
                    }]
                });

            });
        });

    }
    // end total sales

    // start recent commissions
    function loadRecentCommissions(range = '30days')
    {
        runSilentAjax(() => {
            return $.get('/agentDashboard/recent-commissions', {range: range}, function(response) {

                $('.agentDashboardTotalAgentCommissionAmount').text('$' + response.totalAgentCommission);

                let rows = response.rows;

                let currentPage = 1;
                let perPage = 5;

                function renderTable()
                {
                    let start = (currentPage - 1) * perPage;
                    let end = start + perPage;

                    let pageData = rows.slice(start, end);

                    let html = '';

                    if (pageData.length === 0) {

                        html = `
                            <tr>
                                <td colspan="4" class="text-center text-gray-400 py-6">
                                    No data available in table
                                </td>
                            </tr>
                        `;

                    } else {

                        pageData.forEach(r => {

                            html += `
                                <tr>
                                    <td class="px-4 py-2 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${r.check_date}</td>
                                    <td class="px-3 py-2 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${r.reservation_number}</td>
                                    <td class="px-3 py-2 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">${r.customer_name}</td>
                                    <td class="px-3 py-2 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">$${r.agent_commission}</td>
                                </tr>
                            `;
                        });
                    }

                    $('#recentCommissionsBody').html(html);

                    renderPagination();
                }

                function renderPagination()
                {
                    let totalPages = Math.ceil(rows.length / perPage);

                    if (totalPages <= 1) {
                        $('#recentCommissionsPagination').html('');
                        return;
                    }

                    $('#recentCommissionsPagination').html(`
                        <button class="commission-prev px-3 py-1 bg-gray-200 rounded"
                            ${currentPage === 1 ? 'disabled' : ''}>
                            Prev
                        </button>

                        <span class="mt-1">
                            Page ${currentPage} of ${totalPages}
                        </span>

                        <button class="commission-next px-3 py-1 bg-gray-200 rounded"
                            ${currentPage === totalPages ? 'disabled' : ''}>
                            Next
                        </button>
                    `);
                }

                renderTable();

                $(document).off('click', '.commission-next').on('click', '.commission-next', function() {
                    currentPage++;
                    renderTable();
                });

                $(document).off('click', '.commission-prev').on('click', '.commission-prev', function() {
                    currentPage--;
                    renderTable();
                });
                

            });
        });
    }

    $(document).ready(function() {

        loadRecentCommissions();

        $('#commissionRange').on('change', function() {

            loadRecentCommissions($(this).val());

        });
    });

    $(document).on('change', '#commissionRange', function () {
        showLoaderOnSubmit();
        loadRecentCommissions($(this).val());
    });
    // end recent commissions

    // start owners dashboard total sales
    if ($('#ownersDashboardAgencyTotalSalesChartContainer').length) {

        runSilentAjax(() => {
            return $.get('/ownersDashboard/agency-total-sales', function (data) {

                function renderChart(containerId) {
                    Highcharts.chart(containerId, {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: null
                        },
                        xAxis: {
                            type: 'category'
                        },
                        yAxis: {
                            title: {
                                text: 'Total Sales'
                            }
                        },
                        legend: {
                            enabled: false
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                            name: 'Sales',
                            colorByPoint: false,
                            data: data
                        }]
                    });
                }

                renderChart('ownersDashboardAgencyTotalSalesChartContainer');
                renderChart('ownersDashboardAgencyTotalSalesChartContainerFullScreen');
            });
        });

    }
    // end owners dashboard total sales

    // ----------------------------------------------------------- //
    // end agent dashboard //
    // ----------------------------------------------------------- //

    // ----------------------------------------------------------- //
    // start owner dashboard //
    // ----------------------------------------------------------- //

    // start agents birthday
    if ($('#birthday-today').length) {

        runSilentAjax(() => {
            return $.get('/ownersDashboard/agent-birthdays-counts', function(data){

                $('#birthday-today').text(data.today);
                $('#birthday-week').text(data.week);
                $('#birthday-month').text(data.month);
                $('#birthday-sixmonths').text(data.sixMonths);

            });
        });

    }

    $(document).on('click', '.birthday-link', function(e){

        e.preventDefault();

        let range = $(this).data('range');

        let card = $(this).closest('.dashboard-card');
        let details = card.find('.birthday-details');

        runSilentAjax(() => {

            return $.get('/ownersDashboard/agent-birthdays-details/' + range, function(rows){

                let currentPage = 1;
                const perPage = 5;

                function renderBirthdays() {

                    let start = (currentPage - 1) * perPage;
                    let end = start + perPage;

                    let pageRows = rows.slice(start, end);

                    let html = `
                        <div class="mb-4 text-center">
                            <button class="close-birthdays text-lg cursor-pointer">
                                Go Back
                            </button>
                        </div>

                        <div class="flex justify-end mb-4">
                            <input type="text" class="birthday-search border-b outline-none" placeholder="Quick Search">
                        </div>
                    `;

                    if (pageRows.length === 0) {

                        html += `
                            <div class="text-center text-gray-500 mt-8">
                                No Birthdays.
                            </div>
                        `;

                    } else {

                        pageRows.forEach(r => {

                            html += `
                                <div class="bg-[#fff] shadow mb-3 p-4 birthday-row" style="box-shadow: 0 5px 5px -11px rgba(0,0,0,.2),0 1px 4px -22px rgba(0,0,0,.14),0 3px 14px 2px rgba(0,0,0,.12);">

                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-user"></i>
                                        <span class="birthday-name">${r.name}</span>
                                    </div>

                                    <div class="ml-8">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span class="birthday-date">${r.date}</span>
                                    </div>

                                </div>
                            `;
                        });
                    }

                    if (rows.length > 5) {

                        const totalPages = Math.ceil(rows.length / perPage);

                        html += `
                            <div class="flex justify-end gap-3 mt-4">
                                <button class="birthday-prev px-3 py-1 bg-gray-200 rounded cursor-pointer"
                                    ${currentPage === 1 ? 'disabled' : ''}>
                                    Prev
                                </button>

                                <span class="mt-1">
                                    Page ${currentPage} of ${totalPages}
                                </span>

                                <button class="birthday-next px-3 py-1 bg-gray-200 rounded cursor-pointer"
                                    ${currentPage === totalPages ? 'disabled' : ''}>
                                    Next
                                </button>
                            </div>
                        `;
                    }

                    details.html(html);
                }

                renderBirthdays();

                details.off('click', '.birthday-next').on('click', '.birthday-next', function () {
                    if (currentPage < Math.ceil(rows.length / perPage)) {
                        currentPage++;
                        renderBirthdays();
                    }
                });

                details.off('click', '.birthday-prev').on('click', '.birthday-prev', function () {
                    if (currentPage > 1) {
                        currentPage--;
                        renderBirthdays();
                    }
                });

                card.find('.owners-birthday-grid').hide();
                card.find('.birthday-link[data-range="all"]').closest('.text-center').hide();
                details.removeClass('hidden');
            });

        });

    });

    $(document).on('click', '.close-birthdays', function(){

        let card = $(this).closest('.dashboard-card');

        card.find('.birthday-details').addClass('hidden').empty();
        card.find('.owners-birthday-grid').show();
        card.find('.birthday-link[data-range="all"]').closest('.text-center').show();
    });
    // end agents birthday

    // start customers birthday
    if ($('#customer-birthday-today').length) {

        runSilentAjax(() => {

            return $.get('/ownersDashboard/customer-birthdays-counts', function(data){

                $('#customer-birthday-today').text(data.today);
                $('#customer-birthday-week').text(data.week);
                $('#customer-birthday-month').text(data.month);
                $('#customer-birthday-sixmonths').text(data.sixMonths);

            });

        });

    }

    $(document).on('click', '.customer-birthday-link', function(e){

        e.preventDefault();

        let range = $(this).data('range');

        let card = $(this).closest('.dashboard-card');
        let details = card.find('.customer-birthday-details');

        runSilentAjax(() => {

            return $.get('/ownersDashboard/customer-birthdays-details/' + range, function(rows){

                let currentPage = 1;
                const perPage = 5;

                let filteredRows = [...rows];
                let currentSearch = '';

                function renderCustomerBirthdays() {

                    let start = (currentPage - 1) * perPage;
                    let end = start + perPage;

                    let pageRows = filteredRows.slice(start, end);

                    let html = `
                        <div class="mb-4 text-center">
                            <button class="close-customer-birthdays text-lg cursor-pointer">
                                Go Back
                            </button>
                        </div>

                        <div class="flex justify-end mb-4">
                            <input type="text" class="quickSearchInput border-b outline-none" placeholder="Quick Search" value="${currentSearch}">
                        </div>
                    `;

                    if (pageRows.length === 0) {

                        html += `
                            <div class="text-center text-gray-500 mt-8">
                                No Birthdays.
                            </div>
                        `;

                    } else {

                        pageRows.forEach(r => {

                            html += `
                                <div class="bg-[#fff] shadow mb-3 p-4" style="box-shadow: 0 5px 5px -11px rgba(0,0,0,.2),0 1px 4px -22px rgba(0,0,0,.14),0 3px 14px 2px rgba(0,0,0,.12);">

                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-user"></i>
                                        <span class="customer-birthday-name">
                                            ${r.name}
                                        </span>
                                    </div>

                                    <div class="ml-8">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span class="customer-birthday-date">
                                            ${r.date}
                                        </span>
                                    </div>

                                </div>
                            `;
                        });
                    }

                    if (filteredRows.length > 5) {

                        const totalPages = Math.ceil(filteredRows.length / perPage);

                        html += `
                            <div class="flex justify-end gap-3 mt-4">
                                <button
                                    class="customer-birthday-prev px-3 py-1 bg-gray-200 rounded cursor-pointer"
                                    ${currentPage === 1 ? 'disabled' : ''}>
                                    Prev
                                </button>

                                <span class="mt-1">
                                    Page ${currentPage} of ${totalPages}
                                </span>

                                <button
                                    class="customer-birthday-next px-3 py-1 bg-gray-200 rounded cursor-pointer"
                                    ${currentPage === totalPages ? 'disabled' : ''}>
                                    Next
                                </button>
                            </div>
                        `;
                    }

                    details.html(html);
                }

                renderCustomerBirthdays();

                details.off('click', '.customer-birthday-next').on('click', '.customer-birthday-next', function(){
                    if (currentPage < Math.ceil(filteredRows.length / perPage)) {
                        currentPage++;
                        renderCustomerBirthdays();
                    }
                });

                details.off('click', '.customer-birthday-prev').on('click', '.customer-birthday-prev', function(){
                    if (currentPage > 1) {
                        currentPage--;
                        renderCustomerBirthdays();
                    }
                });

                details.off('keyup', '.quickSearchInput').on('keyup', '.quickSearchInput', function(){
                    currentSearch = $(this).val().toLowerCase();

                    filteredRows = rows.filter(function(r){

                        return ((r.name && r.name.toLowerCase().includes(currentSearch)) || (r.date && r.date.toLowerCase().includes(currentSearch)));
                    });

                    currentPage = 1;

                    renderCustomerBirthdays();
                });

                card.find('.customer-birthday-grid').hide();
                card.find('.customer-birthday-link[data-range="all"]').closest('.text-center').hide();
                details.removeClass('hidden');
            });

        });

    });

    $(document).on('click', '.close-customer-birthdays', function(){

        let card = $(this).closest('.dashboard-card');

        card.find('.customer-birthday-details').addClass('hidden').empty();
        card.find('.customer-birthday-grid').show();
        card.find('.customer-birthday-link[data-range="all"]').closest('.text-center').show();
    });

    // end customers birthday

    // start customer anniversaries
    if ($('#customer-anniversary-today').length) {

        runSilentAjax(() => {

            return $.get('/ownersDashboard/customer-anniversary-counts', function(data){

                $('#customer-anniversary-today').text(data.today);
                $('#customer-anniversary-week').text(data.week);
                $('#customer-anniversary-month').text(data.month);
                $('#customer-anniversary-sixmonths').text(data.sixMonths);

            });

        });

    }

    $(document).on('click', '.customer-anniversary-link', function(e){

        e.preventDefault();

        let range = $(this).data('range');

        let card = $(this).closest('.dashboard-card');
        let details = card.find('.customer-anniversary-details');

        runSilentAjax(() => {

            return $.get('/ownersDashboard/customer-anniversary-details/' + range, function(rows){

                let currentPage = 1;
                const perPage = 5;

                let filteredRows = [...rows];
                let currentSearch = '';

                function render() {

                    let start = (currentPage - 1) * perPage;
                    let end = start + perPage;

                    let pageRows = filteredRows.slice(start, end);

                    let html = `
                        <div class="mb-4 text-center">
                            <button class="close-customer-anniversary text-lg cursor-pointer">
                                Go Back
                            </button>
                        </div>

                        <div class="flex justify-end mb-4">
                            <input type="text" class="anniversary-search border-b outline-none" placeholder="Quick Search" value="${currentSearch}">
                        </div>
                    `;

                    if (pageRows.length === 0) {

                        html += `
                            <div class="text-center text-gray-500 mt-8">
                                No Anniversaries.
                            </div>
                        `;
                    } else {

                        pageRows.forEach(r => {

                            html += `
                                <div class="bg-[#fff] shadow mb-3 p-4" style="box-shadow: 0 5px 5px -11px rgba(0,0,0,.2),0 1px 4px -22px rgba(0,0,0,.14),0 3px 14px 2px rgba(0,0,0,.12);">

                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-user"></i>
                                        <span class="customer-anniversary-name">${r.name}</span>
                                    </div>

                                    <div class="ml-8">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span class="customer-anniversary-date">${r.date}</span>
                                    </div>

                                </div>
                            `;
                        });
                    }

                    if (filteredRows.length > 5) {

                        const totalPages = Math.ceil(filteredRows.length / perPage);

                        html += `
                            <div class="flex justify-end gap-3 mt-4">
                                <button class="customer-anniversary-prev px-3 py-1 bg-gray-200 rounded"
                                    ${currentPage === 1 ? 'disabled' : ''}>
                                    Prev
                                </button>

                                <span class="mt-1">
                                    Page ${currentPage} of ${totalPages}
                                </span>

                                <button class="customer-anniversary-next px-3 py-1 bg-gray-200 rounded"
                                    ${currentPage === totalPages ? 'disabled' : ''}>
                                    Next
                                </button>
                            </div>
                        `;
                    }

                    details.html(html);
                }

                render();

                details.off('click', '.customer-anniversary-next').on('click', '.customer-anniversary-next', function(){
                    if (currentPage < Math.ceil(filteredRows.length / perPage)) {
                        currentPage++;
                        render();
                    }
                });

                details.off('click', '.customer-anniversary-prev').on('click', '.customer-anniversary-prev', function(){
                    if (currentPage > 1) {
                        currentPage--;
                        render();
                    }
                });

                details.off('keyup', '.anniversary-search').on('keyup', '.anniversary-search', function(){
                    currentSearch = $(this).val().toLowerCase();

                    filteredRows = rows.filter(r =>
                        (r.name && r.name.toLowerCase().includes(currentSearch)) ||
                        (r.date && r.date.toLowerCase().includes(currentSearch))
                    );

                    currentPage = 1;
                    render();
                });

                card.find('.customer-anniversary-grid').hide();
                card.find('.customer-anniversary-link[data-range="all"]').closest('.text-center').hide();

                details.removeClass('hidden');
            });

        });

    });

    $(document).on('click', '.close-customer-anniversary', function(){

        let card = $(this).closest('.dashboard-card');

        card.find('.customer-anniversary-details').addClass('hidden').empty();
        card.find('.customer-anniversary-grid').show();
        card.find('.customer-anniversary-link[data-range="all"]').closest('.text-center').show();
    });
    // end customer anniversaries

    // ----------------------------------------------------------- //
    // end owner dashboard //
    // ----------------------------------------------------------- //
});
