{{--
|--------------------------------------------------------------------------
| index.blade.php
|--------------------------------------------------------------------------
| สำหรับแสดง/เพิ่ม/ลบ/แก้ไข ตำแหน่งงานในระบบ
|
| Components ที่ใช้:
| - Main button: resources/views/components/button.blade.php
| - Header pf page: resources/views/components/header.blade.php
|--------------------------------------------------------------------------
--}}

@extends('layouts.app')
@section('title', 'จัดการข้อมูลตำแหน่ง')
@section('content')
    <style>
        body {
            background-color: #ffffff;
            color: #333333;
        }

        .table-container {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .table-header {
            border-bottom: 1px solid #e0e0e0;
            padding: 16px 24px;
        }

        .table-header h4 {
            color: #2c2c2c;
            margin: 0;
            font-weight: 500;
            font-size: 1.1rem;
        }

        .table-custom {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        /* Table style */
        .table-custom thead th {
            background-color: #ffffff;
            border: none;
            border-bottom: 2px solid #e0e0e0;
            padding: 16px 24px;
            text-align: center;
        }

        .table-custom tbody td {
            padding: 16px 24px;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-custom tbody tr:hover {
            background-color: #f8f8f8;
            transition: background-color 0.15s ease;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 3px;
            font-weight: 400;
            margin: 0 2px;
            font-size: 0.8rem;
            border: 1px solid;
            transition: all 0.15s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background-color: #ffffff;
            color: #333333;
            border-color: #cccccc;
        }

        .btn-edit:hover {
            background-color: #f0f0f0;
            border-color: #999999;
            color: #333333;
        }

        .btn-delete {
            background-color: #ffffff;
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
        }

        .btn-add {
            background-color: #ffffff;
            color: #333333;
            border: 1px solid #cccccc;
            padding: 10px 20px;
            border-radius: 3px;
            font-weight: 400;
            margin-bottom: 16px;
            font-size: 0.9rem;
            transition: all 0.15s ease;
        }

        .btn-add:hover {
            background-color: #f0f0f0;
            border-color: #999999;
            color: #333333;
        }

        /* Modal Styles */
        .modal-content-custom {
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .modal-header-custom {
            background-color: #f8f8f8;
            color: #2c2c2c;
            border-bottom: 1px solid #e0e0e0;
            border-radius: 4px 4px 0 0;
            padding: 16px 24px;
        }

        .modal-header-custom .modal-title {
            font-weight: 500;
            font-size: 1.1rem;
        }

        .modal-body-custom {
            padding: 24px;
            background-color: #ffffff;
        }

        .form-group-modal {
            margin-bottom: 16px;
        }

        .form-control {
            border: 1px solid #cccccc;
            border-radius: 3px;
            padding: 10px 12px;
            font-size: 0.9rem;
            transition: border-color 0.15s ease;
        }

        .form-control:focus {
            border-color: #666666;
            box-shadow: 0 0 0 0.15rem rgba(102, 102, 102, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 500;
            color: #2c2c2c;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        .btn-modal-save {
            background-color: #ffffff;
            color: #333333;
            border: 1px solid #cccccc;
            padding: 10px 20px;
            border-radius: 3px;
            font-weight: 400;
            font-size: 0.9rem;
        }

        .btn-modal-save:hover {
            background-color: #f0f0f0;
            border-color: #999999;
            color: #333333;
        }

        .btn-modal-cancel {
            background-color: #ffffff;
            color: #666666;
            border: 1px solid #cccccc;
            padding: 10px 20px;
            border-radius: 3px;
            font-weight: 400;
            font-size: 0.9rem;
        }

        .btn-modal-cancel:hover {
            background-color: #f0f0f0;
            border-color: #999999;
            color: #666666;
        }

        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #666666;
            background-color: #ffffff;
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 16px;
            color: #cccccc;
        }

        .empty-state h5 {
            color: #333333;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #666666;
            margin: 0;
        }

        /* Alert Styles */
        .alert {
            border: 1px solid;
            border-radius: 3px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 0.9rem;
        }

        .alert-success {
            background-color: #f8f9fa;
            color: #2c2c2c;
            border-color: #e0e0e0;
        }

        .alert-danger {
            background-color: #f8f9fa;
            color: #2c2c2c;
            border-color: #e0e0e0;
        }

        /* Pagination */
        .pagination .page-link {
            color: #333333;
            border: 1px solid #cccccc;
            padding: 6px 10px;
            font-size: 0.85rem;
        }

        .pagination .page-link:hover {
            background-color: #f0f0f0;
            border-color: #999999;
            color: #333333;
        }

        .pagination .page-item.active .page-link {
            background-color: #333333;
            border-color: #333333;
            color: #ffffff;
        }

        /* Professional spacing and typography */
        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        /* .modal-backdrop {
                            display: none !important;
                        } */

        body {
            overflow: auto !important;
            padding-right: 0 !important;
        }

        /* Remove all shadows */
        * {
            box-shadow: none !important;
        }

        /* Minimal professional look */
        .btn-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #666666;
        }

        .btn-close:hover {
            color: #333333;
        }

        .btn i {
            color: inherit;
        }

        /* Table striped alternative */
        .table-custom tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .table-custom tbody tr:nth-child(even):hover {
            background-color: #f0f0f0;
        }

        /* Professional delete modal */
        .delete-modal-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 16px 24px;
        }

        .delete-modal-body {
            padding: 24px;
            background-color: #ffffff;
        }

        .delete-icon {
            font-size: 2rem;
            color: #dc3545;
            margin-bottom: 16px;
        }

        /* Text improvements */
        h5 {
            font-weight: 500;
        }

        .text-muted {
            color: #666666 !important;
        }

        /* Form validation styles */
        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 4px;
        }
    </style>
    <div class="container-fluid">
        <!-- Page Header and Button -->
        <x-header 
        title="จัดการข้อมูลตำแหน่งงานในระบบ"  
        text="ตำแหน่งงานทั้งหมด {{ $positions->total() }} ตำแหน่ง"  
        icon="fas fa-user-tie">
            <x-slot name="action">
                <x-button type="primary" buttonType="button" text="เพิ่มตำแหน่ง" onclick="openCreateModal()"
                    icon="fas fa-plus" />
            </x-slot>
        </x-header>

        <!-- Table Container -->
        <div class="mt-2 overflow-hidden rounded-md border border-gray-200 bg-white drop-shadow-md">
            <div class="table-responsive">
                @if (isset($positions) && $positions->count() > 0)
                    <table class="w-full border-collapse">
                        <colgroup>
                            <col class="w-[10%]" />
                            <col class="w-[60%]" />
                            <col class="w-[30%]" />
                        </colgroup>
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50 drop-shadow-sm">
                                <th class="py-3 px-6 text-center font-medium text-lg ">ลำดับ</th>
                                <th class="py-3 pl-[300px] text-left font-medium text-lg ">ชื่อตำแหน่ง</th>
                                <th class="py-3 px-6 text-center font-medium text-lg">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($positions as $index => $position)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-[9px] px-6 text-center text-base md:text-lg font-normal">
                                        {{ ($positions->currentPage() - 1) * $positions->perPage() + $index + 1 }}
                                    </td>
                                    <td class="py-[9px] pl-[300px] text-base md:text-lg font-normal mx-auto">
                                        {{ $position->name }}
                                    </td>
                                    <td class="py-[9px] px-6">
                                        <div class="flex items-center justify-center gap-3">
                                            <!-- ปุ่มแก้ไข -->
                                            <x-button type="outline-primary" text="แก้ไข" icon="fas fa-pen"
                                                onclick="handleEdit({{ $position->id }}, '{{ $position->name }}', '{{ $position->description }}')" />
                                            <!-- ปุ่มลบ -->
                                            <x-button type="outline-danger" text="ลบ" icon="fas fa-trash-alt"
                                                onclick="confirmDelete({{ $position->id }})" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination Control (Tailwind default) -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4 py-3 border-t border-gray-200">
                        {{-- ตัวเลือกจำนวนรายการต่อหน้า --}}
                        <div class="flex items-center text-md text-gray-700">
                            <span>แสดง</span>
                            <form method="GET" class="mx-2">
                                {{-- คงพารามิเตอร์อื่น ๆ ที่มีอยู่ ยกเว้น per_page/page --}}
                                @foreach(request()->except(['per_page', 'page']) as $key => $val)
                                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                                @endforeach

                                <select name="per_page" onchange="this.form.submit()"
                                    class="border border-gray-300 rounded-md px-2 py-1 text-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500">
                                    <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </form>
                            <span>รายการต่อหน้า</span>
                        </div>

                        {{-- ข้อมูลหน้าปัจจุบัน + ปุ่มเปลี่ยนหน้า --}}
                        <div class="flex items-center gap-4">
                            <span class=" text-md text-gray-700">
                                หน้า {{ $positions->currentPage() }} จาก {{ $positions->lastPage() }}
                            </span>
                            <div>
                                {{-- Tailwind default ของ Laravel และคง query string ทั้งหมด (ยกเว้น page) --}}
                                {{ $positions->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>

                @else
                    <div class="text-center py-12 text-gray-600 bg-white">
                        <i class="fas fa-user-tie text-4xl text-gray-300 mb-4 block"></i>
                        <h5 class="text-gray-900 font-medium mb-1">ยังไม่มีข้อมูล</h5>
                        <p>คลิกปุ่ม "เพิ่มข้อมูล" เพื่อเริ่มต้นเพิ่มข้อมูลตำแหน่ง</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="positionModal" tabindex="-1" aria-labelledby="positionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title" id="positionModalLabel">
                        <i class="fas fa-plus me-2"></i>เพิ่มข้อมูลตำแหน่ง
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    <form id="positionForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="form_method" value="POST">
                        <input type="hidden" id="positionId" name="id">

                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อตำแหน่ง <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" required
                                placeholder="กรุณาระบุชื่อตำแหน่ง">
                            <div class="text-red-500 text-sm mt-1 hidden" id="nameError">กรุณากรอกชื่อตำแหน่ง</div>
                        </div>

                        {{-- <div class="mb-3">
                            <label for="description" class="form-label">คำอธิบาย</label>
                            <textarea id="description" name="description" class="form-control" rows="4"
                                placeholder="คำอธิบายเกี่ยวกับตำแหน่งงาน (ไม่บังคับ)"></textarea>
                        </div> --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <x-button type=defualt text="ยกเลิก" icon="fas fa-times" data-bs-dismiss="modal" />
                    <x-button type="primary" buttonType="button" text="บันทึก" onclick="submitForm()" icon="fas fa-save"
                        id="positionSubmitBtn"
                        class="btn-disabled transition-colors disabled:opacity-50 disabled:cursor-not-allowed" />
                </div>
            </div>
        </div>
    </div>

    <x-delete-warning-modal text="ตำแหน่ง" formAction="{{ route('positions.destroy', ':id') }}" entityUrl="/positions" />

    @if(session('success'))
        <div id="successMessage"
            class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-[10000] transform transition-transform duration-300">
            <div class="flex items-center space-x-3">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <script>
        // Form validation variables
        let isFormValid = false;

        // ฟังก์ชันตรวจสอบความถูกต้องของฟอร์ม
        function validateForm() {
            const nameInput = document.getElementById('name');
            const nameError = document.getElementById('nameError');
            const submitBtn = document.getElementById('positionSubmitBtn');
            if (!nameInput || !nameError || !submitBtn) return false;

            const nameValue = nameInput.value.trim();
            let isValid = true;

            // ตรวจสอบชื่อตำแหน่ง (required field)
            if (nameValue === '') {
                nameInput.classList.add('is-invalid');
                nameError.style.display = 'block';
                nameError.textContent = 'กรุณากรอกชื่อตำแหน่ง';
                isValid = false;
            } else {
                nameInput.classList.remove('is-invalid');
                nameError.style.display = 'none';
            }

            // อัพเดทสถานะปุ่มส่ง
            updateSubmitButton(isValid);
            isFormValid = isValid;
            return isValid;
        }

        // ฟังก์ชันอัพเดทสถานะปุ่มส่ง
        function updateSubmitButton(isValid) {
            const submitBtn = document.getElementById('positionSubmitBtn');
            if (!submitBtn) return;

            if (isValid) {
                submitBtn.classList.remove('btn-disabled');
                submitBtn.disabled = false;
                submitBtn.style.pointerEvents = 'auto';
            } else {
                submitBtn.classList.add('btn-disabled');
                submitBtn.disabled = true;
                submitBtn.style.pointerEvents = 'none';
            }
        }

        // ฟังก์ชันเปิด modal สำหรับเพิ่มข้อมูล
        function openCreateModal() {
            clearModalBackdrop();

            const form = document.getElementById('positionForm');
            const modalTitle = document.getElementById('positionModalLabel');

            if (!form || !modalTitle) return;

            resetForm();

            form.action = "{{ route('positions.store') }}";
            document.getElementById('form_method').value = 'POST';
            modalTitle.innerHTML = '<i class="fas fa-plus me-2"></i>เพิ่มข้อมูลตำแหน่ง';

            const modalEl = document.getElementById('positionModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();

            // Focus บน input แรกหลังจาก modal เปิด
            modalEl.addEventListener('shown.bs.modal', function () {
                document.getElementById('name').focus();
            });
        }

        // ฟังก์ชันเปิด modal สำหรับแก้ไขข้อมูล
        function handleEdit(id, name, description) {
            clearModalBackdrop();

            const form = document.getElementById('positionForm');
            const modalTitle = document.getElementById('positionModalLabel');

            if (!form || !modalTitle) return;

            resetForm();

            form.action = `/positions/${id}`;
            document.getElementById('form_method').value = 'PUT';
            document.getElementById('positionId').value = id;
            document.getElementById('name').value = name;
            //document.getElementById('description').value = description || '';
            modalTitle.innerHTML = '<i class="fas fa-edit me-2"></i>แก้ไขข้อมูลตำแหน่ง';

            // ตรวจสอบความถูกต้องหลังจากกรอกข้อมูล
            setTimeout(() => {
                validateForm();
            }, 100);

            const modalEl = document.getElementById('positionModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();

            // Focus บน input แรกหลังจาก modal เปิด
            modalEl.addEventListener('shown.bs.modal', function () {
                document.getElementById('name').focus();
            });
        }

        // ฟังก์ชันส่งฟอร์ม
        function submitForm() {
            // ตรวจสอบความถูกต้องอีกครั้งก่อนส่ง
            if (!validateForm()) {
                return false;
            }

            const form = document.getElementById('positionForm');
            const modalEl = document.getElementById('positionModal');

            if (form && modalEl && isFormValid) {
                // แสดง loading state
                const submitBtn = document.getElementById('positionSubmitBtn');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังบันทึก...';
                    submitBtn.disabled = true;
                    // กู้คืนปุ่มหากเกิดข้อผิดพลาด
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 5000);
                }

                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
                form.submit();
            }
        }

        // ฟังก์ชันรีเซ็ตฟอร์ม
        function resetForm() {
            const form = document.getElementById('positionForm');
            if (form) {
                form.reset();
                document.getElementById('positionId').value = '';
                document.getElementById('form_method').value = 'POST';

                // เคลียร์ validation states
                const inputs = form.querySelectorAll('.form-control');
                inputs.forEach(input => {
                    input.classList.remove('is-invalid');
                });

                const errors = form.querySelectorAll('.invalid-feedback');
                errors.forEach(error => {
                    error.style.display = 'none';
                });

                // รีเซ็ตสถานะปุ่มส่ง
                updateSubmitButton(false);
                isFormValid = false;
            }
        }

        // ฟังก์ชันเคลียร์ modal backdrop ที่ค้าง
        function clearModalBackdrop() {
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(modal => {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });

            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => {
                backdrop.remove();
            });

            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function () {
            clearModalBackdrop();

            // เพิ่ม event listeners สำหรับ real-time validation
            const nameInput = document.getElementById('name');
            const descriptionInput = document.getElementById('description');

            if (nameInput) {
                // ตรวจสอบทันทีเมื่อพิมพ์
                nameInput.addEventListener('input', function () {
                    validateForm();
                });

                // ตรวจสอบเมื่อ focus out
                nameInput.addEventListener('blur', function () {
                    validateForm();
                });

                // ตรวจสอบเมื่อกด Enter
                nameInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        if (validateForm()) {
                            submitForm();
                        }
                    }
                });
            }

            if (descriptionInput) {
                // ตรวจสอบเมื่อ focus out (สำหรับ validation ในอนาคต)
                descriptionInput.addEventListener('blur', function () {
                    validateForm();
                });
            }

            // ป้องกันการ submit ฟอร์มโดยตรง
            const form = document.getElementById('positionForm');
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (validateForm()) {
                        return true;
                    }
                    return false;
                });
            }
        });

        window.addEventListener('pageshow', function (event) {
            clearModalBackdrop();
        });

        window.addEventListener('load', function () {
            clearModalBackdrop();
        });

        // Auto-hide success/error messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const messages = document.querySelectorAll('#successMessage, #warningMessage, #errorMessage');
            messages.forEach(function (message) {
                setTimeout(function () {
                    if (message.parentElement) {
                        message.style.transform = 'translateX(100%)';
                        setTimeout(function () {
                            if (message.parentElement) {
                                message.remove();
                            }
                        }, 300);
                    }
                }, 5000);
            });
        });
    </script>
@endsection