{{--
|--------------------------------------------------------------------------
| nav-link.blade.php
|--------------------------------------------------------------------------
|
| สำหรับสร้าง Navigation Link ภายใน Navbar
| ใช้ตรวจสอบ Aactive state ของเมนูทั้งแบบระบุ route เดี่ยว
| หรือหลาย route ในรูปแบบ array เพื่อแสดงตำแหน่งปัจจุบันของเมนูที่กำลังใช้งานอยู่
|
| Props:
| - route : string|array → route name หรือ pattern ('dashboard', 'users.*')
 ใช้ตรวจสอบว่าหน้านั้นตรงกับ URL ปัจจุบันหรือไม่
| - href  : string       → URL ปลายทางของลิงก์
|
| Features:
| - รองรับการส่ง route ได้หลายค่าในรูปแบบ array
| - ตรวจสอบเส้นทางด้วยทั้ง request()->is() และ Route::is()
| - เพิ่มคลาส 'active' ให้ลิงก์ที่ตรงกับหน้า/route ปัจจุบัน
| - ใช้ {{ $slot }} เพื่อรองรับการส่งข้อความหรือตัวอักษรเมนูจากภายนอก
|
| ใช้ร่วมกับ:
| - app.blade.php 
| 
---------------------}}

@props(['route', 'href'])

@php
    $isActive = '';

    if (is_array($route)) {
        foreach ($route as $r) {
            if (request()->is($r) || Route::is($r)) {
                $isActive = 'active';
                break;
            }
        }
    } else {
        if (request()->is($route) || Route::is($route)) {
            $isActive = 'active';
        }
    }
@endphp

<li class="nav-item">
    <a class="nav-link text-white {{ $isActive }}" href="{{ $href }}">
        {{ $slot }}
    </a>
</li>
