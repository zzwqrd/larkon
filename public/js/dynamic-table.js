document.addEventListener('DOMContentLoaded', function () {
    // 0. تهيئة الإعدادات والترجمات
    const tableContentAppend = document.getElementById('table_content_append');

    // Senior Approach: Read configuration from DOM data attributes
    const config = {
        baseUrl: tableContentAppend ? tableContentAppend.dataset.indexRoute : '',
        bulkDeleteUrl: tableContentAppend ? tableContentAppend.dataset.bulkDeleteRoute : '',
        deleteUrl: tableContentAppend ? tableContentAppend.dataset.deleteRoute : ''
    };

    const trans = window.TableTranslations || {
        are_you_sure: 'Are you sure?',
        about_to_delete: 'You are about to delete :count items',
        yes_delete: 'Yes, delete it!',
        cancel: 'Cancel',
        deleted: 'Deleted!',
        success_msg: 'Item has been deleted successfully.',
        success_bulk_msg: 'Your selection has been deleted successfully.'
    };
    const searchInput = document.getElementById('tableSearch');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    let currentSearch = '';
    let currentPage = 1;
    let isFetching = false;

    // --- Core AJAX fetching logic ---
    async function fetchData(page = 1, search = '') {
        if (isFetching || !config.baseUrl) return;
        isFetching = true;

        const url = new URL(config.baseUrl);
        url.searchParams.set('page', page);
        if (search) url.searchParams.set('q', search);

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();

            // Update DOM (Entire table structure including headers and pagination)
            if (tableContentAppend) {
                tableContentAppend.innerHTML = data.html;
            }

            currentPage = page;
            currentSearch = search;

            // Update UI components state
            toggleBulkDeleteBtn();

        } catch (error) {
            console.error('Fetch error:', error);
            if (tableContentAppend) {
                tableContentAppend.innerHTML = '<div class="alert alert-danger m-3">Error loading data.</div>';
            }
        } finally {
            isFetching = false;
        }
    }

    // Initial Load (Automated like old project)
    fetchData(1);

    // --- Event Listeners (Using Delegation where needed) ---

    // 1. Search Input (Debounced)
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchData(1, e.target.value);
            }, 500);
        });
    }

    // 2. Pagination (Event Delegation)
    if (tableContentAppend) {
        tableContentAppend.addEventListener('click', function (e) {
            const pageLink = e.target.closest('.ajax-page-link');
            if (pageLink) {
                e.preventDefault();
                const page = pageLink.dataset.page;
                fetchData(page, currentSearch);
            }
        });
    }

    // 3. Selection Logic (Event Delegation)
    function getCheckboxes() {
        return document.querySelectorAll('.product-checkbox');
    }

    function toggleBulkDeleteBtn() {
        if (bulkDeleteBtn) {
            const anyChecked = Array.from(getCheckboxes()).some(cb => cb.checked);
            bulkDeleteBtn.style.setProperty('display', anyChecked ? 'flex' : 'none', 'important');
        }
    }

    if (tableContentAppend) {
        tableContentAppend.addEventListener('change', function (e) {
            // Check All Logic
            if (e.target.id === 'checkAll') {
                getCheckboxes().forEach(cb => cb.checked = e.target.checked);
                toggleBulkDeleteBtn();
            }

            // Single Checkbox Logic
            if (e.target.classList.contains('product-checkbox')) {
                toggleBulkDeleteBtn();
                const boxes = Array.from(getCheckboxes());
                const checkAll = document.getElementById('checkAll');
                if (checkAll) checkAll.checked = boxes.every(cb => cb.checked) && boxes.length > 0;
            }
        });
    }

    // 4. Deletion Logic (AJAX) - (delegated via tableContentAppend later)
    async function performDelete(url, method = 'DELETE', payload = null) {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: payload ? JSON.stringify(payload) : null
        });

        if (!response.ok) throw new Error('Delete failed');
        return await response.json();
    }

    function confirmAndExecute(title, text, confirmBtnText, onConfirm) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff5c75',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmBtnText,
            cancelButtonText: trans.cancel,
            customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-secondary' },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                onConfirm();
            }
        });
    }

    // Single Delete (Event Delegation)
    if (tableContentAppend) {
        tableContentAppend.addEventListener('click', function (e) {
            const btn = e.target.closest('.delete-btn');
            if (btn) {
                e.preventDefault();
                const url = btn.dataset.url; // Read directly from the element (Senior Approach)
                if (!url) return;

                confirmAndExecute(trans.are_you_sure, trans.about_to_delete.replace(':count', '1'), trans.yes_delete, async () => {
                    try {
                        await performDelete(url);
                        fetchData(currentPage, currentSearch); // Refresh current view
                        Swal.fire({ title: trans.deleted, text: trans.success_msg, icon: 'success', customClass: { confirmButton: 'btn btn-primary' }, buttonsStyling: false });
                    } catch (err) {
                        Swal.fire('Error', 'Deletion failed', 'error');
                    }
                });
            }
        });
    }

    // Bulk Delete
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = bulkDeleteBtn.dataset.route; // Read from the bulk delete button root
            if (!url) return;

            const ids = Array.from(getCheckboxes()).filter(cb => cb.checked).map(cb => cb.value);
            if (ids.length === 0) return;

            confirmAndExecute(trans.are_you_sure, trans.about_to_delete.replace(':count', ids.length), trans.yes_delete, async () => {
                try {
                    await performDelete(url, 'POST', { ids: ids });
                    fetchData(1, currentSearch);
                    Swal.fire({ title: trans.deleted, text: trans.success_bulk_msg, icon: 'success', customClass: { confirmButton: 'btn btn-primary' }, buttonsStyling: false });
                } catch (err) {
                    Swal.fire('Error', 'Bulk deletion failed', 'error');
                }
            });
        });
    }

    // --- Export Features (Remain local to current view for speed, or can be server-side later) ---
    const printBtn = document.getElementById('exportPrintBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (!tableContentAppend) return;
            const rows = Array.from(tableContentAppend.querySelectorAll('tbody tr'));
            let printContents = '<div style="font-family: Arial, sans-serif; padding: 20px;" dir="auto">';
            printContents += '<h2 style="text-align: center; margin-bottom: 20px;">Product List</h2>';
            printContents += '<table style="width: 100%; border-collapse: collapse;">';
            printContents += '<thead><tr style="background-color: #f8f9fa;">';
            document.querySelectorAll('thead th').forEach((th, i) => {
                if (i > 0 && i < 6) printContents += `<th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: left;">${th.innerText}</th>`;
            });
            printContents += '</tr></thead><tbody>';
            rows.forEach(row => {
                printContents += '<tr style="border-bottom: 1px solid #ddd;">';
                row.querySelectorAll('td').forEach((td, i) => {
                    if (i > 0 && i < 6) printContents += `<td style="padding: 10px;">${td.innerText.trim()}</td>`;
                });
                printContents += '</tr>';
            });
            printContents += '</tbody></table></div>';
            const win = window.open('', '_blank');
            win.document.write(printContents);
            win.document.close();
            win.print();
            win.close();
        });
    }

    const excelBtn = document.getElementById('exportExcelBtn');
    if (excelBtn) {
        excelBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (typeof XLSX === 'undefined' || !tableContentAppend) return;
            const data = [Array.from(document.querySelectorAll('thead th')).slice(1, 6).map(th => th.innerText.trim())];
            tableContentAppend.querySelectorAll('tbody tr').forEach(row => {
                data.push(Array.from(row.querySelectorAll('td')).slice(1, 6).map(td => td.innerText.trim()));
            });
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);
            XLSX.utils.book_append_sheet(wb, ws, "Products");
            XLSX.writeFile(wb, "Products_Export.xlsx");
        });
    }
});
