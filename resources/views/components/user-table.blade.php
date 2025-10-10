@props(['index', 'employee'])

@php
    // Base style สำหรับ badge
    $baseBadgeClass = 'px-2 py-1 rounded-full text-sm font-normal ring-1';

    $roleColors = [
        'admin' => 'bg-sky-100 text-sky-700 ring-sky-200',
        'ผู้บริหาร' => 'bg-purple-100 text-purple-700 ring-purple-200',
        'ผู้ประเมิน' => 'bg-blue-100 text-blue-700 ring-blue-200',
        'ผู้รับการประเมิน' => 'bg-violet-100 text-violet-700 ring-violet-200',
        'default' => 'bg-gray-100 text-gray-700 ring-gray-200',
    ];

    $typeColors = [
        'สนับสนุน' => 'bg-orange-100 text-orange-700 ring-orange-200',
        'วิชาการ' => 'bg-yellow-100 text-yellow-700 ring-yellow-200',
        'default' => 'bg-gray-100 text-gray-700 ring-gray-200',
    ];

    $roleClass = $baseBadgeClass . ' ' . ($roleColors[$employee['role']] ?? $roleColors['default']);
    $typeClass = $baseBadgeClass . ' ' . ($typeColors[$employee['type']] ?? $typeColors['default']);

    $editPayload = [
        'id' => $employee['id'],
        'prefix' => $employee['prefix'] ?? '',
        'name' => $employee['name'],
        'employee_id' => $employee['code'],
        'email' => $employee['email'] ?? '',
        'phone' => $employee['contact'],
        'personnel_type' => $employee['type'],
        'bio' => $employee['bio'] ?? '',
        'status' => $employee['status'] ?? 'active',
        'position_id' => $employee['position_id'] ?? null,
        'department_id' => $employee['department_id'] ?? null,
        'roles' => [['name' => $employee['role'] ?? '']],
    ];
@endphp

<tr class="border-b
           [&>td]:py-2 [&>td]:px-6 [&>td]:text-left
           [&>td]:text-black [&>td]:text-base [&>td]:font-normal">

    <td class="text-center md:text-lg">{{ $index }}</td>

    <td class="md:text-lg">
        {{ $employee['name'] }}
    </td>

    <td>{{ $employee['code'] }}</td>

    <td>{{ $employee['position'] }}</td>

    <td>
        <span class="{{ $typeClass }}">
            {{ $employee['type'] }}
        </span>
    </td>

    <td>
        <span class="{{ $roleClass }}">
            {{ $employee['role'] }}
        </span>
    </td>

    <td>{{ $employee['contact'] }}</td>

    <td>
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