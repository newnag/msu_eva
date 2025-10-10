@props([
    'indexRoute',
    'editUrlBase'   => '/criteria-config',
    'deleteUrlBase' => '/report-version',
    'showUrlBase'   => '/criteria-config',
    'csrfToken',
])

@php
    $th = 'px-4 py-3 text-lg font-semibold text-black';
@endphp

<div class="bg-white rounded-md shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 rounded-t-md">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="{{ $th }} w-[80px] text-center">ลำดับ</th>
                <th scope="col" class="{{ $th }} w-[30%]">ชื่อเวอร์ชันเกณฑ์การประเมิน</th>
                <th scope="col" class="{{ $th }}">สร้างโดย</th>
                <th scope="col" class="{{ $th }}">วันที่สร้าง</th>
                <th scope="col" class="{{ $th }}">วันที่แก้ไข</th>
                <th scope="col" class="{{ $th }} w-[220px] text-center">การดำเนินการ</th>
            </tr>
        </thead>
            <tbody id="criteria-table-body" class="divide-y divide-gray-100">
                <tr id="criteria-loading">
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    // ==================== Utils ================ //
    const toThaiDate = (iso) => {
        if (!iso) return '-';
        try {
            return new Intl.DateTimeFormat('th-TH', { dateStyle: 'short' }).format(new Date(iso));
        } catch { return '-'; }
    };

    // ==================== Inline Alert ======================= //
    function showAlert(message, type = 'success') {
        let modal = document.getElementById('custom-alert-modal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'custom-alert-modal';
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center';
            modal.style.background = 'rgba(0,0,0,0.6)';
            modal.innerHTML = `
                <div id="custom-alert-box" class="bg-white rounded-lg shadow-2xl max-w-sm w-full p-6 text-center animate-fade-in">
                    <div class="flex justify-center mb-4">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-full ${type === 'error' ? 'bg-red-100' : 'bg-green-100'}">
                            ${type === 'error'
                                ? '<svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>'
                                : '<svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>'}
                        </span>
                    </div>
                    <div class="text-lg font-semibold mb-2 ${type === 'error' ? 'text-red-600' : 'text-green-600'}">${type === 'error' ? 'เกิดข้อผิดพลาด' : 'สำเร็จ'}</div>
                    <div class="mb-4 text-gray-700">${message}</div>
                    <button id="custom-alert-ok" class="mt-2 px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">ตกลง</button>
                </div>
            `;
            document.body.appendChild(modal);
        } else {
            modal.style.display = '';
        }
        document.getElementById('custom-alert-ok').onclick = function () {
            modal.style.display = 'none';
        };
    }

    // =========================== Fetch + Render ======================= //
    function fetchCriteriaVersions() {
        fetch(@json($indexRoute), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': @json($csrfToken),
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => renderCriteriaTable(data.data))
        .catch(() => {
            const tbody = document.getElementById('criteria-table-body');
            tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-red-500">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>';
        });
    }

    function renderCriteriaTable(criteriaVersions) {
        const tbody = document.getElementById('criteria-table-body');
        tbody.innerHTML = '';

        if (!criteriaVersions || criteriaVersions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">ไม่พบข้อมูลเกณฑ์</td></tr>';
            return;
        }

        criteriaVersions.forEach((item, index) => {
            const title = (item.version_name || '').toString().trim();
            const createdAt = toThaiDate(item.created_at);
            const updatedAt = toThaiDate(item.updated_at);
            const creatorName = (item.created_by && item.created_by.name) ? item.created_by.name : '-';

            const showUrl = `${@json($showUrlBase)}/${item.id}`;
            const editUrl = `${@json($editUrlBase)}/${item.id}/edit`;

            // สร้างแถวที่ "ทั้งแถวคลิกเพื่อดูรายละเอียด"
            const tr = document.createElement('tr');
            tr.className = 'group hover:bg-gray-50 cursor-pointer';
            tr.setAttribute('data-title', title.toLowerCase());
            tr.addEventListener('click', () => {
                if (e.target.closest('button, a, .action-stop')) return;
                 window.location.href = showUrl; 
                });

            tr.innerHTML = `
                <td class="px-4 py-3 text-black text-lg text-center font-normal">${index + 1}</td>

                <td class="px-4 py-3 text-black text-lg font-normal">
                    <a href="${showUrl}" class="decoration-transparent group-hover:decoration-inherit">
                        ${title || 'ไม่มีการระบุชื่อเวอร์ชัน'}
                    </a>
                </td>

                <td class="px-4 py-3 text-black text-lg font-normal">${creatorName}</td>
                <td class="px-4 py-3 text-black text-lg font-normal">${createdAt}</td>
                <td class="px-4 py-3 text-black text-lg font-normal">${updatedAt}</td>

                <td class="px-4 py-2">
                    <div class="flex items-center justify-center gap-3">
                        <!-- ปุ่มแก้ไข -->
                        <a href="${editUrl}"
                           class="action-stop inline-flex items-center gap-1.5 rounded-md border !border-blue-600 text-blue-600 hover:bg-blue-50 font-medium text-sm px-3 py-1.5 shadow-sm">
                            <i class="fas fa-edit"></i><span>แก้ไข</span>
                        </a>

                        <!-- ปุ่มลบ -->
                         <button type="button"
                            onclick="event.stopPropagation(); showDeleteModal(${item.id}, this)"
                            class="action-stop inline-flex items-center gap-1.5 rounded-md border !border-red-500 text-red-500 hover:bg-red-50 font-medium text-sm px-3 py-1.5 shadow-sm">
                            <i class="fas fa-trash-alt"></i><span>ลบ</span>
                        </button>
                    </div>
                </td>
            `;

            tbody.appendChild(tr);
        });
    }

  

    // =============================== Delete =================================== //
    let deleteModal = null, deleteTargetId = null, deleteTargetBtn = null;

    function ensureDeleteModal() {
        if (deleteModal) return;
        deleteModal = document.createElement('div');
        deleteModal.id = 'delete-modal';
        deleteModal.className = 'fixed inset-0 z-[99999] flex items-center justify-center';
        deleteModal.innerHTML = `
            <div class="fixed inset-0 bg-gray-800 bg-opacity-40 modal-overlay"></div>
            <div class="relative z-10 mt-6 bg-white border border-red-200 rounded-xl shadow-2xl max-w-md w-full p-8 text-center animate-fade-in">
                <div class="mx-auto mb-4 flex items-center justify-center w-16 h-16 rounded-full bg-red-100">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">ยืนยันการลบเวอร์ชัน</h3>
                <p class="text-gray-600 mb-6">คุณต้องการลบเวอร์ชันนี้หรือไม่? <br><span class="text-red-500 font-semibold">ข้อมูลนี้จะไม่สามารถกู้คืนได้</span></p>
                <div class="flex justify-center gap-4 mt-4">
                    <button id="cancel-delete-btn" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold hover:bg-gray-300">ยกเลิก</button>
                    <button id="confirm-delete-btn" class="px-6 py-2 bg-red-600 text-white rounded-md font-semibold hover:bg-red-500">ลบ</button>
                </div>
            </div>
        `;
        document.body.appendChild(deleteModal);

        deleteModal.querySelector('.modal-overlay').onclick = function (e) { e.stopPropagation(); };
        deleteModal.querySelector('#cancel-delete-btn').onclick = hideDeleteModal;
        deleteModal.querySelector('#confirm-delete-btn').onclick = function () {
            if (deleteTargetId && deleteTargetBtn) doDeleteCriteriaVersion(deleteTargetId, deleteTargetBtn);
        };
    }

    function showDeleteModal(id, btn) {
        ensureDeleteModal();
        deleteTargetId = id;
        deleteTargetBtn = btn;
        deleteModal.classList.remove('hidden');
    }
    function hideDeleteModal() {
        deleteModal?.classList.add('hidden');
        deleteTargetId = null;
        deleteTargetBtn = null;
    }

    function doDeleteCriteriaVersion(id, btn) {
        btn.disabled = true;
        fetch(`${@json($deleteUrlBase)}/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': @json($csrfToken),
            },
            credentials: 'same-origin'
        })
        .then(res => {
            if (res.ok) return res;
            return res.json().then(data => { throw new Error(data.message || 'ลบไม่สำเร็จ'); });
        })
        .then(() => {
            hideDeleteModal();
            showAlert('ลบข้อมูลสำเร็จ', 'success');
            setTimeout(() => { window.location.reload(); }, 1200);
        })
        .catch(err => {
            showAlert('เกิดข้อผิดพลาด: ' + err.message, 'error');
            btn.disabled = false;
            hideDeleteModal();
        });
    }

    // ============================= init ============================ //
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success')) showAlert('อัปเดตข้อมูลสำเร็จ', 'success');

        fetchCriteriaVersions();

        // ค้นหา client-side
        const searchInput = document.getElementById('search-input');
        const tbody = document.getElementById('criteria-table-body');
        if (searchInput && tbody) {
            let t = null;
            searchInput.addEventListener('input', function (e) {
                const q = e.target.value.trim().toLowerCase();
                clearTimeout(t);
                t = setTimeout(() => {
                    Array.from(tbody.querySelectorAll('tr[data-title]')).forEach(row => {
                        const title = row.getAttribute('data-title') || '';
                        row.classList.toggle('hidden', q && !title.includes(q));
                    });

                    const anyVisible = Array.from(tbody.querySelectorAll('tr[data-title]')).some(tr => !tr.classList.contains('hidden'));
                    const noRow = tbody.querySelector('#no-result-row');
                    if (!anyVisible) {
                        if (!noRow) {
                            const tr = document.createElement('tr');
                            tr.id = 'no-result-row';
                            tr.innerHTML = `<td colspan="6" class="px-4 py-6 text-center text-base text-gray-600">ไม่พบข้อมูลเกณฑ์การประเมินที่ตรงกับคำค้น</td>`;
                            tbody.appendChild(tr);
                        }
                    } else {
                        noRow?.remove();
                    }
                }, 150);
            });
        }
    });
</script>
@endpush
