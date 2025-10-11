{{--
|--------------------------------------------------------------------------
| UI Component: Label
|--------------------------------------------------------------------------
| ใช้สำหรับแสดงชื่อฟิลด์ (Label) ของ input ต่าง ๆ ในฟอร์ม
| รองรับการกำหนดค่า "for", "required", และการเพิ่ม class เพิ่มเติม
|
| Usage Examples:
| 1. ปกติ:
|    <x-ui.label for="username">ชื่อผู้ใช้</x-ui.label>
|
| 2. กำหนดให้เป็นฟิลด์บังคับ:
|    <x-ui.label for="email" required>อีเมล</x-ui.label>
|
| 3. เพิ่มคลาสเพิ่มเติม:
|    <x-ui.label for="password" class="text-blue-700">รหัสผ่าน</x-ui.label>
|
| Props:
|  - for       : (optional) ผูก label กับ input โดยใช้ค่า id
|  - required  : (boolean, default: false) แสดงเครื่องหมาย * สีแดง
|  - class     : (optional) เพิ่ม class Tailwind เพิ่มเติม
|
--}}

@props([
    'for' => null,
    'required' => false,
    'class' => '',
])

<label
    @if($for) for="{{ $for }}" @endif
    {{ $attributes->merge(['class' => "block text-lg font-medium text-gray-900 mb-2 $class"]) }}
>
    {{ $slot }}
    @if($required)
        <span class="text-red-500 text-base">*</span>
    @endif
</label>