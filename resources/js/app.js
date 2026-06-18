import './bootstrap';
import Alpine from 'alpinejs';

window.dateDropdown = function(initialDate = '', minYear = 1920, maxYear = 2040) {
   return {
       day: '',
       month: '',
       year: '',
       init() {
           if (initialDate) {
               const parts = initialDate.split('-');
               if (parts.length === 3) {
                   this.year = parseInt(parts[0]);
                   this.month = parseInt(parts[1]);
                   this.day = parseInt(parts[2]);
               }
           }
       },

       setDate(dateStr) {
           if (!dateStr) {
               this.year = '';
               this.month = '';
               this.day = '';
               return;
           }
           const parts = dateStr.split('-');
           if (parts.length === 3) {
               this.year = parseInt(parts[0]);
               this.month = parseInt(parts[1]);
               this.day = parseInt(parts[2]);
           }
       },
       days: Array.from({ length: 31 }, (_, i) => i + 1),
       months: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
       years: Array.from({ length: maxYear - minYear + 1 }, (_, i) => maxYear - i),
       get formattedDate() {
           if (!this.year || !this.month || !this.day) return '';
           return `${this.year}-${String(this.month).padStart(2,'0')}-${String(this.day).padStart(2,'0')}`;
       }
   }
};

window.showLoader = function () {

    if ($(".loaderContainer").length) return;

    const loader = `
        <div class="loaderContainer">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
    `;

    $("body").append(loader);
}

window.hideLoader = function () {

    $(".loaderContainer").fadeOut(300, function () {
        $(this).remove();
    });

}

$(document).on("submit", "form", function () {
    showLoader();
});

window.openPdf = function (url) {

    showLoader();

    setTimeout(function () {

        window.open(url, '_blank');

        hideLoader();

    }, 500);

};

window.Alpine = Alpine;

Alpine.start();


$(document).ready(() => {

    window.showLoaderOnSubmit = function () {
        showLoader();
    };

    window.disableGlobalLoader = false;

    $(document).ajaxStart(function () {
        if (!window.disableGlobalLoader) {
            showLoader();
        }
    });

    $(document).ajaxStop(function () {
        hideLoader();
    });


    // Excel Button for data table
    $('.exportExcelBtn').on('click', function() {
        const table = $('table').first()[0]; 

        if (!table) {
            alert('No table found to export!');
            return;
        }

        const workbook = XLSX.utils.table_to_book(table, { sheet: "Travelux CRM" });

        const ws = workbook.Sheets["Travelux CRM"];
        const range = XLSX.utils.decode_range(ws['!ref']);
        for (let C = range.s.c; C <= range.e.c; ++C) {
            const cell_address = { c: C, r: 0 }; 
            const cell_ref = XLSX.utils.encode_cell(cell_address);
            if (!ws[cell_ref]) continue;
            ws[cell_ref].s = { font: { bold: true } };
        }

        XLSX.writeFile(workbook, 'Travelux CRM.xlsx');
    });
    // end excel button for data table

    let deleteForm = null;

    // window.openDeleteModal = function(button) {
    //     deleteForm = $(button).closest('form'); 
    //     $('#deleteModal').removeClass('hidden');
    // }

    window.openDeleteModal = function(target) {

        if (target instanceof HTMLFormElement) {

            deleteForm = $(target);

        } else {

            deleteForm = $(target).closest('form');

        }

        $('#deleteModal').removeClass('hidden');
    }

    window.closeDeleteModal = function() {
        $('#deleteModal').addClass('hidden');
        deleteForm = null;
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        if (deleteForm) {
            deleteForm.submit();
        }
    });

    window.openLogoutModal = function(){
        $('#logoutModal').removeClass('hidden');
    }

    window.closeLogoutModal = function(){
        $('#logoutModal').addClass('hidden');
    }
});
