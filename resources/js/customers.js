$(document).ready(function() {
    // start add secndayr email
    $('#addSecondaryEmail').on('click', function () {
        const row = `
            <div class="relative mt-2 flex items-center gap-3">
                <input type="email" name="secondary_email" class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] pt-5 pb-1" >
                <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-5  peer-focus:top-1 peer-focus:text-sm">Secondary Email</label>
            </div>
        `;
        $('#secondaryEmailContainer').append(row);
        $(this).closest('button').addClass('hidden');
    });
    // end add secondary email

    
    // start add family member
    window.openAddFamilyMemberModal = function () {
        $('.family-validation-error').remove();

        const $modal = $('#customersAddFamilyMemberModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Add Family Member');

        const $form = $('#familyMemberFormContainer');
        $form.attr('action', $form.data('store-url')); 
        $('#family_member_method').val('POST');

        $modal.find('input, textarea').each(function () {
            if ($(this).attr('type') === 'checkbox') {
                $(this).prop('checked', false);
            } else if ($(this).attr('type') !== 'hidden') {
                $(this).val('');
            }
        });

        $modal.find('select').each(function () {
            $(this).val(''); 
        });

        $('#family_member_id').val('');
        $('#family_address_preview').text('');
    };

    function buildAddress(member) {
        let parts = [];

        if (member.address_line1) parts.push(member.address_line1);

        let cityStateZip = [
            member.city,
            member.state,
            member.zip_code
        ].filter(Boolean).join(' ');

        if (cityStateZip) parts.push(cityStateZip);

        if (member.country) parts.push(member.country);

        return parts.join(', ');
    }


    window.openEditFamilyMemberModal = function (member) {
        $('.family-validation-error').remove();

        const $modal = $('#customersAddFamilyMemberModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Edit Family Member');
        $modal.find('.modal-icon').removeClass('fa-plus-circle').addClass('fa-edit');

        const $form = $('#familyMemberFormContainer');
        $form.attr('action', `/family-members/${member.id}`);
        $('#family_member_method').val('PUT');

        $('#family_member_id').val(member.id ?? '');
        $('#family_fname').val(member.fname ?? '');
        $('#family_mname').val(member.mname ?? '');
        $('#family_lname').val(member.lname ?? '');
        $('#family_nickname').val(member.nickname ?? '');
        $('#family_relation').val(member.relation ?? '-1');
        $('#family_gender').val(member.gender ?? '-1');
        $('#family_cellphone').val(member.cellphone ?? '');
        $('#family_home_phone').val(member.home_phone ?? '');
        $('#family_work_phone').val(member.work_phone ?? '');
        $('#family_email').val(member.email ?? '');
        $('#family_traveler_number').val(member.traveler_number ?? '');
        $('#family_passport_number').val(member.passport_number ?? '');
        $('#family_passport_issue_date').val(member.passport_issue_date ?? '');
        $('#family_passport_expiration_date').val(member.passport_expiration_date ?? '');
        $('#family_address_line1').val(member.address_line1 ?? '');
        $('#family_address_line2').val(member.address_line2 ?? '');
        $('#family_city').val(member.city ?? '');
        $('#family_state').val(member.state ?? '');
        $('#family_zip_code').val(member.zip_code ?? '');
        $('#family_country').val(member.country ?? '');
        $('#family_special_notes').val(member.special_notes ?? '');

        const $deceasedCheckbox = $modal.find('input[name="deceased"][type="checkbox"]');
        if ($deceasedCheckbox.length) {
            $deceasedCheckbox.prop('checked', member.deceased == 1);
        }

        if (member.birth_date) {
            const bd = new Date(member.birth_date);

            const year = bd.getFullYear();
            const month = bd.getMonth() + 1;
            const day = bd.getDate();

            const $yearSelect = $modal.find('[x-model="year"]');
            const $monthSelect = $modal.find('[x-model="month"]');
            const $daySelect = $modal.find('[x-model="day"]');

            if ($yearSelect.length) $yearSelect.val(year).trigger('change');
            if ($monthSelect.length) $monthSelect.val(month).trigger('change');
            if ($daySelect.length) $daySelect.val(day).trigger('change');
        }

        $('#family_address_preview').text(buildAddress(member));
    };

    window.closeAddFamilyMemberModal = function() {
        $('.family-validation-error').remove();

        $('#customersAddFamilyMemberModal').addClass('hidden');
    }

    window.openFamilyDeleteModal = function(memberId) {
        const form = document.getElementById('deleteFamilyMemberForm');

        form.action = `/members/${memberId}`;

        openDeleteModal(form);
    }

    window.saveFamilyMemberAjax = function() {

        localStorage.setItem('customerActiveTab', 'family');

        showLoaderOnSubmit();

        let memberId = $('#family_member_id').val();
        let method = $('#family_member_method').val();

        let isUpdate = method === 'PUT';

        let url = isUpdate ? `/family-members/${memberId}` : $('#familyMemberFormContainer').data('store-url');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                _method: isUpdate ? 'PUT' : 'POST',

                fname: $('#family_fname').val(),
                mname: $('#family_mname').val(),
                lname: $('#family_lname').val(),
                nickname: $('#family_nickname').val(),
                birth_date: $('#family_birth_date').val(),
                relation: $('#family_relation').val(),
                gender: $('#family_gender').val(),
                cellphone: $('#family_cellphone').val(),
                home_phone: $('#family_home_phone').val(),
                work_phone: $('#family_work_phone').val(),
                email: $('#family_email').val(),
                traveler_number: $('#family_traveler_number').val(),
                deceased: $('input[name="deceased"][type="checkbox"]').is(':checked') ? 1 : 0,
                passport_number: $('#family_passport_number').val(),
                passport_issue_date: $('#family_passport_issue_date').val(),
                passport_expiration_date: $('#family_passport_expiration_date').val(),
                address_line1: $('#family_address_line1').val(),
                address_line2: $('#family_address_line2').val(),
                city: $('#family_city').val(),
                state: $('#family_state').val(),
                zip_code: $('#family_zip_code').val(),
                country: $('#family_country').val(),
                special_notes: $('#family_special_notes').val()
            })
        })
        .then(async response => {

            if (!response.ok) {

                const data = await response.json();

                $('.family-validation-error').remove();

                if (data.errors) {

                    const fieldMap = {
                        fname: '#family_fname',
                        lname: '#family_lname',
                        mname: '#family_mname',
                        nickname: '#family_nickname',
                        birth_date: '#family_birth_date',
                        relation: '#family_relation',
                        gender: '#family_gender',
                        cellphone: '#family_cellphone',
                        home_phone: '#family_home_phone',
                        work_phone: '#family_work_phone',
                        email: '#family_email',
                        traveler_number: '#family_traveler_number',
                        passport_number: '#family_passport_number',
                        passport_issue_date: '#family_passport_issue_date',
                        passport_expiration_date: '#family_passport_expiration_date',
                        address_line1: '#family_address_line1',
                        address_line2: '#family_address_line2',
                        city: '#family_city',
                        state: '#family_state',
                        zip_code: '#family_zip_code',
                        country: '#family_country',
                        special_notes: '#family_special_notes'
                    };

                    Object.keys(data.errors).forEach(field => {

                        const selector = fieldMap[field];

                        if (selector) {
                            $(selector).after(`
                                <div class="family-validation-error text-red-500 text-sm mt-1">
                                    ${data.errors[field][0]}
                                </div>
                            `);
                        }

                    });

                }

                hideLoader();

                return;
            }

            location.reload();

        });
    };
    // end add family member

    // start survey preview
    window.openSurveyPreviewModal = function(content){
        $('#surveyPreviewContent').html(content);
        $('#surveyPreviewModal').removeClass('hidden');
    }

    window.closeSurveyPreviewModal = function(){
        $('#surveyPreviewModal').addClass('hidden');
    }
    // end survey preview

    // start form intake preview
    window.openFormIntakePreviewModal = function(content){
        $('#formIntakePreviewContent').html(content);
        $('#formIntakePreviewModal').removeClass('hidden');
    }

    window.closeFormIntakePreviewModal = function(){
        $('#formIntakePreviewModal').addClass('hidden');
    }
    // end form intake preview

    // start invite customer
    window.openInviteCustomerAttentionModal = function(){
        $('#inviteCustomerAttentionModal').removeClass('hidden');
    }

    window.closeInviteCustomerAttentionModal = function(){
        $('#inviteCustomerAttentionModal').addClass('hidden');
    }
    // end invite customer
});
