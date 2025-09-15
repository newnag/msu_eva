@extends('layouts.app')

@section('content')
    <div class="py-3">
        <div class="mx-auto sm:px-6 lg:px-8">
            <!-- Header + Toolbar -->
            <div class="flex justify-between items-center mb-1">
                <h2 class="font-semibold text-2xl text-black">
                    จัดการเกณฑ์การประเมิน
                </h2>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none">
                        <input id="search-input" type="text" placeholder="ค้นหาเกรฑ์การประเมิน"
                            class="w-full md:w-72 bg-white border border-gray-100 rounded-md px-10 py-2.5 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>

                    <a href="{{ route('criteria_config.create') }}"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-lg px-4 py-2.5 rounded-md transition">
                        <i class="fas fa-plus"></i>
                        เพิ่มเกณฑ์
                    </a>
                </div>
            </div>

            <!-- เส้นตรง -->
            <hr class="border-t-2 border-gray-400 mb-3">

            <!-- Grid: ตำแหน่งวางการ์ด -->
            <div id="criteria-grid" class="grid grid-cols-5 gap-4">
                <!-- Loading -->
                <div id="criteria-loading" class="col-span-5 flex justify-center py-10">
                    <span class="text-gray-500">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Date format (Thai)
        const toThaiDate = (iso) => {
            if (!iso) return '-';
            try {
                return new Intl.DateTimeFormat('th-TH', { dateStyle: 'short' }).format(new Date(iso));
            } catch { return '-'; }
        };

        // Alert modal
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

        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success')) showAlert('อัปเดตข้อมูลสำเร็จ', 'success');

            fetchCriteriaVersions(); // โหลดข้อมูลรายการ

            // ค้นหาแบบ client-side ด้วย data-attribute
            const searchInput = document.getElementById('search-input');
            const grid = document.getElementById('criteria-grid');
            let t = null;
            searchInput.addEventListener('input', function (e) {
                const q = e.target.value.trim().toLowerCase();
                clearTimeout(t);
                t = setTimeout(() => {
                    grid.querySelectorAll('[data-title]').forEach(card => {
                        const title = card.getAttribute('data-title') || '';
                        card.classList.toggle('hidden', q && !title.includes(q));
                    });
                    const anyVisible = Array.from(grid.children).some(el => !el.classList.contains('hidden'));
                    if (!anyVisible) {
                        if (!grid.querySelector('#no-result')) {
                            grid.insertAdjacentHTML('beforeend',
                                '<div id="no-result" class="col-span-5 text-center text-gray-500">ไม่พบผลลัพธ์ตามคำค้น</div>');
                        }
                    } else {
                        grid.querySelector('#no-result')?.remove();
                    }
                }, 150);
            });
        });

        // ดึงข้อมูลจาก backend
        function fetchCriteriaVersions() {
            fetch("{{ route('report-structure.index') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            })
                .then(response => response.json())
                .then(data => renderCriteriaCards(data.data))
                .catch(() => {
                    document.getElementById('criteria-grid').innerHTML =
                        '<div class="col-span-5 text-center text-red-500">เกิดข้อผิดพลาดในการโหลดข้อมูล</div>';
                });
        }

        // ==================== การ์ด ==================== //
        function renderCriteriaCards(criteriaVersions) {
            const grid = document.getElementById('criteria-grid');
            grid.innerHTML = '';

            if (!criteriaVersions || criteriaVersions.length === 0) {
                grid.innerHTML = '<div class="col-span-5 text-center text-gray-500">ไม่พบข้อมูลเกณฑ์</div>';
                return;
            }

            criteriaVersions.forEach((item) => {
                const card = document.createElement('div');
                card.className = 'bg-white rounded-md drop-shadow-md border border-gray-100 p-2 flex flex-col h-72';

                const title = (item.version_name || '').toString().trim();
                const createdAt = toThaiDate(item.created_at);
                const updatedAt = toThaiDate(item.updated_at);

                // ตรวจสอบชื่อผู้สร้าง
                let creatorName = '-';
                if (item.created_by && typeof item.created_by === 'object' && item.created_by.name) {
                    creatorName = item.created_by.name;
                } else if (item.created_by_name) {
                    creatorName = item.created_by_name;
                } else if (typeof item.created_by === 'string') {
                    creatorName = item.created_by;
                }

                card.setAttribute('data-title', title.toLowerCase());

                // เนื้อหาการ์ด
                card.innerHTML = `
                <div class="text-center mt-3 mb-2">
                    <h3 class="text-xl font-semibold text-blue-700 leading-tight">
                        ${title || 'ไม่ระบุชื่อเวอร์ชัน'}
                    </h3>
                </div>

                <!-- สร้างโดย: -->
                <div class="text-center text-gray-700 text-base font-normal">
                    <p><span class="font-medium text-gray-700">สร้างโดย:</span> ${creatorName}</p>
                </div>

                <!-- วันที่สร้างและแก้ไขล่าสุด -->
                <div class="text-center text-gray-700 text-sm font-normal mt-10">
                    <p><span class="font-medium text-gray-700">วันที่สร้าง:</span> ${createdAt}</p>
                    <p><span class="font-medium text-gray-700">แก้ไขล่าสุด:</span> ${updatedAt}</p>
                </div>

               <!-- Edit and Delete Button -->
                <div class="mt-auto pt-4 space-y-3">
                <div class="flex gap-3 mb-3">
                    <!-- ปุ่มแก้ไข -->
                    <a href="/criteria-config/${item.id}/edit"
                    class="flex-1 text-center rounded-full border !border-blue-600 text-blue-600 
                            hover:bg-blue-50 font-medium text-sm py-1.5 shadow-sm 
                            flex items-center justify-center gap-1.5">
                        <i class="fas fa-edit mr-2"></i>
                        แก้ไข
                    </a>

                    <!-- ปุ่มลบ -->
                   <button type="button"
                            onclick="showDeleteModal(${item.id}, this)"
                            class="flex-1 text-center rounded-full border !border-red-500 text-red-500 hover:bg-red-50 
                                font-medium text-sm py-1.5 shadow-sm flex items-center justify-center gap-1.5">
                        <i class="fas fa-trash-alt mr-2"></i>
                        ลบ
                    </button>
                </div>
                </div>
                `;
                grid.appendChild(card);
            });
        }

        // ============ ลบเกณฑ์การประเมิน ============= //
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
            fetch(`/report-version/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
    </script>
@endsection