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

    $('input[type=radio][name^="permissions"]').on('change', function() {
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
                $('#modalSuccessContent').html(response.message);
                $('#successModal').removeClass('hidden');
                changedPermissions = {};
            },
            error: function(xhr) {
                if (xhr.responseJSON?.error) {
                    $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">${xhr.responseJSON.error}</p>`);
                    $('#errorModal').removeClass('hidden');
                } else {
                    $('#modalErrorContent').html(`<p class="text-gray-800 text-sm">An unexpected error occurred.</p>`);
                    $('#errorModal').removeClass('hidden');
                }
            },
        });
    });

    $('.deleteUserBtn').on('click', function() {
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
                $(`#userRow${userId}`).remove();
                reapplyUserRowStriping();
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
});

function reapplyUserRowStriping() {
    $('.user-row:visible').each(function(index) {
        $(this).removeClass('bg-white bg-[#f3f4f6]');
        $(this).addClass(index % 2 === 0 ? 'bg-white' : 'bg-[#f3f4f6]');
    });
}
