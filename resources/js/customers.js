$(document).ready(function() {
    window.openAddFamilyMemberModal = function() {
        $('#customersAddFamilyMemberModal').removeClass('hidden');
    }

    window.closeAddFamilyMemberModal = function() {
        $('#customersAddFamilyMemberModal').addClass('hidden');
    }

    $('#addSecondaryEmail').on('click', function () {
        const row = `
            <div class="relative mt-2 flex items-center gap-3">
                <input type="email" name="secondary_email" class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325] pt-5 pb-1" >
                <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-5  peer-focus:top-1 peer-focus:text-sm">Secondary Email</label>
            </div>
        `;
        $('#secondaryEmailContainer').append(row);
        $(this).closest('button').addClass('hidden');
    });
});
