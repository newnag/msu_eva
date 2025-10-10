@extends('layouts.app')

@section('content')
    <div class="py-4 bg-gradient-to-r from-blue-50 to-indigo-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <x-ui.heading level="h1" size="text-3xl" class="mb-2">
                สร้างเกณฑ์การประเมินใหม่
                </x-ui.heading>

                <p class="text-gray-600 text-lg">กรุณากรอกข้อมูลเกณฑ์การประเมินให้ครบถ้วนเพื่อสร้างเกณฑ์ที่สมบูรณ์</p>
            </div>

            <form id="jsonForm" action="{{ route('report-structure.store') }}" method="POST" class="space-y-8" novalidate>
                @csrf
                <!-- Assessment info -->
                <x-criteria.assessment-info
                    :version-name="old('version_name')"
                    :report-title="old('report_title')"
                    :report-description="old('report_description')"
                    :assessment-type="old('assessment_type')"
                    :comment="old('comment')"
                />

                <!-- Categories -->
                <x-criteria.categories />
                <!-- END Category Block -->

                <div class="flex justify-end mt-10 space-x-4">
                    <button type="button" id="reset_form_btn"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        ล้างฟอร์ม
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading_overlay"
        class="fixed inset-0  bg-opacity-50 backdrop-blur-md flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-700 text-lg">กำลังส่งข้อมูล กรุณารอสักครู่...</p>
        </div>
    </div>
    <!-- Confirmation Modal -->
    <x-criteria.confirm-modal />
    <!-- Success Modal -->
    <x-criteria.success-modal />
@endsection

@push('scripts')
    <script>
        function cloneAndClear(blockSelector) {
            let node = document.querySelector(blockSelector).cloneNode(true);
            node.querySelectorAll('input[type="checkbox"]').forEach(inp => inp.checked = false);
            node.querySelectorAll('input:not([type="checkbox"])').forEach(inp => inp.value = '');
            node.querySelectorAll(
                '.evaluation_list_block:not(:first-child), .quant_criteria_block:not(:first-child), .qual_criteria_block:not(:first-child), .quant_sub_criteria_block:not(:first-child), .qual_sub_criteria_block:not(:first-child)'
            ).forEach(e => e.remove());

            if (blockSelector === '.evaluation_list_block') {
                const container = document.querySelector('.evaluation_lists_container');
                const index = container.querySelectorAll('.evaluation_list_block').length + 1;
                node.querySelector('.eval_sequence').textContent = index;
                node.querySelector('.quantity_main_criterias_container').classList.add('hidden');
                node.querySelector('.quality_main_criterias_container').classList.add('hidden');
            }
            if (blockSelector === '.category_block') {
                const container = document.getElementById('categories_container');
                const index = container.querySelectorAll('.category_block').length + 1;
                node.querySelector('.category_sequence').textContent = index;
            }
            return node;
        }

        function updateButtonStates(containerSelector, upBtnSelector, downBtnSelector) {
            const items = document.querySelectorAll(containerSelector);
            items.forEach((item, index) => {
                const upBtn = item.querySelector(upBtnSelector);
                const downBtn = item.querySelector(downBtnSelector);
                upBtn.disabled = index === 0;
                downBtn.disabled = index === items.length - 1;
            });
        }

        function updateEvalSequence(container) {
            container.querySelectorAll('.evaluation_list_block').forEach((evalBlock, index) => {
                evalBlock.querySelector('.eval_sequence').textContent = index + 1;
            });
        }

        function updateCategorySequence(container) {
            container.querySelectorAll('.category_block').forEach((catBlock, index) => {
                catBlock.querySelector('.category_sequence').textContent = index + 1;
            });
        }

        function updateQuantMainSequence(container) {
            container.querySelectorAll('.quant_criteria_block').forEach((block, idx) => {
                block.querySelector('.quant_main_sequence').textContent = idx + 1;
            });
        }

        function updateQuantSubSequence(container) {
            container.querySelectorAll('.quant_sub_criteria_block').forEach((block, idx) => {
                block.querySelector('.quant_sub_sequence').textContent = idx + 1;
            });
        }

        function updateQualMainSequence(container) {
            container.querySelectorAll('.qual_criteria_block').forEach((block, idx) => {
                block.querySelector('.qual_main_sequence').textContent = idx + 1;
            });
        }

        function updateQualSubSequence(container) {
            container.querySelectorAll('.qual_sub_criteria_block').forEach((block, idx) => {
                block.querySelector('.qual_sub_sequence').textContent = idx + 1;
            });
        }

        function showLoading() {
            document.getElementById('loading_overlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loading_overlay').classList.add('hidden');
        }

        function showConfirmModal(versionName) {
            document.getElementById('version_name_display').textContent = versionName || 'ไม่ระบุ';
            document.getElementById('confirm_modal').classList.remove('hidden');
        }

        // Modal-based alert for validation error
        function showValidationErrorModal(message) {
            let modal = document.getElementById('custom-alert-modal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'custom-alert-modal';
                modal.className = 'fixed inset-0 z-50 flex items-center justify-center';
                modal.style.background = 'rgba(0,0,0,0.6)';
                modal.innerHTML = `
                    <div id="custom-alert-box" class="bg-white rounded-lg shadow-2xl max-w-sm w-full p-6 text-center animate-fade-in">
                        <div class="flex justify-center mb-4">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </span>
                        </div>
                        <div class="text-lg font-semibold mb-2 text-red-600">กรอกข้อมูลไม่ครบถ้วน</div>
                        <div class="mb-4 text-gray-700">${message}</div>
                        <button id="custom-alert-ok" class="mt-2 px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">ตกลง</button>
                    </div>
                `;
                document.body.appendChild(modal);
            } else {
                modal.className = 'fixed inset-0 z-50 flex items-center justify-center';
                modal.style.background = 'rgba(0,0,0,0.6)';
                modal.querySelector('#custom-alert-box').className = `bg-white rounded-lg shadow-2xl max-w-sm w-full p-6 text-center animate-fade-in`;
                modal.querySelector('#custom-alert-box').innerHTML = `
                    <div class="flex justify-center mb-4">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                            <svg class=\"w-7 h-7 text-red-500\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6 18L18 6M6 6l12 12\"/></svg>
                        </span>
                    </div>
                    <div class="text-lg font-semibold mb-2 text-red-600">กรอกข้อมูลไม่ครบถ้วน</div>
                    <div class="mb-4 text-gray-700">${message}</div>
                    <button id=\"custom-alert-ok\" class=\"mt-2 px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none\">ตกลง</button>
                `;
                modal.style.display = '';
            }
            modal.querySelector('#custom-alert-ok').onclick = function() {
                modal.style.display = 'none';
            };
        }
        // // Validate required fields before showing confirm modal
        document.getElementById('jsonForm').addEventListener('submit', function(e) {
            // Prevent default submit for custom validation
            e.preventDefault();
            // Basic required fields
            const versionName = document.getElementById('version_name').value.trim();
            const reportTitle = document.getElementById('report_title').value.trim();
            const reportDescription = document.getElementById('report_description').value.trim();
            const assessmentType = document.getElementById('assessment_type').value.trim();
            let errorMsg = '';
            if (!versionName) errorMsg += 'กรุณากรอกปีผู้ประเมิน\n';
            if (!reportTitle) errorMsg += 'กรุณากรอกชื่อเกณฑ์\n';
            if (!reportDescription) errorMsg += 'กรุณากรอกรายละเอียดเกณฑ์\n';
            if (!assessmentType) errorMsg += 'กรุณาเลือกประเภทการประเมิน\n';
            if (errorMsg) {
                showValidationErrorModal(errorMsg.replace(/\n/g, '<br>'));
                return false;
            }
            // If valid, show confirm modal
            showConfirmModal(versionName);
        }, true);

        function hideConfirmModal() {
            document.getElementById('confirm_modal').classList.add('hidden');
        }

        function showSuccessModal() {
            document.getElementById('success_modal').classList.remove('hidden');
            let countdown = 5;
            const countdownElement = document.getElementById('countdown');
            const interval = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(interval);
                    window.location.href = "{{ route('criteria_config.index') }}";
                }
            }, 1000);
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete_category_btn')) {
                const block = e.target.closest('.category_block');
                const container = document.getElementById('categories_container');
                if (confirm('ต้องการลบหมวดหมู่นี้ใช่หรือไม่?')) {
            if (container.querySelectorAll('.category_block').length > 1) {
                block.remove();
                updateCategorySequence(container);
                updateButtonStates('.category_block', '.move_category_up_btn', '.move_category_down_btn');
            } else {
                showValidationErrorModal('ต้องมีหมวดหมู่การประเมินอย่างน้อย 1 รายการ');
            }
                }
            }

            if (e.target.closest('.delete_eval_btn')) {
                const block = e.target.closest('.evaluation_list_block');
                const container = block.closest('.evaluation_lists_container');
            if (container.querySelectorAll('.evaluation_list_block').length > 1) {
                block.remove();
                updateEvalSequence(container);
                updateButtonStates('.evaluation_list_block', '.move_eval_up_btn', '.move_eval_down_btn');
            } else {
                showValidationErrorModal('ต้องมีรายการประเมินอย่างน้อย 1 รายการ');
            }
            }

            if (e.target.closest('.delete_quant_btn')) {
                const block = e.target.closest('.quant_criteria_block');
                const container = block.closest('.quantity_main_criterias_container');
            if (container.querySelectorAll('.quant_criteria_block').length > 1) {
                block.remove();
                updateQuantMainSequence(container);
                updateButtonStates('.quant_criteria_block', '.move_quant_up_btn', '.move_quant_down_btn');
            } else {
                showValidationErrorModal('ต้องมีเกณฑ์ปริมาณหลักอย่างน้อย 1 รายการ');
            }
            }

            if (e.target.closest('.delete_quant_sub_btn')) {
                const block = e.target.closest('.quant_sub_criteria_block');
                const container = block.closest('.quant_sub_criteria_container');
            if (container.querySelectorAll('.quant_sub_criteria_block').length > 1) {
                block.remove();
                updateQuantSubSequence(container);
            } else {
                showValidationErrorModal('ต้องมีเกณฑ์ปริมาณย่อยอย่างน้อย 1 รายการ');
            }
            }

            if (e.target.closest('.delete_qual_btn')) {
                const block = e.target.closest('.qual_criteria_block');
                const container = block.closest('.quality_main_criterias_container');
            if (container.querySelectorAll('.qual_criteria_block').length > 1) {
                block.remove();
                updateQualMainSequence(container);
                updateButtonStates('.qual_criteria_block', '.move_qual_up_btn', '.move_qual_down_btn');
            } else {
                showValidationErrorModal('ต้องมีเกณฑ์คุณภาพหลักอย่างน้อย 1 รายการ');
            }
            }

            if (e.target.closest('.delete_qual_sub_btn')) {
                const block = e.target.closest('.qual_sub_criteria_block');
                const container = block.closest('.qual_sub_criterias_container');
            if (container.querySelectorAll('.qual_sub_criteria_block').length > 1) {
                block.remove();
                updateQualSubSequence(container);
            } else {
                showValidationErrorModal('ต้องมีเกณฑ์คุณภาพย่อยอย่างน้อย 1 รายการ');
            }
            }

            if (e.target.closest('.move_category_up_btn')) {
                const block = e.target.closest('.category_block');
                const previous = block.previousElementSibling;
                if (previous && previous.classList.contains('category_block')) {
                    block.parentNode.insertBefore(block, previous);
                    updateButtonStates('.category_block', '.move_category_up_btn', '.move_category_down_btn');
                    updateCategorySequence(document.getElementById('categories_container'));
                }
            }

            if (e.target.closest('.move_category_down_btn')) {
                const block = e.target.closest('.category_block');
                const next = block.nextElementSibling;
                if (next && next.classList.contains('category_block')) {
                    block.parentNode.insertBefore(next, block);
                    updateButtonStates('.category_block', '.move_category_up_btn', '.move_category_down_btn');
                    updateCategorySequence(document.getElementById('categories_container'));
                }
            }

            if (e.target.closest('.move_eval_up_btn')) {
                const block = e.target.closest('.evaluation_list_block');
                const container = block.closest('.evaluation_lists_container');
                const previous = block.previousElementSibling;
                if (previous && previous.classList.contains('evaluation_list_block')) {
                    container.insertBefore(block, previous);
                    updateEvalSequence(container);
                    updateButtonStates('.evaluation_list_block', '.move_eval_up_btn', '.move_eval_down_btn');
                }
            }

            if (e.target.closest('.move_eval_down_btn')) {
                const block = e.target.closest('.evaluation_list_block');
                const container = block.closest('.evaluation_lists_container');
                const next = block.nextElementSibling;
                if (next && next.classList.contains('evaluation_list_block')) {
                    container.insertBefore(next, block);
                    updateEvalSequence(container);
                    updateButtonStates('.evaluation_list_block', '.move_eval_up_btn', '.move_eval_down_btn');
                }
            }

            if (e.target.closest('.move_quant_up_btn')) {
                const block = e.target.closest('.quant_criteria_block');
                const container = block.closest('.quantity_main_criterias_container');
                const previous = block.previousElementSibling;
                if (previous && previous.classList.contains('quant_criteria_block')) {
                    block.parentNode.insertBefore(block, previous);
                    updateButtonStates('.quant_criteria_block', '.move_quant_up_btn', '.move_quant_down_btn');
                    updateQuantMainSequence(container);
                }
            }

            if (e.target.closest('.move_quant_down_btn')) {
                const block = e.target.closest('.quant_criteria_block');
                const container = block.closest('.quantity_main_criterias_container');
                const next = block.nextElementSibling;
                if (next && next.classList.contains('quant_criteria_block')) {
                    block.parentNode.insertBefore(next, block);
                    updateButtonStates('.quant_criteria_block', '.move_quant_up_btn', '.move_quant_down_btn');
                    updateQuantMainSequence(container);
                }
            }

            if (e.target.closest('.move_qual_up_btn')) {
                const block = e.target.closest('.qual_criteria_block');
                const container = block.closest('.quality_main_criterias_container');
                const previous = block.previousElementSibling;
                if (previous && previous.classList.contains('qual_criteria_block')) {
                    block.parentNode.insertBefore(block, previous);
                    updateButtonStates('.qual_criteria_block', '.move_qual_up_btn', '.move_qual_down_btn');
                    updateQualMainSequence(container);
                }
            }

            if (e.target.closest('.move_qual_down_btn')) {
                const block = e.target.closest('.qual_criteria_block');
                const container = block.closest('.quality_main_criterias_container');
                const next = block.nextElementSibling;
                if (next && next.classList.contains('qual_criteria_block')) {
                    block.parentNode.insertBefore(next, block);
                    updateButtonStates('.qual_criteria_block', '.move_qual_up_btn', '.move_qual_down_btn');
                    updateQualMainSequence(container);
                }
            }

            if (e.target.closest('#add_category_btn')) {
                let newBlock = cloneAndClear('.category_block');
                document.getElementById('categories_container').appendChild(newBlock);
                updateButtonStates('.category_block', '.move_category_up_btn', '.move_category_down_btn');
                updateCategorySequence(document.getElementById('categories_container'));
                newBlock.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            if (e.target.closest('.add_evaluation_list_btn')) {
                let parent = e.target.closest('.category_block').querySelector('.evaluation_lists_container');
                let newBlock = cloneAndClear('.evaluation_list_block');
                parent.appendChild(newBlock);
                updateButtonStates('.evaluation_list_block', '.move_eval_up_btn', '.move_eval_down_btn');
                updateEvalSequence(parent);
                newBlock.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            if (e.target.closest('.add_quant_criteria_btn')) {
                let parent = e.target.closest('.evaluation_list_block').querySelector(
                    '.quantity_main_criterias_container');
                let newBlock = cloneAndClear('.quant_criteria_block');
                parent.appendChild(newBlock);
                updateButtonStates('.quant_criteria_block', '.move_quant_up_btn', '.move_quant_down_btn');
                updateQuantMainSequence(parent);
                newBlock.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            if (e.target.closest('.add_quant_sub_criteria_btn')) {
                let parent = e.target.closest('.quant_criteria_block').querySelector(
                    '.quant_sub_criteria_container');
                let newBlock = cloneAndClear('.quant_sub_criteria_block');
                parent.appendChild(newBlock);
                updateQuantSubSequence(parent);
                newBlock.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            if (e.target.closest('.add_qual_criteria_btn')) {
                let parent = e.target.closest('.evaluation_list_block').querySelector(
                    '.quality_main_criterias_container');
                let newBlock = cloneAndClear('.qual_criteria_block');
                parent.appendChild(newBlock);
                updateButtonStates('.qual_criteria_block', '.move_qual_up_btn', '.move_qual_down_btn');
                updateQualMainSequence(parent);
                newBlock.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            if (e.target.closest('.add_qual_sub_criteria_btn')) {
                let parent = e.target.closest('.qual_criteria_block').querySelector(
                    '.qual_sub_criterias_container');
                let newBlock = cloneAndClear('.qual_sub_criteria_block');
                parent.appendChild(newBlock);
                updateQualSubSequence(parent);
                newBlock.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('criteria_type')) {
                const evalBlock = e.target.closest('.evaluation_list_block');
                const quantityContainer = evalBlock.querySelector('.quantity_main_criterias_container');
                const qualityContainer = evalBlock.querySelector('.quality_main_criterias_container');
                const quantityCheckbox = evalBlock.querySelector('.quantity_criteria_type');
                const qualityCheckbox = evalBlock.querySelector('.quality_criteria_type');
                quantityContainer.classList.toggle('hidden', !quantityCheckbox.checked);
                qualityContainer.classList.toggle('hidden', !qualityCheckbox.checked);
            }
        });

        document.getElementById('reset_form_btn').addEventListener('click', function() {
            showValidationErrorModal('ต้องการล้างข้อมูลทั้งหมดใช่หรือไม่? <br><br><button id="confirm-reset-btn" class="mt-2 px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">ยืนยัน</button>');
            setTimeout(() => {
                const confirmBtn = document.getElementById('confirm-reset-btn');
                if (confirmBtn) {
                    confirmBtn.onclick = function() {
                        document.getElementById('custom-alert-modal').style.display = 'none';
                        document.getElementById('jsonForm').reset();
                        document.querySelectorAll('.quantity_main_criterias_container, .quality_main_criterias_container')
                            .forEach(container => container.classList.add('hidden'));
                        updateCategorySequence(document.getElementById('categories_container'));
                        document.querySelectorAll('.evaluation_lists_container').forEach(updateEvalSequence);
                        document.querySelectorAll('.quantity_main_criterias_container').forEach(updateQuantMainSequence);
                        document.querySelectorAll('.quant_sub_criteria_container').forEach(updateQuantSubSequence);
                        document.querySelectorAll('.quality_main_criterias_container').forEach(updateQualMainSequence);
                        document.querySelectorAll('.qual_sub_criterias_container').forEach(updateQualSubSequence);
                    };
                }
            }, 100);
        });

        let finalData = null;

        document.getElementById('jsonForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const versionName = document.querySelector('.version_name').value.trim();
            if (!versionName) {
                //alert('กรุณากรอกปีผู้ประเมิน');
                return;
            }

            const reportTitle = document.querySelector('.report_title').value.trim();
            const reportDescription = document.querySelector('.report_description').value.trim();
            if (!reportTitle || !reportDescription) {
                //alert('กรุณากรอกชื่อเกณฑ์และรายละเอียดเกณฑ์');
                return;
            }

            finalData = {
                version_name: versionName,
                created_by: document.getElementById('auth-user-id')?.value || 1,
                report_datas: [],
                categories: []
            };

            let rd = document.querySelector('.report_datas_block');
            const assessmentType = rd.querySelector('.assessment_type').value || null;
            finalData.report_datas.push({
                report_title: reportTitle,
                report_description: reportDescription,
                assessment_type: assessmentType,
                comment: rd.querySelector('.comment').value || null
            });

            document.querySelectorAll('#categories_container .category_block').forEach((catBlock, catI) => {
                const mainCategories = catBlock.querySelector('.main_categories').value.trim();
                const subCategories = catBlock.querySelector('.sub_categories').value.trim();
                if (!mainCategories || !subCategories) {
                    //alert(`กรุณากรอกหมวดหมู่หลักและหมวดหมู่ย่อยสำหรับหมวดหมู่ที่ ${catI + 1}`);
                    return;
                }

                let category = {
                    main_categories: mainCategories,
                    sub_categories: subCategories,
                    sequence: Number(catBlock.querySelector('.category_sequence').textContent),
                    evaluation_lists: []
                };

                catBlock.querySelectorAll('.evaluation_lists_container .evaluation_list_block').forEach((
                    evalBlock, evalI) => {
                    const evalName = evalBlock.querySelector('.eval_name').value.trim();
                    const sumScore = evalBlock.querySelector('.sum_score').value;
                    if (!evalName || !sumScore) {
                        // alert(
                        //     `กรุณากรอกชื่อรายการประเมินและคะแนนรวมสำหรับรายการที่ ${evalI + 1} ในหมวดหมู่ที่ ${catI + 1}`
                        // );
                        return;
                    }

                    const quantityChecked = evalBlock.querySelector('.quantity_criteria_type')
                        .checked;
                    const qualityChecked = evalBlock.querySelector('.quality_criteria_type')
                        .checked;

                    let evalList = {
                        name: evalName,
                        sum_score: Number(sumScore),
                        sequence: Number(evalBlock.querySelector('.eval_sequence').textContent),
                        annotation: evalBlock.querySelector('.annotation').value || null,
                        quantity_main_criterias: [],
                        quality_main_criterias: []
                    };

                    if (quantityChecked) {
                        let valid = true;
                        evalBlock.querySelectorAll(
                            '.quantity_main_criterias_container .quant_criteria_block').forEach(
                            (qMain, qj) => {
                                const quantName = qMain.querySelector('.quant_name').value
                                    .trim();
                                const quantTooltips = qMain.querySelector('.quant_tooltips')
                                    .value.trim();
                                if (!quantName || !quantTooltips) {
                                    // alert(
                                    //     `กรุณากรอกชื่อเกณฑ์และคำอธิบายสำหรับเกณฑ์ปริมาณหลักที่ ${qj + 1} ในรายการประเมินที่ ${evalI + 1} หมวดหมู่ที่ ${catI + 1}`
                                    // );
                                    valid = false;
                                    return;
                                }

                                let quantMain = {
                                    name: quantName,
                                    tooltips: quantTooltips,
                                    sequence: Number(qMain.querySelector(
                                        '.quant_main_sequence').textContent),
                                    quantity_sub_criterias: []
                                };

                                qMain.querySelectorAll(
                                    '.quant_sub_criteria_container .quant_sub_criteria_block'
                                ).forEach((subQ, sk) => {
                                    const subName = subQ.querySelector(
                                        '.quant_sub_name').value.trim();
                                    const scoreA = subQ.querySelector('.score_a').value;
                                    const scoreB = subQ.querySelector('.score_b').value;
                                    if (!subName || !scoreA || !scoreB) {
                                        alert(
                                            `กรุณากรอกชื่อเกณฑ์ย่อย, คะแนน A, และคะแนน B สำหรับเกณฑ์ปริมาณย่อยที่ ${sk + 1} ในเกณฑ์ปริมาณหลักที่ ${qj + 1} รายการประเมินที่ ${evalI + 1} หมวดหมู่ที่ ${catI + 1}`
                                        );
                                        valid = false;
                                        return;
                                    }

                                    quantMain.quantity_sub_criterias.push({
                                        name: subName,
                                        sequence: Number(subQ.querySelector(
                                                '.quant_sub_sequence')
                                            .textContent),
                                        score_a: Number(scoreA),
                                        score_b: Number(scoreB)
                                    });
                                });

                                if (valid) {
                                    evalList.quantity_main_criterias.push(quantMain);
                                }
                            });
                        if (!valid) return;
                    }

                    if (qualityChecked) {
                        let valid = true;
                        evalBlock.querySelectorAll(
                            '.quality_main_criterias_container .qual_criteria_block').forEach((
                            qMain, qj) => {
                            const qualName = qMain.querySelector('.qual_name').value.trim();
                            const qualRatio = qMain.querySelector('.qual_ratio').value;
                            const qualTooltips = qMain.querySelector('.qual_tooltips').value
                                .trim();
                            if (!qualName || !qualRatio || !qualTooltips) {
                                alert(
                                    `กรุณากรอกชื่อเกณฑ์, สัดส่วน, และคำอธิบายสำหรับเกณฑ์คุณภาพหลักที่ ${qj + 1} ในรายการประเมินที่ ${evalI + 1} หมวดหมู่ที่ ${catI + 1}`
                                );
                                valid = false;
                                return;
                            }

                            let qualMain = {
                                name: qualName,
                                ratio: Number(qualRatio),
                                tooltips: qualTooltips,
                                sequence: Number(qMain.querySelector(
                                    '.qual_main_sequence').textContent),
                                quality_sub_criterias: []
                            };

                            qMain.querySelectorAll(
                                '.qual_sub_criterias_container .qual_sub_criteria_block'
                            ).forEach((subQ, sk) => {
                                const subName = subQ.querySelector('.qual_sub_name')
                                    .value.trim();
                                const numScore = subQ.querySelector('.num_score')
                                    .value;
                                if (!subName || !numScore) {
                                    showValidationErrorModal(`กรุณากรอกชื่อเกณฑ์ย่อยและคะแนนสูงสุดสำหรับเกณฑ์คุณภาพย่อยที่ ${sk + 1} ในเกณฑ์คุณภาพหลักที่ ${qj + 1} รายการประเมินที่ ${evalI + 1} หมวดหมู่ที่ ${catI + 1}`);
                                    valid = false;
                                    return;
                                }

                                qualMain.quality_sub_criterias.push({
                                    name: subName,
                                    sequence: Number(subQ.querySelector(
                                            '.qual_sub_sequence')
                                        .textContent),
                                    num_score: Number(numScore)
                                });
                            });

                            if (valid) {
                                evalList.quality_main_criterias.push(qualMain);
                            }
                        });
                        if (!valid) return;
                    }

                    category.evaluation_lists.push(evalList);
                });

                if (category.evaluation_lists.length === 0) {
                    showValidationErrorModal(`กรุณาเพิ่มรายการประเมินอย่างน้อย 1 รายการในหมวดหมู่ที่ ${catI + 1}`);
                    return;
                }

                finalData.categories.push(category);
            });

            if (finalData.categories.length === 0) {
                showValidationErrorModal('กรุณาเพิ่มหมวดหมู่การประเมินอย่างน้อย 1 หมวดหมู่');
                return;
            }

            showConfirmModal(finalData.version_name);
        });

        document.getElementById('confirm_submit_btn').addEventListener('click', async function handleSubmit() {
            hideConfirmModal();
            showLoading();

            try {
                const response = await fetch("{{ route('report-structure.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value ||
                            document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(finalData)
                });

                const data = await response.json();

                // Log the response for debugging
                console.log('Response from server:', data);

                hideLoading();

                if (response.ok && data.success) {
                    // Success case (HTTP 201)
                    showSuccessModal();
                } else if (response.status === 422) {
                    // Validation error (HTTP 422)
                    let errorMessage = 'เกิดข้อผิดพลาดในการตรวจสอบข้อมูล:\n';

                    // Check for both 'error' and 'errors' to handle potential response variations
                    const errors = data.error || data.errors || {};

                    if (Object.keys(errors).length > 0) {
                        // Process validation errors if present
                        for (const [field, messages] of Object.entries(errors)) {
                            errorMessage +=
                                `${field}: ${Array.isArray(messages) ? messages.join(', ') : messages}\n`;
                        }
                    } else {
                        // Fallback if no specific errors are provided
                        errorMessage += data.message || 'ไม่พบรายละเอียดข้อผิดพลาด';
                    }

                    alert(errorMessage);
                } else {
                    // Other errors (e.g., HTTP 500)
                    alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถบันทึกข้อมูลได้'));
                }
            } catch (error) {
                // Network or unexpected errors
                hideLoading();
                console.error('Fetch error:', error);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message);
            }
        });
    </script>
@endpush