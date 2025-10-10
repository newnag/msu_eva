@extends('layouts.app')

@php
    $personnelTypes = [
        'สนับสนุน' => 'สนับสนุน',
        'วิชาการ' => 'วิชาการ',
    ];
@endphp

@section('content')
    <div class="d-flex flex-column flex-md-row justify-between items-start md:items-center mb-4 gap-3">
        <h2 class="text-xl md:text-2xl font-semibold text-gray-800">
            รายชื่อเจ้าหน้าที่ทั้งหมด ({{ $users->total() }} คน)
        </h2>

        <div class="d-flex gap-2 align-items-center flex-wrap">
            <x-button type="secondary" text="นำเข้ารายชื่อเจ้าหน้าที่" onclick="openImportModal(this)"
                data-action="{{ route('users.import') }}" icon="fas fa-file-import" />

            <x-button type="primary" text="เพิ่มเจ้าหน้าที่" onclick="openCreateModal(this)"
                data-action="{{ route('users.store') }}" icon="fas fa-user-plus" />
        </div>

        @include('user.management.user-form-modal')
        @include('user.management.import-user-modal')
    </div>

    <div class="flex flex-wrap gap-4 mb-4 justify-between">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex items-center gap-2">

                <x-button type="primary" text="รีเซ็ตตัวกรอง" href="{{ route('users.index') }}" />
            </div>

            <x-filter name="department_id" label="สาขา" :options="$departments->pluck('department_name', 'id')->toArray()" />

            <x-filter name="personnel_type" label="ประเภทบุคลากร" :options="$personnelTypes" />

            <x-filter name="position_id" label="ตำแหน่งงาน" :options="$positions->pluck('name', 'id')->toArray()" />
        </form>

        <x-search-bar />
    </div>

    <div class="mt-2 overflow-hidden rounded-md border border-gray-200 bg-white drop-shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm md:text-base">
                <thead class="bg-gray-50 text-black sticky top-0 z-10
                 [&_th]:py-3 [&_th]:px-6 [&_th]:text-left  [&_th]:text-base [&_th]:font-medium
                 [&_tr]:border-b [&_tr]:border-gray-200
                 [&_th:last-child]:md:text-center">
                    <tr>
                        <th>ลำดับ</th>
                        <th>ข้อมูลพนักงาน</th>
                        <th>รหัสพนักงาน</th>
                        <th>ตำแหน่งงาน</th>
                        <th>ประเภทบุคลากร</th>
                        <th>บทบาท</th>
                        <th>ติดต่อ</th>
                        <th>การดำเนินการ</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $index => $user)
                                <x-user-table :index="$users->firstItem() + $index" :employee="[
                            'id' => $user->id,
                            'prefix' => $user->prefix,
                            'name' => $user->name,
                            'code' => $user->employee_id,
                            'position' => optional($user->position)->name,
                            'type' => $user->personnel_type,
                            'contact' => $user->phone,
                            'email' => $user->email,
                            'bio' => $user->bio,
                            'status' => $user->status,
                            'position_id' => $user->position_id,
                            'department_id' => $user->department_id,
                            'role' => $user->getRoleNames()->first(),
                        ]" />
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-500">
                                <i class="fas fa-user text-3xl mb-2 block"></i>
                                <p>ไม่มีข้อมูลเจ้าหน้าที่</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mt-4 text-sm text-gray-600">
        <span>
            แสดง {{ $users->firstItem() }} - {{ $users->lastItem() }} จาก {{ $users->total() }} รายการ
        </span>
        <div class="flex gap-2 items-center">
            {{ $users->links() }}
        </div>
    </div>
@endsection