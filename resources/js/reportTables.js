function sortValue(cell, type) {
    const value = (cell?.dataset.sortValue ?? cell?.textContent ?? '').trim();
    if (type === 'number' || /^[($+-]?\s*\d[\d,.\s]*[%)]?$/.test(value)) {
        const number = Number(value.replace(/[$,%\s()]/g, ''));
        return Number.isFinite(number) ? (value.startsWith('(') ? -number : number) : 0;
    }
    if (type === 'date' || /^\d{2}[/-]\d{2}[/-]\d{4}$/.test(value)) {
        const date = Date.parse(value);
        return Number.isNaN(date) ? 0 : date;
    }
    return value.toLocaleLowerCase();
}

function sortTable(table, index, direction) {
    const body = table.tBodies[0];
    if (!body) return;
    const headers = [...table.tHead.rows[table.tHead.rows.length - 1].cells];
    const type = headers[index]?.dataset.sortType;
    const rows = [...body.rows];
    if (rows.some(row => row.cells.length !== headers.length)) return;
    rows.sort((left, right) => {
        const a = sortValue(left.cells[index], type);
        const b = sortValue(right.cells[index], type);
        const result = typeof a === 'number' && typeof b === 'number'
            ? a - b : String(a).localeCompare(String(b), undefined, { numeric: true });
        return direction === 'desc' ? -result : result;
    });
    headers.forEach((header, column) => header.setAttribute('aria-sort',
        column === index ? (direction === 'asc' ? 'ascending' : 'descending') : 'none'));
    body.append(...rows);
}

export function initialiseReportTables(root = document) {
    root.querySelectorAll('table[data-report-sortable]').forEach(table => {
        if (!table.tHead || table.dataset.reportSortReady) return;
        table.dataset.reportSortReady = 'true';
        const headers = [...table.tHead.rows[table.tHead.rows.length - 1].cells];
        headers.forEach((header, index) => {
            if (header.colSpan > 1 || !header.textContent.trim()) return;
            header.tabIndex = 0;
            header.style.cursor = 'pointer';
            header.title = `Sort by ${header.textContent.trim()}`;
            header.setAttribute('aria-sort', 'none');
            const sort = () => sortTable(table, index, header.getAttribute('aria-sort') === 'ascending' ? 'desc' : 'asc');
            header.addEventListener('click', sort);
            header.addEventListener('keydown', event => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    sort();
                }
            });
        });
        if (table.dataset.reportSortColumn !== undefined) {
            sortTable(table, Number(table.dataset.reportSortColumn), table.dataset.reportSortDirection ?? 'asc');
        }
    });
}

initialiseReportTables();
new MutationObserver(records => {
    if (records.some(record => [...record.addedNodes].some(node =>
        node.nodeType === Node.ELEMENT_NODE && (node.matches('table') || node.querySelector('table'))))) {
        initialiseReportTables();
    }
}).observe(document.body, { childList: true, subtree: true });
