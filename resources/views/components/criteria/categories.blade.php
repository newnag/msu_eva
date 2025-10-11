<div id="categories_container" class="space-y-8">
    <h2 class="flex items-center mb-6 text-2xl text-black font-bold">
        <span class="mr-3 font-bold text-2xl text-white rounded-full w-8 h-8 bg-blue-600 flex items-center justify-center ">2</span>
        หมวดหมู่การประเมิน
    </h2>

    <!-- Category Block -->
    <div class="category_block bg-white p-8 rounded-lg drop-shadow-md border-l-4 border-blue-600 hover:shadow-xl transition-shadow duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-xl text-gray-900">หมวดหมู่การประเมิน</h3>
            <div class="flex space-x-3">
                <button type="button" class="move_category_up_btn text-blue-600 hover:text-blue-800 disabled:text-gray-400 transition duration-200" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
                <button type="button" class="move_category_down_btn text-blue-600 hover:text-blue-800 disabled:text-gray-400 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <button type="button" class="delete_category_btn text-red-600 hover:text-red-800 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ลำดับ</label>
                <span class="category_sequence text-gray-700 font-medium text-lg">1</span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">หมวดหมู่หลัก <span class="text-red-500">*</span></label>
                <input required class="main_categories border border-gray-300 text-gray-900 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition duration-200" placeholder="ชื่อหมวดหมู่หลัก">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">หมวดหมู่ย่อย <span class="text-red-500">*</span></label>
                <input required class="sub_categories border border-gray-300 text-gray-900 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition duration-200" placeholder="ชื่อหมวดหมู่ย่อย">
            </div>
        </div>

        <div class="evaluation_lists_container space-y-6 mt-8">
            <h4 class="font-bold text-lg text-gray-900 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                รายการประเมิน
            </h4>

            <div class="evaluation_list_block bg-gray-100 p-6 rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-bold text-gray-900">รายการประเมิน</h5>
                    <div class="flex space-x-3">
                        <button type="button" class="move_eval_up_btn text-blue-600 hover:text-blue-800 disabled:text-gray-400 transition duration-200" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                        <button type="button" class="move_eval_down_btn text-blue-600 hover:text-blue-800 disabled:text-gray-400 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <button type="button" class="delete_eval_btn text-red-600 hover:text-red-800 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ลำดับ</label>
                        <span name="eval_sequence" class="eval_sequence text-gray-700 font-medium text-lg">1</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อรายการ <span class="text-red-500">*</span></label>
                        <input required name="eval_name" class="eval_name border border-gray-300 text-gray-900 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition duration-200" placeholder="ชื่อรายการประเมิน">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">คะแนนรวม <span class="text-red-500">*</span></label>
                        <input type="number" required name="sum_score" class="sum_score border border-gray-300 text-gray-900 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition duration-200" placeholder="คะแนนรวม">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">หมายเหตุ</label>
                        <input name="annotation" class="annotation border border-gray-300 text-gray-900 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition duration-200" placeholder="หมายเหตุ">
                    </div>
                </div>

                <!-- Criteria Type Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">ประเภทเกณฑ์</label>
                    <div class="criteria_type_check_group flex gap-6 text-gray-900">
                        <label class="flex items-center">
                            <input type="checkbox" class="criteria_type quantity_criteria_type form-checkbox h-5 w-5 text-green-600 rounded focus:ring-green-500" value="quantity">
                            <span class="ml-2 text-sm">เกณฑ์ด้านปริมาณ</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="criteria_type quality_criteria_type form-checkbox h-5 w-5 text-purple-600 rounded focus:ring-purple-500" value="quality">
                            <span class="ml-2 text-sm">เกณฑ์ด้านคุณภาพ</span>
                        </label>
                    </div>
                </div>

                <!-- Quantity Criteria Section -->
                <x-criteria.quantity-section :show="false" />

                <!-- Quality Criteria Section -->
                <x-criteria.quality-section :show="false" />
            </div>
        </div>

        <button type="button" class="add_evaluation_list_btn mt-6 text-sm px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition flex items-center">
            <x-icon.plus class="h-5 w-5 mr-2" />
            เพิ่มรายการประเมิน
        </button>
    </div>

    <!-- END Category Block -->
    <button type="button" id="add_category_btn" class="my-6 px-5 py-2.5 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition flex items-center">
        <x-icon.plus class="h-6 w-6 mr-2" />
        เพิ่มหมวดหมู่การประเมิน
    </button>
