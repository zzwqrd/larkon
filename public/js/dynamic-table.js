document.addEventListener('DOMContentLoaded', function () {
    // 0. تهيئة متغيرات الترجمة (تقرأ من الـ Blade وإلا تستخدم الافتراضي)
    const trans = window.TableTranslations || {
        are_you_sure: 'Are you sure?',
        about_to_delete: 'You are about to delete :count records!',
        yes_delete: 'Yes, delete!',
        cancel: 'Cancel',
        deleted: 'Deleted!',
        success_msg: 'Item has been deleted successfully.',
        success_bulk_msg: 'Your selection has been deleted successfully.'
    };

    const checkAll = document.getElementById('customCheck1');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const searchInput = document.getElementById('tableSearch');
    const paginationContainer = document.getElementById('tablePagination');
    
    let allRows = Array.from(document.querySelectorAll('tbody tr')); 
    let filteredRows = [...allRows];
    let currentPage = 1;
    const rowsPerPage = 5;

    // دالة لجلب مربعات الاختيار في كل مرة لتحديثات الـ DOM
    function getCheckboxes() {
        return document.querySelectorAll('tbody .form-check-input');
    }

    function toggleBulkDeleteBtn() {
        if (bulkDeleteBtn) {
            const anyChecked = Array.from(getCheckboxes()).some(cb => cb.checked);
            if (anyChecked) {
                bulkDeleteBtn.style.setProperty('display', 'flex', 'important');
            } else {
                bulkDeleteBtn.style.setProperty('display', 'none', 'important');
            }
        }
    }

    // 1. تفعيل تحديد الكل 
    if (checkAll) {
        checkAll.addEventListener('change', function () {
            getCheckboxes().forEach(function (checkbox) {
                const row = checkbox.closest('tr');
                if(row && row.style.display !== 'none'){
                    checkbox.checked = checkAll.checked;
                }
            });
            toggleBulkDeleteBtn();
        });
    }

    // 2. تفعيل التحديد الفردي عن طريق الـ Delegation
    const tbody = document.querySelector('tbody');
    if (tbody) {
        tbody.addEventListener('change', function (e) {
            if (e.target.classList.contains('form-check-input')) {
                toggleBulkDeleteBtn();
                
                const visibleBoxes = Array.from(getCheckboxes()).filter(cb => {
                    const row = cb.closest('tr');
                    return row && row.style.display !== 'none';
                });
                const allChecked = visibleBoxes.every(cb => cb.checked) && visibleBoxes.length > 0;
                if(checkAll) checkAll.checked = allChecked;
            }
        });
    }

    // --- Pagination Logic ---
    function renderTable() {
        allRows.forEach(row => row.style.display = 'none');
        
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        
        const currentRows = filteredRows.slice(startIndex, endIndex);
        currentRows.forEach(row => row.style.display = '');
        
        renderPagination();
    }

    function renderPagination() {
        if (!paginationContainer) return;
        paginationContainer.innerHTML = '';
        
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        if (totalPages === 0) return; 

        // زر السابق (Previous)
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="javascript:void(0);">Previous</a>`;
        prevLi.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });
        paginationContainer.appendChild(prevLi);

        // أزرار الأرقام (Page Numbers)
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${currentPage === i ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="javascript:void(0);">${i}</a>`;
            li.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                renderTable();
            });
            paginationContainer.appendChild(li);
        }

        // زر التالي (Next)
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="javascript:void(0);">Next</a>`;
        nextLi.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        });
        paginationContainer.appendChild(nextLi);
    }

    // 3. تفعيل البحث المباشر
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            
            filteredRows = allRows.filter(function (row) {
                return row.textContent.toLowerCase().includes(term);
            });
            
            currentPage = 1;
            renderTable();
            
            if(checkAll) checkAll.checked = false;
            getCheckboxes().forEach(cb => cb.checked = false);
            toggleBulkDeleteBtn();
        });
    }

    renderTable();

    // دالة مساعدة للحذف المتكرر (Refactored Reusable Deletion Logic)
    function executeDeletion(actionType, elementsToDelete, successMsg) {
        Swal.fire({
            title: trans.are_you_sure,
            text: trans.about_to_delete.replace(':count', elementsToDelete.length),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff5c75', 
            cancelButtonColor: '#6c757d', 
            confirmButtonText: trans.yes_delete,
            cancelButtonText: trans.cancel,
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // أ. إزالة الصف مباشرة
                elementsToDelete.forEach(el => {
                    const row = el.closest('tr');
                    if(row) row.remove();
                });
                
                // ب. تحديث المصفوفات الداخلية الأساسية
                allRows = Array.from(document.querySelectorAll('tbody tr'));
                
                // ج. تحديث الفلترة
                const term = searchInput ? searchInput.value.toLowerCase() : '';
                filteredRows = allRows.filter(function (row) {
                    return row.textContent.toLowerCase().includes(term);
                });
                
                // د. التحقق وتحديث الأزرار
                if (actionType === 'bulk' && checkAll) checkAll.checked = false;
                toggleBulkDeleteBtn();
                
                // هـ. التحقق من الصفحة للحفاظ على الباجينيشن
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                if (currentPage > totalPages && totalPages > 0) {
                    currentPage = totalPages;
                }

                // و. الإعادة للرسم
                renderTable();

                Swal.fire({
                    title: trans.deleted,
                    text: successMsg,
                    icon: 'success',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            }
        });
    }

    // 4. الحذف المتعدد
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const checkedBoxes = Array.from(getCheckboxes()).filter(cb => cb.checked);
            if (checkedBoxes.length > 0) {
                executeDeletion('bulk', checkedBoxes, trans.success_bulk_msg);
            }
        });
    }

    // 5. الحذف الفردي (Single Item Delete)
    if (tbody) {
        tbody.addEventListener('click', function (e) {
            const deleteBtn = e.target.closest('.btn-soft-danger');
            if (deleteBtn && deleteBtn.innerHTML.includes('solar:trash-bin-minimalistic-2-broken')) {
                e.preventDefault();
                executeDeletion('single', [deleteBtn], trans.success_msg);
            }
        });
    }

    // 6. Print Feature (Advanced Formatting)
    const printBtn = document.getElementById('exportPrintBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            let printContents = '<div style="font-family: Arial, sans-serif; padding: 20px;" dir="auto">';
            printContents += '<h2 style="text-align: center; margin-bottom: 20px; font-weight: bold; color: #333;">Product List</h2>';
            printContents += '<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">';
            printContents += '<thead><tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">';
            
            // Generate Headers (skipping Checkbox and Action columns)
            const headers = Array.from(document.querySelectorAll('thead th'));
            headers.forEach((th, index) => {
                if(index > 0 && index < headers.length - 1) { 
                    printContents += `<th style="padding: 12px; text-align: start; border-bottom: 2px solid #dee2e6; color: #555;">${th.innerText}</th>`;
                }
            });
            printContents += '</tr></thead><tbody>';

            // Generate Current Table Rows
            filteredRows.forEach(row => {
                printContents += '<tr style="border-bottom: 1px solid #dee2e6;">';
                const cells = Array.from(row.querySelectorAll('td'));
                cells.forEach((td, index) => {
                    if (index > 0 && index < cells.length - 1) {
                        let textValue = td.innerText.replace(/\n+/g, ' ').trim();
                        printContents += `<td style="padding: 12px; vertical-align: middle; color: #444;">${textValue}</td>`;
                    }
                });
                printContents += '</tr>';
            });

            printContents += '</tbody></table></div>';

            const printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print Layout</title>');
            printWindow.document.write('<style>@media print { body { -webkit-print-color-adjust: exact; } }</style>');
            printWindow.document.write('</head><body onload="window.print(); window.close();">');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
        });
    }

    // 7. Advanced Excel Export (SheetJS)
    const excelBtn = document.getElementById('exportExcelBtn');
    if (excelBtn) {
        excelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (typeof XLSX === 'undefined') {
                console.error("SheetJS is not loaded!");
                return;
            }

            const exportData = [];
            
            // Extract headers
            const headers = Array.from(document.querySelectorAll('thead th'));
            const headerRow = [];
            headers.forEach((th, index) => {
                if(index > 0 && index < headers.length - 1) {
                    headerRow.push(th.innerText.trim());
                }
            });
            exportData.push(headerRow);

            // Extract rows safely handling formats
            filteredRows.forEach(row => {
                const rowData = [];
                const cells = Array.from(row.querySelectorAll('td'));
                cells.forEach((td, index) => {
                    if (index > 0 && index < cells.length - 1) {
                        let textContent = td.innerText.replace(/\n+/g, ' ').trim();
                        rowData.push(textContent);
                    }
                });
                exportData.push(rowData);
            });

            // Create Workbook and Worksheet
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(exportData);

            // Auto-size columns slightly
            const wsCols = headerRow.map(h => ({ wch: Math.max(h.length + 5, 20) }));
            ws['!cols'] = wsCols;

            XLSX.utils.book_append_sheet(wb, ws, "Products Export");
            
            // Generate Excel File
            XLSX.writeFile(wb, "Larkon_Products_Export.xlsx");
        });
    }
});
