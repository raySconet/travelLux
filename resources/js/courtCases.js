$(document).ready(() => {
    getSectionsAndTodos();

    flatpickr(".datetimepicker", {
        enableTime: false,
        dateFormat: "m-d-Y",
        defaultDate: new Date(),
    });
    let clientCounter = 2;
    // Add Edit Buttons
    $(document).on('click', '.addClientInfoButton', function () {
        clientCounter++;

        // Clone client container
        let container = $(this).parent().parent();
        let newClient = container.clone();

        // Clear inputs
        newClient.find('input, textarea, select').val('');

        // Update button appearance
        $(this)
            .removeClass("addClientInfoButton bg-[#14548d]")
            .addClass("removeClientInfoButton bg-[#a51a1a]")
            .children()
            .removeClass("fa-plus")
            .addClass("fa-minus");

        // Add a unique data-id to pair client & treating
        newClient.attr('data-client', clientCounter);

        // Append client to list
        $(".appendDuplicates").append(newClient);

        // Clone treating section
        let newTreating = $(".treatingToDuplicate").first().clone();
        newTreating.removeAttr('id'); // avoid duplicate IDs
        newTreating.attr('data-client', clientCounter);
        newTreating.find('input, textarea, select').val('');

        // Set treating name (if you have a heading or label)
        newTreating.find('.treatingName p').text(`${clientCounter} Treating Chart`);

        // Append treating section
        $(".treatingAppendDuplicates").append(newTreating);


        // Clone treating section
        let newNegotiating = $(".negotiationToDuplicate").first().clone();
        newNegotiating.removeAttr('id'); // avoid duplicate IDs
        newNegotiating.attr('data-client', clientCounter);
        newNegotiating.find('input, textarea, select').val('');

        // Set treating name (if you have a heading or label)
        newNegotiating.find('.negotiationName p').text(`${clientCounter} Negotiation Chart`);
        // Append Negotiation section
        $(".negotiationAppendDuplicates").append(newNegotiating);

        // Clone treating section
        let newAffidavite = $(".affidavitToDuplicate").first().clone();
        newAffidavite.removeAttr('id'); // avoid duplicate IDs
        newAffidavite.attr('data-client', clientCounter);
        newAffidavite.find('input, textarea, select').val('');

        // Set treating name (if you have a heading or label)
        newAffidavite.find('.affidavitName p').text(`${clientCounter} Affidavit Chart`);
        // Append Negotiation section
        $(".affidavitAppendDuplicates").append(newAffidavite);
    });


    // 🗑️ Remove Client + its Treating
    $(document).on('click', '.removeClientInfoButton', function () {
        let clientContainer = $(this).parent().parent();
        let clientId = clientContainer.data('client');

        // Remove client
        clientContainer.remove();
        // Remove matching treating
        $(`.treatingAppendDuplicates [data-client="${clientId}"]`).remove();

        $(`.negotiationAppendDuplicates [data-client="${clientId}"]`).remove();

        $(`.affidavitAppendDuplicates [data-client="${clientId}"]`).remove();
    });


    // 🔄 When client name changes, update treating name
    $(document).on('input', '.clientNameInput', function () {
        let clientId = $(this).parent().parent().attr('data-client');
        let clientName = $(this).val();

        // Update the treating label
        $(`.treatingAppendDuplicates [data-client="${clientId}"] .treatingName p`)
            .text(`${clientName} Treating Chart`);

        $(`.negotiationAppendDuplicates [data-client="${clientId}"] .negotiationName p`)
            .text(`${clientName} Negotiation Chart`);

        $(`.affidavitAppendDuplicates [data-client="${clientId}"] .affidavitName p`)
            .text(`${clientName} Negotiation Chart`);
    });

    $(document).on('click', '.addClientInfoButtonFor3p', function() {

        let container = $(this).parent().parent();
        if (container.find('.hidden').length) {
            container.find('.hidden').removeClass('hidden');
        } else {
             let newItem = container.clone(); // duplicate first
            $(this).removeClass("addClientInfoButtonFor3p bg-[#14548d]")
                .addClass("removeClientInfoButtonFor3p bg-[#a51a1a]")
                .children()
                .removeClass("fa-plus")
                .addClass("fa-minus");
            $(".appendDuplicatesFor3p").append(newItem); // add to end
        }

    });

    $(document).on('click', '.removeClientInfoButtonFor3p', function() {
        let btn = $('.removeClientInfoButtonFor3p');
        // disable button temporarily
        btn.prop('disabled', true);
        $(this).parent().parent().remove();

        // enable button again after 1 second
        setTimeout(() => {
            btn.prop('disabled', false);
        }, 500);
    });

    $(document).on('click', '.addClientInfoButtonFor1p', function() {
        let container = $(this).parent().parent();
        if (container.find('.hidden').length) {
            container.find('.hidden').removeClass('hidden');
        } else {
            let newItem = container.clone(); // duplicate first
            $(this).removeClass("addClientInfoButtonFor1p bg-[#14548d]")
                .addClass("removeClientInfoButtonFor1p bg-[#a51a1a]")
                .children()
                .removeClass("fa-plus")
                .addClass("fa-minus");
            $(".appendDuplicatesFor1p").append(newItem); // add to end
        }
    });

    $(document).on('click', '.removeClientInfoButtonFor1p', function() {
        let btn = $('.removeClientInfoButtonFor1p');
        // disable button temporarily
        btn.prop('disabled', true);
        $(this).parent().parent().remove();

        // enable button again after 1 second
        setTimeout(() => {
            btn.prop('disabled', false);
        }, 500);
    });

    $(document).on('click', '.addClientInfoButtonForDefense', function() {
        let container = $(this).parent().parent();
        if (container.find('.hidden').length) {
            container.find('.hidden').removeClass('hidden');
        } else {
            let newItem = container.clone(); // duplicate first
            $(this).removeClass("addClientInfoButtonForDefense bg-[#14548d]")
                .addClass("removeClientInfoButtonForDefense bg-[#a51a1a]")
                .children()
                .removeClass("fa-plus")
                .addClass("fa-minus");
            $(".appendDuplicatesForDefense").append(newItem); // add to end
        }
    });

    $(document).on('click', '.removeClientInfoButtonForDefense', function() {
        let btn = $('.removeClientInfoButtonForDefense');
        // disable button temporarily
        btn.prop('disabled', true);
        $(this).parent().parent().remove();

        // enable button again after 1 second
        setTimeout(() => {
            btn.prop('disabled', false);
        }, 500);
    });

    $(document).on('click', '.addButtonForNegotiation', function() {
        let container = $(this).parent().parent();
        let newItem = container.clone(); // duplicate first
         if (container.find('.hidden').length) {
            container.find('.hidden').removeClass('hidden');
        } else {
            $(this).removeClass("addButtonForNegotiation bg-[#14548d]")
                .addClass("removeClientInfoButtonForNegotiation bg-[#a51a1a]")
                .children()
                .removeClass("fa-plus")
                .addClass("fa-minus");
            $(this).parent().parent().parent().append(newItem); // add to end
        }
    });

    $(document).on('click', '.removeClientInfoButtonForNegotiation', function() {
        let btn = $('.removeClientInfoButtonForNegotiation');
        // disable button temporarily
        btn.prop('disabled', true);
        $(this).parent().parent().remove();

        // enable button again after 1 second
        setTimeout(() => {
            btn.prop('disabled', false);
        }, 500);
    });

    $(document).on('click', '.addAffidavitButton', function() {
        let container = $(this).parent().parent();
        let newItem = container.clone(); // duplicate first
        $(this).removeClass("addAffidavitButton bg-[#14548d]")
            .addClass("removeClientInfoButtonForAffidavit bg-[#a51a1a]")
            .children()
            .removeClass("fa-plus")
            .addClass("fa-minus");
        $(this).parent().parent().parent().append(newItem); // add to end
    });

    $(document).on('click', '.removeClientInfoButtonForAffidavit', function() {
        let btn = $('.removeClientInfoButtonForAffidavit');
        // disable button temporarily
        btn.prop('disabled', true);
        $(this).parent().parent().remove();

        // enable button again after 1 second
        setTimeout(() => {
            btn.prop('disabled', false);
        }, 500);
    });

    $(document).on('click', '.addButtonForDeposits', function() {
        let container = $(this).parent().parent();
        let newItem = container.clone(); // duplicate first
        $(this).removeClass("addButtonForDeposits bg-[#14548d]")
            .addClass("removeClientInfoButtonForDeposits bg-[#a51a1a]")
            .children()
            .removeClass("fa-plus")
            .addClass("fa-minus");
        $(this).parent().parent().parent().append(newItem); // add to end
    });

    $(document).on('click', '.removeClientInfoButtonForDeposits', function() {
        let btn = $('.removeClientInfoButtonForDeposits');
        // disable button temporarily
        btn.prop('disabled', true);
        $(this).parent().parent().remove();

        // enable button again after 1 second
        setTimeout(() => {
            btn.prop('disabled', false);
        }, 500);
    });

    $(document).on('click', '.addButtonForExpenses', function() {
        let container = $(this).parent().parent();
        let newItem = container.clone(); // duplicate first
        $(this).removeClass("addButtonForExpenses bg-[#14548d]")
            .addClass("removeClientInfoButtonForExpenses bg-[#a51a1a]")
            .children()
            .removeClass("fa-plus")
            .addClass("fa-minus");
        $(this).parent().parent().parent().append(newItem); // add to end
    });

    $(document).on('click', '.removeClientInfoButtonForExpenses', function() {
        let btn = $('.removeClientInfoButtonForExpenses');
        // disable button temporarily
        btn.prop('disabled', true);
        $(this).parent().parent().remove();

        // enable button again after 1 second
        setTimeout(() => {
            btn.prop('disabled', false);
        }, 500);
    });

    $(document).on('click', '.addButtonForAdvances', function() {
        let container = $(this).parent().parent();
        let newItem = container.clone(); // duplicate first
        $(this).removeClass("addButtonForAdvances bg-[#14548d]")
            .addClass("removeClientInfoButtonForAdvances bg-[#a51a1a]")
            .children()
            .removeClass("fa-plus")
            .addClass("fa-minus");
        $(this).parent().parent().parent().append(newItem); // add to end
    });

    $(document).on('click', '.removeClientInfoButtonForAdvances', function() {
        let btn = $('.removeClientInfoButtonForAdvances');
        // disable button temporarily
        btn.prop('disabled', true);
        $(this).parent().parent().remove();

        // enable button again after 1 second
        setTimeout(() => {
            btn.prop('disabled', false);
        }, 500);
    });

    // End Add Edit Buttons

    // manage modal section
    $('#openManageSectionModal').on('click', function() {
        $("#todoSectionTitle").val("");
        $("#sectionDescription").val("");
        drawCategories();
        $('#addManageSectionsModal').removeClass('hidden');
    });

    $('#closeAddManageSectionsModal').on('click', function() {
        $('#addManageSectionsModal').addClass('hidden');
    });

    // Optional: Close when clicking outside modal content
    $('#addManageSectionsModal').on('click', function(e) {
        if ($(e.target).is(this)) {
            $(this).addClass('hidden');
        }

    });

    $(document).on('click', '#submitManageSectionBtn', function () {
        let url = '/sections/store'; // default for creating
        if(editingSectionId) {
            url = `/sections/update/${editingSectionId}`; // if editing, call update route
        }
        $.ajax({
            type: 'POST',
            url: url,
            data: $('#addManageSectionForm').serialize(),
            dataType: 'json',
            success: function (response) {
                $("#todoSectionTitle").val("");
                $("#sectionDescription").val("");
                $('#addManageSectionsModal').addClass('hidden');
                getSectionsAndTodos();
                // $('#modalSuccessContent').html(response.message);
                // $('#successModal').removeClass('hidden');
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    // clear previous highlights
                    $('input, select, textarea').removeClass('border-red-500');

                    $.each(errors, function (key, messages) {
                        const field = $(`[name="${key}"]`);
                        field.addClass('border-red-500'); // Tailwind red border
                        // optional: add a small error message
                        if (field.next('.error-text').length === 0) {
                            field.after(`<span class="error-text text-red-600 text-sm">${messages[0]}</span>`);
                        }
                    });
                }
            },
            complete: function () {
            }
        });
    });


    // todo modal
    $(document).on('click', '.addNewToDo', function () {
        let sectionId = null;
        sectionId = $(this).parent().attr('dataId');
        $('#sectionId').val(sectionId);
        $('#addTodoModal').removeClass('hidden');
    });

    $('#closeAddTodoModal').on('click', function() {
        $('#addTodoModal').addClass('hidden');
    });

    // Optional: Close when clicking outside modal content
    $('#addTodoModal').on('click', function(e) {
        if ($(e.target).is(this)) {
            $(this).addClass('hidden');
        }
    });

    $(document).on('click', '#submitTodoBtn', function () {
        $.ajax({
            type: 'POST',
            url: `todos/store`,
            data: $('#addTodoForm').serialize(),
            dataType: 'json',
            success: function (response) {
                $("#addTodoForm")[0].reset();
                $('#addTodoModal').addClass('hidden');
                getSectionsAndTodos();
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
            },
            complete: function () {
            }
        });
    });

    let editingSectionId = null;
    $(document).on('click', '.editTodoSection', function () {
       editingSectionId = $(this).parent().attr('dataId');
        $.ajax({
            type: 'GET',
            url: `/sections/show/${editingSectionId}`,
            dataType: 'json',
            beforeSend: function () {
                drawCategories() ;
            },
            success: function (response) {
                if(response.success) {
                    // Fill the form fields
                    $('#todoSectionTitle').val(response.section.title);
                    $('#sectionDescription').val(response.section.description);
                    $('#todoSectionCategory').val(response.section.categoryId);
                    // Show the modal
                    $('#addManageSectionsModal').removeClass('hidden');
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
            },
            complete: function () {

            }
        });
    });


    $(document).on('click', '.buttonClickForComplete', function () {
        let button = $(this);
        let id = button.data('id');

        $.ajax({
            url: '/todos/toggle-complete',
            type: 'POST',
            data: {
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content') // Required for Laravel
            },
            success: function (response) {
                if (response.success) {
                    // Toggle appearance
                    if (response.completed == "pending") {
                        button.removeClass('completeButton').addClass('incompleteButton');
                    } else {
                        button.removeClass('incompleteButton').addClass('completeButton');
                    }
                }
                getSectionsAndTodos();
            }
        });
    });


    let todoIdToDelete = null;
    // manage modal section
    $(document).on('click', '.deleteTodoBtn', function () {
        todoIdToDelete = $(this).data('id');
        $('#deleteTodoModal').removeClass('hidden');
    });

    $('#closedeleteTodoModal , #dontDeleteTodoBtn').on('click', function() {
        $('#deleteTodoModal').addClass('hidden');
    });

    $(document).on('click', '#deleteTodoBtn', function () {
        const todoId = todoIdToDelete;
        $.ajax({
            url: `/todos/${todoId}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content') // CSRF protection
            },
            success: function (response) {
                if (response.success) {
                    // remove from DOM smoothly
                    getSectionsAndTodos();
                    $('#deleteTodoModal').addClass('hidden');
                } else {
                    alert('Failed to delete todo.');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('Something went wrong while deleting the todo.');
            }
        });
    });

    let sectionIdToDelete = null;
    // manage modal section
    $(document).on('click', '.deleteSectionBtn', function () {
        sectionIdToDelete = $(this).data('id');
        $('#deleteSectionModal').removeClass('hidden');
    });

    $('#closeDeleteSectionModal , #dontDeleteSectionBtn').on('click', function() {
        $('#deleteSectionModal').addClass('hidden');
    });

    $(document).on('click', '#deleteSectionBtn', function () {
        const sectionId = sectionIdToDelete;
        $.ajax({
            url: `/sections/soft-delete/${sectionId}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content') // CSRF protection
            },
            success: function (response) {
                if (response.success) {
                    // remove from DOM smoothly
                    getSectionsAndTodos();
                    $('#deleteSectionModal').addClass('hidden');
                } else {
                    alert('Failed to delete todo.');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('Something went wrong while deleting the section.');
            }
        });
    });

    $(document).on('click', '.arrowToDo', function () {
        const todoId = $(this).data('id');

        $.ajax({
            url: `/todos/update-status/${todoId}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    getSectionsAndTodos(); // refresh the UI
                } else {
                    alert('Failed to update todo status.');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('Something went wrong while updating the todo.');
            }
        });
    });




    $(document).on('click', '#submitMainForm', function () {
        // Create empty arrays for each type
        let clients = [];
        let firstParties = [];
        let thirdParties = [];
        let defenseCounsels = [];
        let deposits = [];
        let expenses = [];
        let advances = [];

        // Loop over each section (use your actual selectors or classes)
        $('.clientToDuplicate').each(function () {
            let clientId = $(this).data('client');
            let clientData = {
                name: $(this).find('.clientNameInput').val(),
                email: $(this).find('.clientEmail').val(),
                address: $(this).find('.clientAddress').val(),
                dob: $(this).find('.clientDob').val(),
                tel: $(this).find('.clientTel').val(),
                ssn: $(this).find('.clientSsn').val(),
                affidavit_charts: [],
                treating_charts: [],
                negotiation_charts: []
            };

            $(`.affidavitToDuplicate[data-client="${clientId}"]`).each(function () {
                clientData.affidavit_charts.push({
                    providerName: $(this).find('.providerName').val(),
                    dateOrdered: $(this).find('.dateOrdered').val(),
                    dateReceivedMr: $(this).find('.dateReceivedMr').val(),
                    dateReceivedBr: $(this).find('.dateReceivedBr').val(),
                    affidavitDateServed: $(this).find('.affidavitDateServed').val(),
                    affidavitNoticeFiled: $(this).find('.affidavitNoticeFiled').val(),
                    mriAndResults: $(this).find('.mriAndResults').val(),
                    controverted: $(this).find('.controverted').val(),
                });
            });

            $(`.treatingToDuplicate[data-client="${clientId}"]`).each(function () {
                clientData.treating_charts.push({
                    ems: $(this).find('.ems').val(),
                    hospital: $(this).find('.hospital').val(),
                    chiropractor: $(this).find('.chiropractor').val(),
                    pcpmd: $(this).find('.pcpmd').val(),
                    mriAndResults: $(this).find('.mriAndResults').val(),
                    painManagement: $(this).find('.painManagement').val(),
                    orthoOrSurgery: $(this).find('.orthoOrSurgery').val(),
                });
            });

            $(`.negotiationToDuplicate[data-client="${clientId}"]`).each(function () {
                // Create a negotiation chart object
                let negotiationChart = {
                    medsTotal: $(this).find('.medsTotal').val(),
                    medsPviTotal: $(this).find('.medsPviTotal').val(),
                    negotiationLastOffer: $(this).find('.negotiationLastOffer').val(),
                    negotiationLastOfferDate: $(this).find('.negotiationLastOfferDate').val(),
                    negotiationLastDemand: $(this).find('.negotiationLastDemand').val(),
                    negotiationLastDemandDate: $(this).find('.negotiationLastDemandDate').val(),
                    physicalPainMentalAnguishText: $(this).find('.physicalPainMentalAnguishText').val(),
                    negotiation_subs: [] // 👈 array for the sub negotiations
                };

                // 🔁 Loop through each sub negotiation row INSIDE this negotiation
                $(this).find('.negotiationSubToDuplicate').each(function () {
                    negotiationChart.negotiation_subs.push({
                        negotiationNameBottom: $(this).find('.negotiationNameBottom').val(),
                        negotiationDateBottom: $(this).find('.negotiationDateBottom').val(),
                        negotiationAmountBottom: $(this).find('.negotiationAmountBottom').val(),
                    });
                });
                // Push the negotiation chart (with subs inside) into client data
                clientData.negotiation_charts.push(negotiationChart);

            });


            clients.push(clientData);
        });

        $('.firstPToDuplicate').each(function () {
            firstParties.push({
                name: $(this).find('.onePName').val(),
                claim: $(this).find('.onePClaim').val(),
                adjuster: $(this).find('.onePBiAdjuster').val(),
                tel: $(this).find('.onePTel').val(),
                fax: $(this).find('.onePFax').val(),
                email: $(this).find('.onePEmail').val(),
            });
        });

        $('.threePToDuplicate').each(function () {
            thirdParties.push({
                name: $(this).find('.threePName').val(),
                claim: $(this).find('.threeClaim').val(),
                adjuster: $(this).find('.threePBiAdjuster').val(),
                tel: $(this).find('.threePTel').val(),
                email: $(this).find('.threeEmail').val(),
                fax: $(this).find('.threeFax').val(),
            });
        });

        $('.defenseToDuplicate').each(function () {
            defenseCounsels.push({
                name: $(this).find('.defenseCounselName').val(),
                attorney: $(this).find('.defenseCounselAttorney').val(),
                address: $(this).find('.defenseCounselAddress').val(),
                tel: $(this).find('.defenseCounselTel').val(),
                email: $(this).find('.defenseCounselEmail').val(),
                fax: $(this).find('.defenseCounselFax').val(),
            });
        });

        $('.DepositsToDuplicate').each(function () {
            deposits.push({
                amount: $(this).find('.depositsAmount').val(),
                name: $(this).find('.deposistsName').val(),
                date: $(this).find('.depositsDate').val(),
                checkNumber: $(this).find('.depositsCheckNumber').val(),
            });
        });


        $('.expensesToDuplicate').each(function () {
            expenses.push({
                name: $(this).find('.expensesName').val(),
                amount: $(this).find('.expensesPrice').val(),
                date: $(this).find('.expensesDate').val(),
                checkNumber: $(this).find('.expensesCheck').val(),
            });
        });

        $('.advancesToDuplicate').each(function () {
            advances.push({
                amount: $(this).find('.advancesAmount').val(),
                name: $(this).find('.advancesName').val(),
                date: $(this).find('.advancesDate').val(),
                checkNumber: $(this).find('.advancesCheckNumber').val(),
            });
        });

        // --- Combine everything
        let formData = $('#mainForm').serialize(); // base form fields
        formData += '&clients=' + encodeURIComponent(JSON.stringify(clients));
        formData += '&first_parties=' + encodeURIComponent(JSON.stringify(firstParties));
        formData += '&third_parties=' + encodeURIComponent(JSON.stringify(thirdParties));
        formData += '&defense_counsels=' + encodeURIComponent(JSON.stringify(defenseCounsels));
        formData += '&expenses=' + encodeURIComponent(JSON.stringify(expenses));
        formData += '&advances=' + encodeURIComponent(JSON.stringify(advances));
        formData += '&deposits=' + encodeURIComponent(JSON.stringify(deposits));
        let  caseId = 1;
        $.ajax({
            type: 'POST',
            url: `/cases/update-case-info/${caseId}`,
            data: formData,
            dataType: 'json',
            success: function (response) {
                alert('Case updated successfully!');},
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
            },
            complete: function () {
            }
        });
        return false;
    });

// ----------------End-----------------//
})




//------------------------Functions---------------------//
function getSectionsAndTodos() {

    let caseId = "1";
    $.ajax({
        url: '/cases/' + caseId + '/sections',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);

            if (response.success) {
                let sections = response.sections;
                let html = `
                <div class="  mt-3 text-green-800">
                    <h2  class="text-lg   text-center">Facilitating Settlement</h2>
                    <p class="mb-1 text-md  ">
                        Once the insurance company has either paid limits OR made a reasonable settlement offer and they are at their top:
                    </p>

                `;
                sections.forEach(section => {
                    html += `<div class="  mt-3 "  style="color: ${section.categorie.color}">`;
                    html += `<h2  class="text-lg text-center" dataId="${section.id}" >${section.title}
                                <i class="fa-regular editTodoSection fa-pen-to-square"  title ="Edit Section" style="margin-left:10px; cursor:pointer;"></i>
                                <i class="fa-solid addNewToDo fa-notes-medical" title ="Add New todo" style="margin-left:10px; cursor:pointer;"></i>
                                <i class="fa-solid fa-xmark deleteSectionBtn" data-id="${section.id}" title="Delete Section" style="margin-left:10px;cursor:pointer;  vertical-align:top; margin-top:4px"></i>

                            </h2>`;
                    html += `<p class="mb-1 text-md  ">${section.description}</p>`;

                    // Todos inside this section
                    if (section.todos && section.todos.length > 0) {
                        section.todos.forEach(todo => {
                             // Only show todos that are still in the section (not today/completed)
                            if (todo.toDoStatus !== 'toBeDone' && todo.toDoStatus !== 'completed') {
                                html += `<div  class=" mt-1" >
                                            <div class="grid grid-cols-1 2xl:grid-cols-12 gap-4 w-full">
                                        `;

                                html += `
                                                <div class="2xl:col-span-1 flex flex-col ">
                                                    <button title="Mark task as complete" data-id="${todo.id}" class="incompleteButton buttonClickForComplete">
                                                        <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                                                    </button>`;
                                html += `       </div>
                                                <div class="2xl:col-span-10 flex flex-col ">`;
                                                    if (todo.title != null && todo.title != 'NULL' && todo.title != '') {
                                html += `
                                                        <p>${todo.title}</p>
                                        `;
                                                    }
                                html += `
                                                    <p style="color:#a22323 !important;">${todo.description}</p>
                                                </div>
                                                <div class="2xl:col-span-1 flex flex-col ">
                                                    <i class="fa-solid fa-xmark deleteTodoBtn" data-id="${todo.id}" title="Delete Todo" style="font-size:16px; color:red; vertical-align:top; margin-top:4px"></i>
                                                </div>
                                            </div>
                                        </div>`;
                            }
                        });


                    } else {
                        html += `<p class="ml-4 text-sm text-gray-500">No todos yet</p>`;
                    }


                    html += `</div>`;

                });

                $('#displayTodosHere').html(html);

                // 2️⃣ Display Today’s Tasks
                let todayHtml = '';
                if (response.todays.length > 0) {
                    response.todays.forEach(todo => {
                        todayHtml += `
                                    <div  class=" mt-1" >
                                        <div class="grid grid-cols-1 2xl:grid-cols-14 gap-4 w-full">
                                            <div class="2xl:col-span-1 flex flex-col ">
                                                <button title="Mark task as complete" data-id="${todo.id}" class="incompleteButton buttonClickForComplete">
                                                    <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                                                </button>
                                            </div>
                                            <div class="2xl:col-span-11 flex flex-col ">
                                                <p style="color:#a22323 !important;" >${todo.title ?? ''}</p>
                                                <p style="color:#a22323 !important;" class="text-sm ">${todo.description ?? ''}</p>
                                            </div>
                                            <div class="2xl:col-span-1 flex flex-col">
                                                <i class="fa-solid fa-arrow-right arrowToDo" data-id="${todo.id}" title="Go to Manage Sections" style="font-size:16px; cursor:pointer; vertical-align:top; margin-top:4px"></i>
                                            </div>
                                            <div class="2xl:col-span-1 flex flex-col ">
                                                <i class="fa-solid fa-xmark deleteTodoBtn" data-id="${todo.id}" title="Delete Todo" style="font-size:16px; color:red; vertical-align:top; margin-top:4px"></i>
                                            </div>
                                        </div>
                                    </div>
                        `;
                    });
                } else {
                    todayHtml = `<p class="text-sm text-gray-400">No tasks for today</p>`;
                }
                $('#displayTodayTodosHere').html(todayHtml);

                // 3️⃣ Display Completed Tasks
                let completedHtml = '';
                if (response.completed.length > 0) {
                    let lastDate = ''; // track the last displayed date

                    response.completed.forEach(todo => {
                        const formattedDate = formatDateMDY(todo.completeDate);

                        // Only show date if it’s different from the last one
                        if (formattedDate !== lastDate) {
                            completedHtml += `<p class="mt-4 mb-1 text-sm text-gray-700 font-bold"><b>${formattedDate}</b></p>`;
                            lastDate = formattedDate;
                        }
                        completedHtml += `
                                <div class="mt-1">
                                    <div class="grid grid-cols-1 2xl:grid-cols-14 gap-4 w-full">
                                        <div class="2xl:col-span-1 flex flex-col">
                                            <button title="Mark task as complete" data-id="${todo.id}" class="completeButton">
                                                <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                                            </button>
                                        </div>
                                        <div class="2xl:col-span-11 flex flex-col">`;
                                            if (todo.title && todo.title !== 'NULL' && todo.title !== '') {
                                                completedHtml += `<p class="font-semibold">${todo.title}</p>`;
                                            }
                        completedHtml += `
                                            <p style="color:#a22323 !important;">${todo.description}</p>
                                        </div>
                                        <div class="2xl:col-span-1 flex flex-col">
                                            <i class="fa-solid fa-arrow-up arrowToDo " data-id="${todo.id}" title="Go to Todo" style="font-size:16px; cursor:pointer;  vertical-align:top; margin-top:4px"></i>
                                        </div>
                                        <div class="2xl:col-span-1 flex flex-col">
                                            <i class="fa-solid fa-xmark deleteTodoBtn" data-id="${todo.id}" title="Delete Todo" style="font-size:16px; color:red; vertical-align:top; margin-top:4px"></i>
                                        </div>
                                    </div>
                                </div>
                            `;
                    });
                } else {
                    completedHtml = `<p class="text-sm text-gray-400">No completed tasks</p>`;
                }
                $('#displayDoneTodosHere').html(completedHtml);
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr.responseText);
        }
    });
}

//------------------------Functions---------------------//
function getCaseInfoMainData() {

    let caseId = "1";
    $.ajax({
        url: '/cases/' + caseId + '/main-info',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);

            if (response.success) {
                let caseData = response.case;
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr.responseText);
        }
    });
}


function drawCategories() {
    $.ajax({
        type: 'GET',
        url: "getCategories",
        dataType: 'json',
        success: function (data) {
            // ✅ Process and return list
            const $categorySelect = $('#todoSectionCategory');
            $categorySelect.empty().append('<option value="-1">Select a category</option>');
            data.forEach(category => {
                $categorySelect.append(`<option value="${category.id}">${category.categoryName}</option>`);
            });
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

            if (typeof callback === "function") {
                callback({ error: errorMsg }, null);
            }
        }
    });
}
function formatDateMDY(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    const month = date.getMonth() + 1; // 0-indexed
    const day = date.getDate();
    const year = date.getFullYear();
    return `${month}/${day}/${year}`;
}
