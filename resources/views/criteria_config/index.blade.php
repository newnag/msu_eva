@extends('layouts.app')

@section('content')
    <div class="py-3">
        <div class="mx-auto sm:px-6 lg:px-8">
            <!-- Header + Toolbar -->
            <div class="flex justify-between items-center mb-3">
                <x-ui.heading> จัดการเกณฑ์การประเมิน</x-ui.heading>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none">
                        <input id="search-input" type="text" placeholder="ค้นหาเกณฑ์การประเมิน"
                            class="w-full md:w-72 bg-white border border-gray-100 rounded-md px-10 py-2.5 text-gray-900 text-base placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>

                    <a href="{{ route('criteria_config.create') }}"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-lg px-4 py-2.5 rounded-md transition">
                        <i class="fas fa-plus"></i>
                        เพิ่มเกณฑ์
                    </a>
                </div>
            </div>

            <!-- ตาราง -->
            <x-criteria.criteria-table
                :index-route="route('report-structure.index')"
                edit-url-base="/criteria-config"
                delete-url-base="/report-version"
                :csrf-token="csrf_token()"
            />
        </div>
    </div>
@endsection
