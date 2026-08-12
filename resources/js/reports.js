$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    window.openEditReservationModal = function() {
        $('#editReservationModal').removeClass('hidden');
    }

    window.closeEditReservationModal = function() {
        $('#editReservationModal').addClass('hidden');
    }

    window.openReportDateRangeModal = function() {
        $('#reportRangeModal').removeClass('hidden');
    }

    window.closeReportDateRangeModal = function() {
        $('#reportRangeModal').addClass('hidden');
    }

    if (window.location.pathname === '/1099Report') {
        openReportDateRangeModal();
    }

    $('#reportRange').on('change', function () {

        const today = new Date();

        let begin = new Date(today);
        let end = new Date(today);

        switch ($(this).val()) {

            case '-30Days':
                begin.setDate(today.getDate() - 30);
                break
            ;

            case '-45Days':
                begin.setDate(today.getDate() - 45);
                break
            ;

            case '-90Days':
                begin.setDate(today.getDate() - 90);
                break
            ;

            case '-180Days':
                begin.setDate(today.getDate() - 180);
                break
            ;

            case '+90':
                end.setDate(today.getDate() + 90);
                break
            ;

            case 'currentYear':
                begin = new Date(today.getFullYear(),0,1);
                end = new Date(today.getFullYear(),11,31);
                break
            ;

            case 'lastYear':
                begin = new Date(today.getFullYear()-1,0,1);
                end = new Date(today.getFullYear()-1,11,31);
                break
            ;

            case 'nextYear':
                begin = new Date(today.getFullYear()+1,0,1);
                end = new Date(today.getFullYear()+1,11,31);
                break
            ;

            case 'currentQuarter':

                let q = Math.floor(today.getMonth()/3);

                begin = new Date(today.getFullYear(), q*3,1);

                end = new Date(today.getFullYear(), q*3+3,0);

                break
            ;

            case 'lastQuarter':

                let lq = Math.floor(today.getMonth()/3)-1;

                let year = today.getFullYear();

                if(lq < 0){
                    lq = 3;
                    year--;
                }

                begin = new Date(year,lq*3,1);

                end = new Date(year,lq*3+3,0);

                break
            ;

            case 'nextQuarter':

                let nq = Math.floor(today.getMonth()/3)+1;

                let nyear = today.getFullYear();

                if(nq > 3){
                    nq = 0;
                    nyear++;
                }

                begin = new Date(nyear,nq*3,1);

                end = new Date(nyear,nq*3+3,0);

                break
            ;

            default:
                return;
        }

        $('#beginDate').val(begin.toISOString().split('T')[0]);

        $('#endDate').val(end.toISOString().split('T')[0]);

    });

    function formatUSDate(date) {

        if (!date) return '';

        const parts = date.split('-');

        return parts[1] + '/' + parts[2] + '/' + parts[0];
    }

    // start vendor report
    $(function () {

        if (window.location.pathname !== "/vendorReport") {
            return;
        }

        openReportDateRangeModal();

        $('#VendorReportBtn').on('click', function () {
            loadVendorReport();
        });

        $('#vendorStatus').on('change', function () {
            loadVendorReport();
        });

    });


    function loadVendorReport() {

        let beginDate = $('#beginDate').val();
        let endDate = $('#endDate').val();
        let status = $('#vendorStatus').val();

        $.ajax({

            url: '/vendorReport/load',
            type: 'POST',
            data: {
                beginDate: beginDate,
                endDate: endDate,
                status: status
            },

            beforeSend() {
                showLoader();
            },

            success: function (html) {

                $('#vendorReportContainer').html(html);
                $('#selectedReportDates').text(formatUSDate(beginDate) + " - " + formatUSDate(endDate));
                closeReportDateRangeModal();
            },

            complete() {
                hideLoader();
            }

        });

    }
    // end vendor report

    // start 1099 report
    $('#1099ReportBtn').on('click', function () {
        load1099Report();
    });

    function load1099Report() {

        $.ajax({
            url: '/1099Report/load',
            type: 'POST',
            data: {
                beginDate: $('#beginDate').val(),
                endDate: $('#endDate').val(),
            },

            beforeSend() {

                showLoader();
            },

            success(response) {

                if (!response.success) {

                    $('#report1099TableBody').html(
                        `<tr>
                            <td colspan="8" class="text-center py-5">
                                ${response.message}
                            </td>
                        </tr>`
                    );

                    $('#totalPaid').text('$0.00');

                    $('#selectedReportDates').text(formatUSDate($('#beginDate').val()) + ' - ' + formatUSDate($('#endDate').val()));

                    closeReportDateRangeModal();

                    return;
                }

                let html = '';

                response.data.forEach(function(agent){

                    html += `
                        <tr>
                            <td class="px-4 py-3 border-b border-[#dee2e6]">${agent.agent}</td>
                            <td class="px-4 py-3 border-b border-[#dee2e6]">${agent.address}</td>
                            <td class="px-4 py-3 border-b border-[#dee2e6]">${agent.phone ?? ''}</td>
                            <td class="px-4 py-3 border-b border-[#dee2e6]">${agent.birth_date}</td>
                            <td class="px-4 py-3 border-b border-[#dee2e6]">${agent.ssn ?? ''}</td>
                            <td class="px-4 py-3 border-b border-[#dee2e6]">${agent.ein ?? ''}</td>
                            <td class="px-4 py-3 border-b border-[#dee2e6]">${agent.postal_code ?? ''}</td>
                            <td class="px-4 py-3 border-b border-[#dee2e6]">$${agent.commission_paid}</td>
                        </tr>
                    `;
                });

                $('#report1099TableBody').html(html);

                $('#totalPaid').text('$' + response.totalPaid);

                $('#selectedReportDates').text(formatUSDate($('#beginDate').val()) + ' - ' + formatUSDate($('#endDate').val()));

                closeReportDateRangeModal();
            },

            error(xhr) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                alert('Failed To Load Report');
            },

            complete() {

                hideLoader();
            }

        });

    }
    // end 1099 report

    // start check history report
    function loadCheckHistoryReport(){

        let beginDate = $('#beginDate').val();
        let endDate = $('#endDate').val();


        $.ajax({

            url: "/checkHistoryReport/load",
            type:"POST",
            data:{
                beginDate:beginDate,
                endDate:endDate
            },


            beforeSend:function(){

                $("#checkHistoryRecords").html(
                    `
                    <div class="text-center p-5 text-gray-500">
                        Loading...
                    </div>
                    `
                );

            },


            success:function(response){

                $("#checkHistoryRecords").html(response);
                $("#selectedReportDates").html(formatUSDate(beginDate) + " - " + formatUSDate(endDate));
                closeReportDateRangeModal();

            },


            error:function(){

                $("#checkHistoryRecords").html(
                    `
                    <div class="text-center p-5 text-red-500">
                        Failed to load report.
                    </div>
                    `
                );

            }

        });


    }

    $(document).on("click","#checkHistoryReportBtn", function(){

        loadCheckHistoryReport();

    });


    $(function () {

        if (window.location.pathname !== "/checkHistoryReport") {
            return;
        }

        openReportDateRangeModal();

    });

    $(document).on("click",".checkHistoryTableRecord",function(){

        let id = $(this).data("id");

        let detail = $(`.checkHistoryDetails[data-detail="${id}"]`);

        detail.toggleClass("hidden");


        let icon = $(this).find(".checkHistoryChevron");


        if(detail.hasClass("hidden")){

            icon.removeClass("fa-chevron-up").addClass("fa-chevron-down");

        }
        else{
            icon.removeClass("fa-chevron-down").addClass("fa-chevron-up");
        }

    });

    $(document).on("keyup", "#checkHistorySearch", function () {

        let value = $(this).val().toLowerCase();

        $(".checkHistoryCard").each(function () {

            let text = $(this).text().toLowerCase();

            $(this).toggle(text.indexOf(value) > -1);

        });

    });

    $(document).on("click", ".undoPaymentBtn", function () {

        let reservationId = $(this).data("reservation");
        let agentId = $(this).data("agent");
        let checkNumber = $(this).data("check");

        undoPayment(reservationId, agentId, checkNumber);

    });


    function undoPayment(reservationId, agentId, checkNumber)
    {

        $.ajax({

            url: "/checkHistoryReport/undoPayment",
            type: "POST",
            data: {
                reservationId: reservationId,
                agentId: agentId,
                checkNumber: checkNumber
            },

            beforeSend: function () {
                showLoader();
            },

            success: function (response) {

                if (response.success) {

                    loadCheckHistoryReport();

                } else {

                    alert(response.message);

                }

            },

            error: function () {

                alert("Failed to undo payment.");

            },

            complete: function () {

                hideLoader();

            }

        });

    }
    // end check history report

    // start current checks report
    $(function () {
        if ($('#currentChecksContainer').length) {
            loadCurrentChecks();
        }
    });

    function loadCurrentChecks() {
        $.ajax({
            url: '/currentChecksReport/load',
            type: 'GET',
            success: function (html) {
                $('#currentChecksContainer').html(html);
            }
        });
    }
    // end current checks report
    
    // start commission claim report
    function showSuccessOverlay(callback = null)
    {
        $('#copySuccessOverlay').removeClass('hidden').addClass('flex');

        setTimeout(function () {
            $('#copySuccessOverlay').addClass('hidden').removeClass('flex');
            if (callback) {
                callback();
            }
        }, 1500);
    }

    $('#searchReservationBtn').click(function () {

        let reservationNumber = $('#reservationNumberSearch').val().trim();

        if (reservationNumber === '') {
            alert('Reservation Number is required.');
            return;
        }

        $.ajax({

            url: '/commissionClaimReport/search',
            type: 'POST',
            data: {
                reservationNumber: reservationNumber,
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend() {
                showLoader();
            },

            success(response) {

                let tbody = $('#commissionClaimTableBody');

                tbody.empty();

                if (!response.success) {
                    alert(response.message);
                    return;
                }

                response.data.forEach(function (reservation) {

                    tbody.append(`
                        <tr class="mt-2 p-2">
                            <td>
                                <button class="claimReservationBtn space-x-2 bg-[#B6844A] text-white font-semibold py-2 px-7 rounded cursor-pointer border border-transparent hover:bg-white hover:border-[#B6844A] hover:text-[#B6844A] transition-all duration-200 mt-2" data-id="${reservation.id}">
                                    <i class='fas fa-check-square'></i>
                                    Claim
                                </button>
                            </td>
                            <td>${reservation.reservation_number}</td>
                            <td>${reservation.customer_name}</td>
                            <td>${reservation.product_name}</td>
                            <td>$${reservation.agent_commission}</td>

                        </tr>
                    `);

                });

            },

            error(xhr) {

                if (xhr.status === 422) {
                    alert(xhr.responseJSON.message);
                } else {
                    alert('Failed to load reservations.');
                }

            },

            complete() {
                hideLoader();
            }

        });

    });

    $(document).on('click', '.claimReservationBtn', function () {

        const reservationId = $(this).data('id');

        $.ajax({

            url: '/commissionClaimReport/claim',
            type: 'POST',
            data: {
                reservationId: reservationId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend() {
                showLoader();
            },

            success(response) {

                if (!response.success) {
                    alert(response.message);
                    return;
                }

                $(`button[data-id="${reservationId}"]`).closest('tr').remove();
                showSuccessOverlay();

            },

            error(xhr) {

                if (xhr.status === 422) {
                    alert(xhr.responseJSON.message);
                } else {
                    alert('Failed to claim the commission.');
                }

            },

            complete() {
                hideLoader();
            }

        });

    });
    // end commission claim report
});
