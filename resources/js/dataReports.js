import './reportTables';

const form = document.getElementById('reportFilters');
const dialog = document.getElementById('reportRangeModal');

document.querySelectorAll('[data-report-open-dialog]').forEach(button => {
    button.addEventListener('click', () => {
        document.getElementById(button.dataset.reportOpenDialog)?.showModal();
    });
});
document.querySelectorAll('[data-report-close-dialog]').forEach(button => {
    button.addEventListener('click', () => button.closest('dialog')?.close());
});
document.querySelectorAll('[data-report-submit-form]').forEach(input => {
    input.addEventListener('change', async () => {
        if (!input.form) return;
        if (!input.form.action.includes('/unknownReservationsReport/checked')) {
            input.form.requestSubmit();
            return;
        }
        const data = new FormData(input.form);
        input.disabled = true;
        try {
            const result = await requestJSON(input.form.action, { method: 'POST', body: data });
            document.querySelectorAll('[data-unknown-unchecked-total]').forEach(element => element.textContent = money(result.unchecked_total));
            document.querySelectorAll('[data-unknown-claimed-total]').forEach(element => element.textContent = money(result.claimed_total));
        } catch (error) {
            input.checked = !input.checked;
            alert(error.message);
        } finally {
            input.disabled = false;
        }
    });
});
document.querySelectorAll('dialog[data-report-auto-open]').forEach(dialog => dialog.showModal());

const money = value => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(Number(value));
async function requestJSON(url, options = {}) {
    const response = await fetch(url, { ...options, headers: { Accept: 'application/json', ...options.headers } });
    const data = await response.json();
    if (!response.ok) throw new Error(data.errors ? Object.values(data.errors).flat().join('\n') : (data.message ?? 'Unable to save the report. Please try again.'));
    return data;
}

document.querySelector('[data-report-print]')?.addEventListener('click', () => window.printElement('reportDataContainer'));
document.querySelector('[data-expense-reservation]')?.addEventListener('change', event => {
    document.getElementById('expenseCustomerName').value = event.target.selectedOptions[0]?.dataset.customerName ?? '';
});
document.querySelector('[data-report-range-year]')?.addEventListener('change', event => {
    form.elements.beginDate.value = `${event.target.value}-01-01`;
    form.elements.endDate.value = `${event.target.value}-12-31`;
});

document.querySelector('[data-report-live-search]')?.addEventListener('input', event => {
    const term = event.target.value.trim().toLocaleLowerCase();
    const report = event.target.dataset.reportLiveSearch;
    if (report === 'salesReport') {
        document.querySelectorAll('.agentSalesContainer').forEach(card => {
            card.hidden = !card.querySelector('.agentInfoContainer').textContent.toLocaleLowerCase().includes(term);
            card.style.display = card.hidden ? 'none' : '';
        });
    } else if (report === 'unknownReservationsReport') {
        document.querySelectorAll('[data-report-unknown-month]').forEach(group => {
            let found = false;
            group.querySelectorAll('tbody tr').forEach(row => {
                const number = row.querySelector('[data-report-unknown-edit]')?.textContent ?? '';
                row.hidden = !number.toLocaleLowerCase().includes(term);
                found ||= !row.hidden;
            });
            group.open = Boolean(term) && found;
            group.querySelectorAll('[data-report-unknown-month-total]').forEach(total => total.hidden = Boolean(term));
        });
    } else {
        document.querySelectorAll('#reportDataContainer tbody tr').forEach(row => {
            row.hidden = !row.textContent.toLocaleLowerCase().includes(term);
        });
    }
});

document.querySelectorAll('[data-report-unknown-edit]').forEach(link => {
    link.addEventListener('click', async event => {
        event.preventDefault();
        try {
            const data = await requestJSON(link.dataset.reportUnknownEdit || link.href);
            const editor = document.getElementById('unknownReservationEditorForm');
            for (const [field, rows] of [['customer_id', data.customers], ['product_id', data.products]]) {
                const placeholder = field === 'customer_id' ? new Option('-- Select Customer --', '') : new Option('-- Select Product --', '-1');
                editor.elements[field].replaceChildren(placeholder, ...rows.map(row => new Option(row.name, row.id)));
            }
            Object.entries(data.reservation).forEach(([field, value]) => {
                if (editor.elements[field]) editor.elements[field].value = value ?? '';
            });
            editor.action = data.update_url;
            const errors = document.getElementById('unknownReservationEditorErrors');
            if (errors) { errors.textContent = ''; errors.hidden = true; }
            document.getElementById('unknownReservationEditor').showModal();
        } catch (error) { alert(error.message); }
    });
});
document.getElementById('unknownReservationEditorForm')?.addEventListener('submit', async event => {
    event.preventDefault();
    const editor = event.target;
    const button = editor.querySelector('[type=submit]');
    button.disabled = true;
    try {
        await requestJSON(editor.action, { method: 'POST', body: new FormData(editor) });
        window.location.reload();
    } catch (error) {
        const errors = document.getElementById('unknownReservationEditorErrors');
        if (errors) { errors.textContent = error.message; errors.hidden = false; }
        else alert(error.message);
    } finally { button.disabled = false; }
});

if (form && dialog) {
    const begin = form.elements.beginDate;
    const end = form.elements.endDate;

    document.querySelector('[data-report-open-range]')?.addEventListener('click', () => dialog.showModal());
    document.querySelectorAll('[data-report-close-range]').forEach(button => {
        button.addEventListener('click', () => dialog.close());
    });
    document.querySelectorAll('[data-report-submit]').forEach(input => {
        input.addEventListener('change', () => form.requestSubmit());
    });

    form.addEventListener('submit', event => {
        const invalidDates = begin && end && begin.value > end.value;
        const firstYear = form.elements.beginYear;
        const lastYear = form.elements.endYear;
        const invalidYears = firstYear && lastYear && Number(firstYear.value) > Number(lastYear.value);
        if (invalidDates || invalidYears) {
            event.preventDefault();
            dialog.showModal();
            const target = invalidDates ? end : lastYear;
            target.setCustomValidity('The end of the range must be on or after its beginning.');
            target.reportValidity();
            event.stopPropagation();
        }
    });
    form.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', () => input.setCustomValidity(''));
    });

    document.querySelector('[data-report-range]')?.addEventListener('change', event => {
        const range = event.target.value;
        if (!range || !begin || !end) return;
        const today = new Date();
        let first = new Date(today.getFullYear(), today.getMonth(), today.getDate());
        let last = new Date(first);
        if (/^[+-]\d+$/.test(range)) {
            if (Number(range) < 0) first.setDate(first.getDate() + Number(range));
            else last.setDate(last.getDate() + Number(range));
        } else if (range.endsWith('Year')) {
            const offset = range === 'lastYear' ? -1 : range === 'nextYear' ? 1 : 0;
            first = new Date(today.getFullYear() + offset, 0, 1);
            last = new Date(today.getFullYear() + offset, 11, 31);
        } else {
            const offset = range === 'lastQuarter' ? -1 : range === 'nextQuarter' ? 1 : 0;
            const month = (Math.floor(today.getMonth() / 3) + offset) * 3;
            first = new Date(today.getFullYear(), month, 1);
            last = new Date(today.getFullYear(), month + 3, 0);
        }
        const iso = date => `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        begin.value = iso(first);
        end.value = iso(last);
        begin.setCustomValidity('');
        end.setCustomValidity('');
    });
}
