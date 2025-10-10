@extends('layouts.app')
@section('content')
    <!-- Success Message -->
    @if(session('success'))
    <div id="successMessage" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-[10000] transform transition-transform duration-300">
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
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-gray-50 min-h-screen py-2">
      <div class="py-2 max-w-6xl mx-auto px-4">
        <h1 class="text-xl md:text-2xl font-semibold text-black mb-4">จัดการรอบการประเมิน</h1>

        <!-- ใช้ฟอร์มเดียวครอบทุกส่วน -->
        <form id="evaluation-form" action="{{ route('assignment-data.store') }}" method="POST">
          @csrf

          <!-- 1. กำหนดกรอบการประเมิน -->
          <div class="bg-white border border-gray-200 shadow-sm rounded-lg mb-6">
            <div class="px-4 py-4 border-b">
              <h2 class="text-xl font-semibold text-black mb-4">1. กำหนดกรอบการประเมิน</h2>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Start -->
                <div>
                  <label for="start_time" class="block text-base font-medium text-gray-800 mb-1">
                    วันที่เริ่มต้นการประเมิน:
                  </label>
                  <div class="relative">
                    <input type="text" name="start_time" id="start_time"
                      class="form-input-custom flatpickr-date w-full pr-10"
                      placeholder="วว/ดด/ปปปป" required>
                    <span class="absolute inset-y-0 right-3 flex items-center text-gray-600 pointer-events-none">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                    </span>
                  </div>
                </div>

                <!-- End -->
                <div>
                  <label for="end_time" class="block text-base font-medium text-gray-800 mb-1">
                    วันที่สิ้นสุดการประเมิน:
                  </label>
                  <div class="relative">
                    <input type="text" name="end_time" id="end_time"
                      class="form-input-custom flatpickr-date w-full pr-10"
                      placeholder="วว/ดด/ปปปป" required>
                    <span class="absolute inset-y-0 right-3 flex items-center text-gray-600 pointer-events-none">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 2. เลือกเกณฑ์การประเมิน -->
          <div class="bg-white border border-gray-200 shadow-sm rounded-lg mb-6">
            <div class="px-4 py-4 border-b border-gray-200">
              <h2 class="text-xl font-semibold text-black mb-4">2. เลือกเกณฑ์การประเมิน</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label for="report_data_id" class="block text-base font-medium text-gray-800 mb-1">
                        เกณฑ์การประเมิน :
                    </label>
                <div class="relative">
                    <select id="report_data_id" name="report_data_id" required
                    class="appearance-none w-full bg-white text-gray-700 border border-gray-300 rounded-lg
                            h-11 px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    <option value="">เลือกเกณฑ์การประเมิน</option>
                    @foreach ($report_data as $item)
                        <option value="{{ $item->id }}">{{ $item->report_title }}</option>
                    @endforeach
                    </select>

                    <!-- custom arrow -->
                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                    </svg>
                    </span>
                </div>
                </div>

              </div>
            </div>
          </div>

          <!-- 3. กำหนดผู้ประเมิน / ผู้รับการประเมิน -->
          <div class="bg-white border border-gray-200 shadow-sm rounded-lg mb-6">
            <div class="px-4 py-4 border-b border-gray-200">
              <h2 class="text-xl font-semibold text-black mb-4">3. กำหนดผู้ประเมิน / ผู้รับการประเมิน</h2>
              <!-- ฟิลเตอร์หน่วยงาน -->
              <div class="mb-6 pb-6 border-b border-gray-200">
                <label for="department_filter" class="block text-base font-medium text-gray-800 mb-2">
                  เลือกหน่วยงาน :
                </label>
                <div class="relative">
                  <select id="department_filter" name="department_filter"
                    class="appearance-none [-webkit-appearance:none] w-full bg-white text-gray-700
                          border border-gray-300 rounded-lg h-11 px-3 pr-10
                          focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    <option value="all">แสดงทั้งหมด</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->id }}">
                        {{ $department->department_name }} - {{ $department->faculty }}
                      </option>
                    @endforeach
                  </select>

                  <!-- custom arrow -->
                  <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                    </svg>
                  </span>
                </div>
                <p class="mt-2 text-sm text-gray-500">
                  เลือกหน่วยงานเพื่อกรองรายชื่อผู้ประเมินและผู้รับการประเมินด้านล่าง
                </p>
                <div id="filter-summary" class="mt-2 p-2 bg-blue-50 rounded text-sm text-blue-700 hidden"></div>
              </div>

              <div class="space-y-8">
                <!-- ผู้รับการประเมิน -->
                <div>
                  <div class="flex items-center justify-between mb-4">
                    <label class="block text-base font-medium text-gray-800">รายชื่อผู้รับการประเมิน :</label>
                    <div class="text-sm text-gray-500">
                      <span id="evaluatees-available-count">0</span> คนที่แสดง จาก
                      <span id="evaluatees-total-count">0</span> คนทั้งหมด
                    </div>
                  </div>
                  <select id="evaluatees" name="evaluatees[]" multiple required
                    class="form-multi-select w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    @foreach ($evaluatees as $user)
                      <option value="{{ $user->id }}" data-department="{{ $user->department_id ?? 'none' }}">
                        {{ $user->name }}
                      </option>
                    @endforeach
                  </select>

                  <div class="mt-4 p-4 bg-gray-50 rounded-lg min-h-[60px]">
                    <p class="text-base font-medium text-gray-800 mb-2">
                      รายชื่อผู้รับการประเมินที่เลือก:
                      <span id="evaluatees-selected-count" class="text-blue-600 font-semibold">0</span> คน
                    </p>
                    <div id="selected-evaluatees" class="flex flex-col gap-2">
                      <span class="text-sm text-gray-500">ยังไม่ได้เลือกรายชื่อ</span>
                    </div>
                  </div>
                </div>

                <!-- ผู้ประเมิน -->
                <div>
                  <div class="flex items-center justify-between mb-4">
                    <label for="evaluators" class="block text-base font-medium text-gray-800">รายชื่อผู้ประเมิน :</label>
                    <div class="text-sm text-gray-500">
                      <span id="evaluators-available-count">0</span> คนที่แสดง จาก
                      <span id="evaluators-total-count">0</span> คนทั้งหมด
                    </div>
                  </div>
                  <select id="evaluators" name="evaluators[]" multiple required
                    class="form-multi-select w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    @foreach ($evaluators as $user)
                      <option value="{{ $user->id }}" data-department="{{ $user->department_id }}">
                        {{ $user->name }} - {{ $user->department->department_name ?? '' }}
                      </option>
                    @endforeach
                  </select>

                  <div class="mt-4 p-4 bg-gray-50 rounded-lg min-h-[60px]">
                    <p class="text-base font-medium text-gray-800 mb-2">
                      รายชื่อผู้ประเมินที่เลือก:
                      <span id="evaluators-selected-count" class="text-blue-600 font-semibold">0</span> คน
                    </p>
                    <div id="selected-evaluators" class="flex flex-col gap-2">
                      <span class="text-sm text-gray-500">ยังไม่ได้เลือกรายชื่อ</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action bar -->
            <div class="flex justify-between items-center px-4 py-4">
              <div class="text-sm text-gray-600">
                <span class="font-medium">สรุป:</span>
                ผู้รับการประเมิน <span id="total-evaluatees" class="text-blue-600 font-semibold">0</span> คน,
                ผู้ประเมิน <span id="total-evaluators" class="text-blue-600 font-semibold">0</span> คน
              </div>
              <div class="flex space-x-3">
                <button type="button" id="reset-btn"
                  class="px-6 py-2 bg-gray-300 text-gray-800 font-semibold rounded-md hover:bg-gray-400 transition-colors">
                  ล้างค่า
                </button>
                <button type="submit"
                  class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                  บันทึก
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

            <script>
                $(document).ready(function() {
                    // ดึงชื่อหน่วยงานจาก blade json
                    const departmentNames = @json($departments->pluck('department_name', 'id'));

                    function formatOption(option) {
                        if (!option.id) return option.text;

                        const $option = $(option.element);
                        if (!$option.length) return option.text; // เพิ่มเช็คนี้

                        const isSelected = $option.is(':selected');
                        const department = $option.data('department') || ''; // กำหนด default
                        const isDisabled = $option.is(':disabled');

                        if (isDisabled) return null;

                        return $(`<div class="flex items-center justify-between" style="padding: 4px 0;" data-id="${option.id}">
                         <div class="flex items-center"> <input type="checkbox" class="mr-2" ${isSelected ? 'checked' : ''} disabled>
                                  <span>${option.text}</span>
                               </div>
                            <span class="${department}">${departmentNames[department] || department}</span>
                            </div>
                       `);
                    }


                    function setupSelect2WithSelectAll(selectId, displayId, selectedCountId, availableCountId) {
                        const $select = $(`#${selectId}`);

                        $select.select2({
                            placeholder: "เลือกรายชื่อ...",
                            width: '100%',
                            closeOnSelect: false,
                            templateResult: formatOption,
                            templateSelection: function(data) {
                                const selected = $select.select2('data');
                                if (selected.length === 0) return 'เลือกรายชื่อ...';
                                return `เลือกแล้ว ${selected.length} คน`;
                            },
                            language: {
                                noResults: function() {
                                    return "ไม่พบรายชื่อที่ตรงกับการค้นหา";
                                },
                                searching: function() {
                                    return "กำลังค้นหา...";
                                }
                            }
                        });

                        $select.on('select2:open', function() {
                            const selectAllClass = `select2-select-all-${selectId}`;
                            $('.select2-results__option--select-all').remove();

                            setTimeout(() => {
                                if (!$(`.${selectAllClass}`).length) {
                                    const $availableOptions = $select.find('option:not(:disabled)');
                                    if ($availableOptions.length > 1) {
                                        const selectAll = $(
                                            `<li class="select2-results__option select2-results__option--select-all ${selectAllClass}" role="option" style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #e5e7eb;">` +
                                            '<span style="font-weight: bold;"><span style="color: #3b82f6;">✓</span> เลือกทั้งหมด (' +
                                            $availableOptions.length + ' คน)</span>' +
                                            '</li>'
                                        );

                                        selectAll.on('click', function(e) {
                                            e.stopPropagation();
                                            const allValues = $select.find('option:not(:disabled)')
                                                .map(function() {
                                                    return $(this).val();
                                                }).get();
                                            $select.val(allValues).trigger('change');
                                            $select.select2('close');
                                        });

                                        $(".select2-results__options").prepend(selectAll);
                                    }
                                }
                            }, 50);
                        });

                        $select.on('select2:close', function() {
                            $('.select2-results__option--select-all').remove();
                        });

                        $select.on('change select2:select select2:unselect', function() {
                            updateDisplayAndCounts();

                            if ($select.data('select2').isOpen()) {
                                $select.select2('close');
                                setTimeout(() => {
                                    $select.select2('open');
                                }, 0);
                            }
                        });

                        updateAvailableCount($select, availableCountId);
                    }

                    function updateAvailableCount($select, countId) {
                        const availableCount = $select.find('option:not(:disabled)').length;
                        $(`#${countId}`).text(availableCount);
                    }

                    function updateDisplayAndCounts() {
                        ['evaluatees', 'evaluators'].forEach(type => {
                            const $select = $(`#${type}`);
                            const selected = $select.find(':selected');
                            const displayId = `selected-${type}`;
                            const selectedCountId = `${type}-selected-count`;

                            $(`#${selectedCountId}`).text(selected.length);

                            if (selected.length === 0) {
                                $(`#${displayId}`).html(
                                    '<span class="text-sm text-gray-500">ยังไม่ได้เลือกรายชื่อ</span>');
                            } else {
                                const tags = selected.map(function() {
                                    const department = $(this).data('department');
                                    return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                ${$(this).text()}
                                <span class=" ${department} ml-2">${departmentNames[department]}</span>
                            </span>`;
                                }).get().join('');
                                $(`#${displayId}`).html(tags);
                            }
                        });

                        $('#total-evaluatees').text($('#evaluatees').find(':selected').length);
                        $('#total-evaluators').text($('#evaluators').find(':selected').length);
                    }

                    function filterByDepartment(department) {
                        const filterSummary = $('#filter-summary');

                        if (department === 'all' || !department) {
                            department = '';
                        }

                        ['evaluatees', 'evaluators'].forEach(selectId => {
                            const $select = $(`#${selectId}`);

                            $select.find('option').each(function() {
                                const optionDept = $(this).data('department');
                                const shouldShow = !department || optionDept == department;
                                $(this).prop('disabled', !shouldShow);
                            });

                            updateAvailableCount($select, `${selectId}-available-count`);

                            $select.select2('destroy');
                            setupSelect2WithSelectAll(
                                selectId,
                                `selected-${selectId}`,
                                `${selectId}-selected-count`,
                                `${selectId}-available-count`
                            );

                            $select.val(null).trigger('change');
                        });

                        if (department) {
                            const deptName = departmentNames[department];
                            const evaluateesCount = $('#evaluatees').find('option:not(:disabled)').length;
                            const evaluatorsCount = $('#evaluators').find('option:not(:disabled)').length;

                            filterSummary.html(
                                `<i class="fas fa-filter mr-2"></i>กำลังแสดงเฉพาะ <strong>${deptName}</strong> - ` +
                                `ผู้รับการประเมิน ${evaluateesCount} คน, ผู้ประเมิน ${evaluatorsCount} คน`
                            ).removeClass('hidden');
                        } else {
                            filterSummary.addClass('hidden');
                        }

                        updateDisplayAndCounts();
                    }

                    setupSelect2WithSelectAll('evaluatees', 'selected-evaluatees', 'evaluatees-selected-count',
                        'evaluatees-available-count');
                    setupSelect2WithSelectAll('evaluators', 'selected-evaluators', 'evaluators-selected-count',
                        'evaluators-available-count');

                    $('#department_filter').on('change', function() {
                        const selectedDepartment = $(this).val();

                        const hasEvaluatees = ($('#evaluatees').val() || []).length > 0;
                        const hasEvaluators = ($('#evaluators').val() || []).length > 0;

                        if ((hasEvaluatees || hasEvaluators) && selectedDepartment !== "") {
                            const confirmed = confirm(
                                "คุณต้องล้างข้อมูลในฟอร์มก่อนจึงจะสามารถเปลี่ยนแผนกได้\n\nต้องการล้างฟอร์มหรือไม่?"
                            );
                            if (!confirmed) {
                                $(this).val('all').trigger('change');
                                return;
                            }

                            $('#evaluation-form')[0].reset();
                            $('#evaluatees').val(null).trigger('change');
                            $('#evaluators').val(null).trigger('change');
                        }

                        filterByDepartment(selectedDepartment);
                    });

                    $('#evaluatees-total-count').text($('#evaluatees option').length);
                    $('#evaluators-total-count').text($('#evaluators option').length);
                    updateDisplayAndCounts();
                    filterByDepartment('all');

                    $('#reset-btn').on('click', function() {
                        if (confirm('คุณต้องการล้างข้อมูลในฟอร์มทั้งหมดใช่หรือไม่?')) {
                            $('#evaluation-form')[0].reset();
                            $('#department_filter').val('all').trigger('change');
                            filterByDepartment('all');
                            alert('ล้างข้อมูลในฟอร์มเรียบร้อยแล้ว');
                        }
                    });

                    // ปรับปรุง submit form ให้สร้างข้อมูลในรูปแบบที่ Controller ต้องการ
                    $('#evaluation-form').on('submit', function(e) {
                        //e.preventDefault();
                        const submitButton = $(this).find('button[type="submit"]');
                        const loadingOverlay = $('#loading-overlay');

                        const evaluateesSelected = $('#evaluatees').val() || [];
                        const evaluatorsSelected = $('#evaluators').val() || [];
                        const reportDataId = $('#report_data_id').val();
                        const startTime = $('#start_time').val();
                        const endTime = $('#end_time').val();

                        // Validation
                        if (!reportDataId) {
                            alert('กรุณาเลือกเกณฑ์การประเมิน');
                            return;
                        }

                        if (!startTime) {
                            alert('กรุณาเลือกวันเริ่มต้นประเมิน');
                            return;
                        }

                        if (!endTime) {
                            alert('กรุณาเลือกวันสิ้นสุดประเมิน');
                            return;
                        }

                        if (evaluateesSelected.length === 0) {
                            alert('กรุณาเลือกผู้รับการประเมินอย่างน้อย 1 คน');
                            return;
                        }

                        if (evaluatorsSelected.length === 0) {
                            alert('กรุณาเลือกผู้ประเมินอย่างน้อย 1 คน');
                            return;
                        }

                        submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลังบันทึก...');
                        loadingOverlay.removeClass('hidden');

                        // สร้าง assignments array ตามที่ Controller ต้องการ
                        const assignments = [];
                        evaluateesSelected.forEach(evaluatee => {
                            evaluatorsSelected.forEach(evaluator => {
                                assignments.push({
                                    report_data_id: reportDataId,
                                    evaluatee: evaluatee,
                                    evaluator: evaluator
                                });
                            });
                        });

                        // สร้าง hidden inputs สำหรับส่งข้อมูล
                        $('#evaluation-form input[name^="assignments"]').remove();

                        assignments.forEach((assignment, index) => {
                            $('<input>').attr({
                                type: 'hidden',
                                name: `assignments[${index}][report_data_id]`,
                                value: assignment.report_data_id
                            }).appendTo('#evaluation-form');

                            $('<input>').attr({
                                type: 'hidden',
                                name: `assignments[${index}][evaluatee]`,
                                value: assignment.evaluatee
                            }).appendTo('#evaluation-form');

                            $('<input>').attr({
                                type: 'hidden',
                                name: `assignments[${index}][evaluator]`,
                                value: assignment.evaluator
                            }).appendTo('#evaluation-form');
                        });

                        // Submit form
                        //this.submit();
                    });
                });

                flatpickr(".flatpickr-date", {
                    dateFormat: "Y-m-d", // ฟอร์แมตที่ส่งเข้า backend
                    altInput: true, // แสดงวันที่แบบอ่านง่าย
                    altFormat: "d/m/Y", // ฟอร์แมตที่ผู้ใช้เห็น
                    locale: "th", // ภาษาไทย
                    allowInput: true
                });
                window.setTimeout(function() {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        // Bootstrap 5 วิธีปิด alert programmatically
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    });
                }, 5000);
            </script>
    </body>
    <style>
        .card-checkbox {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .card-checkbox:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .card-checkbox.selected {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .card-checkbox.selected .card-avatar {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .check-icon {
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.2s ease;
        }

        .card-checkbox.selected .check-icon {
            opacity: 1;
            transform: scale(1);
        }

        .selected-counter {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .select2-results__option--select-all {
            font-weight: bold;
            background-color: #eef2ff !important;
            color: #1e40af !important;
            border-bottom: 1px solid #e5e7eb;
        }

        .select2-results__option--select-all:hover {
            background-color: #dbeafe !important;
        }

        .select2-selection__choice:not(:first-child) {
            display: none !important;
        }

        /* Checkbox ใน dropdown ไม่ควรถูกคลิก */
        .select2-results__option input[type="checkbox"] {
            pointer-events: none;
        }
    </style>

<div id="loading-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden z-50">
    <div class="text-center text-white">
        <!-- Spinner -->
        <svg class="animate-spin h-10 w-10 text-white mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-lg font-semibold">กำลังบันทึกข้อมูล...</p>
        <p class="text-sm">กรุณารอสักครู่</p>
    </div>
</div>
@endsection
