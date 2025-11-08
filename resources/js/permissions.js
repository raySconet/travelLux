let changedPermissions = {};

$(document).ready(function() {
    $('#searchByName').val('');
    $('input[id="searchByName"]').on('keyup', filterUsers);
    filterUsers();

    $('#addUserModal div').removeClass('max-w-2xl').addClass('max-w-xl');
    $('#assignViewAccessModal div').removeClass('max-w-2xl').addClass('max-w-xl');
    $('#editUserModal div').removeClass('max-w-2xl').addClass('max-w-xl');

    $('#closeErrorModal').on('click', function() {
        $('#errorModal').addClass('hidden');
    });

    $('#closeSuccessModal').on('click', function() {
        $('#successModal').addClass('hidden');
    });

    $('#closeUserDeleteConfirmModal, #cancelUserDeleteBtn').on('click', function () {
        $('#deleteUserConfirmModal').addClass('hidden');
    });

    $("#openAddUserModal").on('click', function() {
        $('#addUserModal').removeClass('hidden');
    });

    $("#closeAddUserModal").on('click', function() {
        const $form = $('#addUserForm');
        $('#addUserModal').addClass('hidden');

        $form[0].reset();

        $form.find('.input-error-text').remove();
        $form.find('.border-red-500').removeClass('border-red-500');
    });

    $("#closeEditUserModal").on('click', function() {
        const $form = $('#editUserForm');
        $('#editUserModal').addClass('hidden');

        $form[0].reset();

        $form.find('.input-error-text').remove();
        $form.find('.border-red-500').removeClass('border-red-500');
    });

    $(document).on('click', '.modal', function (e) {
        if ($(e.target).is(this)) {
            $(this).addClass('hidden');

            // Check which modal it is and reset the correct form
            if (this.id === 'addUserModal') {
                resetUserForm($('#addUserForm'));
            } else if (this.id === 'editUserModal') {
                resetUserForm($('#editUserForm'));
            }
        }
    });

    $(document).on('change', 'input[type=radio][name^="permissions"]', function() {
        const userId = $(this).attr('name').match(/\d+/)[0];
        const permission = $(this).val();
        changedPermissions[userId] = permission;
    });

    $('#permissionsForm').on('submit', function(e) {
        e.preventDefault();

        if (Object.keys(changedPermissions).length === 0) {
            $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">No changes to save.</p>`);
            $('#errorModal').removeClass('hidden');
            return;
        }

        const form = $(this);
        const url = form.attr('action');
        // const formData  = form.serialize();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: url,
            data: {
                permissions: changedPermissions,
            },
            success: function(response) {
                const results = response.results;
                const errorMessages = [];

                for (const userId in results) {
                    if (results[userId].status === 'error') {
                        errorMessages.push(`<li class="text-red-600">${results[userId].message}</li>`);
                    } else { // new
                        // update the UI immediately for successful changes
                        const newPermission = changedPermissions[userId];
                        $(`input[name="permission_${userId}"][value="${newPermission}"]`).prop('checked', true);
                    }
                }

                if (errorMessages.length === 0) {
                    $('#modalSuccessContent').html('<p class="text-gray-900 text-sm">Successfully updated.</p>');
                } else {
                    $('#modalSuccessContent').html(`
                        <div class="text-sm text-gray-800 mb-2">Some users could not be updated:</div>
                        <ul class="list-disc list-inside space-y-1">${errorMessages.join('')}</ul>
                    `);
                }
                $('#successModal').removeClass('hidden');
                changedPermissions = {};

                // Hide all modals after 2 seconds (success)
                setTimeout(() => {
                    $('#successModal').addClass("hidden");
                }, 2000);
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'Something went wrong.';
                $('#modalErrorContent').html(`<p>${msg}</p>`);
                $('#errorModal').removeClass('hidden');

                // Hide all modals after 3 seconds (error)
                setTimeout(() => {
                    $('#errorModal').addClass("hidden");
                }, 3000);
            },
            complete: function() {
                // refreshUserRows();
            },
        });
    });

    $(document).on('click', '.deleteUserBtn', function(e) {
        e.preventDefault();
        const userId = $(this).data('user-id');
        $('#confirmUserDeleteBtn').data('user-id', userId);
        $('#deleteUserConfirmModal').removeClass('hidden');
    });

    $('#confirmUserDeleteBtn').on('click', function() {
        const userId = $(this).data('user-id');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'put',
            url: `/users/${userId}`,
            success: function(response) {
                $('#deleteUserConfirmModal').addClass('hidden');
                refreshUserRows();
                // reapplyUserRowStriping();
                $('#modalSuccessContent').html(response.message);
                $('#successModal').removeClass('hidden');

                // Hide all modals after 2 seconds (success)
                setTimeout(() => {
                    $('#successModal').addClass("hidden");
                }, 2000);
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'An unexpected error occurred.';
                $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">${msg}</p>`);
                $('#deleteUserConfirmModal').addClass('hidden');
                $('#errorModal').removeClass('hidden');

                // Hide all modals after 3 seconds (error)
                setTimeout(() => {
                    $('#errorModal').addClass("hidden");
                }, 3000);
            },
        });
    });

    $('#submitAddUserBtn').on('click', function(e) {
        e.preventDefault();
        const form = $('#addUserForm');
        const url = form.attr('action');
        const formData  = form.serialize();
        // console.log(formData);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: url,
            data: formData,
            success: function(response) {
                $('#addUserModal').addClass('hidden');
                form[0].reset();
                refreshUserRows();
                $('#modalSuccessContent').html('<p class="text-gray-900 text-sm">User added successfully.</p>');
                $('#successModal').removeClass('hidden');

                // Hide all modals after 2 seconds (success)
                setTimeout(() => {
                    $('#successModal').addClass("hidden");
                }, 2000);
            },
            error: function(xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;

                    form.find('.input-error-text').remove();
                    form.find('.border-red-500').removeClass('border-red-500');

                    $.each(errors, function (field, messages) {
                        let $input = form.find(`[name="${field}"]`);

                        if ($input.length === 0) {
                            const baseField = field.split('.')[0];
                            $input = form.find(`[name="${baseField}[]"], [name="${baseField}"]`);
                        }

                        $input.addClass('border-red-500');

                        if ($input.next('.input-error-text').length === 0) {
                            $input.after(`<div class="input-error-text text-red-600 text-sm text-left mt-1">${messages[0]}</div>`);
                        }
                    });
                }
            },
        });
    });

    $(document).on('click', '.editUserBtn', function(e) {
        e.preventDefault();
        const userId = $(this).data('user-id');
        // console.log("Edit button clicked for user ID:", userId);

        getUserData(userId, function(err, user) {
            if (err) {
                $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">${err}</p>`);
                $('#errorModal').removeClass('hidden');
                return;
            }

            // $('#editUserId').val(user.id);
            $('#edit_name').val(user.name);
            $('#edit_email').val(user.email);
            $('#edit_password').val("");
            $('#edit_password_confirmation').val("");

            $('#editUserModal').removeClass('hidden');
        });

        $('#submitEditUserBtn').attr('data-edit-user-id', userId);
    });

    $(document).on('click', '#submitEditUserBtn', function(e) {
        e.preventDefault();

        const userId = $(this).attr('data-edit-user-id');
        const form = $('#editUserForm');
        console.log("Form data to be submitted:", form.serialize());
        console.log("Submitting edit for user ID:", userId);
        $.ajax({
            url: `/users/${userId}/update`,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                refreshUserRows();
                $('#editUserModal').addClass('hidden');
                $('#modalSuccessContent').html('<p class="text-gray-900 text-sm">User updated successfully.</p>');
                $('#successModal').removeClass('hidden');

                // Hide success modal after 2 seconds
                setTimeout(() => {
                    $('#successModal').addClass('hidden');
                }, 2000);
            },
            error: function(xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;

                    // Clear old errors first
                    form.find('.input-error-text').remove();
                    form.find('.border-red-500').removeClass('border-red-500');

                    $.each(errors, function (field, messages) {
                        let $input = form.find(`[name="${field}"]`);

                        if ($input.length === 0) {
                            const baseField = field.split('.')[0];
                            $input = form.find(`[name="${baseField}[]"], [name="${baseField}"]`);
                        }

                        $input.addClass('border-red-500');

                        if ($input.next('.input-error-text').length === 0) {
                            $input.after(`<div class="input-error-text text-red-600 text-sm text-left mt-1">${messages[0]}</div>`);
                        }
                    });
                }
            }
        });
    });

    $(document).on('click', '#closeAssignViewAccessModal', function (e) {
        e.preventDefault();
        const $form = $('#assignViewAccessForm');
        $('#assignViewAccessModal').addClass('hidden');

        $form[0].reset();

        $form.find('.input-error-text').remove();
        $form.find('.border-red-500').removeClass('border-red-500');
        $('#assignSelectedUsers').empty();
        $('#assignUserSelect').data('assignSelectedValues', new Set());
        $('#assignUserSelect option').prop('hidden', false);
    });

    $(document).on('click', '.assign-view-access-btn', function (e) {
        e.preventDefault();
        getUsersCategoriesAddEditCasesEvents($(this).attr('data-user-id'));

        $('#submitAssignViewAccessBtn').removeAttr('data-user-id');
        $('#submitAssignViewAccessBtn').attr('data-user-id', $(this).attr('data-user-id'));
        // console.log($('#submitAssignViewAccessBtn').attr('data-user-id'));
        $('#assignViewAccessModal').removeClass('hidden');
    });

    // Remove user when "X" is clicked
    $(document).on('click', '.removeUserSelect', function (e) {
        const $target = $(e.target).closest('.removeUserSelect');
        const userId = $target.attr('data-user-id');
        const $userSelect = $('#assignUserSelect');
        const selectedSet = $userSelect.data('assignSelectedValues');

        if (!userId) {
            console.warn('No userId found on clicked remove icon');
            return;
        }

        $target.parent().remove(); // Remove user from the right side
        selectedSet?.delete(userId); // Remove from the set

        const $option = $userSelect.find(`option[value="${userId}"]`);
        $option.prop('hidden', false);

        // Remove from current value array
        let currentVals = $userSelect.val() || [];
        const newVals = currentVals.filter(val => val !== userId);
        $userSelect.val(newVals);

        // Clear selection to allow re-selecting same item
        setTimeout(() => {
            $userSelect.val(null);
        }, 0);
    });

    // Handle selecting users
    $('#assignUserSelect').on('change', function () {
        const $userSelect = $(this);

        // Initialize Set if not already
        if (!$userSelect.data('assignSelectedValues')) {
            $userSelect.data('assignSelectedValues', new Set());
        }

        const selectedSet = $userSelect.data('assignSelectedValues');
        const val = $userSelect.val();
        if (!val) return;

        val.forEach(v => {
            if (v && v !== '-1' && !selectedSet.has(v)) {
                selectedSet.add(v); // Add to selected set

                const $option = $userSelect.find(`option[value="${v}"]`);
                const label = $option.text();

                $option.prop('hidden', true);

                // Add the user tag to the right-side div
                const $tag = $('<div class="text-sm cursor-default select-none w-full flex justify-between items-center px-2"></div>')
                    .html(`<span>${label}</span>`)
                    .append($(`
                        <i
                            class="removeUserSelect fa-solid fa-xmark fa-lg text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                            title="Remove"
                            data-user-id="${v}">
                        </i>`).addClass('ml-1 text-blue-600'));

                $('#assignSelectedUsers').append($tag);
            }
        });

        // Reset selection to allow re-choosing same users after deletion
        setTimeout(() => {
            $userSelect.val(null);
        }, 0);
    });

    $(document).on('click', '#submitAssignViewAccessBtn', function(e) {
        e.preventDefault();
        const form = $('#assignViewAccessForm');
        const formData  = form.serialize();
        const selectedSet = $('#assignUserSelect').data('assignSelectedValues');
        console.log(formData);
        console.log(selectedSet, $(this).attr('data-user-id')); // Should output array of selected user IDs

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/assignViewAccess',
            data: {
                user_id: $(this).attr('data-user-id'),
                assigned_user_ids: Array.from(selectedSet || []),
            },
            success: function(response) {
                $('#assignViewAccessModal').addClass('hidden');
                form[0].reset();
                $('#assignSelectedUsers').empty();
                $('#assignUserSelect').data('assignSelectedValues', new Set());
                $('#assignUserSelect option').prop('hidden', false);
                refreshUserRows();
                $('#modalSuccessContent').html('<p class="text-gray-900 text-sm">View access assigned successfully.</p>');
                $('#successModal').removeClass('hidden');

                // Hide all modals after 2 seconds (success)
                setTimeout(() => {
                    $('#successModal').addClass("hidden");
                }, 2000);
            },
            error: function(xhr) {
                let errorMsg = 'An unexpected error occurred.';

                if ((xhr.status === 403 || xhr.status === 422) && xhr.responseJSON?.error) {
                    errorMsg = xhr.responseJSON.error;
                }

                $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">${errorMsg}</p>`);
                $('#assignViewAccessModal').addClass('hidden');
                $('#errorModal').removeClass('hidden');

                // Hide all modals after 3 seconds (error)
                setTimeout(() => {
                    $('#errorModal').addClass("hidden");
                }, 3000);
            },
            complete: function() {
                $('#submitAssignViewAccessBtn').removeAttr('data-user-id');
            },
        });
    });
});

function resetUserForm($form) {
    $form[0].reset();
    $form.find('.input-error-text').remove();
    $form.find('.border-red-500').removeClass('border-red-500');
}

function reapplyUserRowStriping() {
    $('.user-row:visible').each(function(index) {
        $(this).removeClass('bg-white bg-[#f3f4f6]');
        $(this).addClass(index % 2 === 0 ? 'bg-white' : 'bg-[#f3f4f6]');
    });
}

function refreshUserRows() {
    $.ajax({
        url: '/users/permissions/partial',
        type: 'GET',
        cache: false,
        success: function(html) {
            $('#userPermissionBody').html(html);
            reapplyUserRowStriping();
            changedPermissions = {};
        },
        error: function() {
            $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">Failed to refresh user list.</p>`);
            $('#errorModal').removeClass('hidden');

            // Hide all modals after 3 seconds (error)
            setTimeout(() => {
                $('#errorModal').addClass("hidden");
            }, 3000);
        }
    });
}

function getUserData(userId, callback) {
    $.ajax({
        url: `/users/${userId}`,
        type: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                callback(null, response.user);
            } else {
                callback(response.message, null);
            }
        },
        error: function(xhr) {
        }
    });
}

function getUsersCategoriesAddEditCasesEvents(userId, callback) {
    const $userSelect = $('#assignUserSelect');

    $userSelect.html('<option value="-1" disabled selected>Loading...</option>');
    $('#assignSelectedUsers').empty();
    $userSelect.data('assignSelectedValues', new Set());

    $.ajax({
        url: '/getUsersAndAssignedUsers',
        method: 'GET',
        data: { user_id: userId },
        dataType: 'json',
        success: function (response) {
            const users = response.users || [];
            const assignedIds = response.assigned_user_ids || [];

            $userSelect.empty();
            users.forEach(function (user) {
                const isAssigned = assignedIds.includes(user.id);

                // Append option
                const $opt = $(`<option value="${user.id}">${user.name}</option>`);
                if (isAssigned) $opt.prop('hidden', true);
                $userSelect.append($opt);

                // Show badge if assigned
                if (isAssigned) {
                    const selectedSet = $userSelect.data('assignSelectedValues');
                    selectedSet.add(user.id.toString());

                    const $tag = $('<div class="text-sm cursor-default select-none w-full flex justify-between items-center px-2"></div>')
                        .html(`<span>${user.name}</span>`)
                        .append($(`
                            <i
                                class="removeUserSelect fa-solid fa-xmark fa-lg text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                                title="Remove"
                                data-user-id="${user.id}">
                            </i>`).addClass('ml-1 text-blue-600'));

                    $('#assignSelectedUsers').append($tag);
                }
            });

            if (typeof callback === 'function') {
                callback();
            }
        },
        error: function () {
            $userSelect.html('<option value="-1" disabled selected>Error loading users</option>');
        }
    });
}

function filterUsers() {
    var searchText = $('input[id="searchByName"]').val().toLowerCase();
    var anyVisible = false;

    $('.user-row').each(function() {
        var name = $(this).find('.col-span-3.font-semibold').text().toLowerCase();

        if (name.includes(searchText)) {
            $(this).show();
            anyVisible = true;
        } else {
            $(this).hide();
        }
    });

    // Remove existing no-match message
    $('#noMatchMessage').remove();
    reapplyUserRowStriping();

    // If nothing is visible, add a message
    if (!anyVisible) {
        $('#userPermissionBody').append(`
            <div id="noMatchMessage" class="col-span-12 text-center py-10 bg-white text-gray-500 dark:text-gray-400">
                <div class="flex flex-col items-center justify-center space-y-3">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m0 0v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8m18 0l-9 6-9-6" />
                    </svg>
                    <p class="text-lg font-medium">No matching records</p>
                    <p class="text-sm text-gray-400">Try adjusting your search.</p>
                </div>
            </div>
        `);
    }
}

