{{--
|--------------------------------------------------------------------
| app.blade.php
|--------------------------------------------------------------------
| Components ที่ใช้:
| - nav-link.blade.php : สร้างลิงก์ใน Navbar 
|  (resources/views/components/nav-link.blade.php)
| 
| CSS
| - resources/css/app.css 
| - resources/css/app-custom.css 
| 
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ระบบประเมินบุคลากร</title>
    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/app.css', 'resources/css/app-custom.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Summernote Rich Text Editor -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-th-TH.min.js"></script>
    <style>
        /* Custom styling for Summernote */
        .note-editor {
            border-radius: 8px;
            border: 1px solid #d1d5db;
        }
        .note-editor.note-frame {
            border: 1px solid #d1d5db;
        }
        .note-editor.note-frame.note-focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgb(59 130 246 / 0.1);
        }
        .note-toolbar {
            background-color: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
</head>

<!--------------- Body ----------------->

<body class="bg-white font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-black shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center ">
                <h1 class="text-lg font-semibold text-white">
                    ระบบประเมินบุคลากร
                </h1>
                <nav class="d-none d-xl-block">
                    <ul class="nav nav-pills align-items-center gap-2">
                        
                        @if(auth()->user() && auth()->user()->hasRole('ผู้บริหาร') || auth()->user() && auth()->user()->hasRole('ผู้ดูแลระบบ'))
                            <li class="nav-item">
                                <a class="nav-link text-white " href="/dashboard">แดชบอร์ด</a>
                            </li>
                        @endif

                        @if(auth()->user() && auth()->user()->hasRole('ผู้ประเมิน'))
                            <li class="nav-item">
                                <a class="nav-link text-white" href="/evaluator-dashboard">หน้าตรวจประเมิน</a>
                            </li>
                        @endif

                        <!-- ผู้รับการประเมิน -->
                        @if(auth()->user() && auth()->user()->hasRole('ผู้รับการประเมิน'))
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('evaluatee-dashboard') ? 'active' : '' }}"
                                href="/evaluatee-dashboard">หน้าการประเมิน</a>
                        </li>
                        @endif

                        <!-- แอดมิน -->

                        @if(auth()->user() && auth()->user()->hasRole('ผู้ดูแลระบบ'))
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('users.index') }}">จัดการสมาชิก</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="settingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                จัดการเกณฑ์
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="settingDropdown">
                                <li><a class="dropdown-item" href="/criteria-config">จัดการโครงสร้างเกณฑ์</a></li>
                                <li><a class="dropdown-item" href="{{ route('assignment-data.index') }}">จัดการรอบการประเมิน</a></li>
                                <li><a class="dropdown-item" href="{{ route('quality-scores.index') }}">คะแนนคุณภาพ</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            @php
                            $settingActive = Route::is('settings.index') || Route::is('departments.index') ||
                            Route::is('positions.index');
                            @endphp
                            <a class="nav-link dropdown-toggle text-white {{ $settingActive ? 'active' : '' }}" href="#"
                                id="settingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                ตั้งค่า
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="settingDropdown">
                                <li><a class="dropdown-item {{ Route::is('settings.index') ? 'fw-bold' : '' }}"
                                        href="{{ route('settings.index') }}">ตั้งค่าเว็บไซต์</a></li>
                                <li><a class="dropdown-item {{ Route::is('departments.index') ? 'fw-bold' : '' }}"
                                        href="{{ route('departments.index') }}">ตั้งค่าหน่วยงาน/แผนก</a></li>
                                <li><a class="dropdown-item {{ Route::is('positions.index') ? 'fw-bold' : '' }}"
                                        href="{{ route('positions.index') }}">ตั้งค่าตำแหน่งงาน</a></li>
                            </ul>
                        </li>
                        @endif

                        @if(auth()->user())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="/settings/profile">ตั้งค่าโปรไฟล์</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endif

                    </ul>
                </nav>

                <button class="mobile-menu-btn d-block d-xl-none" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </header>

        <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="closeMobileMenu()"></div>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu-header">
                <span class="brand" style="color: #495057;">เมนู</span>
                <button class="mobile-menu-close" onclick="closeMobileMenu()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mobile-menu-content">
                <!-- Role-based Navigation Links -->
                @if(auth()->user() && auth()->user()->hasRole('ผู้บริหาร'))
                    <a href="/dashboard" class="mobile-nav-item">
                        <i class="fas fa-home" style="width: 20px; margin-right: 10px;"></i>
                        แดชบอร์ด
                    </a>
                @endif
                
                @if(auth()->user() && auth()->user()->hasRole('ผู้ประเมิน'))
                    <a href="/evaluator-dashboard" class="mobile-nav-item">
                        <i class="fas fa-clipboard-list" style="width: 20px; margin-right: 10px;"></i>
                        หน้าการประเมิน
                    </a>
                @endif
                
                @if(auth()->user() && auth()->user()->hasRole('ผู้รับการประเมิน'))
                    <a href="/evaluatee-dashboard" class="mobile-nav-item">
                        <i class="fas fa-user-check" style="width: 20px; margin-right: 10px;"></i>
                        หน้าการประเมิน
                    </a>
                @endif
                
                @if(auth()->user() && auth()->user()->hasRole('ผู้ดูแลระบบ'))
                    <a href="/dashboard" class="mobile-nav-item">
                        <i class="fas fa-home" style="width: 20px; margin-right: 10px;"></i>
                        หน้าแรก
                    </a>
                    <a href="{{ route('users.index') }}" class="mobile-nav-item">
                        <i class="fas fa-users" style="width: 20px; margin-right: 10px;"></i>
                        จัดการสมาชิก
                    </a>
                    <a href="/criteria-config" class="mobile-nav-item">
                        <i class="fas fa-cogs" style="width: 20px; margin-right: 10px;"></i>
                        จัดการโครงสร้างเกณฑ์
                    </a>
                    <a href="{{ route('assignment-data.index') }}" class="mobile-nav-item">
                        <i class="fas fa-tasks" style="width: 20px; margin-right: 10px;"></i>
                        จัดการรอบการประเมิน
                    </a>
                    <a href="{{ route('quality-scores.index') }}" class="mobile-nav-item">
                        <i class="fas fa-star" style="width: 20px; margin-right: 10px;"></i>
                        คะแนนคุณภาพ
                    </a>
                    
                    <!-- Settings Dropdown for Mobile -->
                    <div class="mobile-dropdown" id="settingsDropdown">
                        <button class="mobile-dropdown-toggle" onclick="toggleMobileDropdown('settingsDropdown')">
                            <span>
                                <i class="fas fa-cog" style="width: 20px; margin-right: 10px;"></i>
                                ตั้งค่า
                            </span>
                            <i class="fas fa-chevron-down transition-transform duration-300"></i>
                        </button>
                        <div class="mobile-dropdown-content">
                            <a href="{{ route('settings.index') }}" class="mobile-dropdown-item">ตั้งค่าเว็บไซต์</a>
                            <a href="{{ route('departments.index') }}" class="mobile-dropdown-item">ตั้งค่าหน่วยงาน/แผนก</a>
                            <a href="{{ route('positions.index') }}" class="mobile-dropdown-item">ตั้งค่าตำแหน่งงาน</a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- User Section -->
            @if(auth()->user())
            <div class="mobile-user-section">
                <div class="mobile-user-info">
                    <i class="fa fa-user"></i>
                    <span>{{ auth()->user()->name }}</span>
                </div>
                <a href="/settings/profile" class="mobile-nav-item" style="padding: 10px 0; border: none;">
                    <i class="fas fa-user-edit" style="width: 20px; margin-right: 10px;"></i>
                    ตั้งค่าโปรไฟล์
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="mobile-nav-item" style="padding: 10px 0; border: none; color: #dc3545; width: 100%; text-align: left;">
                        <i class="fas fa-sign-out-alt" style="width: 20px; margin-right: 10px;"></i>
                        Logout
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    {{------------ Scripts Section ------------}}
    @stack('scripts')

    <!-- JavaScript Functions - Always Available -->
    <script>
        function toggleDropdown(button) {
            const dropdown = button.parentElement;
            const isActive = dropdown.classList.contains('active');
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown.active').forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('active');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('active', !isActive);
        }

        // Toggle mobile menu
        function toggleMobileMenu() {
            const overlay = document.getElementById('mobileMenuOverlay');
            const menu = document.getElementById('mobileMenu');
            
            overlay.classList.add('active');
            menu.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close mobile menu
        function closeMobileMenu() {
            const overlay = document.getElementById('mobileMenuOverlay');
            const menu = document.getElementById('mobileMenu');
            
            overlay.classList.remove('active');
            menu.classList.remove('active');
            document.body.style.overflow = '';
            
            // Close all mobile dropdowns
            document.querySelectorAll('.mobile-dropdown.active').forEach(d => {
                d.classList.remove('active');
            });
        }

        // Toggle mobile dropdown
        function toggleMobileDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const icon = dropdown.querySelector('.fa-chevron-down');
            
            dropdown.classList.toggle('active');
            
            // Rotate icon
            if (dropdown.classList.contains('active')) {
                icon.style.transform = 'rotate(180deg)';
            } else {
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Simplified navbar behavior - remove auto-hide for professional look
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (navbar && window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            } else if (navbar) {
                navbar.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.08)';
            }
        });

        // Add loading animation
        window.addEventListener('load', function() {
            document.body.style.opacity = '1';
        });

        // Active dropdown highlight
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownItems = document.querySelectorAll('.dropdown-item-custom');
            dropdownItems.forEach(item => {
                if (item.classList.contains('fw-bold')) {
                    const dropdown = item.closest('.dropdown');
                    if (dropdown) {
                        const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
                        if (dropdownToggle) {
                            dropdownToggle.classList.add('active');
                        }
                    }
                }
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.dropdown-menu.show');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target) && !dropdown.previousElementSibling.contains(event.target)) {
                    const bsDropdown = new bootstrap.Dropdown(dropdown.previousElementSibling);
                    bsDropdown.hide();
                }
            });
        });
    </script>

    @if (session('success'))
        <script>
            // Show success message if needed
            // alert('{{ session('success') }}');
            console.log('Success: {{ session('success') }}');
        </script>
    @endif
</body>

</html>