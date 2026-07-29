import './bootstrap';

$(document).ready(() => {
    const $masterProduct     = $('#template_product_list option').clone();
    const $masterDestination = $('#template_destination_list option').clone();
    const $masterResort      = $('#template_resort_list option').clone();
    const $masterCruise      = $('#template_cruise_itinerary_list option').clone();

    const rowTemplate = `
        <div class="email-row grid grid-cols-1 md:grid-cols-5 gap-x-5 gap-y-4 items-end">
            <div class="relative">
                <label>Product</label>
                <select name="product_list[]" class="product_list w-full border-b-2 border-[#bdbdbd] mb-4"></select>
            </div>
            <div class="relative">
                <label>Destination</label>
                <select name="destination_list[]" class="destination_list w-full border-b-2 border-[#bdbdbd] mb-4"></select>
            </div>
            <div class="relative">
                <label>Resort/Ship</label>
                <select name="resort_list[]" class="resort_list w-full border-b-2 border-[#bdbdbd] mb-4"></select>
            </div>
            <div class="relative">
                <label>Cruise/Type</label>
                <select name="cruise_itinerary_list[]" class="cruise_list w-full border-b-2 border-[#bdbdbd] mb-4"></select>
            </div>
            <div class="flex justify-center items-center">
                <button type="button" class="delete-row text-[#989898] text-2xl mb-3 cursor-pointer">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>`
    ;

    const isSet = (v) => v !== undefined && v !== null && v !== '' && v !== '-1';

    function rebuildDestinations($row, selectedValue) {
        const productId = $row.find('.product_list').val();
        const $destination = $row.find('.destination_list');
        $destination.empty().append('<option value="">--Select Destination--</option>');
        if (isSet(productId)) {
            $masterDestination.each(function () {
                const $opt = $(this);
                if ($opt.val() && String($opt.data('product')) === String(productId)) {
                    $destination.append($opt.clone());
                }
            });
        }
        const target = isSet(selectedValue) && $destination.find(`option[value="${selectedValue}"]`).length ? selectedValue : '';
        $destination.val(target);
        rebuildResorts($row);
    }

   function rebuildResorts($row, selectedValue) {
       const destId = $row.find('.destination_list').val();
       const $resort = $row.find('.resort_list');
       $resort.empty().append('<option value="">--Select Resort/Ship--</option>');
       if (isSet(destId)) {
           $masterResort.each(function () {
               const $opt = $(this);
               if ($opt.val() && String($opt.data('destination')) === String(destId)) {
                   $resort.append($opt.clone());
               }
           });
       }
       const target = isSet(selectedValue) && $resort.find(`option[value="${selectedValue}"]`).length ? selectedValue : '';
       $resort.val(target);
       rebuildCruises($row);
   }

   function rebuildCruises($row, selectedValue) {
       const resortId = $row.find('.resort_list').val();
       const $cruise = $row.find('.cruise_list');
       $cruise.empty().append('<option value="">--Select Cruise/Type--</option>');
       if (isSet(resortId)) {
           $masterCruise.each(function () {
               const $opt = $(this);
               if ($opt.val() && String($opt.data('resort')) === String(resortId)) {
                   $cruise.append($opt.clone());
               }
           });
       }
       const target = isSet(selectedValue) && $cruise.find(`option[value="${selectedValue}"]`).length ? selectedValue : '';
       $cruise.val(target);
   }

   function buildRow(saved) {
       const $row = $(rowTemplate);

       $row.find('.product_list').html($masterProduct.clone());
       $row.find('.destination_list').html('<option value="">--Select Destination--</option>');
       $row.find('.resort_list').html('<option value="">--Select Resort/Ship--</option>');
       $row.find('.cruise_list').html('<option value="">--Select Cruise/Type--</option>');
       $row.find('.product_list').on('change', () => rebuildDestinations($row));
       $row.find('.destination_list').on('change', () => rebuildResorts($row));
       $row.find('.resort_list').on('change', () => rebuildCruises($row));
       $row.find('.delete-row').on('click', () => $row.remove());

       if (saved && isSet(saved.product)) {
           $row.find('.product_list').val(saved.product);
           rebuildDestinations($row, saved.destination);
           if (isSet(saved.destination)) {
               $row.find('.destination_list').val(saved.destination);
               rebuildResorts($row, saved.resort);
               if (isSet(saved.resort)) {
                   $row.find('.resort_list').val(saved.resort);
                   rebuildCruises($row, saved.cruise);
               }
           }
       }
       return $row;
   }

   const $rowsContainer = $('#addRowAutomatedEmailsContainer');
   if ($rowsContainer.length) {
       let savedRows = [];
       try {
           savedRows = JSON.parse($rowsContainer.attr('data-saved-rows') || '[]');
       } catch (e) {
           savedRows = [];
       }
       const hasAnySavedData = savedRows.some(r => [r.product, r.destination, r.resort, r.cruise].some(isSet));
       if (hasAnySavedData) {
           savedRows.forEach(saved => $rowsContainer.append(buildRow(saved)));
       } else {
           $rowsContainer.append(buildRow(null));
       }
   }

   $(document).on('click', '#addRowAutomatedEmails', function () {
       $rowsContainer.append(buildRow(null));
   });

   function toggleReservationSection() {
       if ($('#email_type').val() === 'Reservation Reminder') {
           $('#reservationReminderSection').show();
       } else {
           $('#reservationReminderSection').hide();
       }
   }

   toggleReservationSection();

   $(document).on('change', '#email_type', toggleReservationSection);

   const attachBtn = $('#attachBtn');
   const attachmentsInput = $('#attachments');
   const attachmentsTableBody = $('#attachmentsTableBody');
   attachBtn.on('click', function () {
       attachmentsInput.trigger('click');
   });

   let dt = new DataTransfer();
   attachmentsInput.on('change', function () {
       const emptyRow = attachmentsTableBody.find('.empty-attachments-row');
       if (emptyRow.length) emptyRow.remove();
       Array.from(this.files).forEach(file => {
           dt.items.add(file);
           const rowId = 'new-file-' + file.name + '-' + Date.now();
           const row = `
            <tr id="${rowId}" class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                    <button type="button" onclick="removeSelectedFile('${rowId}', '${file.name}')">
                        <i class="fas fa-trash text-[#989898] cursor-pointer"></i>
                    </button>
                </td>
                <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                    ${file.name}
                </td>
            </tr>`;
           attachmentsTableBody.append(row);
       });
       attachmentsInput[0].files = dt.files;
   });

   window.removeSelectedFile = function (rowId, fileName) {
       $('#' + rowId).remove();
       const newDt = new DataTransfer();
       Array.from(attachmentsInput[0].files).forEach(file => {
           if (file.name !== fileName) newDt.items.add(file);
       });
       dt = newDt;
       attachmentsInput[0].files = dt.files;
       if (attachmentsTableBody.find('tr').length === 0) {
           attachmentsTableBody.html(`
            <tr class="empty-attachments-row">
                <td colspan="2" class="text-center py-3 text-gray-400">No attachments</td>
            </tr>`);
       }
   };
   
});