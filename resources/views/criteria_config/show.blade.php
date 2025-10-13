@extends('layouts.app')

@section('content')
    <div class="py-2 bg-gradient-to-r from-blue-50 to-indigo-50 min-h-screen">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <h1 class="text-3xl font-semibold text-black mb-3">รายละเอียดเกณฑ์การประเมิน</h1>
            </div>
            <div id="criteria-details">
                <!-- สถานะระหว่างโหลดข้อมูลจาก API -->
                <div class="flex justify-center py-10 text-gray-500">Loading...</div>
            </div>
            <div id="update-success-alert"
                class="hidden fixed top-8 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 text-lg font-semibold">
                อัปเดตข้อมูลสำเร็จ
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ดึงรายละเอียดเวอร์ชันจาก backend
            fetchVersionDetails();
        });

        // ===== ดึงรายละเอียดเวอร์ชันจาก route report-structure.show =====
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
                    // โครง JSON
                    if (data.data) {
                        renderVersionDetails(data.data);
                    } else {
                        document.getElementById('criteria-details').innerHTML =
                            '<div class="text-center text-red-500">ไม่พบข้อมูลเวอร์ชัน</div>';
                    }
                })
                .catch(() => {
                    // กรณี error ระหว่าง fetch
                    document.getElementById('criteria-details').innerHTML =
                        '<div class="text-center text-red-500">เกิดข้อผิดพลาดในการโหลดข้อมูล</div>';
                });
        }

        // ===== สร้าง HTML แสดงรายละเอียดเวอร์ชัน/เกณฑ์ จาก object "data" =====
        function renderVersionDetails(data) {
        let html = '';
        html += `<div class="bg-white rounded-lg shadow-lg p-4 mb-8">`;

        // หมายเหตุของ report_datas 
        const headerNotes = [];

        // === ชื่อเรื่อง / คำอธิบาย / ประเภทการประเมิน / เวอร์ชันการประเมิน ====
        if (data.report_datas && data.report_datas.length > 0) {
            html += `<div class="mb-6">
                        <div class="space-y-4">
                    `;

            // Loop แสดงการ์ดแสดงส่วน header
            data.report_datas.forEach(rd => {
                // เก็บหมายเหตุ (ถ้ามี) 
                if (rd.comment && String(rd.comment).trim() !== '') {
                    headerNotes.push(rd.comment);
                }

                html += `
                    <div class="bg-blue-100 w-full border border-blue-100 rounded-lg px-4 py-3 drop-shadow-xl drop-shadow-gray-100">
                        <!--------- ชื่อเวอร์ชัน -------->
                        <div class="text-base text-gray-600 font-medium text-end">
                            เวอร์ชัน: ${data.version_name ?? '-'}
                        </div>

                        <div class="space-y-3 mb-2">
                            <!-- ชื่อเกณฑ์ (report_title) -->
                            <div class="text-xl text-black font-semibold text-center">${rd.report_title}</div>

                            <!-- คำอธิบายเกณฑ์ (report_description) -->
                            <div class="text-xl text-black font-semibold text-center">${rd.report_description}</div>

                            <!-- ประเภทการประเมิน (assessment_type) -->
                            <div class="text-xl text-black font-semibold text-center">( ${rd.assessment_type} )</div>
                        </div>
                    </div>`;
            });
            html += `</div></div>`;
        }

        // ===== หมวดหมู่เกณฑ์ประเมิน categories[*] =====
        if (data.categories && data.categories.length > 0) {
            // วนลูปหมวดหมู่หลักแต่ละอัน
            data.categories.forEach(cat => {
                html += `
                <div class="border border-gray-200 rounded-lg p-4 my-3 bg-gray-50 shadow-sm">
                    <!--------------- หมวดหมู่หลัก --------------->
                    <div class="mb-2 font-semibold text-xl text-black">
                        หมวดหมู่เกณฑ์ประเมิน: ${cat.main_categories}
                    </div>
                    <!--------------- หมวดหมู่ย่อย --------------->
                    <div class="mb-4 font-medium text-lg text-gray-700">
                        หมวดหมู่ย่อย: ${cat.sub_categories} คะแนน
                `;

                if (cat.evaluation_lists && cat.evaluation_lists.length > 0) {
                    html += `<div class="ml-2">
                                <!-- กล่องรวมรายการแบบประเมิน -->
                                <div class="space-y-3">
                            `;

                    // วนลูปแบบประเมินแต่ละรายการ
                    cat.evaluation_lists.forEach(ev => {
                        html +=
                        `<div class="bg-white border border-gray-100 rounded p-3 shadow-sm">

                            <!-- ชื่อแบบประเมิน (ev.name) + คะแนนรวม (ev.sum_score) -->
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="font-semibold text-xl text-black">ชื่อรายการ: ${ev.name}</span>
                                <span class="font-medium text-lg text-gray-600">(คะแนนรวม: ${ev.sum_score})</span>
                            </div>

                            <!-- หมายเหตุของแบบประเมิน (annotation) ถ้ามี -->
                            ${ev.annotation ? `<div class="text-xs text-gray-500 mb-1">หมายเหตุ: ${ev.annotation}</div>` : ''}`;

                        // ================= เกณฑ์เชิงปริมาณ (Quantity)  quantity_main_criterias[*] ==============
                        if (ev.quantity_main_criterias && ev.quantity_main_criterias.length > 0) {
                            html += `<div class="ml-2 mt-2 space-y-4">`;

                            ev.quantity_main_criterias.forEach((qm, qmIdx) => {
                                const mainNo = `${qmIdx + 1}.`; // 1., 2., 3., ... n.

                                html += `
                                    <div class="mb-2">
                                        <div class="space-y-3">
                                            <!-- เลขลำดับหลัก + ชื่อเกณฑ์หลักเชิงปริมาณ -->
                                            <div class="flex items-center text-lg text-black font-semibold text-center">
                                                <span class="mr-2">${mainNo}</span>
                                                <span>${qm.name}</span>
                                            </div>
                                `;

                                // -------- ตารางเกณฑ์ย่อยเชิงปริมาณ  quantity_sub_criterias[*]-------
                                if (qm.quantity_sub_criterias && qm.quantity_sub_criterias.length > 0) {
                                    html += `
                                        <div class="overflow-x-auto">
                                            <table class="min-w-[320px] w-full text-sm border border-gray-300 rounded border-collapse">
                                                <thead class="bg-sky-100">
                                                    <tr>
                                                        <th class="px-3 py-2 text-center font-semibold text-black text-base border border-gray-300">
                                                            ชื่อเกณฑ์ย่อย
                                                        </th>
                                                        <th class="px-3 py-2 text-center font-semibold text-black text-base border border-gray-300">
                                                            ค่านํ้ำหนักคะแนน (A)
                                                        </th>
                                                        <th class="px-3 py-2 text-center font-semibold text-black text-base border border-gray-300">
                                                            หน่วยภาระงานมาตรฐาน (B)
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    `;

                                    qm.quantity_sub_criterias.forEach((qs, qsIdx) => {
                                        const subNo = `${qmIdx + 1}.${qsIdx + 1}`; // 1.1, 1.2, ...
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

                                    html += `
                                                </tbody>
                                            </table>

                                            <!-- หมายเหตุของเกณฑ์หลักเชิงปริมาณ -->
                                            <div class="text-base text-black font-medium mt-2">
                                                หมายเหตุ:
                                                <div class="ml-4 font-normal text-base">${qm.tooltips}</div>
                                            </div>
                                        </div>
                                    `;
                                }

                                html += `
                                        </div> <!-- /space-y-3 -->
                                    </div>
                                `; // ปิดกล่องของ qm
                            });

                            html += `</div>`;
                        }

                        // ===== เกณฑ์เชิงคุณภาพ (Quality) quality_main_criterias[*] =====
                        if (ev.quality_main_criterias && ev.quality_main_criterias.length > 0) {
                            html += `<div class="ml-2 mt-2">
                                        <!-- ลิสต์ชื่อเกณฑ์หลักเชิงคุณภาพ -->
                                        <ul class="list-disc ml-6 mt-1 space-y-1">
                                    `;
                            ev.quality_main_criterias.forEach(qm => {
                                html += `<li>
                                            <!-- ชื่อเกณฑ์หลักเชิงคุณภาพ (qm.name) + tooltips + สัดส่วน (ratio) -->
                                            <span class="font-semibold text-purple-700">${qm.name}</span>
                                            <span class="text-xs text-gray-400">[${qm.tooltips}]</span>
                                            <span class="text-xs text-gray-500">(สัดส่วน: ${qm.ratio})</span>`;

                                // ตารางเกณฑ์ย่อยเชิงคุณภาพ  quality_sub_criterias[*]
                                if (qm.quality_sub_criterias && qm.quality_sub_criterias.length > 0) {
                                    html += `<div class="overflow-x-auto mt-2">
                                                <table class="min-w-[320px] w-full text-sm border border-gray-200 rounded">
                                                    <thead class="bg-purple-100">
                                                        <tr>
                                                            <th class="px-3 py-2 text-left font-semibold text-black">ชื่อเกณฑ์ย่อย</th>
                                                            <th class="px-3 py-2 text-left font-semibold text-black">คะแนน</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>`;
                                    qm.quality_sub_criterias.forEach(qs => {
                                        html += `<tr class="even:bg-purple-50">
                                                    <td class="px-3 py-2 text-black">${qs.name}</td>
                                                    <td class="px-3 py-2 text-black">${qs.num_score}</td>
                                                </tr>`;
                                    });
                                    html += `</tbody></table></div>`;
                                }
                                html += `</li>`;
                            });
                            html += `</ul></div>`;
                        }
                        html += `</div>`;
                    });
                    html += `</div></div>`;
                }
                html += `</div>`;
            });
        }

        // ====== หมายเหตุจากส่วนหัว ======
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
        html += `</div>`;
        document.getElementById('criteria-details').innerHTML = html;
    }

    </script>
@endsection
