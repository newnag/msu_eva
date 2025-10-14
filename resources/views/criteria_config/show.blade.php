@extends('layouts.app')

@section('content')
    {{--
    ============================================================================
    Page: รายละเอียดเกณฑ์การประเมิน (Criteria Details)
    Purpose: ดึงข้อมูลเวอร์ชันจาก backend และเรนเดอร์โครงสร้างเกณฑ์ทั้งชุด
    Notes:
    - ใช้ route('report-structure.show', ['id' => $id]) ในการ fetch
    - DOM targets: #criteria-details, #update-success-alert
    ============================================================================
    --}}

    <div class="py-2 bg-gradient-to-r from-blue-50 to-indigo-50 min-h-screen">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between gap-3">
                <x-ui.heading class="!mb-0">
                    รายละเอียดเกณฑ์การประเมิน
                </x-ui.heading>
            </div>

            <!-- Placeholder ขณะรอดึงข้อมูล / Loading state -->
            <div id="criteria-details">
                <div class="flex justify-center py-10 text-gray-500">Loading...</div>
            </div>

            <!-- Global alert: แสดงเมื่ออัปเดตสำเร็จ -->
            <div id="update-success-alert"
                class="hidden fixed top-8 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 text-lg font-semibold">
                อัปเดตข้อมูลสำเร็จ
            </div>
        </div>
    </div>

    <script>
        // =========================================================================
        // Lifecycle: เริ่มทำงานเมื่อ DOM พร้อม
        // =========================================================================
        document.addEventListener('DOMContentLoaded', function () {
            fetchVersionDetails();
        });

        // =========================================================================
        // fetchVersionDetails
        // ดึงรายละเอียดเวอร์ชันจาก route report-structure.show (JSON)
        //
        // @returns {void}
        //
        // Behavior:
        //  - success: data.data มี payload -> renderVersionDetails(data.data)
        //  - not found: แสดงข้อความ "ไม่พบข้อมูลเวอร์ชัน"
        //  - error: แสดง "เกิดข้อผิดพลาดในการโหลดข้อมูล"
        // =========================================================================
        function fetchVersionDetails() {
            const url = "{{ route('report-structure.show', ['id' => $id ?? '']) }}";

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.data) {
                        renderVersionDetails(data.data);
                    } else {
                        document.getElementById('criteria-details').innerHTML =
                            '<div class="text-center text-red-500">ไม่พบข้อมูลเวอร์ชัน</div>';
                    }
                })
                .catch(() => {
                    document.getElementById('criteria-details').innerHTML =
                        '<div class="text-center text-red-500">เกิดข้อผิดพลาดในการโหลดข้อมูล</div>';
                });
        }

        // =========================================================================
        // renderVersionDetails
        // เรนเดอร์ HTML จากออบเจ็กต์ข้อมูลของเวอร์ชัน
        //
        // @param {Object} data - payload โครงสร้างเวอร์ชันจาก backend
        //   Expected keys (บางส่วน):
        //     - version_name
        //     - report_datas: [{ report_title, report_description, assessment_type, comment }]
        //     - categories: [{
        //         main_categories, sub_categories, sub_category_score,
        //         evaluation_lists: [{
        //            name, sum_score, annotation,
        //            quantity_main_criterias: [{
        //              name, tooltips, quantity_sub_criterias: [{ name, score_a, score_b }]
        //            }],
        //            quality_main_criterias: [{
        //              name, tooltips, ratio, quality_sub_criterias: [{ name, num_score }]
        //            }]
        //         }]
        //       }]
        // @returns {void}
        //
        // Notes:
        //  - คำนวณผลรวม sub_category_score ต่อ main_categories เพื่อแสดงยอดรวมต่อหมวดหลัก
        //  - ใส่ "หมายเหตุ" ที่มาจาก report_datas.comment (ถ้ามี) ท้ายหน้า
        // =========================================================================
        function renderVersionDetails(data) {
            let html = `
                    <div class="bg-white rounded-lg shadow-lg px-4 pt-3 pb-8 overflow-hidden">
                        <div class="flex justify-end mb-3">
                            <a href="{{ $editUrl ?? route('criteria_config.edit', $id) }}"
                            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-lg px-4 py-2.5 rounded-md transition">
                                <i class="fas fa-edit text-sm"></i><span class="ml-2">แก้ไขเกณฑ์การประเมิน</span>
                            </a>
                        </div>
                `;

            // เก็บหมายเหตุส่วนหัว จาก report_datas.comment
            const headerNotes = [];

            // ---------------------------------------------------------------------
            // Header: ชื่อเรื่อง / คำอธิบาย / ประเภทการประเมิน / เวอร์ชัน
            // ---------------------------------------------------------------------
            if (data.report_datas && data.report_datas.length > 0) {
                html += `<div class="mb-6"><div class="space-y-4">`;

                data.report_datas.forEach(rd => {
                    // รวบรวมหมายเหตุ (ถ้ามี)
                    if (rd.comment && String(rd.comment).trim() !== '') {
                        headerNotes.push(rd.comment);
                    }

                    html += `
                                        <div class="bg-blue-100 w-full border border-blue-100 rounded-lg px-4 py-3 drop-shadow-blue-100 drop-shadow-md">
                                            <!-- เวอร์ชัน -->
                                               <div class="text-lg text-gray-700 font-normal text-end">
                                                    เวอร์ชัน: ${data.version_name ?? '-'}
                                                </div>

                                            <div class="space-y-3 mb-3">
                                                <!-- ชื่อเกณฑ์ -->
                                                <div class="text-2xl text-black font-semibold text-center">${rd.report_title}</div>

                                                <!-- คำอธิบายเกณฑ์ -->
                                                <div class="text-2xl text-black font-semibold text-center">${rd.report_description}</div>

                                                <!-- ประเภทการประเมิน -->
                                                <div class="text-2xl text-black font-semibold text-center">( สาย${rd.assessment_type} )</div>
                                            </div>
                                        </div>`;
                });

                html += `</div></div>`;
            }

            // ---------------------------------------------------------------------
            // เตรียมผลรวมคะแนนย่อย (sub_category_score) ต่อ "หมวดหมู่หลัก"
            // ใช้สำหรับแสดงผลรวมบนหัวข้อของแต่ละหมวดหลัก
            // ---------------------------------------------------------------------
            const sumByMainCategory = {};
            if (data.categories && data.categories.length > 0) {
                data.categories.forEach(cat => {
                    const main = cat.main_categories;
                    const score = parseFloat(cat.sub_category_score) || 0;
                    if (!sumByMainCategory[main]) sumByMainCategory[main] = 0;
                    sumByMainCategory[main] += score;
                });
            }

            // ---------------------------------------------------------------------
            // Categories Section
            // แสดงหมวดหมู่หลัก, หมวดย่อย, รายการประเมิน, เกณฑ์เชิงปริมาณ/คุณภาพ
            // ---------------------------------------------------------------------
            if (data.categories && data.categories.length > 0) {
                data.categories.forEach(cat => {
                    const main = cat.main_categories || 'ไม่ระบุหมวดหมู่';
                    const subIndexByMain = {};
                    const totalScoreRaw = sumByMainCategory[main] ?? 0;
                    const totalScore = Number(totalScoreRaw).toLocaleString(undefined, { maximumFractionDigits: 2 });

                    if (subIndexByMain[main] == null) subIndexByMain[main] = 0;
                    subIndexByMain[main] += 1;
                    const N = subIndexByMain[main];

                    html += `
                                    <div class="border border-gray-200 rounded-lg p-4 my-3 bg-gray-50 drop-shadow-sm">
                                        <!----- หมวดหมู่หลัก ----->
                                        <div class="mb-2 font-bold text-2xl text-black">
                                            ${cat.main_categories}
                                            <span>
                                                <span class="underline decoration-1 underline-offset-2">${totalScore} คะแนน</span> แบ่งเป็น
                                            </span>
                                        </div>

                                        <!---- หมวดย่อย ---->
                                        <div class="mb-4 font-semibold text-xl text-black">
                                            ${N}. ${cat.sub_categories}
                                             <span>
                                                <span class="underline decoration-1 underline-offset-2">${cat.sub_category_score ?? 0} คะแนน ดังนี้</span>
                                            </span>
                                        </div>
                                    `;

                    // --------------------------------------------
                    // Evaluation Lists
                    // --------------------------------------------
                    if (cat.evaluation_lists && cat.evaluation_lists.length > 0) {
                        html += `<div class="ml-2"><div class="space-y-3">`;

                        cat.evaluation_lists.forEach((ev, evIdx) => {
                            html += `
                                           <div class="bg-white border border-gray-100 rounded p-3 shadow-sm">

                                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                                    <span class="font-medium text-lg text-black">${N}.${evIdx + 1} ${ev.name}</span>
                                                    <span class="font-medium text-lg text-black">(ค่าน้ำหนัก ${ev.sum_score} คะแนน) มีองค์ประกอบพิจารณาดังนี้</span> 
                                                </div>

                                                <!-- ${ev.annotation ? `<div class="text-xs text-gray-500 mb-1">หมายเหตุ: ${ev.annotation}</div>` : ''}-->`;

                            // ----------------------------------------------------------
                            // Quantity Criteria (เชิงปริมาณ)
                            // ----------------------------------------------------------
                            if (ev.quantity_main_criterias && ev.quantity_main_criterias.length > 0) {
                                html += `<div class="ml-2 mt-2 space-y-4">`;

                                ev.quantity_main_criterias.forEach((qm, qmIdx) => {
                                    const mainNo = `${qmIdx + 1}.`;

                                    html += `
                                                        <div class="mb-2">
                                                            <div class="space-y-3">
                                                                <!-- เกณฑ์หลักเชิงปริมาณ 
                                                                <div class="flex items-center text-base text-black font-medium text-center">
                                                                    <span class="mr-2">${mainNo}</span>
                                                                    <span>${qm.name}</span> -->
                                                                </div>
                                                    `;

                                    // ตารางเกณฑ์ย่อยเชิงปริมาณ
                                    if (qm.quantity_sub_criterias && qm.quantity_sub_criterias.length > 0) {
                                        html += `
                                                            <div class="overflow-x-auto">
                                                                <table class="min-w-[320px] w-full text-sm border border-gray-300 rounded border-collapse">
                                                                    <thead class="bg-sky-100">
                                                                        <tr>
                                                                            <th class="px-3 py-2 text-center font-medium text-black text-base border border-gray-300">องค์ประกอบพิจารณา</th>
                                                                            <th class="px-3 py-2 text-center font-medium text-black text-base border border-gray-300">ค่านํ้าหนักคะแนน (A)</th>
                                                                            <th class="px-3 py-2 text-center font-medium text-black text-base border border-gray-300">หน่วยภาระงานมาตรฐาน (B)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                        `;

                                        qm.quantity_sub_criterias.forEach((qs, qsIdx) => {
                                            const subNo = `${qmIdx + 1}.${qsIdx + 1}`; // ตัวอย่าง: 1.1, 1.2
                                            html += `
                                                                <tr>
                                                                    <td class="px-3 py-2 text-left font-normal text-black text-base border border-gray-300">
                                                                        <span class="mr-2 text-black">${subNo}</span>${qs.name}
                                                                    </td>
                                                                    <td class="px-3 py-2 text-center font-normal text-black text-base border border-gray-300">
                                                                        ${qs.score_a}
                                                                    </td>
                                                                    <td class="px-3 py-2 text-center font-normal text-black text-base border border-gray-300">
                                                                        ${qs.score_b}
                                                                    </td>
                                                                </tr>
                                                            `;
                                        });

                                        // ======= หมายเหตุของเกณฑ์หลักเชิงปริมาณ ====== //
                                        html += `
                                                                    </tbody>
                                                                </table>
                                                                <!-- หมายเหตุของเกณฑ์หลักเชิงปริมาณ -->
                                                                <div class="text-lg text-black font-medium mt-2">
                                                                    หมายเหตุ:
                                                                    <div class="ml-10 font-normal text-base mt-2">${qm.tooltips}</div>
                                                                </div>
                                                            </div>
                                                        `;
                                    }

                                    html += `
                                                        </div>
                                                    `;
                                });

                                html += `</div>`;
                            }

                            // --------------------------------------------------------------
                            // Quality Criteria (เชิงคุณภาพ)
                            // --------------------------------------------------------------
                            if (ev.quality_main_criterias && ev.quality_main_criterias.length > 0) {

                                // ตรวจว่ามีคำอธิบายหรือไม่ (ใน qm หรือ qs)
                                const hasDescription = ev.quality_main_criterias.some(qm => {
                                    if (qm.description && qm.description.trim() !== '') return true;
                                    if (qm.quality_sub_criterias) {
                                        return qm.quality_sub_criterias.some(qs => qs.description && qs.description.trim() !== '');
                                    }
                                    return false;
                                });

                                html += `
                                                <div class="ml-2 mt-2 overflow-x-auto">
                                                    <table class="min-w-[480px] w-full border border-gray-300 rounded border-collapse">
                                                        <thead class="bg-sky-100">
                                                            <tr class="border border-gray-300">
                                                                <th class="px-3 py-2 text-center font-medium text-black text-base border border-gray-300">
                                                                    องค์ประกอบพิจารณา
                                                                </th>
                                                                ${hasDescription ? `
                                                                <th class="px-3 py-2 text-center font-medium text-black text-base border border-gray-300">
                                                                    คำอธิบาย
                                                                </th>` : ``}
                                                                <th class="px-3 py-2 text-center font-medium text-black text-base border border-gray-300">
                                                                    สัดส่วน
                                                                </th>
                                                                <th class="px-3 py-2 text-center font-medium text-black text-base border border-gray-300">
                                                                    คะแนนที่ได้ (หน่วย:คะแนน)
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                            `;

                                ev.quality_main_criterias.forEach((qm, index) => {
                                    html += `
                                                    <tr class="border border-gray-300">
                                                        <td class="px-3 py-2 text-left font-medium text-black text-base border border-gray-300">
                                                            ${index + 1}. ${qm.name}
                                                        </td>
                                                        ${hasDescription ? `
                                                        <td class="px-3 py-2 text-left font-medium text-black text-base border border-gray-300">
                                                            ${qm.description ?? ''}
                                                        </td>` : ``}
                                                        <td class="px-3 py-2 text-center font-medium text-black text-base border border-gray-300">
                                                            ${qm.ratio} %
                                                        </td>
                                                        <td class="px-3 py-2 text-left font-medium text-black text-base border border-gray-300"></td>
                                                    </tr>
                                                `;

                                    if (qm.quality_sub_criterias && qm.quality_sub_criterias.length > 0) {
                                        qm.quality_sub_criterias.forEach((qs, subIndex) => {
                                            html += `
                                                            <tr class="border border-gray-300">
                                                                <td class="px-6 py-2 text-black text-base border border-gray-300">
                                                                    ${index + 1}.${subIndex + 1} ${qs.name}
                                                                </td>
                                                                ${hasDescription ? `
                                                                <td class="px-3 py-2 text-black text-base border border-gray-300">
                                                                    ${qs.description ?? ''}
                                                                </td>` : ``}
                                                                <td class="px-3 py-2 text-center text-base border border-gray-300"></td>
                                                                <td class="px-3 py-2 text-center text-base border border-gray-300">
                                                                    ${qs.num_score}
                                                                </td>
                                                            </tr>
                                                        `;
                                        });
                                    }
                                });

                                html += `
                                                        </tbody>
                                                    </table>
                                                </div>
                                            `;
                            }

                            html += `</div>`; // ปิดการ์ดรายการประเมิน
                        });

                        html += `</div></div>`; // ปิดกล่องรวมรายการแบบประเมิน
                    }

                    html += `</div>`; // ปิดการ์ดหมวดหมู่หลัก
                });
            }

            // ---------------------------------------------------------------------
            // หมายเหตุจากส่วนหัว (ถ้ามี)
            // ---------------------------------------------------------------------
            if (headerNotes.length > 0) {
                html += `
                                    <div class="mt-6 border-t border-gray-200 pt-4">
                                        <div class="text-lg font-semibold text-gray-800 mb-2">หมายเหตุ</div>
                                        <ul class="list-disc ml-6 space-y-1 text-sm text-gray-700">
                                            ${headerNotes.map(note => `<li>${note}</li>`).join('')}
                                        </ul>
                                    </div>
                                `;
            }

      

            html += `</div>`; // ปิด wrapper หลักของหน้า
                  // -----------------------------------------------------------------
    // ปุ่ม "แก้ไขเกณฑ์การประเมิน" ด้านล่างสุด (เรียงแถวเดียวกับอีกปุ่ม)
    // -----------------------------------------------------------------
    html += `
        <div class="flex justify-center gap-3 mt-8">
            <a href="{{ route('criteria_config.index') }}"
               class="inline-flex items-center bg-gray-500 hover:bg-gray-700 text-white font-lg px-4 py-2.5 rounded-md transition">
                <i class="fas fa-angle-left text-sm"></i><span class="ml-2">กลับไปหน้ารายการ</span>
            </a>
             <a href="{{ $editUrl ?? route('criteria_config.edit', $id) }}"
               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-lg px-4 py-2.5 rounded-md transition">
                <i class="fas fa-edit text-sm"></i><span class="ml-2">แก้ไขเกณฑ์การประเมิน</span>
            </a>
        </div>
    `;
            document.getElementById('criteria-details').innerHTML = html;
        }
    </script>
@endsection