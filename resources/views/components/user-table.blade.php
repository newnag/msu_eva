@props(['index', 'employee'])

@php
    $roleClass = match ($employee['role']) {
        'admin' => 'bg-sky-100 text-sky-700 ring-1 ring-sky-200',
        'ผู้บริหาร' => 'bg-purple-100 text-purple-700 ring-1 ring-purple-200',
        'ผู้ประเมิน' => 'bg-blue-100 text-blue-700 ring-1 ring-blue-200',
        'ผู้รับการประเมิน' => 'bg-violet-100 text-violet-700 ring-1 ring-violet-200',
        default => 'bg-gray-10 text-gray-700 ring-1 ring-gray-200',
    };

    $typeClass = match ($employee['type']) {
        'สนับสนุน' => 'bg-orange-100 text-orange-700 ring-1 ring-orange-200',
        'วิชาการ' => 'bg-yellow-100 text-yellow-700 ring-1 ring-yellow-200',
        default => '',
    };
@endphp

<tr class="border-b">
    <td class="py-2  px-6 text-center text-base md:text-lg font-normal">{{ $index }}</td>
    <td class="py-2  px-6 text-left text-base md:text-lg font-normal">
        {{ $employee['name'] }}
    </td>
    <td class="py-2 px-6 text-left">{{ $employee['code'] }}</td>
    <td class="py-2  px-6 text-left">{{ $employee['position'] }}</td>
    <td class="py-2  px-6 text-left">
        <span class="{{ $typeClass }} px-3 py-1 rounded-full text-sm">
            {{ $employee['type'] }}
        </span>
    </td>
    <td class="py-2  px-6 text-left">
        <span class="{{ $roleClass }} px-3 py-1 rounded-full text-sm">
            {{ $employee['role'] }}
        </span>
    </td>

    <td class="py-2  px-6 text-left">{{ $employee['contact'] }}</td>
    <td class="py-2  px-6 text-left">
        <div class="flex items-center justify-start gap-3">
            <!-- ปุ่มแก้ไข -->
            <x-button type="outline-primary" text="แก้ไข" class="text-sm" icon="fas fa-edit"
                onclick='openEditModal({ 
                    id: {{ $employee["id"] }},
                    prefix: "{{ $employee["prefix"] ?? "" }}",
                    name: "{{ $employee["name"] }}",
                    employee_id: "{{ $employee["code"] }}",
                    email: "{{ $employee["email"] ?? "" }}",
                    phone: "{{ $employee["contact"] }}",
                    personnel_type: "{{ $employee["type"] }}",
                    bio: "{{ $employee["bio"] ?? "" }}",
                    status: "{{ $employee["status"] ?? "active" }}",
                    position_id: {{ $employee["position_id"] ?? "null" }},
                    department_id: {{ $employee["department_id"] ?? "null" }},
                    roles: [{ name: {!! json_encode($employee["role"] ?? "") !!} }]
                })'
            />
            <!-- ปุ่มลบ -->
            <x-button type="outline-danger" text="ลบ" icon="fas fa-trash-alt"
                onclick="confirmDelete({{ $employee['id'] }})" />
        </div>
    </td>
</tr>


<x-delete-warning-modal text="เจ้าหน้าที่" formAction="{{ route('users.destroy', ':id') }}" entityUrl="/users" />