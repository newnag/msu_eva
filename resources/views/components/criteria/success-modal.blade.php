@props([
    'id' => 'success_modal',
    'title' => 'ส่งข้อมูลสำเร็จ',
    'redirectRoute' => route('criteria_config.index'),
])
<x-ui.modal :id="$id" :title="$title"
    :icon="[
        'bg' => 'bg-green-100',
        'svg' => '<svg xmlns=`http://www.w3.org/2000/svg` class=`h-10 w-10 text-green-600` fill=`none` viewBox=`0 0 24 24` stroke=`currentColor`><path stroke-linecap=`round` stroke-linejoin=`round` stroke-width=`2` d=`M5 13l4 4L19 7` /></svg>'
    ]">
    <p class="text-gray-600 mb-6">ข้อมูลเกณฑ์การประเมินถูกบันทึกเรียบร้อยแล้ว</p>
    <p class="text-gray-500 text-sm mb-6">กำลังเปลี่ยนเส้นทางใน <span id="countdown">5</span> วินาที...</p>

    <x-slot:footer>
        <a href="{{ $redirectRoute }}"
           class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">ไปหน้ารายการเกณฑ์</a>
    </x-slot:footer>
</x-ui.modal>
