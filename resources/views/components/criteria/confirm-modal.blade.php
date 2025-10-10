@props([
    'id' => 'confirm_modal',
    'title' => 'ยืนยันการบันทึกข้อมูล',
])
<x-ui.modal :id="$id" :title="$title"
    :icon="[
        'bg' => 'bg-blue-100',
        'svg' => '<svg xmlns=`http://www.w3.org/2000/svg` class=`h-10 w-10 text-blue-600` fill=`none` viewBox=`0 0 24 24` stroke=`currentColor`><path stroke-linecap=`round` stroke-linejoin=`round` stroke-width=`2` d=`M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z` /></svg>'
    ]">
    <p class="text-gray-600 mb-3">ปีผู้ประเมิน: <span id="version_name_display" class="font-medium"></span></p>
    <p class="text-gray-600 mb-6">คุณต้องการบันทึกข้อมูลเกณฑ์การประเมินนี้หรือไม่?</p>

    <x-slot:footer>
        <button id="cancel_modal_btn"
            class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">ยกเลิก</button>
        <button id="confirm_submit_btn"
            class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">ยืนยัน</button>
    </x-slot:footer>
</x-ui.modal>
