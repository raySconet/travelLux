import './bootstrap';
import Alpine from 'alpinejs';

window.dateDropdown = function(initialDate = '', minYear = 1920, maxYear = 2040) {
    let day = '';
    let month = '';
    let year = '';

    if(initialDate) {
        const parts = initialDate.split('-'); 
        if(parts.length === 3) {
            year = parseInt(parts[0]);
            month = parseInt(parts[1]);
            day = parseInt(parts[2]);
        }
    }

    return {
        day,
        month,
        year,
        days: Array.from({ length: 31 }, (_, i) => i + 1),
        months: [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ],
        years: Array.from({ length: maxYear - minYear + 1 }, (_, i) => minYear + i),
        get formattedDate() {
            if (!this.day || !this.month || !this.year) return '';
            return `${String(this.year)}-${String(this.month).padStart(2,'0')}-${String(this.day).padStart(2,'0')}`;
        }
    }
};

window.Alpine = Alpine;

Alpine.start();

$(document).ready(() => {

    $(document).ajaxStart(function () {
        $('#ajaxLoader').fadeIn();
    });

    $(document).ajaxStop(function () {
        $('#ajaxLoader').fadeOut(300);
    });


    // Excel Button for data table
    $('.exportExcelBtn').on('click', function() {
        const table = $('table').first()[0]; 

        if (!table) {
            alert('No table found to export!');
            return;
        }

        const workbook = XLSX.utils.table_to_book(table, { sheet: "Archer Luxury Travel CRM" });

        const ws = workbook.Sheets["Archer Luxury Travel CRM"];
        const range = XLSX.utils.decode_range(ws['!ref']);
        for (let C = range.s.c; C <= range.e.c; ++C) {
            const cell_address = { c: C, r: 0 }; 
            const cell_ref = XLSX.utils.encode_cell(cell_address);
            if (!ws[cell_ref]) continue;
            ws[cell_ref].s = { font: { bold: true } };
        }

        XLSX.writeFile(workbook, 'Archer Luxury Travel CRM.xlsx');
    });
    
    //  window.openDeleteModal = function() {
 
    //     $('#deleteModal').removeClass('hidden');
    // }

    // window.closeDeleteModal = function() {
    //     $('#deleteModal').addClass('hidden');
    // }

    let deleteForm = null;

    window.openDeleteModal = function(button) {
        deleteForm = $(button).closest('form'); 
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
});
