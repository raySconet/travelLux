$(document).ready(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const caseId = urlParams.get('caseId');
    $("#hiddenCaseId").val(caseId);
    $("#openGeneratePdfModal").attr('data-case-id', caseId);

    drawCategories();
    getSectionsAndTodos();
    setTimeout(function(){
        getCaseInfoMainData() ;
    },200);
   $('#toDoNoteBox').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
        ]
    });
    $('#displayTodoNoteHere').summernote({
        height: 200,
        callbacks: {
            onKeyup: function () {
                clearTimeout(typingTimer);

                typingTimer = setTimeout(function () {
                    let content = $('#displayTodoNoteHere').summernote('code');
                    saveTextarea(content);
                }, 1500); // 1.5 seconds
            }

        },
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
        ]
    });
    let typingTimer;
    const doneTypingInterval = 2000; // 2 seconds

    $('#displayTodoNoteHere').on('input', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(saveTextarea, doneTypingInterval);
    });


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
        $(this).parent().html("").append(`<span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer removeClientInfoButton bg-[#a51a1a]" style="margin-top:2px; width:22px; height:22px;">
                            <i class="fa-solid text-white text-md leading-none fa-minus"></i>
                        </span>`);

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

        updateClientButtons();
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
        updateClientButtons();
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
            newItem.find('input, textarea, select').val('');

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
            newItem.find('input, textarea, select').val('');
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
            newItem.find('input, textarea, select').val('');
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
            newItem.find('input, textarea, select').val('');
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
            newItem.find('input, textarea, select').val('');
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
            newItem.find('input, textarea, select').val('');
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
            newItem.find('input, textarea, select').val('');
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
            newItem.find('input, textarea, select').val('');
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
        let formData = $('#addManageSectionForm').serialize();
        let caseId = $("#hiddenCaseId").val();

        // Append caseId to the serialized form data
        formData += `&caseId=${encodeURIComponent(caseId)}`;
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
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
        flatpickr(".datetimepicker", {
            enableTime: false,
            dateFormat: "m-d-Y",
            defaultDate: new Date(),
        });
        let sectionId = null;
        sectionId = $(this).parent().attr('dataId');
        $("#todoTitle").val("");
        $("#todoDescription").val("");
        $("#todoId").val("");

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

    // $(document).on('click', '#submitTodoBtn', function () {
    //     $.ajax({
    //         type: 'POST',
    //         url: `todos/store`,
    //         data: $('#addTodoForm').serialize(),
    //         dataType: 'json',
    //         success: function (response) {
    //             $("#addTodoForm")[0].reset();
    //             $('#addTodoModal').addClass('hidden');
    //             getSectionsAndTodos();
    //         },
    //         error: function (xhr) {
    //             console.error('Error:', xhr.responseText);
    //         },
    //         complete: function () {
    //         }
    //     });
    // });

    let editingSectionId = null;
    $(document).on('click', '.editTodoSection', function () {
        editingSectionId = $(this).parent().attr('dataId');
        $.ajax({
            type: 'GET',
            url: `/sections/show/${editingSectionId}`,
            dataType: 'json',
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

    $(document).on('change', '#mainCaseStage', function () {
        const categoryId = $(this).val();
        const caseId = $('#hiddenCaseId').val();
        if(!caseId || !categoryId) return;

        $.ajax({
            url: '/cases/update-category', // route we'll define
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                case_id: caseId,
                category_id: categoryId
            },
            success: function(response) {
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Error updating case category.');
            }
        });
    });



    $(document).on('click', '.submitMainForm', function () {
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
                $(this).find('.toduplicatehere').each(function () {
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
            let data = {
                name: $(this).find('.onePName').val()?.trim(),
                claim: $(this).find('.onePClaim').val()?.trim(),
                adjuster: $(this).find('.onePBiAdjuster').val()?.trim(),
                tel: $(this).find('.onePTel').val()?.trim(),
                fax: $(this).find('.onePFax').val()?.trim(),
                email: $(this).find('.onePEmail').val()?.trim(),
            };

            // ✅ Only push if at least one field has a value
            if (Object.values(data).some(val => val !== '' && val !== null && val !== undefined)) {
                firstParties.push(data);
            }
        });

        $('.threePToDuplicate').each(function () {
            let data = {
                name: $(this).find('.threePName').val()?.trim(),
                claim: $(this).find('.threeClaim').val()?.trim(),
                adjuster: $(this).find('.threePBiAdjuster').val()?.trim(),
                tel: $(this).find('.threePTel').val()?.trim(),
                email: $(this).find('.threeEmail').val()?.trim(),
                fax: $(this).find('.threeFax').val()?.trim(),
            };

            if (Object.values(data).some(val => val !== '' && val !== null && val !== undefined)) {
                thirdParties.push(data);
            }
        });

        $('.defenseToDuplicate').each(function () {
            let data = {
                name: $(this).find('.defenseCounselName').val()?.trim(),
                attorney: $(this).find('.defenseCounselAttorney').val()?.trim(),
                address: $(this).find('.defenseCounselAddress').val()?.trim(),
                tel: $(this).find('.defenseCounselTel').val()?.trim(),
                email: $(this).find('.defenseCounselEmail').val()?.trim(),
                fax: $(this).find('.defenseCounselFax').val()?.trim(),
            };

            if (Object.values(data).some(val => val !== '' && val !== null && val !== undefined)) {
                defenseCounsels.push(data);
            }
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
        let  caseId = $("#hiddenCaseId").val();
        $.ajax({
            type: 'POST',
            url: `/cases/update-case-info/${caseId}`,
            data: formData,
            dataType: 'json',
            success: function (response) {
                // alert('Case updated successfully!');
                // getCaseInfoMainData();

                $('.firstPToDuplicate').each(function () {
                    const allFirst = $('.firstPToDuplicate');
                    const data = {
                        name: $(this).find('.onePName').val()?.trim(),
                        claim: $(this).find('.onePClaim').val()?.trim(),
                        adjuster: $(this).find('.onePBiAdjuster').val()?.trim(),
                        tel: $(this).find('.onePTel').val()?.trim(),
                        fax: $(this).find('.onePFax').val()?.trim(),
                        email: $(this).find('.onePEmail').val()?.trim(),
                    };

                    const isEmpty = !Object.values(data).some(val => val !== '' && val !== null && val !== undefined);

                    if (isEmpty && allFirst.length > 1) {
                        $(this).remove();
                    }
                });


                // 🟡 THIRD PARTY
                $('.threePToDuplicate').each(function () {
                    const allThird = $('.threePToDuplicate');
                    const data = {
                        name: $(this).find('.threePName').val()?.trim(),
                        claim: $(this).find('.threeClaim').val()?.trim(),
                        adjuster: $(this).find('.threePBiAdjuster').val()?.trim(),
                        tel: $(this).find('.threePTel').val()?.trim(),
                        email: $(this).find('.threeEmail').val()?.trim(),
                        fax: $(this).find('.threeFax').val()?.trim(),
                    };

                    const isEmpty = !Object.values(data).some(val => val !== '' && val !== null && val !== undefined);

                    if (isEmpty && allThird.length > 1) {
                        $(this).remove();
                    }
                });

                $('.defenseToDuplicate').each(function () {
                    const allDefense = $('.defenseToDuplicate'); // all rows
                    const data = {
                        name: $(this).find('.defenseCounselName').val()?.trim(),
                        attorney: $(this).find('.defenseCounselAttorney').val()?.trim(),
                        address: $(this).find('.defenseCounselAddress').val()?.trim(),
                        tel: $(this).find('.defenseCounselTel').val()?.trim(),
                        email: $(this).find('.defenseCounselEmail').val()?.trim(),
                        fax: $(this).find('.defenseCounselFax').val()?.trim(),
                    };

                    // ✅ If ALL fields are empty
                    const isEmpty = !Object.values(data).some(val => val !== '' && val !== null && val !== undefined);

                    if (isEmpty && allDefense.length > 1) {
                        $(this).remove();
                    }
                });

                updateDefenseCounselButtons();
                updateOnePButtons();
                updateThreePButtons();

                const container3 = $('.threePToDuplicate');
                container3.find('.hidden').removeClass('hidden'); // first show everything

                container3.find('input').each(function () {
                    if (!$(this).val().trim()) {
                        $(this).closest('div').addClass('hidden'); // hide again if empty
                    }
                });

                // Hide empty selects
                container3.find('select').each(function () {
                    if ($(this).val() === "" || $(this).val() === null) {
                        $(this).closest('div').addClass('hidden'); // hide select if no value
                    }
                });

                // --- First Party container ---
                const container1 = $('.firstPToDuplicate');
                container1.find('.hidden').removeClass('hidden'); // first show everything

                container1.find('input').each(function () {
                    if (!$(this).val().trim()) {
                        $(this).closest('div').addClass('hidden'); // hide again if empty
                    }
                });

                container1.find('select').each(function () {
                    if ($(this).val() === "" || $(this).val() === null) {
                        $(this).closest('div').addClass('hidden'); // hide select if no value
                    }
                });


                const container4 = $('.defenseToDuplicate');
                container4.find('.hidden').removeClass('hidden'); // first show everything

                container4.find('input, textarea').each(function () {
                    if (!$(this).val().trim()) {
                        $(this).closest('div').addClass('hidden'); // hide again if empty
                    }
                });



            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
            },
            complete: function () {
            }
        });
        return false;
    });






    function saveTextarea(content) {
        // const text = $('#displayTodoNoteHere').val();
        const caseId = $('#hiddenCaseId').val();

        $.ajax({
            url: '/save-textarea', // Laravel route
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                text: content,
                case_id: caseId
            },
            success: function(response) {
                $('#status').text('Saved!').fadeIn().delay(1000).fadeOut();
            },
            error: function(xhr) {
                $('#status').text('Error saving').fadeIn().delay(2000).fadeOut();
            }
        });
    }


    $(document).on('click', '.editTodoBtn', function () {
        const todoId = $(this).data('id');

        $.ajax({
            url: `/todos/${todoId}/edit`,
            type: 'GET',
            success: function (todo) {
                // Fill form with fetched data
                $('#todoId').val(todo.id);
                $('#todoTitle').val(todo.title);
                $('#todoDate').val(formatDateMDYTwo(todo.completeDate));
                $('#todoDescription').val(todo.description);
                $('#toDoNoteBox').summernote('code', todo.noteBox);
                $('#sectionId').val(todo.sectionId);
                $('#completedBy').val(todo.completedBy);


                // Open the modal
                $('#addTodoModal').removeClass('hidden');

            },
            error: function (xhr) {
                console.error(xhr.responseText);

            }
        });
    });


    $(document).on('click', '#submitTodoBtn', function (e) {
        e.preventDefault();

        let todoId = $('#todoId').val(); // hidden field for edit mode
        let formData = $('#addTodoForm').serialize();

        // ✅ Use the right Laravel route depending on create or update
        let url = todoId ? `/todos/${todoId}` : `/todos/store`;
        let method = todoId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: formData,
            success: function (response) {
                $('#addTodoModal').addClass('hidden');

                // ✅ Reset form
                $('#addTodoForm')[0].reset();

                getSectionsAndTodos();


            },
            error: function (xhr) {
                console.error(xhr.responseText);

            }
        });
    });


   $(document).on('change', '.threePName', function (e) {
        const $this = $(this); // the select that triggered the event
        const insuranceId = $this.val();

        // Find the parent container of this row
        const $row = $this.closest('.threePToDuplicate');

        if (!insuranceId || insuranceId == -1) {
            // Clear fields only in this row
            $row.find('.threeClaim').val('');
            return;
        }

        $.ajax({
            url: `/insurance/${insuranceId}/fetch`,
            type: 'GET',
            success: function(data) {
                // Fill only fields in this row
                $row.find('.threeClaim').val(data.claim_number || '');
                $row.find('.threePBiAdjuster').val(data.bi_adjuster || '');
                $row.find('.threePTel').val(data.tel || '');
                $row.find('.threeEmail').val(data.email || '');
                $row.find('.threeFax').val(data.fax || '');
            },
            error: function(xhr) {
                alert('Failed to fetch insurance details.');
                console.log(xhr.responseText);
            }
        });
    });


    $(document).on('change', '.onePName', function (e) {
        const $this = $(this); // the select that triggered the event
        const insuranceId = $this.val();

        // Find the parent container of this row
        const $row = $this.closest('.firstPToDuplicate');

        if (!insuranceId || insuranceId == -1) {
            // Clear fields only in this row
            $row.find('.threeClaim').val('');
            return;
        }

        $.ajax({
            url: `/insurance/${insuranceId}/fetch`,
            type: 'GET',
            success: function(data) {
                // Fill only fields in this row
                $row.find('.onePClaim').val(data.claim_number || '');
                $row.find('.onePBiAdjuster').val(data.bi_adjuster || '');
                $row.find('.onePTel').val(data.tel || '');
                $row.find('.onePEmail').val(data.email || '');
                $row.find('.onePFax').val(data.fax || '');
            },
            error: function(xhr) {
                alert('Failed to fetch insurance details.');
                console.log(xhr.responseText);
            }
        });
    });


    $(document).on('click', '.editContactInfo', function () {
        const container = $('.threePToDuplicate'); // adjust this selector to your block’s wrapper class
        // Show all previously hidden fields
        container.find('.hidden').removeClass('hidden');

        const container2 = $('.firstPToDuplicate'); // adjust this selector to your block’s wrapper class
        // Show all previously hidden fields
        container2.find('.hidden').removeClass('hidden');
    });



    $('#openGeneratePdfModal').on('click', function () {
    $('#generatePdfModal').removeClass('hidden');
    });

    // Close modal
    $('#closePdfModal').on('click', function () {
        $('#generatePdfModal').addClass('hidden');
    });

    // Close when clicking outside the modal content
    $('#generatePdfModal').on('click', function (e) {
        // Only close if the clicked element is the modal itself (the backdrop)
        if (e.target === this) {
            $(this).addClass('hidden');
        }
    });

    // AJAX on clicking "Generate"
    $('#generatePdfBtn').on('click', function () {

        let gender = $('input[name="gender"]:checked').val();
        let caseId = $('#openGeneratePdfModal').data('case-id'); // <-- GET CASE ID

        if (!gender) {
            alert('Please select Male or Female');
            return;
        }

        $.ajax({
            url: '/generatePDF/' + caseId,   // <-- SEND CASE ID
            type: 'GET',
            data: {
                gender: gender
            },
            success: function (response) {
                console.log(response);
                alert('PDF Generated!');
                $('#generatePdfModal').addClass('hidden');
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('Something went wrong.');
            }
        });
    });

// ----------------End-----------------//
})




//------------------------Functions---------------------//
function getSectionsAndTodos() {

    let caseId = $("#hiddenCaseId").val();
    $.ajax({
        url: '/cases/' + caseId + '/sections',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);

            if (response.success) {
                let sections = response.sections;
                let html = `
                `;
                sections.forEach(section => {
                    html += `<div class="  mt-3 "  style="color: ${section.categorie.color}">`;
                    html += `<h2  class="text-lg text-center" dataId="${section.id}" >${section.title ? section.title : ''}
                                <i class="fa-regular editTodoSection fa-pen-to-square"  title ="Edit Section" style="margin-left:10px; cursor:pointer;"></i>
                                <i class="fa-solid addNewToDo fa-notes-medical" title ="Add New todo" style="margin-left:10px; cursor:pointer;"></i>
                                <i class="fa-solid fa-xmark deleteSectionBtn" data-id="${section.id}" title="Delete Section" style="margin-left:10px;cursor:pointer;  vertical-align:top; margin-top:4px"></i>

                            </h2>`;
                    html += `<p class="mb-1 text-md  ">${section.description  ? section.description : ''}</p>`;

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
                                                    </button>
                                                    <button class="editTodoBtn" style="margin-top:5px; cursor:pointer;" data-id="${todo.id}">
                                                        <i class="fa fa-pen"></i>
                                                    </button>
                                                    `;

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
                                            <div class="2xl:col-span-14 flex flex-col ">
                                                <p style="color:#a22323 !important; margin-bottom:-15px;" >${todo.completedBy ?? ''}</p>
                                            </div>

                                            <div class="2xl:col-span-1 flex flex-col ">
                                                <button title="Mark task as complete" data-id="${todo.id}" class="incompleteButton buttonClickForComplete">
                                                    <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                                                </button>
                                                <button class="editTodoBtn" style="margin-top:5px; cursor:pointer;" data-id="${todo.id}">
                                                    <i class="fa fa-pen"></i>
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
                                        <div class="2xl:col-span-14 flex flex-col ">
                                            <p style="color:#a22323 !important; margin-bottom:-15px;" >${todo.completedBy ?? ''}</p>
                                        </div>

                                        <div class="2xl:col-span-1 flex flex-col">
                                            <button title="Mark task as complete" data-id="${todo.id}" class="completeButton">
                                                <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                                            </button>git
                                            <button class="editTodoBtn" style="margin-top:5px; cursor:pointer;" data-id="${todo.id}">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                        </div>
                                        <div class="2xl:col-span-11 flex flex-col">
                                        `;
                                            if (todo.title && todo.title !== 'NULL' && todo.title !== '') {
                                                completedHtml += `<p class="font-semibold">${todo.title}</p>`;
                                            }
                        completedHtml += `
                                            <p style="color:#a22323 !important;">${todo.description}</p>
                                        `;

                                         if (todo.noteBox && todo.noteBox !== 'NULL' && todo.noteBox !== '') {
                                                completedHtml += `<p class="">${todo.noteBox}</p>`;
                                            }
                        completedHtml += `
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

// //------------------------Functions---------------------//
function getCaseInfoMainData() {
    let caseId = $("#hiddenCaseId").val();

    $.ajax({
        url: '/cases/' + caseId + '/main-info',
        type: 'GET',
        dataType: 'json',
        success: function(response) {

            console.log(response);
            if (response.success) {
                let caseData = response.case;

                $('#displayTodoNoteHere').summernote('code', caseData.todoNote);
                $("#mainCaseStage").val(caseData.categoryId || '');

                $("#referralChiro").val(caseData.referralChiro || '');
                $("#doi").val(caseData.doi || '');
                $("#facts").val(caseData.facts || '');
                $("#injuries").val(caseData.injuries || '');
                $("#chiro").val(caseData.chiro || '');
                $("#policeReport").val(caseData.policeReport || '');
                $("#photos").val(caseData.photos || '');
                $("#propertyDesctiption").val(caseData.propertyDesctiption || '');
                $("#3pLiability").val(caseData['3pLiability'] || '');
                $("#3pCoverage").val(caseData['3pCoverage'] || '');
                $("#3pLiabilityLimit").val(caseData['3pLiabilityLimit'] || '');
                $("#1pCoverageLimits").val(caseData['1pCoverageLimits'] || '');
                $("#preExistingInjuries").val(caseData['preExistingInjuries'] || '');


                $("#caseInfoStyle").val(caseData['caseInfoStyle'] || '');
                $("#causeNumber").val(caseData['causeNumber'] || '');
                $("#courtCounty").val(caseData['courtCounty'] || '');
                $("#recordsServed").val(caseData['recordsServed'] || '');
                $("#filed").val(caseData['filed'] || '');
                $("#served").val(caseData['served'] || '');
                $("#answer").val(caseData['answer'] || '');
                $("#expertDesignationDeadlinesP").val(caseData['expertDesignationDeadlinesP'] || '');
                $("#discoveryResponsesP").val(caseData['discoveryResponsesP'] || '');
                $("#discoveryResponsesD").val(caseData['discoveryResponsesD'] || '');
                $("#discoveryPeriodEnds").val(caseData['discoveryPeriodEnds'] || '');
                $("#expertDesignationDeadlinesD").val(caseData['expertDesignationDeadlinesD'] || '');
                $("#deposP").val(caseData['deposP'] || '');
                $("#deposD").val(caseData['deposD'] || '');
                $("#docketCall").val(caseData['docketCall'] || '');
                $("#billingServedDate").val(caseData['billingServedDate'] || '');
                $("#noticeFiledDate").val(caseData['noticeFiledDate'] || '');
                $("#trial").val(caseData['trial'] || '');

                $("#firstpPermissionToRelease").val(caseData['firstpPermissionToRelease'] || '');
                $("#thirdpRelease").val(caseData['thirdpRelease'] || '');
                $("#firstpRelease").val(caseData['firstpRelease'] || '');
                $("#pip").val(caseData['pip'] || '');
                $("#statutoryLiens").val(caseData['statutoryLiens'] || '');
                $("#otherLiensSubrogationInterests").val(caseData['otherLiensSubrogationInterests'] || '');
                $("#disbursal").val(caseData['disbursal'] || '');
                $("#checks").val(caseData['checks'] || '');


               // --- DEPOSITS / EXPENSES / ADVANCES ---
                const depositTemplate = $('.DepositsToDuplicate').first();
                const depositContainer = $('.appendDuplicatesForDeposits');
                depositContainer.empty();

                const expensesTemplate = $('.expensesToDuplicate').first();
                const expensesContainer = $('.appendDuplicatesForExpenses');
                expensesContainer.empty();

                const advancesTemplate = $('.advancesToDuplicate').first();
                const advancesContainer = $('.appendDuplicatesForAdvances');
                advancesContainer.empty();

                const depositsData = caseData.deposit_expenses_advs || [{}];

                // --- Deposits ---
                let depositAdded = false;
                depositsData.forEach(item => {
                    if (item.depositPrice || item.depositName || item.depositDate || item.depositCheckNumber) {
                        let newDeposit = depositTemplate.clone();
                        newDeposit.find('.depositsAmount').val(item.depositPrice || '');
                        newDeposit.find('.deposistsName').val(item.depositName || '');
                        newDeposit.find('.depositsDate').val(item.depositDate || '');
                        newDeposit.find('.depositsCheckNumber').val(item.depositCheckNumber || '');
                        newDeposit.find('.addButtonForDeposits')
                            .removeClass('bg-[#14548d]').addClass('removeClientInfoButtonForDeposits bg-[#a51a1a]')
                            .children().removeClass('fa-plus').addClass('fa-minus');
                        depositContainer.append(newDeposit);
                        depositAdded = true;
                    }
                });
                // If no deposits, append one empty row
                if (!depositAdded) {
                    let newDeposit = depositTemplate.clone();
                    depositContainer.append(newDeposit);
                }

                // --- Expenses ---
                let expenseAdded = false;
                depositsData.forEach(item => {
                    if (item.expensesName || item.expensesPrice || item.expensesDate || item.expensesCheck) {
                        let newExpense = expensesTemplate.clone();
                        newExpense.find('.expensesName').val(item.expensesName || '');
                        newExpense.find('.expensesPrice').val(item.expensesPrice || '');
                        newExpense.find('.expensesDate').val(item.expensesDate || '');
                        newExpense.find('.expensesCheck').val(item.expensesCheck || '');
                        newExpense.find('.addButtonForExpenses')
                            .removeClass('bg-[#14548d]').addClass('removeClientInfoButtonForExpenses bg-[#a51a1a]')
                            .children().removeClass('fa-plus').addClass('fa-minus');
                        expensesContainer.append(newExpense);
                        expenseAdded = true;
                    }
                });

                if (!expenseAdded) {
                    let newExpense = expensesTemplate.clone();
                    expensesContainer.append(newExpense);
                }

                // --- Advances ---
                let advanceAdded = false;
                depositsData.forEach(item => {
                    if (item.advancesAmount || item.advancesName || item.advancesDate || item.advancesCheckNumber) {
                        let newAdvances = advancesTemplate.clone();
                        newAdvances.find('.advancesAmount').val(item.advancesAmount || '');
                        newAdvances.find('.advancesName').val(item.advancesName || '');
                        newAdvances.find('.advancesDate').val(item.advancesDate || '');
                        newAdvances.find('.advancesCheckNumber').val(item.advancesCheckNumber || '');
                        newAdvances.find('.addButtonForAdvances')
                            .removeClass('bg-[#14548d]').addClass('removeClientInfoButtonForAdvances bg-[#a51a1a]')
                            .children().removeClass('fa-plus').addClass('fa-minus');
                        advancesContainer.append(newAdvances);
                        advanceAdded = true;
                    }
                });

                if (!advanceAdded) {
                    let newAdvances = advancesTemplate.clone();
                    advancesContainer.append(newAdvances);
                }

                // --- After filling all deposits/expenses/advances ---
                function updateAddButtons(container, addButtonClass, removeButtonClass) {
                    container.find(`.${addButtonClass}, .${removeButtonClass}`).each(function(index, el) {
                        const $btn = $(el);
                        $btn.removeClass(removeButtonClass + ' bg-[#a51a1a]')
                            .addClass(addButtonClass + ' bg-[#14548d]')
                            .children().removeClass('fa-minus').addClass('fa-plus');
                    });
                    // Make only the last one an "add" button
                    const $last = container.children().last().find(`.${addButtonClass}`);
                    $last.removeClass(removeButtonClass + ' bg-[#a51a1a]')
                        .addClass(addButtonClass + ' bg-[#14548d]')
                        .children().removeClass('fa-minus').addClass('fa-plus');
                    // Make all others "remove"
                    container.children().not(':last').find(`.${addButtonClass}`).each(function() {
                        $(this).addClass(removeButtonClass + ' bg-[#a51a1a]')
                            .removeClass(addButtonClass + ' bg-[#14548d]')
                            .children().removeClass('fa-plus').addClass('fa-minus');
                    });
                }

                // Update buttons for each container
                updateAddButtons(depositContainer, 'addButtonForDeposits', 'removeClientInfoButtonForDeposits');
                updateAddButtons(expensesContainer, 'addButtonForExpenses', 'removeClientInfoButtonForExpenses');
                updateAddButtons(advancesContainer, 'addButtonForAdvances', 'removeClientInfoButtonForAdvances');

                // --- CLIENTS ---
                const clientTemplate = $('.clientToDuplicate').first();
                const clientContainer = $('.appendDuplicates');
                clientContainer.empty();

                // Use actual clients or a single empty placeholder
                const clients = (caseData.clients && caseData.clients.length > 0) ? caseData.clients : [{}];

                clients.forEach((client, index) => {
                    let newClient = clientTemplate.clone().removeAttr('id').attr('data-client', index + 1);

                    // Fill fields only if there is actual data
                    if (client.client_name) newClient.find('.clientNameInput').val(client.client_name);
                    if (client.client_tel) newClient.find('.clientTel').val(client.client_tel);
                    if (client.client_email) newClient.find('.clientEmail').val(client.client_email);
                    if (client.client_address) newClient.find('.clientAddress').val(client.client_address);
                    if (client.client_dob) newClient.find('.clientDob').val(client.client_dob);
                    if (client.client_ssn) newClient.find('.clientSsn').val(client.client_ssn);

                    // Remove hidden so the + button shows
                    newClient.removeClass('hidden');

                    // Only last row is "add" button; all others are "remove"
                    // if (index === clients.length - 1) {
                    //     newClient.find('.addClientInfoButton')
                    //         .removeClass('removeClientInfoButton bg-[#a51a1a]')
                    //         .addClass('addClientInfoButton bg-[#14548d]')
                    //         .children().removeClass('fa-minus').addClass('fa-plus');
                    //     newClient.find('.addClientInfoButton').parent().append(`<span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer removeClientInfoButton bg-[#a51a1a]" style="margin-top:2px; width:22px; height:22px;">
                    //             <i class="fa-solid text-white text-md leading-none fa-minus"></i>
                    //         </span>`);
                    // } else {
                    //     newClient.find('.clientButtons').html("").append(`<span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer removeClientInfoButton bg-[#a51a1a]" style="margin-top:2px; width:22px; height:22px;">
                    //         <i class="fa-solid text-white text-md leading-none fa-minus"></i>
                    //     </span>`);
                    // }

                    clientContainer.append(newClient);

                });
                updateClientButtons();

                // --- 3P ---
                const threePTemplate = $('.threePToDuplicate').first();
                const threePContainer = $('.appendDuplicatesFor3p');
                threePContainer.empty();

                const third_parties = (caseData.third_parties && caseData.third_parties.length > 0) ? caseData.third_parties : [{}];

                third_parties.forEach((third, index) => {
                    let new3p = threePTemplate.clone().removeAttr('id');

                    if (third.third_party_name) new3p.find('.threePName').val(third.third_party_name);
                    if (third.third_party_claim) new3p.find('.threeClaim').val(third.third_party_claim);
                    if (third.third_party_adjuster) new3p.find('.threePBiAdjuster').val(third.third_party_adjuster);
                    if (third.third_party_tel) new3p.find('.threePTel').val(third.third_party_tel);
                    if (third.third_party_email) new3p.find('.threeEmail').val(third.third_party_email);
                    if (third.third_party_fax) new3p.find('.threeFax').val(third.third_party_fax);
                    const isEmptyThird = [
                        third.third_party_name,
                        third.third_party_claim,
                        third.third_party_adjuster,
                        third.third_party_tel,
                        third.third_party_email,
                        third.third_party_fax
                    ].every(field => field === null || field === undefined || field === '');

                    if (!isEmptyThird || third_parties.length > 1) {
                        new3p.find('.hidden').removeClass('hidden'); // show hidden fields
                    }

                    const emailField = new3p.find('.threeEmail').closest('div');
                    const faxField = new3p.find('.threeFax').closest('div');

                    // EMAIL
                    if (third.third_party_email && third.third_party_email.trim() !== '') {
                        new3p.find('.threeEmail').val(third.third_party_email);
                        emailField.removeClass('hidden');
                    } else {
                        emailField.addClass('hidden');
                    }

                    // FAX
                    if (third.third_party_fax && third.third_party_fax.trim() !== '') {
                        new3p.find('.threeFax').val(third.third_party_fax);
                        faxField.removeClass('hidden');
                    } else {
                        faxField.addClass('hidden');
                    }

                    if (index === third_parties.length - 1) {
                        // last row: + button stays
                        new3p.find('.addClientInfoButtonFor3p')
                            .removeClass('removeClientInfoButtonFor3p bg-[#a51a1a]')
                            .addClass('addClientInfoButtonFor3p bg-[#14548d]')
                            .children().removeClass('fa-minus').addClass('fa-plus');
                    } else {
                        new3p.find('.addClientInfoButtonFor3p')
                            .addClass('removeClientInfoButtonFor3p bg-[#a51a1a]')
                            .removeClass('addClientInfoButtonFor3p bg-[#14548d]')
                            .children().removeClass('fa-plus').addClass('fa-minus');
                    }

                    threePContainer.append(new3p);
                });

                // --- 1P ---
                const firstPTemplate = $('.firstPToDuplicate').first();
                const firstPContainer = $('.appendDuplicatesFor1p');
                firstPContainer.empty();

                const first_parties = (caseData.first_parties && caseData.first_parties.length > 0) ? caseData.first_parties : [{}];

                first_parties.forEach((first, index) => {
                    let new1p = firstPTemplate.clone().removeAttr('id');

                    if (first.name) new1p.find('.onePName').val(first.name);
                    if (first.claim) new1p.find('.onePClaim').val(first.claim);
                    if (first.adjuster) new1p.find('.onePBiAdjuster').val(first.adjuster);
                    if (first.tel) new1p.find('.onePTel').val(first.tel);
                    if (first.fax) new1p.find('.onePFax').val(first.fax);
                    if (first.email) new1p.find('.onePEmail').val(first.email);


                    const isEmptyFirst = [
                        first.name,
                        first.claim,
                        first.adjuster,
                        first.tel,
                        first.fax,
                        first.email
                    ].every(field => field === null || field === undefined || field === '');

                    if (!isEmptyFirst || third_parties.length > 1) {
                        new1p.find('.hidden').removeClass('hidden'); // show hidden fields
                    }


                    const emailField = new1p.find('.onePEmail').closest('div');
                    const faxField = new1p.find('.onePFax').closest('div');

                    // EMAIL
                    if (first.email && first.email.trim() !== '') {
                        new1p.find('.threeEmail').val(first.email);
                        emailField.removeClass('hidden');
                    } else {
                        emailField.addClass('hidden');
                    }

                    // FAX
                    if (first.fax && first.fax.trim() !== '') {
                        new1p.find('.threeFax').val(first.fax);
                        faxField.removeClass('hidden');
                    } else {
                        faxField.addClass('hidden');
                    }

                    if (index === first_parties.length - 1) {
                        new1p.find('.addClientInfoButtonFor1p')
                            .removeClass('removeClientInfoButtonFor1p bg-[#a51a1a]')
                            .addClass('addClientInfoButtonFor1p bg-[#14548d]')
                            .children().removeClass('fa-minus').addClass('fa-plus');
                    } else {
                        new1p.find('.addClientInfoButtonFor1p')
                            .addClass('removeClientInfoButtonFor1p bg-[#a51a1a]')
                            .removeClass('addClientInfoButtonFor1p bg-[#14548d]')
                            .children().removeClass('fa-plus').addClass('fa-minus');
                    }

                    firstPContainer.append(new1p);
                });

                // --- DEFENSE COUNSEL ---
                const defenseTemplate = $('.defenseToDuplicate').first();
                const defenseContainer = $('.appendDuplicatesForDefense');
                defenseContainer.empty();

                const defense_counsels = (caseData.defense_counsels && caseData.defense_counsels.length > 0) ? caseData.defense_counsels : [{}];

                defense_counsels.forEach((def, index) => {
                    let newDef = defenseTemplate.clone().removeAttr('id');

                    if (def.defense_name) newDef.find('.defenseCounselName').val(def.defense_name);
                    if (def.defense_attorney) newDef.find('.defenseCounselAttorney').val(def.defense_attorney);
                    if (def.defense_address) newDef.find('.defenseCounselAddress').val(def.defense_address);
                    if (def.defense_tel) newDef.find('.defenseCounselTel').val(def.defense_tel);
                    if (def.defense_email) newDef.find('.defenseCounselEmail').val(def.defense_email);
                    if (def.defense_fax) newDef.find('.defenseCounselFax').val(def.defense_fax);


                    const isEmptyDef = [
                        def.defense_name,
                        def.defense_attorney,
                        def.defense_address,
                        def.defense_tel,
                        def.defense_email,
                        def.defense_fax
                    ].every(field => field === null || field === undefined || field === '');

                    if (!isEmptyDef || defense_counsels.length > 1) {
                        newDef.find('.hidden').removeClass('hidden'); // show hidden fields
                    }


                    if (index === defense_counsels.length - 1) {
                        newDef.find('.addClientInfoButtonForDefense')
                            .removeClass('removeClientInfoButtonForDefense bg-[#a51a1a]')
                            .addClass('addClientInfoButtonForDefense bg-[#14548d]')
                            .children().removeClass('fa-minus').addClass('fa-plus');
                    } else {
                        newDef.find('.addClientInfoButtonForDefense')
                            .addClass('removeClientInfoButtonForDefense bg-[#a51a1a]')
                            .removeClass('addClientInfoButtonForDefense bg-[#14548d]')
                            .children().removeClass('fa-plus').addClass('fa-minus');
                    }

                    defenseContainer.append(newDef);
                });



                // --- TREATING CHARTS ---
                const treatingTemplate = $('.treatingToDuplicate').first();
                const treatingContainer = $('.treatingAppendDuplicates');
                treatingContainer.empty();

                const clientsForTreating = (caseData.clients && caseData.clients.length > 0) ? caseData.clients : [{}];

                clientsForTreating.forEach((client, index) => {
                    const treating = client.treating_chart || client.treatingChart || {};
                    const clientName = client.client_name || `Client ${index + 1}`;

                    let newTreating = treatingTemplate.clone().removeAttr('id').attr('data-client', index + 1);

                    newTreating.find('.treatingName p').text(`${clientName} - Treating Chart`);

                    newTreating.find('.ems').val(treating?.ems || '');
                    newTreating.find('.hospital').val(treating?.hospital || '');
                    newTreating.find('.chiropractor').val(treating?.chiropractor || '');
                    newTreating.find('.pcpmd').val(treating?.pcpOrMd || '');
                    newTreating.find('.mriAndResults').val(treating?.mriAndResults || '');
                    newTreating.find('.painManagement').val(treating?.painManagement || '');
                    newTreating.find('.orthoOrSurgery').val(treating?.orthoOrSurgery || '');

                    newTreating.removeClass('hidden');
                    treatingContainer.append(newTreating);
                });

                // --- AFFIDAVIT CHART ---
                const affidavitTemplate = $('.affidavitToDuplicate').first();
                const affidavitContainer = $('.affidavitAppendDuplicates');
                affidavitContainer.empty();

                const clientsForAffidavit = (caseData.clients && caseData.clients.length > 0) ? caseData.clients : [{}];

                clientsForAffidavit.forEach((client, index) => {
                    const clientName = client.client_name || `Client ${index + 1}`;
                    const affidavits = client.affidavits || client.affidavit_chart || [];

                    let newAffidavit = affidavitTemplate.clone()
                        .removeAttr('id')
                        .attr('data-client', index + 1);

                    newAffidavit.find('.affidavitName p').text(`${clientName} - Affidavit Chart`);

                    const innerGridContainer = newAffidavit.find('.toduplicatehere').parent().first();
                    const innerGridTemplate = innerGridContainer.find('.toduplicatehere').first().clone();
                    innerGridContainer.empty();

                    if (affidavits.length > 0) {
                        affidavits.forEach((aff, i) => {
                            let newRow = innerGridTemplate.clone();

                            newRow.find('.providerName').val(aff?.providerName || '');
                            newRow.find('.dateOrdered').val(aff?.dateOrdered || '');
                            newRow.find('.dateReceivedMr').val(aff?.dateReceivedMr || '');
                            newRow.find('.dateReceivedBr').val(aff?.dateReceivedBr || '');
                            newRow.find('.affidavitDateServed').val(aff?.dateServed || '');
                            newRow.find('.affidavitNoticeFiled').val(aff?.noticeFilled || '');
                            newRow.find('.mriAndResults').val(aff?.mri_and_results || '');
                            newRow.find('.controverted').val(aff?.controverted || '');

                            if (i !== affidavits.length - 1) {
                                newRow.find('.addAffidavitButton')
                                    .addClass('removeClientInfoButtonForAffidavit bg-[#a51a1a]')
                                    .removeClass('addAffidavitButton bg-[#14548d]')
                                    .children().removeClass('fa-plus').addClass('fa-minus');
                            }

                            innerGridContainer.append(newRow);
                        });
                    } else {
                        // Append one empty row if no affidavits
                        innerGridContainer.append(innerGridTemplate.clone());
                    }

                    newAffidavit.removeClass('hidden');
                    affidavitContainer.append(newAffidavit);
                });


                // --- NEGOTIATION CHART ---
                const negotiationTemplate = $('.negotiationToDuplicate').first();
                const negotiationContainer = $('.negotiationAppendDuplicates');
                negotiationContainer.empty();

                // Ensure we have at least one "client" to render
                const clientsForNegotiation = (caseData.clients && caseData.clients.length > 0) ? caseData.clients : [{}];

                clientsForNegotiation.forEach((client, index) => {
                    const clientName = client.client_name || `Client ${index + 1}`;
                    const negotiation = client.negotiating_charts || [{}]; // at least one sub negotiation if empty

                    let newNegotiation = negotiationTemplate.clone()
                        .removeAttr('id')
                        .attr('data-client', index + 1);

                    newNegotiation.find('.negotiationName p').text(`${clientName} - Negotiation Chart`);

                    const subGridContainer = newNegotiation.find('.negotiationSubToDuplicate').parent().first();
                    const subGridTemplate = newNegotiation.find('.negotiationSubToDuplicate').first().clone();
                    subGridContainer.empty();

                    negotiation.forEach((sub, i) => {

                        if(sub.medsTotal != null && sub.medsTotal != undefined){
                            newNegotiation.find('.medsTotal').val(sub.medsTotal);
                        }
                        if(sub.medsPviTotal != null && sub.medsPviTotal != undefined){
                            newNegotiation.find('.medsPviTotal').val(sub?.medsPviTotal);
                        }
                        if(sub.negotiationLastOffer != null && sub.negotiationLastOffer != undefined){
                            newNegotiation.find('.negotiationLastOffer').val(sub?.negotiationLastOffer);
                        }
                        if(sub.negotiationLastOfferDate != null && sub.negotiationLastOfferDate != undefined){
                            newNegotiation.find('.negotiationLastOfferDate').val(sub?.negotiationLastOfferDate);
                        }
                        if(sub.negotiationLastDemand != null && sub.negotiationLastDemand != undefined){
                            newNegotiation.find('.negotiationLastDemand').val(sub?.negotiationLastDemand);
                        }
                        if(sub.negotiationLastDemandDate != null && sub.negotiationLastDemandDate != undefined){
                            newNegotiation.find('.negotiationLastDemandDate').val(sub?.negotiationLastDemandDate);
                        }
                        if(sub.physicalPainMentalAnguishText != null && sub.physicalPainMentalAnguishText != undefined){
                            newNegotiation.find('.physicalPainMentalAnguishText').val(sub?.physicalPainMentalAnguishText);
                        }

                        let newSub = subGridTemplate.clone();
                        if (i !== negotiation.length - 1) {
                            newSub.find('.addButtonForNegotiation')
                                .addClass('removeClientInfoButtonForNegotiation bg-[#a51a1a]')
                                .removeClass('addButtonForNegotiation bg-[#14548d]')
                                .children()
                                .removeClass('fa-plus')
                                .addClass('fa-minus');
                        }

                        // Populate bottom row fields if any exist, or always show first row if only one
                        if ((sub.negotiationNameBottom || sub.negotiationDateBottom || sub.negotiationAmountBottom) || (i === 0 && negotiation.length === 1)) {
                            newSub.find('.negotiationNameBottom').val(sub?.negotiationNameBottom || '');
                            newSub.find('.negotiationDateBottom').val(sub?.negotiationDateBottom || '');
                            newSub.find('.negotiationAmountBottom').val(sub?.negotiationAmountBottom || '');
                            subGridContainer.append(newSub);
                        }
                    });

                    newNegotiation.removeClass('hidden');
                    negotiationContainer.append(newNegotiation);
                });


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
        url: "getCategoriesInsurance",
        dataType: 'json',
        success: function (data) {
            // categories
            const categories = data.categories;
            const $categorySelect = $('#todoSectionCategory');
            const $mainCaseStage = $('#mainCaseStage');
            $categorySelect.empty().append('<option value="-1">Select a category</option>');
            $mainCaseStage.empty().append('<option value="-1">Select Stage</option>');
            categories.forEach(category => {
                $categorySelect.append(`<option value="${category.id}">${category.categoryName}</option>`);
                $mainCaseStage.append(`<option value="${category.id}">${category.categoryName}</option>`);
            });

            // insurances
            const insurances = data.insurances || [];
            const $insuranceSelect = $('.threePName');
            const $insuranceSelectTwo = $('.onePName');
            $insuranceSelect.empty().append('<option value="">Insurance Co. Name</option>');
            $insuranceSelectTwo.empty().append('<option value="">Insurance Co. Name</option>');
            insurances.forEach(insurance => {
                $insuranceSelect.append(`<option value="${insurance.insurance_name}">${insurance.insurance_name}</option>`);
                $insuranceSelectTwo.append(`<option value="${insurance.insurance_name}">${insurance.insurance_name}</option>`);
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

function formatDateMDYTwo(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    const month = date.getMonth() + 1; // 0-indexed
    const day = date.getDate();
    const year = date.getFullYear();
    return `${month}-${day}-${year}`;
}
// 🔁 Helper: Make sure only last client row has both + and -
function updateClientButtons() {
    const clients = $('.clientToDuplicate');

    clients.each(function (index) {
        const buttonContainer = $(this).find('.clientButtons');
        buttonContainer.empty();

        // 🧩 CASE 1: Only one client → show only PLUS
        if (clients.length === 1) {
            buttonContainer.append(`
                <span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButton"
                      style="width:22px; height:22px;">
                    <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                </span>
            `);
        }

        // 🧩 CASE 2: Last one → show PLUS and MINUS
        else if (index === clients.length - 1) {
            buttonContainer.append(`
                <span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButton"
                      style="width:22px; height:22px;">
                    <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                </span>
                <span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#a51a1a] removeClientInfoButton"
                      style="width:22px; height:22px; margin-left:6px;">
                    <i class="fa-solid fa-minus text-white text-md leading-none"></i>
                </span>
            `);
        }

        // 🧩 CASE 3: Any other → only MINUS
        else {
            buttonContainer.append(`
                <span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#a51a1a] removeClientInfoButton"
                      style="width:22px; height:22px;">
                    <i class="fa-solid fa-minus text-white text-md leading-none"></i>
                </span>
            `);
        }
    });
}

function updateThreePButtons() {
    const clients = $('.threePToDuplicate');

    clients.each(function (index) {
        const buttonContainer = $(this).find('.clientButtonsThreeP');
        buttonContainer.empty();

        if (index === clients.length - 1) {
            // Last → both + and -
            buttonContainer.append(`
                <span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButtonFor3p"
                      style="width:22px; height:22px;">
                    <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                </span>

            `);
        } else {
            // Others → only -
            buttonContainer.append(`
                <span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#a51a1a] removeClientInfoButtonFor3p "
                      style="width:22px; height:22px;">
                    <i class="fa-solid fa-minus text-white text-md leading-none"></i>
                </span>
            `);
        }
    });
}


function updateOnePButtons() {
    const clients = $('.firstPToDuplicate');

    clients.each(function (index) {
        const buttonContainer = $(this).find('.clientButtonsOneP');
        buttonContainer.empty();

        if (index === clients.length - 1) {
            // Last → both + and -
            buttonContainer.append(`
                <span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButtonFor1p"
                      style="width:22px; height:22px;">
                    <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                </span>

            `);
        } else {
            // Others → only -
            buttonContainer.append(`
                <span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#a51a1a] removeClientInfoButtonFor1p"
                      style="width:22px; height:22px;">
                    <i class="fa-solid fa-minus text-white text-md leading-none"></i>
                </span>
            `);
        }
    });
}

function updateDefenseCounselButtons() {
    const clients = $('.defenseToDuplicate');

    clients.each(function (index) {
        const buttonContainer = $(this).find('.clientButtonsDefense');
        buttonContainer.empty();

        if (index === clients.length - 1) {
            // Last → both + and -
            buttonContainer.append(`
                <span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#14548d] addClientInfoButtonForDefense"
                      style="width:22px; height:22px;">
                    <i class="fa-solid fa-plus text-white text-md leading-none"></i>
                </span>

            `);
        } else {
            // Others → only -
            buttonContainer.append(`
                <span class="flex items-center justify-center w-6 h-6 rounded-full cursor-pointer bg-[#a51a1a] removeClientInfoButtonForDefense "
                      style="width:22px; height:22px;">
                    <i class="fa-solid fa-minus text-white text-md leading-none"></i>
                </span>
            `);
        }
    });
}
