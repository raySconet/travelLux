$(document).ready(function() {
    // start invite customer

    let currentStep = 1;
    document.getElementById('nextBtn').addEventListener('click', function () {

        if (currentStep === 1) {

            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');

            document.getElementById('backBtn').classList.remove('hidden');

            document.getElementById('personalInformationStepIcon').className ='fas fa-check-circle text-[22px] text-[#B6844A]';

            document.getElementById('familyMembersStepNumber').style.display = 'none';
            document.getElementById('familyMembersStepIcon').style.display = 'inline-block';

            document.getElementById('familyMembersStepText').classList.remove('text-black/40');
            document.getElementById('familyMembersStepText').classList.add('text-[#B6844A]');

            currentStep = 2;
        }

        else if (currentStep === 2) {

            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step3').classList.remove('hidden');

            document.getElementById('familyMembersStepIcon').className ='fas fa-check-circle text-[22px] text-[#B6844A]';

            document.getElementById('interestsStepNumber').style.display = 'none';
            document.getElementById('interestsStepIcon').style.display = 'inline-block';

            document.getElementById('interestsStepText').classList.remove('text-black/40');
            document.getElementById('interestsStepText').classList.add('text-[#B6844A]');

            currentStep = 3;
        }

        else if (currentStep === 3) {
            populateReviewInformation();
            
            document.getElementById('step3').classList.add('hidden');
            document.getElementById('step4').classList.remove('hidden');

            document.getElementById('interestsStepIcon').className ='fas fa-check-circle text-[22px] text-[#B6844A]';

            document.getElementById('informationReviewStepNumber').style.display = 'none';
            document.getElementById('informationReviewStepIcon').style.display = 'inline-block';

            document.getElementById('informationReviewStepText').classList.remove('text-black/40');
            document.getElementById('informationReviewStepText').classList.add('text-[#B6844A]');

            document.getElementById('nextBtn').innerText = 'SAVE';

            currentStep = 4;
        }

        else if (currentStep === 4) {
            submitInvitationForm();
        }

    });

    document.getElementById('backBtn').addEventListener('click', function () {

        if (currentStep === 2) {

            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');

            this.classList.add('hidden');

            document.getElementById('personalInformationStepIcon').className ='fas fa-edit text-[22px] text-[#B6844A]';

            document.getElementById('familyMembersStepNumber').style.display = 'inline-block';
            document.getElementById('familyMembersStepIcon').style.display = 'none';

            document.getElementById('familyMembersStepText').classList.remove('text-[#B6844A]');
            document.getElementById('familyMembersStepText').classList.add('text-black/40');

            currentStep = 1;
        }

        else if (currentStep === 3) {

            document.getElementById('step3').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');

            document.getElementById('familyMembersStepIcon').className ='fas fa-edit text-[22px] text-[#B6844A]';

            document.getElementById('interestsStepNumber').style.display = 'inline-block';
            document.getElementById('interestsStepIcon').style.display = 'none';

            document.getElementById('interestsStepText').classList.remove('text-[#B6844A]');
            document.getElementById('interestsStepText').classList.add('text-black/40');

            currentStep = 2;
            
        }

        else if (currentStep === 4) {

            document.getElementById('step4').classList.add('hidden');
            document.getElementById('step3').classList.remove('hidden');

            document.getElementById('interestsStepIcon').className ='fas fa-edit text-[22px] text-[#B6844A]';

            document.getElementById('informationReviewStepNumber').style.display = 'inline-block';
            document.getElementById('informationReviewStepIcon').style.display = 'none';

            document.getElementById('informationReviewStepText').classList.remove('text-[#B6844A]');
            document.getElementById('informationReviewStepText').classList.add('text-black/40');

            document.getElementById('nextBtn').innerText = 'NEXT';

            currentStep = 3;
        }

    });

    function populateReviewInformation() {
        const secondaryEmail = document.querySelector('[name="secondary_email"]');

        document.getElementById('review_fname').textContent = document.getElementById('fname').value;
        document.getElementById('review_mname').textContent = document.getElementById('mname').value;
        document.getElementById('review_lname').textContent = document.getElementById('lname').value;
        document.getElementById('review_nickname').textContent = document.getElementById('nickname').value;
        document.getElementById('review_email').textContent = document.getElementById('email').value;
        document.getElementById('review_secondary_email').textContent = secondaryEmail ? secondaryEmail.value : '';
        document.getElementById('review_cellphone').textContent = document.getElementById('cellphone').value;
        document.getElementById('review_home_phone').textContent = document.getElementById('home_phone').value;
        document.getElementById('review_work_phone').textContent = document.getElementById('work_phone').value;
        document.getElementById('review_gender').textContent = document.getElementById('gender').value;
        document.getElementById('review_birth_date').textContent = document.querySelector('input[name="birth_date"]').value;
        document.getElementById('review_anniversary_date').textContent = document.querySelector('input[name="anniversary_date"]').value;
        document.getElementById('review_special_notes').textContent = document.getElementById('special_notes').value;
        document.getElementById('review_address_line1').textContent = document.getElementById('address_line1').value;
        document.getElementById('review_address_line2').textContent = document.getElementById('address_line2').value;
        document.getElementById('review_city').textContent = document.getElementById('city').value;
        document.getElementById('review_state').textContent = document.getElementById('state').value;
        document.getElementById('review_postal_code').textContent = document.getElementById('postal_code').value;
        document.getElementById('review_country').textContent = document.getElementById('country').value;
    }

    async function submitInvitationForm() {

        const btn = document.getElementById('nextBtn');

        btn.disabled = true;
        btn.innerText = 'SAVING...';

        try {

            const form = document.getElementById('invitationForm');

            const response = await fetch(
                '/invitation/submit',
                {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }
            );

            const data = await response.json();

            if (!data.success) {
                throw new Error();
            }

            document.getElementById('informationReviewStepIcon').className ='fas fa-check-circle text-[22px] text-[#B6844A]';
            document.getElementById('informationSavedStepNumber').style.display = 'none';
            document.getElementById('informationSavedStepIcon').style.display = 'inline-block';
            document.getElementById('informationSavedStepText').classList.remove('text-black/40');
            document.getElementById('step4').classList.add('hidden');
            document.getElementById('step5').classList.remove('hidden');
            document.getElementById('nextBtn').classList.add('hidden');
            document.getElementById('backBtn').classList.add('hidden');
            document.getElementById('informationSavedStepText').classList.add('text-[#B6844A]');

            currentStep = 5;

        } catch (error) {

            alert('Failed To Submit Invitation Form, Please Try Again.');

            btn.disabled = false;
            btn.innerText = 'SAVE';
        }
    }
    // end invite customer
});
