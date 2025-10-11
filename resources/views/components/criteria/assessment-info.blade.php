{{--
|--------------------------------------------------------------------------
| ข้อมูลเกณฑ์การประเมิน
|--------------------------------------------------------------------------
--}}

@props([
'versionName' => '',
'reportTitle' => '',
'reportDescription' => '',
'assessmentType' => '',
'comment' => '',
])

<div {{ $attributes->merge(['class' => 'report_datas_block']) }}>
  <h2 class="flex items-center mb-6 text-2xl text-black font-bold">
    <span class="mr-3 font-bold text-2xl text-white rounded-full w-8 h-8 bg-blue-600 flex items-center justify-center ">1</span>
    ข้อมูลเกณฑ์การประเมิน
  </h2>

  <div class="space-y-6">

    {{-- 1) ชื่อเวอร์ชันเกณฑ์ --}}
    <div>
      <x-ui.label for="version_name" required>ชื่อเวอร์ชันเกณฑ์การประเมิน</x-ui.label>
      <input id="version_name" name="version_name" required
             class="version_name w-full px-3 py-2 text-base"
             placeholder="เช่น รอบประเมิน 1/2568"
             value="{{ old('version_name', $versionName) }}">
      <input type="hidden" id="auth-user-id" value="{{ Auth::user()->id }}">
    </div>

    {{-- 2) ประเภทการประเมิน --}}
    <div>
      <x-ui.label for="assessment_type" required>ประเภทการประเมิน</x-ui.label>
      <div class="relative">
        <select id="assessment_type" name="assessment_type" required
                class="assessment_type w-full text-base pr-10 appearance-none px-3 py-2">
          <option value="" disabled selected hidden>-- เลือกประเภทการประเมิน --</option>
          <option value="กลุ่มวิชาการ" {{ old('assessment_type', $assessmentType) === 'กลุ่มวิชาการ' ? 'selected' : '' }}>กลุ่มวิชาการ</option>
          <option value="กลุ่มสนับสนุน" {{ old('assessment_type', $assessmentType) === 'กลุ่มสนับสนุน' ? 'selected' : '' }}>กลุ่มสนับสนุน</option>
        </select>

        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
          <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </span>
      </div>
    </div>

    {{-- 3) ชื่อเกณฑ์ --}}
    <div>
      <x-ui.label for="report_title" required>ชื่อเกณฑ์</x-ui.label>
      <input id="report_title" name="report_title" required
             class="report_title w-full text-base px-3 py-2"
             placeholder="เช่น เกณฑ์ประเมินสายสนับสนุน (งานธุรการ)"
             value="{{ old('report_title', $reportTitle) }}">
    </div>

    {{-- 4) รายละเอียดเกณฑ์ --}}
    <div>
      <x-ui.label for="report_description" required>รายละเอียดเกณฑ์</x-ui.label>
      <textarea id="report_description" name="report_description" rows="4" required
                class="report_description w-full text-base px-3 py-2"
                placeholder="รายละเอียดเพิ่มเติมของเกณฑ์">{{ old('report_description', $reportDescription) }}</textarea>
    </div>

    {{-- 5) หมายเหตุ --}}
    <div>
      <x-ui.label for="comment">หมายเหตุ (ถ้ามี)</x-ui.label>
      <input id="comment" name="comment"
             class="comment w-full text-base px-3 py-2"
             placeholder="หมายเหตุ (ถ้ามี)"
             value="{{ old('comment', $comment) }}">
    </div>

  </div>
</div>


{{-- =========================================================
     Auto focus next field on Enter key
   ========================================================= 
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Collect all visible and enabled form fields
  const fields = Array.from(
    document.querySelectorAll('input, select, textarea')
  ).filter(el => el.type !== 'hidden' && !el.disabled);

  // Add keyboard listener for each fieldผ
  fields.forEach((field, index) => {
    field.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        const next = fields[index + 1];
        if (next) next.focus();
      }
    });
  });
});
</script> --}}
