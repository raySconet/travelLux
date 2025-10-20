$(document).ready(() => {
    getSectionsAndTodos();

    // Add Edit Buttons
    $(document).on('click', '.addClientInfoButton', function() {
        let container = $(this).parent().parent();
        let newItem = container.clone(); // duplicate first

        newItem.find("input, textarea, select").each(function () {
            // clear value
            $(this).val("");
        });

        $(this).removeClass("addClientInfoButton bg-[#14548d]")
            .addClass("removeClientInfoButton bg-[#a51a1a]")
            .children()
            .removeClass("fa-plus")
            .addClass("fa-minus");
        $(".appendDuplicates").append(newItem); // add to end

        let newTreating = $("#treatingToDuplicate").clone(); // duplicate first
        newTreating.find("input, textarea, select").each(function () {
            // clear value
            $(this).val("");
        });
        $(".treatingAppendDuplicates").append(newTreating); // add to end

    });

    $(document).on('click', '.removeClientInfoButton', function() {
        let btn = $('.removeClientInfoButton');
        // disable button temporarily
        btn.prop('disabled', true);
        $(this).parent().parent().remove();

        // enable button again after 1 second
        setTimeout(() => {
            btn.prop('disabled', false);
        }, 500);
    });

    $(document).on('click', '.addClientInfoButtonFor3p', function() {
        let container = $(this).parent().parent();
        let newItem = container.clone(); // duplicate first
        $(this).removeClass("addClientInfoButtonFor3p bg-[#14548d]")
            .addClass("removeClientInfoButtonFor3p bg-[#a51a1a]")
            .children()
            .removeClass("fa-plus")
            .addClass("fa-minus");
        $(".appendDuplicatesFor3p").append(newItem); // add to end
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
        let newItem = container.clone(); // duplicate first
        $(this).removeClass("addClientInfoButtonFor1p bg-[#14548d]")
            .addClass("removeClientInfoButtonFor1p bg-[#a51a1a]")
            .children()
            .removeClass("fa-plus")
            .addClass("fa-minus");
        $(".appendDuplicatesFor1p").append(newItem); // add to end
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
        let newItem = container.clone(); // duplicate first
        $(this).removeClass("addClientInfoButtonForDefense bg-[#14548d]")
            .addClass("removeClientInfoButtonForDefense bg-[#a51a1a]")
            .children()
            .removeClass("fa-plus")
            .addClass("fa-minus");
        $(".appendDuplicatesForDefense").append(newItem); // add to end
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
        $(this).removeClass("addButtonForNegotiation bg-[#14548d]")
            .addClass("removeClientInfoButtonForNegotiation bg-[#a51a1a]")
            .children()
            .removeClass("fa-plus")
            .addClass("fa-minus");
        $(this).parent().parent().parent().append(newItem); // add to end
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
    $('#openManageSectionModal').on('click', function() {
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
        $.ajax({
            type: 'POST',
            url: "sections/store",
            data: $('#addManageSectionForm').serialize(),
            dataType: 'json',
            success: function (response) {
                $("#addManageSectionForm")[0].reset();
                $('#addManageSectionsModal').addClass('hidden');
                // $('#modalSuccessContent').html(response.message);
                // $('#successModal').removeClass('hidden');
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
                    html += ` <h2  class="text-lg  addNewToDo text-center"  dataId="${section.id}" >${section.title} <i class="fa-regular fa-pen-to-square" style="margin-left:5px; cursor:pointer;"></i></h2>`;
                    html += `<p class="mb-1 text-md  ">${section.description}</p>`;
                    html += `</div>`;
                });
                $('#displayTodosHere').html(html);
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr.responseText);
        }
    });
}
