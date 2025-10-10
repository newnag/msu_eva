@extends('layouts.app')

@section('content')
        <style>
            /* Custom animations */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fadeIn {
                animation: fadeIn 0.5s ease-out forwards;
            }

            .stat-card {
                transition: all 0.3s ease;
                opacity: 0;
                animation: fadeIn 0.5s ease-out forwards;
            }

            .stat-card:nth-child(1) {
                animation-delay: 0.1s;
            }

            .stat-card:nth-child(2) {
                animation-delay: 0.2s;
            }

            .stat-card:nth-child(3) {
                animation-delay: 0.3s;
            }

            .hover-scale:hover {
                transform: translateY(-5px);
            }

            .loading-skeleton {
                background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: loading 1.5s infinite;
            }

            @keyframes loading {
                0% {
                    background-position: 200% 0;
                }

                100% {
                    background-position: -200% 0;
                }
            }
        </style>

    <div class="min-h-screen py-2">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-row justify-between space-y-3">
                <!-- Header -->
                <div class="animate-fadeIn">
                    <x-ui.heading class="mb-2">
                        ภาพรวมการประเมินผล
                    </x-ui.heading>
                </div>
                <!-- Filter Summary -->
                <div class="mb-6">
                    @if (request('start_time') || request('end_time') || request('department_name'))
                        <span class="text-black ml-4 inline-flex text-sm border px-2 py-1 rounded bg-gray-100">
                            มีการกรองข้อมูล
                        </span>
                    @endif
                </div>
            </div>

            <div class="space-y-3">
                <!--------------------- KPI Cards !---------------------> 
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Total Participants -->
                    <x-kpi-card title="จำนวนผู้เข้ารับการประเมิน" :value="$totalParticipants ?? 0" unit="คน"
                        subtitle="{{ $evaluationPeriod ?? 'ทุกรอบการประเมิน' }}"
                        iconContainerClass="p-3 bg-blue-100 rounded-lg">
                        <x-slot:icon>
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </x-slot:icon>
                    </x-kpi-card>

                    <!-- Average Score -->
                    <x-kpi-card title="คะแนนเฉลี่ยรวม" :value="$averageScore ?? 0 " :decimals="2" unit="คะแนน"
                        subtitle="{{ $evaluationPeriod ?? 'ทุกรอบการประเมิน' }}"
                        iconContainerClass="p-3 bg-blue-100 rounded-lg">
                        <x-slot:icon>
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </x-slot:icon>
                    </x-kpi-card>
                </div>

                <!-----------------------  Filter Inputs ----------------------->
                <div class="bg-white rounded-xl shadow-lg py-3 px-6 animate-fadeIn">
                    <form id="filterForm" method="get" class="space-y-1">
                        <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-3 md:space-y-0">
                            <!-- Department -->
                            <div class="relative">
                                <label for="department_name" class="block mb-1 text-base text-gray-600 font-medium">
                                    หน่วยงาน/แผนก
                                </label>
                                <select id="department_name" name="department_name"
                                    class="appearance-none text-base text-black font-normal bg-white border border-gray-50 rounded-lg 
                                        focus:ring-blue-500 focus:border-blue-500 px-4 py-2 w-full">
                                    <option value="">ทุกหน่วยงาน</option>
                                    @foreach ($departments ?? [] as $dept)
                                    <option value="{{ $dept->department_name }}" 
                                        {{ request('department_name') == $dept->department_name ? 'selected' : '' }}>
                                        {{ $dept->department_name }}
                                    </option>
                                    @endforeach
                                </select>

                                <!-- Custom arrow -->
                                <div class="absolute inset-y-0 right-3 top-6 flex items-center pointer-events-none text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label class="block mb-1 text-base text-gray-600 font-medium">วันที่เริ่มต้น</label>
                                <input name="start_time" type="date" value="{{ request('start_time', '') }}"
                                    class="text-base text-black font-normal bg-white border border-gray-50 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-4 py-2 w-48" />
                            </div>

                            <!-- End Date -->
                            <div>
                                <label class="block mb-1 text-base text-gray-600 font-medium">วันที่สิ้นสุด</label>
                                <input name="end_time" type="date" value="{{ request('end_time', '') }}"
                                    class="text-base text-black font-normal bg-white border border-gray-50 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-4 py-2 w-48" />
                            </div>

                            <!-- Reset Button -->
                            <div>
                                <label class="block mb-1 text-base text-gray-600 font-medium invisible">รีเซ็ต</label>
                                <button type="button" onclick="resetFilters()"
                                    class="flex items-center px-4 py-2 rounded-md bg-white border border-gray-50 text-gray-800 hover:bg-gray-300 transition space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-rotate-ccw">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                        <path d="M3 3v5h5"/>
                                    </svg>
                                    <span>ล้างค่า</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!---------------------------  Graphs  ----------------------------->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Evaluation Results Status  -->
                    <div id="statusCard" class="bg-white rounded-xl shadow-lg p-6 animate-fadeIn"
                        style="animation-delay: 0.4s;">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">สถานะผลการประเมิน</h3>
                            <x-download-menu />
                        </div>
                        <div class="h-80"><canvas id="statusChart"></canvas></div>
                    </div>

                    <!--- Score Distribution Chart --->
                    <div id="scoreCard" class="bg-white rounded-xl shadow-lg p-6 animate-fadeIn" style="animation-delay: 0.5s;">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">การกระจายตัวของคะแนน</h3>
                            <x-download-menu />
                        </div>
                        <div class="h-80"><canvas id="scoreDistributionChart"></canvas></div>
                    </div>
                </div>

                <!---------------------------  Users Table  ----------------------------->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fadeIn" style="animation-delay: 0.6s;">
                    <!-- Table Header with Filters and Export Button -->
                    <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-0">ผลการประเมินรายบุคคล</h3>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <!-- Left: Status Filter -->
                        <div>
                        <select id="statusFilter"
                            class="text-black text-base w-full sm:w-44 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">สถานะทั้งหมด</option>
                            <option value="Assigned">มอบหมายแล้ว</option>
                            <option value="Draft">ฉบับร่าง</option>
                            <option value="Pending">รอดำเนินการ</option>
                            <option value="Completed">เสร็จสิ้น</option>
                        </select>
                        </div>

                        <!-- Right: Search + Export -->
                        <div class="mt-3 sm:mt-0 flex flex-col sm:flex-row sm:items-center gap-3 sm:ml-auto">
                        <!-- Search bar -->
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="ค้นหาชื่อผู้รับการประเมิน"
                            class="text-black text-base w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            </div>
                        </div>

                        <!-- Export to Excel Button -->
                        <button onclick="exportToExcel()"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-base rounded-lg text-white bg-blue-700 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3 m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                            </svg>
                            ส่งออกเป็น Excel
                        </button>
                        </div>
                    </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table id="userParticipant" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-center text-base font-medium text-black uppercase tracking-wider">ลำดับ</th>
                                    <th class="px-6 py-3 text-left text-base font-medium text-black uppercase tracking-wider">ชื่อผู้รับการประเมิน</th>
                                    <th class="px-6 py-3 text-left text-base font-medium text-gray-900 uppercase tracking-wider">ชื่อผู้ประเมิน</th>
                                    <th class="px-6 py-3 text-left text-base font-medium text-gray-900 uppercase tracking-wider">คะแนน</th>
                                    <th class="px-6 py-3 text-left text-base font-medium text-gray-900 uppercase tracking-wider">สถานะ</th>
                                    <th class="px-6 py-3 text-center text-base font-medium text-gray-900 uppercase tracking-wider">ดำเนินการ
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody" class="bg-white divide-y divide-gray-200">
                                @forelse($reports ?? [] as $report)
                                    @php
                                        // Flexible access for both arrays and objects
                                        $evaluatee_name = is_array($report)
                                            ? $report['evaluatee_name'] ?? 'Unknown User'
                                            : $report->evaluatee_name ?? 'Unknown User';
                                        $evaluator_name = is_array($report)
                                            ? $report['evaluator_name'] ?? 'Unknown User'
                                            : $report->evaluator_name ?? 'Unknown User';
                                        $score = is_array($report) ? $report['score'] ?? 0 : $report->score ?? 0;

                                        // Status field: supports 'status' (API/array) or 'report_status' (Eloquent)
                                        $status = is_array($report)
                                            ? $report['status'] ?? 'UNKNOWN'
                                            : $report->report_status ?? ($report->status ?? 'UNKNOWN');

                                        // Pretty label for status
                                        $prettyStatus = ucfirst(strtolower($status));

                                        // Map status to Thai label
                                        $prettyStatus = match ($status) {
                                            'Completed' => 'เสร็จสิ้น',
                                            'Pending' => 'รอดำเนินการ',
                                            'Draft' => 'แบบร่าง',
                                            default => 'มอบหมายแล้ว',
                                        };

                                        // Color logic
                                        $statusClass = match ($status) {
                                            'Completed' => 'bg-green-100 text-green-800',
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'Draft' => 'bg-gray-100 text-gray-900',
                                            default => 'bg-blue-100 text-blue-800',
                                        };

                                    @endphp
                                    <tr data-status="{{ $status }}" class="hover:bg-gray-50 text-gray-900 text-base font-normal transition-colors duration-150">
                                        <td class="px-6 py-2 whitespace-nowrap text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-2 whitespace-nowrap"> {{ $evaluatee_name }}</td>
                                        <td class="px-6 py-2 whitespace-nowrap"> {{ $evaluator_name }} </td>
                                        <td class="px-6 py-2 whitespace-nowrap">{{ $score }}</td>
                                        <!-- สถานะ -->
                                        <td class="px-6 py-2 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $statusClass }}">
                                                {{ $prettyStatus }}
                                            </span>
                                        </td>
                                        <!-- ดำเนินการ -->
                                        <td class="px-6 py-2 whitespace-nowrap text-center">
                                            <x-button 
                                                type="outline-primary" 
                                                text="ดูรายละเอียด" 
                                                :onclick="'openReportDetails(' . $report['report_id'] . ')'" 
                                            />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-2 text-center text-gray-500">ไม่พบรายงานการประเมิน</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="loading-state" class="hidden p-10 text-center">
                        <div class="loading-skeleton h-10 w-full rounded mb-4"></div>
                        <div class="loading-skeleton h-10 w-3/4 rounded mb-4 mx-auto"></div>
                        <div class="loading-skeleton h-10 w-1/2 rounded mx-auto"></div>
                    </div>

                    <div id="empty-state" class="hidden p-10 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">ไม่พบผู้เข้าร่วม</h3>
                        <p class="mt-1 text-sm text-gray-500">เริ่มต้นโดยการเพิ่มผู้เข้าร่วมใหม่ในการประเมิน</p>
                    </div>

                    <!-- Pagination if needed -->
                    <div class="px-6 py-3 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                แสดง <span class="font-medium">1</span> ถึง <span class="font-medium">10</span> จาก <span
                                    class="font-medium">{{ $totalParticipants ?? 50 }}</span> รายการ
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 rounded border border-gray-300 text-gray-500 hover:bg-gray-50">ก่อนหน้า</button>
                                <button class="px-3 py-1 rounded border border-gray-300 text-gray-500 hover:bg-gray-50">ถัดไป</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

        <script>
            function resetFilters() {
                document.querySelector('input[name="start_time"]').value = '';
                document.querySelector('input[name="end_time"]').value = '';
                document.querySelector('select[name="department_name"]').value = '';
                document.getElementById('filterForm').submit();
            }
        </script>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ============ Utilities for exporting Chart.js charts ============ //
            // Download a file from a given data URL (e.g. PNG, JPG)
            function downloadDataUrl(dataUrl, filename) {
                const a = document.createElement('a');
                a.href = dataUrl; a.download = filename;
                document.body.appendChild(a); a.click(); a.remove();
            }

            // Export a Chart.js chart as an image with a temporary title
            function getChartDataUrlWithTitle(chart, mime = 'image/png', quality = 1.0, titleText = '') {
                const prev = chart.options.plugins.title || {};
                const prevDisplay = prev.display;
                const prevText = prev.text;

                chart.options.plugins.title.display = true;
                if (titleText) chart.options.plugins.title.text = titleText;
                chart.update('none');

                const dataUrl = chart.canvas.toDataURL(mime, quality);

                chart.options.plugins.title.display = prevDisplay ?? false;
                chart.options.plugins.title.text = prevText;
                chart.update('none');

                return dataUrl;
            }

            // Attach download buttons (PNG, JPG, PDF) to a chart card
            function attachDownload(cardEl, chart, titleText, filenameBase) {
                const btnPng = cardEl.querySelector('[data-dl="png"]');
                const btnJpg = cardEl.querySelector('[data-dl="jpg"]');
                const btnPdf = cardEl.querySelector('[data-dl="pdf"]');

                // ดาวน์โหลดเป็น PNG
                btnPng?.addEventListener('click', () => {
                const url = getChartDataUrlWithTitle(chart, 'image/png', 1.0, titleText);
                const date = new Date().toISOString().slice(0, 10);
                downloadDataUrl(url, `${filenameBase}_${date}.png`);
                });

                // ดาวน์โหลดเป็น JPG
                btnJpg?.addEventListener('click', () => {
                const url = getChartDataUrlWithTitle(chart, 'image/jpeg', 0.95, titleText);
                const date = new Date().toISOString().slice(0, 10);
                downloadDataUrl(url, `${filenameBase}_${date}.jpg`);
                });

                // ดาวน์โหลดเป็น PDF
                btnPdf?.addEventListener('click', () => {
                const { jsPDF } = window.jspdf;
                const imgData = getChartDataUrlWithTitle(chart, 'image/png', 1.0, titleText);

                const pdf = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
                const pageW = pdf.internal.pageSize.getWidth();
                const pageH = pdf.internal.pageSize.getHeight();
                const margin = 10;
                const availW = pageW - margin * 2;
                const availH = pageH - margin * 2;

                const img = new Image();
                img.src = imgData;
                img.onload = () => {
                    const ratio = Math.min(availW / img.width, availH / img.height);
                    const w = img.width * ratio;
                    const h = img.height * ratio;
                    const x = (pageW - w) / 2;
                    const y = (pageH - h) / 2;
                    pdf.addImage(imgData, 'PNG', x, y, w, h);
                    const date = new Date().toISOString().slice(0, 10);
                    pdf.save(`${filenameBase}_${date}.pdf`);
                };
                });
            }

            // ============ STATUS (Histogram) ============ //
            const statusCardEl = document.getElementById('statusCard');
            const statusTitle = statusCardEl?.querySelector('h3')?.innerText || 'สถานะผลการประเมิน';

            const statusLabels = ['มอบหมายแล้ว', 'แบบร่าง', 'รอดำเนินการ', 'เสร็จสิ้น'];
            const statusData = [
                {{ $statusCounts_chart['Assigned'] ?? 0 }},
                {{ $statusCounts_chart['Draft'] ?? 0 }},
                {{ $statusCounts_chart['Pending'] ?? 0 }},
                {{ $statusCounts_chart['Completed'] ?? 0 }}
            ];

            const statusCanvas = document.getElementById('statusChart');
            if (statusCanvas) {
                const statusCtx = statusCanvas.getContext('2d');
                const yMaxStatus = Math.max(...statusData, 0) + 1;
                const statusChart = new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: statusLabels,
                    datasets: [{
                    label: 'จำนวนรายงาน',
                    data: statusData,
                    backgroundColor: [
                        'rgba(59,130,246,0.8)',   // Assigned
                        'rgba(156,163,175,0.8)',  // Draft
                        'rgba(251,191,36,0.8)',   // Pending
                        'rgba(16,185,129,0.8)'    // Completed
                    ],
                    borderColor: [
                        'rgba(59,130,246,1)',
                        'rgba(156,163,175,1)',
                        'rgba(251,191,36,1)',
                        'rgba(16,185,129,1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: { padding: { top: 12 } },
                    plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (ctx) => ` ${ctx.label}: ${ctx.raw} รายงาน` }},
                    datalabels: {
                        anchor: 'end', align: 'end', offset: 4, clamp: true, clip: false,
                        color: '#111827', font: { weight: 'bold' }, formatter: (v) => v > 0 ? v : ''
                    },
                    title: { display: false, text: statusTitle, color: '#111827', font: { size: 18, weight: 'bold' } }
                    },
                    scales: {
                    x: { title: { display: true, text: 'สถานะ', font: { size: 15 } }, ticks: { font: { size: 14 } }, grid: { display: false } },
                    y: { beginAtZero: true, suggestedMax: yMaxStatus, title: { display: true, text: 'จำนวน (รายงาน)', font: { size: 14 } }, ticks: { precision: 0, font: { size: 14 } }, grid: { color: 'rgba(0,0,0,0.05)' } }
                    },
                    animation: { duration: 1200, easing: 'easeOutQuart' }
                },
                plugins: [ChartDataLabels]
                });

                attachDownload(statusCardEl, statusChart, statusTitle, 'status_chart');
            }

            // ============== SCORE DISTRIBUTION (Histogram) ============== //
            const scoreCanvas = document.getElementById('scoreDistributionChart');
            if (scoreCanvas) {
                const scoreCardEl = scoreCanvas.closest('.bg-white');
                const chartTitle  = scoreCardEl?.querySelector('h3')?.innerText || 'การกระจายตัวของคะแนน';
                const scoreDistCtx = scoreCanvas.getContext('2d');

                const scores = (window.reports || [])
                .map(r => Number(r.score ?? r.total_score ?? r.final_score ?? NaN))
                .filter(v => !Number.isNaN(v));

                const bins = [
                { label: '<60',   min: -Infinity, max: 59 },
                { label: '60–69', min: 60,  max: 69 },
                { label: '70–79', min: 70,  max: 79 },
                { label: '80–89', min: 80,  max: 89 },
                { label: '90–100',min: 90,  max: 100 }
                ];

                const counts = bins.map(b => scores.filter(s => s >= b.min && s <= b.max).length);
                const total = scores.length || 0;
                const suggestedYMax = Math.max(...counts, 0) + 1;

                const scoreDistChart = new Chart(scoreDistCtx, {
                type: 'bar',
                data: {
                    labels: bins.map(b => b.label),
                    datasets: [{
                    label: 'จำนวนผู้เข้ารับการประเมิน',
                    data: counts,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: { padding: { top: 12 } },
                    plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                        label: (ctx) => {
                            const n = ctx.parsed.y || 0;
                            const pct = total ? (n / total * 100).toFixed(1) : '0.0';
                            return ` ${n} คน (${pct}%)`;
                        }
                        }
                    },
                    datalabels: {
                        anchor: 'end', align: 'end', offset: 4, clamp: true, clip: false,
                        color: '#111827', font: { weight: 'bold' }, formatter: (v) => v > 0 ? v : ''
                    },
                    title: { display: false, text: chartTitle, color: '#111827', font: { size: 18, weight: 'bold' } }
                    },
                    scales: {
                    x: { title: { display: true, text: 'ช่วงคะแนน', font: { size: 15 } }, ticks: { font: { size: 14 } }, grid: { display: false } },
                    y: { beginAtZero: true, suggestedMax: suggestedYMax, title: { display: true, text: 'จำนวน (คน)', font: { size: 14 } }, ticks: { precision: 0, font: { size: 14 } }, grid: { color: 'rgba(0,0,0,0.05)' } }
                    },
                    animation: { duration: 1200, easing: 'easeOutQuart' }
                },
                plugins: [ChartDataLabels]
                });

                attachDownload(scoreCardEl, scoreDistChart, chartTitle, 'score_distribution');
            }

            // ==================== Filter functionality ===================== //
            const statusFilter = document.getElementById('statusFilter');
            const searchInput  = document.getElementById('searchInput');
            const rows = document.querySelectorAll('#userTableBody tr');

            function applyFilters() {
                const status = statusFilter.value;
                const q = (searchInput.value || '').toLowerCase().trim();

                rows.forEach(tr => {
                const rowStatus = tr.dataset.status || '';
                const nameCell  = tr.children[1]?.textContent.toLowerCase() || ''; 
                const matchStatus = !status || rowStatus === status;
                const matchSearch = !q || nameCell.includes(q);
                tr.style.display = (matchStatus && matchSearch) ? '' : 'none';
                });
            }
            statusFilter.addEventListener('change', applyFilters);
            searchInput?.addEventListener('input', applyFilters);

            // ==================== Search functionality ===================== //
            document.getElementById('searchInput').addEventListener('input', function (e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#userTableBody tr');
                let hasMatch = false;

                rows.forEach(row => {
                    const userName = row.querySelector('td:nth-child(2) .text-sm.font-medium')
                        .textContent.toLowerCase();

                    if (userName.includes(searchTerm)) {
                        row.style.display = '';
                        hasMatch = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show empty state if no matches
                document.getElementById('empty-state').style.display = hasMatch ? 'none' : 'block';
            });
        });

        window.reports = @json($reports);
        // Export functions
        function exportToExcel() {
            // Show loading indicator
            document.getElementById('loading-state').style.display = 'block';

            setTimeout(() => {
                const tableData = [];
                // Add header row in Thai
                tableData.push([
                    'ลำดับ',
                    'รอบประเมิน',
                    'ชื่อ-สกุล',
                    'แผนก',
                    'กลุ่มงาน',
                    'ตำแหน่ง',
                    'คะแนนรวม',
                    'คะแนนด้านปริมาณ',
                    'คะแนนด้านคุณภาพ',
                    'ข้อเสนอแนะ',
                    'ชื่อผู้ประเมิน',
                    'สร้างเมื่อ',
                    'แก้ไขเมื่อ',
                ]);

                (window.reports || []).forEach((report, index) => {
                    tableData.push([
                        index + 1,
                        `${report.start_time || ''} ถึง ${report.end_time || ''}`,
                        report.evaluatee_name || '',
                        report.evaluatee_department_name || '',
                        report.evaluatee_personnel_type || '',
                        report.evaluatee_position_name || '',
                        report.score ?? '',
                        report.quantity_score ?? '',
                        report.quality_score ?? '',
                        report.comment ?? '',
                        report.evaluator_name || '',
                        report.created_at || '',
                        report.updated_at || '',
                    ]);
                });

                // Create XLSX and export
                const ws = XLSX.utils.aoa_to_sheet(tableData);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "Evaluation Results");
                XLSX.writeFile(wb, "evaluation_results.xlsx");

                // Hide loading indicator
                document.getElementById('loading-state').style.display = 'none';
            }, 1000);
        }

        function openReportDetails(reportId) {
            window.open(`/dashboard/${reportId}`, '_blank');
        }

        // ================== Auto-submit filter form with debounce ==================== //
        document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('filterForm');
        if (!form) return;

        const start = form.querySelector('input[name="start_time"]');
        const end   = form.querySelector('input[name="end_time"]');
        const dept  = form.querySelector('select[name="department_name"]');

        let debounceId;

        const submitIfValid = () => {
        //กัน submit ถ้าวันที่ยังไม่ valid
        const s = start?.value ? new Date(start.value) : null;
        const e = end?.value ? new Date(end.value) : null;
        if (s && e && s > e) {
            const tmp = start.value;
            start.value = end.value;
            end.value = tmp;
        }
        form.requestSubmit();
        };

        const debounceSubmit = () => {
        clearTimeout(debounceId);
        debounceId = setTimeout(submitIfValid, 500); 
        };

        //date ใช้ทั้ง input (ระหว่างลาก/เลือก) และ change (เมื่อเลือกเสร็จ)
        if (start) {
        start.addEventListener('input', debounceSubmit);
        start.addEventListener('change', submitIfValid);
        }
        if (end) {
        end.addEventListener('input', debounceSubmit);
        end.addEventListener('change', submitIfValid);
        }

        //department
        if (dept) {
        dept.addEventListener('change', submitIfValid);
        }

        //reset function
        window.resetFilters = function() {
        if (start) start.value = '';
        if (end) end.value = '';
        if (dept) dept.value = '';
        form.requestSubmit();
        };
    });
    </script>
@endpush