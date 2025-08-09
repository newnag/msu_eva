{{--
|--------------------------------------------------------------------------
| show-profile.blade.php
|--------------------------------------------------------------------------
| This file is part of the User Profile component.
|
| Component 
| - resources/views/components/user-info-item.blade.php : แสดงข้อมูลผู้ใช้
| 
--}}

@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow mt-3">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-user text-blue-600"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800">ข้อมูลส่วนตัว</h2>
        </div>
        <!-- Divider -->
        <div class="h-px bg-gray-300 my-4"></div>
        <!-- Profile Name and Email -->
        <div class="ml-2">
            <div class="mb-8">
                <h3 class="text-2xl font-semibold my-2">{{ $user->name }}</h3>
                <p class="inline-block text-base font-normal text-gray-700 bg-gray-100 px-2 rounded m-0">
                    <span class="text-base font-semibold mr-1">อีเมล:</span> {{ $user->email ?? '-' }}
                </p>
                {{-- @if($user->email_verified_at)
                <p class="text-sm text-green-600">Verified at: {{ $user->email_verified_at->format('d M Y, H:i') }}</p>
                @else
                <p class="text-sm text-red-600">Email not verified</p>
                @endif --}}
            </div>

            <!-- User Info -->
            <div class="grid grid-cols-2 gap-6">
                <x-user-info-item label="รหัสพนักงาน:" :value="$user->employee_id" />

                <x-user-info-item label="เบอร์โทร:" :value="$user->phone ? preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1-$2-$3', $user->phone) : '-'" />

                <x-user-info-item label="ประเภทบุคลากร:" :value="$user->personnel_type ?? '-'" />

                <x-user-info-item label="ตำแหน่ง:" :value="$user->position->name ?? '-'" />

                <x-user-info-item label="สาขาวิชา:" :value="$user->department->department_name ?? '-'" />

                <x-user-info-item label="บทบาท:" :value="$user->roles->pluck('name')->join(', ') ?: '-'" />

                <div class="col-span-2 space-y-1">
                    <label class="text-black text-base font-semibold">ประวัติการศึกษา:</label>
                    <p class="text-gray-600 text-base font-normal whitespace-pre-line  ">{{ $user->bio ?? 'ไม่มีข้อมูล' }}
                    </p>
                </div>
            </div>
        </div>
        <!-- Divider -->
        <div class="h-px bg-gray-300 my-4 "></div>

        <!-- Edit Button -->
        <div class="flex justify-start">
            <x-button type="warning" text="แก้ไขข้อมูล" icon="fas fa-edit" href="{{ route('profile.edit') }}" />
        </div>
    </div>
@endsection