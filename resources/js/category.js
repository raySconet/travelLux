$(document).ready(() => {
    getUsers(function () {
        getEventsCases(function(categories, permissions) {
            // console.log("Received data:", data);
            renderEventCases(categories, permissions);
        });
    });

    $('#openDrawer').on('click', function () {
        $('#sidebarDrawer').removeClass('-translate-x-full');

        $('#categoryViewSection')
            .removeClass('grid-cols-1 xl:grid-cols-[100px_1fr]')
            .addClass('grid-cols-12');

        $('#categorySidebar')
            .removeClass('col-span-1')
            .addClass('col-span-12 xl:col-span-3');

        $('#categoryLayout')
            .addClass('col-span-12 xl:col-span-9');

        $('#categorySidebar > div > div').addClass('min-h-[750px]'); // sm:min-h-[120px]
        // $('#categorySidebar > div').removeClass('h-full');
    });

    $('#closeDrawer').on('click', function () {
        $('#sidebarDrawer').addClass('-translate-x-full');

        setTimeout(function () {
            $('#categoryViewSection')
                .removeClass('grid-cols-12')
                .addClass('grid-cols-1 xl:grid-cols-[100px_1fr]');

            $('#categorySidebar')
                .removeClass('col-span-12 xl:col-span-3');

            $('#categoryLayout')
                .removeClass('col-span-12 xl:col-span-9');

            $('#categorySidebar > div > div').removeClass('min-h-[750px]');
            // $('#categorySidebar > div').addClass('h-full');
        }, 100);
    });

    $(document).on('click', '.flip', function () {
        const panelSelector = $(this).data("target");
        const panel = $(panelSelector);
        const isOpen = panel.is(":visible");

        if(isOpen) {
            $(this).addClass('rounded').removeClass('rounded-t');
        } else {
            $(this).removeClass('rounded').addClass('rounded-t');
        }

        // Toggle icons
        $(this).children(".fa-chevron-right").toggle(isOpen);
        $(this).children(".fa-chevron-down").toggle(!isOpen);

        // Toggle panel
        panel.slideToggle();
    });

    $(document).on('change', 'input[type="checkbox"][data-user-id]', function () {
        const allCheckboxes = $('input[type="checkbox"][data-user-id]');

        // Uncheck all others
        allCheckboxes.not(this).prop('checked', false);

        // Check the clicked one
        $(this).prop('checked', true);
        getEventsCases(function(categories, permissions) {
            // console.log("Received data:", data);
            renderEventCases(categories, permissions);
        });

        // console.log('Selected User ID:', $(this).data('user-id'));
    });

    $('#openAddCategoryModal').on('click', function() {
        $('.input-error-text').remove();
        $('input, select').removeClass('border-red-500');
        $('.colorBox').removeClass('border border-red-500');
        $('.colorBox').css('background-color', '#14548d');
        $('.colorInput').val('#14548d');
        $('.colorBox').spectrum({
            color: "#14548d",
            showPalette: true,
            showInput: true,
            allowEmpty: false,
            preferredFormat: "hex",
            showInitial: true,
            palette: [
                ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF'],
                ['#000000', '#FFFFFF', '#808080', '#C0C0C0', '#800000', '#808000'],
                ['#3490dc', '#f97316', '#10b981', '#8b5cf6', '#f43f5e', '#eab308'],
                ['#1abc9c', '#2ecc71', '#9b59b6', '#34495e', '#16a085', '#27ae60'],
                ['#e67e22', '#e74c3c', '#f39c12', '#d35400', '#c0392b', '#f1c40f'],
                ['#2980b9', '#3498db', '#5dade2', '#85c1e9', '#aed6f1', '#d6eaf8'],
                ['#a0522d', '#cd853f', '#deb887', '#f5deb3', '#ffe4b5', '#faf0e6'],
                ['#ffb6c1', '#ffcccb', '#ffe4e1', '#e0ffff', '#d8bfd8', '#e6e6fa'],
                ['#39ff14', '#ccff00', '#ff073a', '#fe019a', '#ff6ec7', '#dfff00'],
                ['#6c757d', '#adb5bd', '#ced4da', '#dee2e6', '#e9ecef', '#f8f9fa'],
                ['#2e8b57', '#3cb371', '#6b8e23', '#556b2f', '#228b22', '#66cdaa'],
                ['#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997']
            ],

            // Make it act like an input
            change: function(color) {
            const hex = color.toHexString();
            $('.colorBox').css('background-color', hex);     // Update square visually
            $('.colorInput').val(hex);                       // Store value (optional)
            console.log("Selected color:", hex);
            },
            move: function(color) {
            // Live update while dragging
            $('.colorBox').css('background-color', color.toHexString());
            },
            showButtons: false,
            show: function () {
                $('.sp-container').addClass('z-50'); // Optional: bring to front if overlapping UI
            }
        });
        $('#addCategoryModal').removeClass('hidden');
    });

    // Close modal
    $('#closeAddCategoryModal').on('click', function() {
        $('#addCategoryModal').addClass('hidden');
    });

     $('#closeEditCategoryModal').on('click', function() {
        $('#editCategoryModal').addClass('hidden');
    });

    $('#closeErrorModal').on('click', function() {
        $('#errorModal').addClass('hidden');
    });

    $('#closeSuccessModal').on('click', function() {
        $('#successModal').addClass('hidden');
    });

    $('#closeCategoryDeleteConfirmModal, #cancelCategoryDeleteBtn').on('click', function() {
        $('#deleteCategoryConfirmModal').addClass('hidden');
    });

    // Optional: Close when clicking outside modal content
    $('#addCategoryModal, #errorModal, #successModal, #editCategoryModal, #deleteCategoryConfirmModal').on('click', function(e) {
        if ($(e.target).is(this)) {
            $(this).addClass('hidden');
        }
    });

    // Initialize Spectrum on the square (div)
    $('.colorBox').css('background-color', '#14548d');
    $('.colorBox').spectrum({
        color: "#14548d",
        showPalette: true,
        showInput: true,
        allowEmpty: false,
        preferredFormat: "hex",
        showInitial: true,
        palette: [
            ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF'],
            ['#000000', '#FFFFFF', '#808080', '#C0C0C0', '#800000', '#808000'],
            ['#3490dc', '#f97316', '#10b981', '#8b5cf6', '#f43f5e', '#eab308'],
            ['#1abc9c', '#2ecc71', '#9b59b6', '#34495e', '#16a085', '#27ae60'],
            ['#e67e22', '#e74c3c', '#f39c12', '#d35400', '#c0392b', '#f1c40f'],
            ['#2980b9', '#3498db', '#5dade2', '#85c1e9', '#aed6f1', '#d6eaf8'],
            ['#a0522d', '#cd853f', '#deb887', '#f5deb3', '#ffe4b5', '#faf0e6'],
            ['#ffb6c1', '#ffcccb', '#ffe4e1', '#e0ffff', '#d8bfd8', '#e6e6fa'],
            ['#39ff14', '#ccff00', '#ff073a', '#fe019a', '#ff6ec7', '#dfff00'],
            ['#6c757d', '#adb5bd', '#ced4da', '#dee2e6', '#e9ecef', '#f8f9fa'],
            ['#2e8b57', '#3cb371', '#6b8e23', '#556b2f', '#228b22', '#66cdaa'],
            ['#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997']
        ],

        // Make it act like an input
        change: function(color) {
        const hex = color.toHexString();
        $('.colorBox').css('background-color', hex);     // Update square visually
        $('.colorInput').val(hex);                       // Store value (optional)
        console.log("Selected color:", hex);
        },
        move: function(color) {
        // Live update while dragging
        $('.colorBox').css('background-color', color.toHexString());
        },
        showButtons: false,
        show: function () {
            $('.sp-container').addClass('z-50'); // Optional: bring to front if overlapping UI
        }
    });

    // Open picker on click
    $('.colorBox').on('click', function () {
        $(this).spectrum('show');
    });

    // Optional: hide picker when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.colorBox, .sp-container').length) {
            $('.colorBox').spectrum('hide');
        }
    });

    // add category steps

    $('#submitCategoryBtn').on('click', function () {
        const $form = $('#addCategoryForm');
        const $button = $('#submitCategoryBtn');

        // Clear previous inline errors & modal errors
        $('#modalErrorContent').empty();
        $('#errorModal').addClass('hidden');

        $button.prop('disabled', true).text('Saving...');
        // console.log($form.serialize());
        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: $form.serialize(),
            dataType: 'json',
            success: function (response) {
                $form[0].reset();
                $('.colorBox').css('background-color', '#14548d;');
                $('#addCategoryModal').addClass('hidden');
                $('#modalSuccessContent').html(response.message);
                $('#successModal').removeClass('hidden');
                getEventsCases(function(categories, permissions) {
                    renderEventCases(categories, permissions);
                });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};

                    $('.input-error-text').remove();
                    $form.find('input, select, textarea').removeClass('border-red-500');

                    // if (errors.name) {
                    //     let errorHtml = '<ul class="text-sm text-red-600 space-y-1">';
                    //     errors.name.forEach(function (error) {
                    //         errorHtml += `<li>${error}</li>`;
                    //     });
                    //     errorHtml += '</ul>';
                    //     $('#errorCategoryName').html(errorHtml);
                    // }
                    $.each(errors, function (field, messages) {
                        let $input = $form.find(`[name="${field}"]`);

                        if ($input.length === 0) {
                            const baseField = field.split('.')[0];
                            $input = $form.find(`[name="${baseField}[]"], [name="${baseField}"]`);
                        }

                        $input.addClass('border-red-500');

                        if ($input.next('.input-error-text').length === 0) {
                            $input.after(`<p class="input-error-text text-red-600 text-sm mt-1">${messages[0]}</p>`);
                        }

                        if ($input.attr('name') === 'color') {
                            $('.colorBox').addClass('border border-red-500');
                        }
                    });
                } else {
                    $('#modalErrorContent').html('An unexpected error occurred. Please try again.');
                    $('#errorModal').removeClass('hidden');
                }
            },
            complete: function () {
                $button.prop('disabled', false).text('Add Category');
            }
        });
    });

    $(document).on('click', '.editCategoryBtn', function(e) {
        e.stopPropagation();

        $('#editCategoryForm')[0].reset();
        $('.input-error-text').remove();
        $('input').removeClass('border-red-500');
        $('.colorBox').removeClass('border border-red-500');

        const categoryId = $(this).data('id');
        console.log("ssss", categoryId);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '/getCategories',
            data: {
                category_id: categoryId,
            },
            dataType: 'json',
            success: function (response) {
                console.log(response); // still useful for debugging

                const category = response[0]; // Get the first (and presumably only) category

                if (category) {
                    $('input[name="nameEditCategory"]').val(category.categoryName);
                    $('#submitEditCategoryBtn').data('id', category.id);
                    // $('#name').val(category.categoryName);
                    $('.colorInput').val(category.color);
                    $('.colorBox').css('background-color', category.color);
                    $('.colorBox').spectrum({
                        color: category.color,
                        showPalette: true,
                        showInput: true,
                        allowEmpty: false,
                        preferredFormat: "hex",
                        showInitial: true,
                        palette: [
                            ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF'],
                            ['#000000', '#FFFFFF', '#808080', '#C0C0C0', '#800000', '#808000'],
                            ['#3490dc', '#f97316', '#10b981', '#8b5cf6', '#f43f5e', '#eab308'],
                            ['#1abc9c', '#2ecc71', '#9b59b6', '#34495e', '#16a085', '#27ae60'],
                            ['#e67e22', '#e74c3c', '#f39c12', '#d35400', '#c0392b', '#f1c40f'],
                            ['#2980b9', '#3498db', '#5dade2', '#85c1e9', '#aed6f1', '#d6eaf8'],
                            ['#a0522d', '#cd853f', '#deb887', '#f5deb3', '#ffe4b5', '#faf0e6'],
                            ['#ffb6c1', '#ffcccb', '#ffe4e1', '#e0ffff', '#d8bfd8', '#e6e6fa'],
                            ['#39ff14', '#ccff00', '#ff073a', '#fe019a', '#ff6ec7', '#dfff00'],
                            ['#6c757d', '#adb5bd', '#ced4da', '#dee2e6', '#e9ecef', '#f8f9fa'],
                            ['#2e8b57', '#3cb371', '#6b8e23', '#556b2f', '#228b22', '#66cdaa'],
                            ['#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997']
                        ],

                        // Make it act like an input
                        change: function(color) {
                        const hex = color.toHexString();
                        $('.colorBox').css('background-color', hex);     // Update square visually
                        $('.colorInput').val(hex);                       // Store value (optional)
                        console.log("Selected color:", hex);
                        },
                        move: function(color) {
                        // Live update while dragging
                        $('.colorBox').css('background-color', color.toHexString());
                        },
                        showButtons: false,
                        show: function () {
                            $('.sp-container').addClass('z-50'); // Optional: bring to front if overlapping UI
                        }
                    });
                }

                // $('#submitEditCategoryBtn').attr('data-id', category.id);
            },
            error: function (xhr) {
                console.error(`Error fetching category name:`, xhr);

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
            }
        });

        $('#editCategoryModal').removeClass('hidden');
    });

    $(document).on('click', '#submitEditCategoryBtn', function() {
        const id = $(this).data('id');

        const routes = {
            CategoryUpdate: (id) => `categoryUpdate/${id}`,
        };

        let actionUrl = routes.CategoryUpdate(id);

        submitEditCategory(actionUrl);
    });

    $(document).on('click', '.deletedCategoryBtn', function(e) {
        e.stopPropagation();

        const categoryId = $(this).data('id');
        console.log(categoryId);

        let actionUrl = '/category/delete';
        let method = 'POST';

        $('#confirmCategoryDeleteBtn')
        .data('action-url', actionUrl)
        .data('method', method)
        .data('category-id', categoryId);

        $('#deleteCategoryConfirmModal').removeClass('hidden');
    });

    $(document).on('click', '#confirmCategoryDeleteBtn', function() {
        const actionUrl = $(this).data('action-url');
        const categoryId = $(this).data('category-id');

        deleteCategory(actionUrl, categoryId);
    });
    // $('#deletedCategoryBtn')
    // const data = getEventsCases();
    // renderEventCases(data);
});

function getEventsCases(callback) {
    const checkedBox = $('.lawyersCheckboxSection input[type="checkbox"]:checked');
    const userId = checkedBox.data('user-id');

    if (!userId) {
        $('#modalErrorContent').text('Please select a user first.');
        $('#errorModal').removeClass('hidden');
        return;
    }

    $.ajax({
        url: '/getEventsCases',
        data: { user_id: userId },
        method: 'GET',
        success: function (response) {
            console.log(response);
            if (typeof callback === 'function') {
                callback(response.categories, response.permissions);
            }
        },
        error: function (xhr) {
            const errorMsg = xhr.responseJSON?.error || 'Error loading data';
            $('#modalErrorContent').text(errorMsg);
            $('#errorModal').removeClass('hidden');
        }
    });
}

function renderEventCases(categories, permissions) {
    const container = $("#categoryLayoutContent");
    container.empty();
    console.log(categories, permissions);

    categories.forEach(category => {
        const itemCount = category.items.length;

        const baseColor = category.colorClass;
        const darkerBorderColor = darkenHexColor(baseColor, 20);

        const borderStyle = `style="border-color: ${darkerBorderColor}; background-color: ${baseColor};"`;

        const editButtonHtml = permissions.can_edit ? `
            <button title="Edit" class="editCategoryBtn text-[limegreen] hover:text-green-800 cursor-pointer" data-id="${category.id}">
                <i class="fa-solid fa-pen-to-square shadow-lg fa-lg"></i>
            </button>
        ` : '';

        const deleteButtonHtml = permissions.can_delete ? `
            <button title="Delete" class="deletedCategoryBtn text-red-600 hover:text-red-800 ml-2 cursor-pointer" data-id="${category.id}">
                <i class="fa-solid fa-trash fa-lg"></i>
            </button>
        ` : '';

        // it was w-13 h-28 - added h-35px
        const $categoryTitle = $(`
            <div
                id="flip${category.id}"
                data-target="#${category.panelId}"
                class="flex items-center gap-2 flip bg-[#e4e9eec7] text-gray-900 py-2 px-4 rounded cursor-pointer h-[35px]"
            >
                <i class="fa-solid fa-chevron-right w-[15px] h-[15px]"></i>
                <i class="fa-solid fa-chevron-down w-[15px] h-[15px]" style="display:none"></i>
                <div class="w-[10px] h-[22px] border" ${borderStyle}></div>
                <label>${category.label}: ${itemCount} item(s)</label>

                <div class="ml-auto">
                    ${editButtonHtml}
                    ${deleteButtonHtml}
                </div>
            </div>
        `);

        // Category content panel bg-[#eaf1ff] border border-[#cacaff]
        const $categoryContent = $(`
            <div
                id="${category.panelId}"
                class="panel bg-[#f8f9fb3d] text-gray-800 p-2"
                style="display:none"
            ></div>
        `);

        if (itemCount === 0) {
            const $noItemsPanel = $(`
                <div class="grid grid-cols-12 gap-2 mb-1">
                    <div class="col-span-12 my-auto italic text-gray-400 text-center py-2">No events/cases here</div>
                </div>
            `);
            $categoryContent.append($noItemsPanel);
        } else {
            category.items.forEach(item => {
                const title = `${item.description.atty_initials}${item.description.stage_of_process ? ' - ' + item.description.stage_of_process : ''}${item.description.client_name ? ' - ' + item.description.client_name : ''}`
                const fromFormatted = formatCustomDate(item.from);
                const toFormatted = formatCustomDate(item.to);
                // it was w-13 h-28
                const $panel = $(`
                    <div class="grid grid-cols-12 gap-2 mb-1">
                        <div class="col-span-2 flex gap-2">
                            <div class="w-[10px] h-[22px] border my-auto" style="background-color: ${baseColor}; border-color: ${darkerBorderColor};"></div>
                            <label class="my-auto">${item.tag}</label>
                        </div>
                        <div class="col-span-6 my-auto">${title}</div>
                        <div class="col-span-2 my-auto">${fromFormatted}</div>
                        <div class="col-span-2 my-auto">${toFormatted}</div>
                    </div>
                `);
                $categoryContent.append($panel);
            });
        }

        const $categoryDiv = $('<div class="mb-1"></div>');
        $categoryDiv.append($categoryTitle, $categoryContent);
        container.append($categoryDiv);
    });
}

// Darken hex color by a percentage (0-100)
function darkenHexColor(hex, percent) {
    // Remove #
    hex = hex.replace('#', '');
    // Parse r,g,b
    let r = parseInt(hex.substring(0,2), 16);
    let g = parseInt(hex.substring(2,4), 16);
    let b = parseInt(hex.substring(4,6), 16);
    // Darken each channel
    r = Math.floor(r * (1 - percent/100));
    g = Math.floor(g * (1 - percent/100));
    b = Math.floor(b * (1 - percent/100));
    // Convert back to hex string
    r = r.toString(16).padStart(2, '0');
    g = g.toString(16).padStart(2, '0');
    b = b.toString(16).padStart(2, '0');
    return `#${r}${g}${b}`;
}

function getUsers(callback) {
    $.ajax({
        url: '/getUsers',
        method: 'GET',
        success: function (response) {
            console.log('Users response:', response);
            let users = response.users || [];
            const authUserId = response.auth_user_id;
            const assignedUserIds = response.assigned_user_ids || [];

            const authUser = users.find(user => user.id === authUserId);

            // If permission is "user", only show their own data
            if (authUser?.userPermission === 'user' || authUser?.userPermission === 'admin') { // added new authUser?.userPermission === 'admin'
                users = users.filter(user =>
                    user.id === authUserId || assignedUserIds.includes(user.id)
                );

                const authUserIndex = users.findIndex(user => user.id === authUserId);
                if (authUserIndex > -1) {
                    const [authUserData] = users.splice(authUserIndex, 1);
                    users.unshift(authUserData);
                }
            } else {
                // Move auth user to top of list
                const authUserIndex = users.findIndex(user => user.id === authUserId);
                if (authUserIndex > -1) {
                    const [authUserData] = users.splice(authUserIndex, 1);
                    users.unshift(authUserData);
                }
            }
            // Move auth user to the top of the list
            // const authUserIndex = users.findIndex(user => user.id === authUserId);
            // if (authUserIndex > -1) {
            //     const [authUser] = users.splice(authUserIndex, 1);
            //     users.unshift(authUser);
            // }

            const $lawyersList = $('#lawyersList');
            $lawyersList.empty();

            users.forEach(user => {
                const isChecked = user.id === authUserId || user.isChecked;
                const checked = isChecked ? 'checked' : '';

                // addded h-35px
                const lawyerHtml = `
                    <li>
                        <label class="lawyersCheckboxSection w-full p-3 bg-[#eaf1ff] rounded-lg shadow flex items-center justify-between hover:bg-[#dce9ff] transition duration-200 ease-in-out cursor-pointer h-[35px]">
                            <span class="text-sm font-medium text-gray-900">${user.name}</span>
                            <input type="checkbox" data-user-id="${user.id}" ${checked} />
                        </label>
                    </li>
                `;
                $lawyersList.append(lawyerHtml);
            });
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
        },
        complete: function () {
            if (typeof callback === 'function') {
                callback();
            }
        }
    });
}

function formatCustomDate(dateStr) {
    if (!dateStr) return '';

    const hasTime = dateStr.includes('T') || dateStr.includes(' ');
    let date;

    if (hasTime) {
        date = new Date(dateStr);
    } else {
        // Create a date in local timezone at 12:00 AM
        const parts = dateStr.split('-');
        const year = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1; // Month is 0-based
        const day = parseInt(parts[2], 10);
        date = new Date(year, month, day, 0, 0, 0);
    }

    if (isNaN(date)) return dateStr;

    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const day = days[date.getDay()];

    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const dd = String(date.getDate()).padStart(2, '0');
    const yyyy = date.getFullYear();

    let hours = date.getHours();
    let minutes = date.getMinutes();
    let ampm = 'AM';

    if (hours >= 12) {
        ampm = 'PM';
        if (hours > 12) hours -= 12;
    } else if (hours === 0) {
        hours = 12;
    }

    const hh = String(hours).padStart(2, '0');
    const min = String(minutes).padStart(2, '0');

    return `${day} ${mm}/${dd}/${yyyy} ${hh}:${min}${ampm}`;
}

function submitEditCategory(actionUrl) {
    const $form = $('#editCategoryForm');
    const $button = $('#submitEditCategoryBtn');

    // console.log($form.serialize());
    // console.log('Name sent:', $form.find('input[name="nameEditCategory"]').val());
    // console.log('Color sent:', $form.find('input[name="color"]').val());


    $('#modalErrorContent').empty();
    $('#errorModal').addClass('hidden');

    $button.prop('disabled', true).html(`
        <i class="fa-solid fa-spinner fa-spin"></i>
        <span class="ml-1">Saving...</span>
    `);

    $('.input-error-text').remove();
    $('input, select').removeClass('border-red-500');

    $.ajax({
        type: 'POST',
        url: actionUrl,
        data: $form.serialize(),
        dataType: 'json',
        success: function (response) {
            $form[0].reset();
            $('#editCategoryModal').addClass('hidden');
            $('#modalSuccessContent').html(response.message);
            $('#successModal').removeClass('hidden');
            getEventsCases(function(categories, permissions) {
                renderEventCases(categories, permissions);
            });
        },
        error: function (xhr) {
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                const errors = xhr.responseJSON.errors;

                // Loop over errors and display inline
                $.each(errors, function (field, messages) {
                    let $input = $form.find(`[name="${field}"]`);

                    if ($input.length === 0) {
                        const baseField = field.split('.')[0];
                        $input = $form.find(`[name="${baseField}[]"], [name="${baseField}"]`);
                    }

                    $input.addClass('border-red-500');

                    if ($input.next('.input-error-text').length === 0) {
                        $input.after(`<p class="input-error-text text-red-600 text-sm mt-1">${messages[0]}</p>`);
                    }

                    if ($input.attr('name') === 'color') {
                        $('.colorBox').addClass('border border-red-500');
                    }
                });
            } else if (xhr.responseJSON?.error) {
                // For 403 or other custom errors
                $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">${xhr.responseJSON.error}</p>`);
                $('#editCategoryModal').addClass('hidden');
                $('#errorModal').removeClass('hidden');
            } else {
                // Fallback error message
                $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">An unexpected error occurred.</p>`);
                $('#editCategoryModal').addClass('hidden');
                $('#errorModal').removeClass('hidden');
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

function deleteCategory(actionUrl, categoryId) {
    const $button = $('#confirmCategoryDeleteBtn');

    $('#deleteCategoryConfirmModal').addClass('hidden');
    $('#modalErrorContent').empty();
    $('#errorModal').addClass('hidden');

    $button.prop('disabled', true).html(`
        <i class="fa-solid fa-spinner fa-spin"></i>
        <span class="ml-1">
            Deleting...
        </span>
    `);

    $.ajax({
        type: 'POST',
        url: actionUrl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            category_id: categoryId,
        },
        dataType: 'json',
        success: function (response) {
            $('#modalSuccessContent').html(response.message);
            $('#successModal').removeClass('hidden');
            getEventsCases(function(categories, permissions) {
                renderEventCases(categories, permissions);
            });
        },
        error: function (xhr) {
            let jsonResponse = xhr.responseJSON;

            if (xhr.status === 422 && jsonResponse.errors) {
                const errors = jsonResponse.errors;

                // Loop over validation errors and display them
                $.each(errors, function (field, messages) {
                    let $input = $(`[name="${field}"]`);
                    if ($input.length === 0) {
                        const baseField = field.split('.')[0];
                        $input = $(`[name="${baseField}[]"], [name="${baseField}"]`);
                    }

                    $input.addClass('border-red-500');

                    if ($input.next('.input-error-text').length === 0) {
                        $input.after(`<p class="input-error-text text-red-600 text-sm mt-1">${messages[0]}</p>`);
                    }
                });
            } else if (jsonResponse && jsonResponse.message) {
                // Show backend message returned on other error statuses (like 404 or your custom logic)
                $('#modalErrorContent').html(jsonResponse.message);
                $('#errorModal').removeClass('hidden');
            } else if (xhr.responseJSON?.error) {
                $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">${xhr.responseJSON.error}</p>`);
                $('#editCategoryModal').addClass('hidden');
                $('#errorModal').removeClass('hidden');
            } else {
                // Generic fallback
                $('#modalErrorContent').html("An error occurred while deleting the category.");
                $('#errorModal').removeClass('hidden');
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
