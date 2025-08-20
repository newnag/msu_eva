@props(['paginator'])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4 py-3 border-t border-gray-200">
    {{-- ตัวเลือกจำนวนรายการต่อหน้า (แสดงตลอด) --}}
    <div class="flex items-center text-md text-gray-700">
        <span>แสดง</span>
        <form method="GET" class="mx-2">
            {{-- คงพารามิเตอร์อื่นๆที่มีอยู่ยกเว้น per_page/page --}}
            @foreach(request()->except(['per_page', 'page']) as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach

            <select name="per_page" onchange="this.form.submit()"
                class="border border-gray-300 rounded-md px-2 py-1 text-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500">
                <option value="5"  {{ request('per_page', 5) == 5  ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
        </form>
        <span>รายการต่อหน้า</span>
    </div>

    {{-- ข้อมูลหน้าปัจจุบัน + ปุ่มเปลี่ยนหน้า (แสดงถ้ามีหลายหน้า) --}}
    @if ($paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $paginator->hasPages())
        <div class="flex items-center gap-4">
            <span class="text-md text-gray-700">
                หน้า {{ $paginator->currentPage() }} จาก {{ $paginator->lastPage() }}
            </span>
            <div>
                {{ $paginator->appends(request()->except('page'))->links() }}
            </div>
        </div>
    @endif
</div>

