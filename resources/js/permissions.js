let changedPermissions = {};

$(document).ready(function() {
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
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'Something went wrong.';
                $('#modalErrorContent').html(`<p>${msg}</p>`);
                $('#errorModal').removeClass('hidden');
            },
            complete: function() {
                refreshUserRows();
            },
        });
    });

    $(document).on('click', '.deleteUserBtn', function() {
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
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'An unexpected error occurred.';
                $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">${msg}</p>`);
                $('#deleteUserConfirmModal').addClass('hidden');
                $('#errorModal').removeClass('hidden');
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
                            $input.after(`<p class="input-error-text text-red-600 text-sm text-center mt-1">${messages[0]}</p>`);
                        }
                    });
                }
            },
        });
    });

    $(document).on('click', '.editUserBtn', function(e) {
        e.preventDefault();
        const userId = $(this).data('user-id');
        console.log("Edit button clicked for user ID:", userId);

        getUserData(userId, function(err, user) {
            if (err) {
                $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">${err}</p>`);
                $('#errorModal').removeClass('hidden');
                return;
            }

            // $('#editUserId').val(user.id);
            $('#edit_name').val(user.name);
            $('#edit_email').val(user.email);

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
                            $input.after(`<p class="input-error-text text-red-600 text-sm text-center mt-1">${messages[0]}</p>`);
                        }
                    });
                }
            }
        });
    });
});

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
        success: function(html) {
            $('#userPermissionTable').html(html);
            reapplyUserRowStriping();
            changedPermissions = {};
        },
        error: function() {
            $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">Failed to refresh user list.</p>`);
            $('#errorModal').removeClass('hidden');
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
