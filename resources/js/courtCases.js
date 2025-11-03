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
        drawCategories();
        $("#addManageSectionForm")[0].reset();
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
                $("#addManageSectionForm")[0].reset();
                $('#addManageSectionsModal').addClass('hidden');
                getSectionsAndTodos();
                // $('#modalSuccessContent').html(response.message);
                // $('#successModal').removeClass('hidden');
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
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
                                <i class="fa-solid addNewToDo fa-notes-medical" title ="Add new todo" style="margin-left:10px; cursor:pointer;"></i>
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
                                                <div class="2xl:col-span-11 flex flex-col ">`;
                                                    if (todo.title != null && todo.title != 'NULL' && todo.title != '') {
                                html += `
                                                    <div class="2xl:col-span-12 flex flex-col ">
                                                        <p>${todo.title}</p>
                                                    </div>
                                        `;
                                                    }
                                html += `
                                                    <p style="color:#a22323 !important;">${todo.description}</p>
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
                                        <div class="grid grid-cols-1 2xl:grid-cols-12 gap-4 w-full">
                                            <div class="2xl:col-span-1 flex flex-col ">
                                                <button title="Mark task as complete" data-id="${todo.id}" class="incompleteButton buttonClickForComplete">
                                                    <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                                                </button>
                                            </div>
                                            <div class="2xl:col-span-11 flex flex-col ">
                                                <p style="color:#a22323 !important;" >${todo.title ?? ''}</p>
                                                <p style="color:#a22323 !important;" class="text-sm ">${todo.description ?? ''}</p>
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
                    response.completed.forEach(todo => {
                        completedHtml += `
                                    <div  class=" mt-1" >
                                      <p><b>${formatDateMDY(todo.completeDate)}</b></p>
                                        <div class="grid grid-cols-1 2xl:grid-cols-12 gap-4 w-full">
                                            <div class="2xl:col-span-1 flex flex-col ">
                                                <button title="Mark task as complete" data-id="${todo.id}" class="completeButton ">
                                                    <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                                                </button>
                                            </div>
                                            <div class="2xl:col-span-11 flex flex-col ">`;
                                                if (todo.title != null && todo.title != 'NULL' && todo.title != '') {

                                        completedHtml += ` <p class="font-semibold"> ${todo.title ?? ''}</p> `;
                                                }
                                        completedHtml += `
                                                <p style="color:#a22323 !important;">${todo.description}</p>
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

function drawCategories() {
    $.ajax({
        type: 'GET',
        url: "getCategories",
        dataType: 'json',
        success: function (data) {
            console.log(data)
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
