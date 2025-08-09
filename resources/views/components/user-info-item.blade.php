{{--
|--------------------------------------------------------------------------
| show-profile.blade.php
|--------------------------------------------------------------------------
| Component สำหรับแสดงข้อมูลผู้ใช้หน้าตั้งค่าโปรไฟล์แบบ label และ value
| label จะแสดงเป็นหัวข้อ และ value จะแสดงข้อมูลด้านล่าง
|
| ใช้ร่วมกับ:
| -  resources/views/user/profile/show-profile.blade.php
|--------------------------------------------------------------------------
--}}

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    <label class="text-black text-base font-semibold">{{ $label }}</label>
    <p class="text-gray-600 text-base font-normal {!! $valueClass ?? '' !!}">
        {!! $value !!}
    </p>
</div>