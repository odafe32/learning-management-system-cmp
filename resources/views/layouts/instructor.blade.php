@props(['metaTitle' => config('app.name', 'Laravel'), 'metaDesc' => 'Default LMS description', 'metaImage' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="author" content="LMS Platform">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $metaTitle }}" />
    <meta property="og:description" content="{{ $metaDesc }}" />
    <meta property="og:image" content="{{ $metaImage }}" />

    <!-- Twitter Meta Tags -->
    <meta property="twitter:title" content="{{ $metaTitle }}" />
    <meta property="twitter:description" content="{{ $metaDesc }}" />
    <meta property="twitter:image" content="{{ $metaImage }}" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ url('assets/images/logo/favicon.png') }}?v={{ env('CACHE_VERSION', 1) }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.min.css') }}?v={{ env('CACHE_VERSION', 1) }}">
    <link rel="stylesheet" href="{{ url('assets/css/file-upload.css') }}?v={{ env('CACHE_VERSION', 1) }}">
    <link rel="stylesheet" href="{{ url('assets/css/plyr.css') }}?v={{ env('CACHE_VERSION', 1) }}">
    <link rel="stylesheet" href="{{ url('assets/css/full-calendar.css') }}?v={{ env('CACHE_VERSION', 1) }}">
    <link rel="stylesheet" href="{{ url('assets/css/jquery-ui.css') }}?v={{ env('CACHE_VERSION', 1) }}">
    <link rel="stylesheet" href="{{ url('assets/css/editor-quill.css') }}?v={{ env('CACHE_VERSION', 1) }}">
    <link rel="stylesheet" href="{{ url('assets/css/apexcharts.css') }}?v={{ env('CACHE_VERSION', 1) }}">
    <link rel="stylesheet" href="{{ url('assets/css/calendar.css') }}?v={{ env('CACHE_VERSION', 1) }}">
    <link rel="stylesheet" href="{{ url('assets/css/jquery-jvectormap-2.0.5.css') }}?v={{ env('CACHE_VERSION', 1) }}">
    <link rel="stylesheet" href="{{ url('assets/css/main.css') }}?v={{ env('CACHE_VERSION', 1) }}">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>

.sidebar-menu__item.active > .sidebar-menu__link,
.sidebar-menu__link.active {
    background-color: var(--main-600, #6366f1) !important;
    color: white !important;
}

.sidebar-menu__item.active > .sidebar-menu__link .icon,
.sidebar-menu__link.active .icon {
    color: white !important;
}

/* Active states for dropdown parent when child is active */
.sidebar-menu__item.has-dropdown.active > .sidebar-menu__link {
    background-color: var(--main-100, #e0e7ff) !important;
    color: var(--main-600, #6366f1) !important;
}

.sidebar-menu__item.has-dropdown.active > .sidebar-menu__link .icon {
    color: var(--main-600, #6366f1) !important;
}

/* Open state for dropdowns */
.sidebar-menu__item.has-dropdown.open > .sidebar-submenu {
    display: block !important;
}

/* Active states for submenu items */
.sidebar-submenu__item.active > .sidebar-submenu__link,
.sidebar-submenu__link.active {
    background-color: var(--main-600, #6366f1) !important;
    color: white !important;
    margin-left: 10px;
    border-radius: 6px;
}

/* Hover states */
.sidebar-menu__link:hover:not(.active) {
    background-color: var(--main-50, #f8fafc);
    color: var(--main-600, #6366f1);
}

.sidebar-submenu__link:hover:not(.active) {
    background-color: var(--main-50, #f8fafc);
    color: var(--main-600, #6366f1);
    margin-left: 10px;
    border-radius: 6px;
}

 .alert {
        border: none;
        border-radius: 12px;
        padding: 20px;
        position: relative;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-left: 4px solid #10b981;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left: 4px solid #ef4444;
    }

    .alert .btn-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        opacity: 0.7;
        cursor: pointer;
        padding: 4px;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .alert .btn-close:hover {
        opacity: 1;
        background-color: rgba(0, 0, 0, 0.1);
        transform: scale(1.1);
    }

    .alert-success .btn-close:hover {
        background-color: rgba(16, 185, 129, 0.1);
    }

    .alert-danger .btn-close:hover {
        background-color: rgba(239, 68, 68, 0.1);
    }

    .text-success-emphasis {
        color: #065f46 !important;
    }

    .text-danger-emphasis {
        color: #7f1d1d !important;
    }

    /* Animation */
    .alert.fade {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .alert.fade:not(.show) {
        opacity: 0;
        transform: translateY(-10px);
    }

    .alert.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* Logout loading state */
    .logout-loading .spinner-border {
        width: 1rem;
        height: 1rem;
    }
    
    .logout-loading {
        opacity: 0.7;
        pointer-events: none;
    }
    </style>
</head>
<body>
<!--==================== Preloader Start ====================-->
  <div class="preloader">
    <div class="loader"></div>
  </div>
<!--==================== Preloader End ====================-->

<!--==================== Sidebar Overlay End ====================-->
<div class="side-overlay"></div>
<!--==================== Sidebar Overlay End ====================-->


<!-- ============================ Sidebar Start ============================ -->
<aside class="sidebar">
    <!-- sidebar close btn -->
     <button type="button" class="sidebar-close-btn text-gray-500 hover-text-white hover-bg-main-600 text-md w-24 h-24 border border-gray-100 hover-border-main-600 d-xl-none d-flex flex-center rounded-circle position-absolute"><i class="ph ph-x"></i></button>
    <!-- sidebar close btn -->
    
    <a href="{{ route('instructor.dashboard') }}" class="sidebar__logo text-center p-20 position-sticky inset-block-start-0 bg-white w-100 z-1 pb-10">
        <img src="{{ url('assets/images/logo/logo.png') }}" alt="Logo">
    </a>

    <div class="sidebar-menu-wrapper overflow-y-auto scroll-sm">
        <div class="p-20 pt-10">
            <ul class="sidebar-menu">
                <!-- Dashboard -->
                <li class="sidebar-menu__item {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('instructor.dashboard') }}" class="sidebar-menu__link {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-squares-four"></i></span>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                
                <!-- Courses -->
                <li class="sidebar-menu__item has-dropdown {{ request()->routeIs('instructor.courses.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)" class="sidebar-menu__link {{ request()->routeIs('instructor.courses.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-graduation-cap"></i></span>
                        <span class="text">Courses</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu" style="{{ request()->routeIs('instructor.courses.*') ? 'display: block;' : '' }}">
                        <li class="sidebar-submenu__item {{ request()->routeIs('instructor.courses.create') ? 'active' : '' }}">
                            <a href="{{ route('instructor.courses.create') }}" class="sidebar-submenu__link {{ request()->routeIs('instructor.courses.create') ? 'active' : '' }}">Create Courses</a>
                        </li>
                        <li class="sidebar-submenu__item {{ request()->routeIs('instructor.courses.manage') ? 'active' : '' }}">
                            <a href="{{ route('instructor.courses.manage') }}" class="sidebar-submenu__link {{ request()->routeIs('instructor.courses.manage') ? 'active' : '' }}">Manage Courses</a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>
                
                <!-- Lecture Materials -->
                <li class="sidebar-menu__item has-dropdown {{ request()->routeIs('instructor.materials.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)" class="sidebar-menu__link {{ request()->routeIs('instructor.materials.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-bookmarks"></i></span>
                        <span class="text">Lecture Materials</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu" style="{{ request()->routeIs('instructor.materials.*') ? 'display: block;' : '' }}">
                        <li class="sidebar-submenu__item {{ request()->routeIs('instructor.materials.upload') ? 'active' : '' }}">
                            <a href="{{ route('instructor.materials.upload') }}" class="sidebar-submenu__link {{ request()->routeIs('instructor.materials.upload') ? 'active' : '' }}">Upload Material</a>
                        </li>
                        <li class="sidebar-submenu__item {{ request()->routeIs('instructor.materials.index') ? 'active' : '' }}">
                            <a href="{{ route('instructor.materials.index') }}" class="sidebar-submenu__link {{ request()->routeIs('instructor.materials.index') ? 'active' : '' }}">View Materials</a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>
                
                <!-- Assignments -->
                <li class="sidebar-menu__item has-dropdown {{ request()->routeIs('instructor.assignments.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)" class="sidebar-menu__link {{ request()->routeIs('instructor.assignments.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-clipboard-text"></i></span>
                        <span class="text">Assignments</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu" style="{{ request()->routeIs('instructor.assignments.*') ? 'display: block;' : '' }}">
                        <li class="sidebar-submenu__item {{ request()->routeIs('instructor.assignments.create') ? 'active' : '' }}">
                            <a href="{{ route('instructor.assignments.create') }}" class="sidebar-submenu__link {{ request()->routeIs('instructor.assignments.create') ? 'active' : '' }}">Create Assignment</a>
                        </li>
                        <li class="sidebar-submenu__item {{ request()->routeIs('instructor.assignments.manage') ? 'active' : '' }}">
                            <a href="{{ route('instructor.assignments.manage') }}" class="sidebar-submenu__link {{ request()->routeIs('instructor.assignments.manage') ? 'active' : '' }}">Manage Assignments</a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>
                
                <!-- Submissions -->
                <li class="sidebar-menu__item has-dropdown {{ request()->routeIs('instructor.submissions.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)" class="sidebar-menu__link {{ request()->routeIs('instructor.submissions.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-upload"></i></span>
                        <span class="text">Submissions</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu" style="{{ request()->routeIs('instructor.submissions.*') ? 'display: block;' : '' }}">
                        <li class="sidebar-submenu__item {{ request()->routeIs('instructor.submissions.index') ? 'active' : '' }}">
                            <a href="{{ route('instructor.submissions.index') }}" class="sidebar-submenu__link {{ request()->routeIs('instructor.submissions.index') ? 'active' : '' }}">View Submissions</a>
                        </li>
                        <li class="sidebar-submenu__item {{ request()->routeIs('instructor.submissions.grade') ? 'active' : '' }}">
                            <a href="{{ route('instructor.submissions.grade') }}" class="sidebar-submenu__link {{ request()->routeIs('instructor.submissions.grade') ? 'active' : '' }}">Grade Assignments</a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>
                
                <!-- Students -->
                <li class="sidebar-menu__item has-dropdown {{ request()->routeIs('instructor.students.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)" class="sidebar-menu__link {{ request()->routeIs('instructor.students.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-users"></i></span>
                        <span class="text">Students</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu" style="{{ request()->routeIs('instructor.students.*') ? 'display: block;' : '' }}">
                        <li class="sidebar-submenu__item {{ request()->routeIs('instructor.students.index') ? 'active' : '' }}">
                            <a href="{{ route('instructor.students.index') }}" class="sidebar-submenu__link {{ request()->routeIs('instructor.students.index') ? 'active' : '' }}">View Enrolled Students</a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>
                
                <!-- Messages -->
                <li class="sidebar-menu__item {{ request()->routeIs('instructor.messages.*') ? 'active' : '' }}">
                    <a href="{{ route('instructor.messages.index') }}" class="sidebar-menu__link {{ request()->routeIs('instructor.messages.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-chats-teardrop"></i></span> 
                        <span class="text">Messages</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>    
<!-- ============================ Sidebar End  ============================ --> 

    <div class="dashboard-main-wrapper">
        <div class="top-navbar flex-between gap-16">

    <div class="flex-align gap-16">
        <!-- Toggle Button Start -->
         <button type="button" class="toggle-btn d-xl-none d-flex text-26 text-gray-500"><i class="ph ph-list"></i></button>
        <!-- Toggle Button End -->
        
        
    </div>

    <div class="flex-align gap-16">
        <div class="flex-align gap-8">
            <!-- Notification Start -->
            <div class="dropdown">
                <button class="dropdown-btn shaking-animation text-gray-500 w-40 h-40 bg-main-50 hover-bg-main-100 transition-2 rounded-circle text-xl flex-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="position-relative">
                        <i class="ph ph-bell"></i>
                        <span class="alarm-notify position-absolute end-0"></span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                    <div class="card border border-gray-100 rounded-12 box-shadow-custom p-0 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="py-8 px-24 bg-main-600">
                                <div class="flex-between">
                                    <h5 class="text-xl fw-semibold text-white mb-0">Notifications</h5>
                                    <div class="flex-align gap-12">
                                        <button type="button" class="bg-white rounded-6 text-sm px-8 py-2 hover-text-primary-600"> New </button>
                                        <button type="button" class="close-dropdown hover-scale-1 text-xl text-white"><i class="ph ph-x"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="p-24 max-h-270 overflow-y-auto scroll-sm">
                                <div class="d-flex align-items-start gap-12">
                                    <img src="{{ url('assets/images/thumbs/notification-img1.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover">
                                    <div class="border-bottom border-gray-100 mb-24 pb-24">
                                        <div class="flex-align gap-4">
                                            <a href="#" class="fw-medium text-15 mb-0 text-gray-300 hover-text-main-600 text-line-2">Ashwin Bose is requesting access to Design File - Final Project. </a>
                                            <!-- Three Dot Dropdown Start -->
                                            <div class="dropdown flex-shrink-0">
                                                <button class="text-gray-200 rounded-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ph-fill ph-dots-three-outline"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                                                    <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                                                        <div class="card-body p-12">
                                                            <div class="max-h-200 overflow-y-auto scroll-sm pe-8">
                                                                <ul>
                                                                    <li class="mb-0">
                                                                        <a href="#" class="py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 rounded-8 fw-normal text-xs d-block">
                                                                            <span class="text">Mark as read</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="mb-0">
                                                                        <a href="#" class="py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 rounded-8 fw-normal text-xs d-block">
                                                                            <span class="text">Delete Notification</span>
                                                                        </a>
                                                                    </li>
                                                                  
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Three Dot Dropdown End -->
                                        </div>
                                  
                                    </div>
                                </div>
                             
                            </div>
                            <a href="#" class="py-13 px-24 fw-bold text-center d-block text-primary-600 border-top border-gray-100 hover-text-decoration-underline"> View All </a>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Notification Start -->
            
          
        </div>


        <!-- User Profile Start -->
 <div class="dropdown">
    <button class="users arrow-down-icon border border-gray-200 rounded-pill p-4 d-inline-block pe-40 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="position-relative">
            <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : url('assets/images/thumbs/user-img.png') }}" 
                 alt="Profile Image" 
                 class="h-32 w-32 rounded-circle object-fit-cover">
            <span class="activation-badge w-8 h-8 position-absolute inset-block-end-0 inset-inline-end-0"></span>
        </span>
    </button>
    <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
        <div class="card border border-gray-100 rounded-12 box-shadow-custom">
            <div class="card-body">
                <div class="flex-align gap-8 mb-20 pb-20 border-bottom border-gray-100">
                    <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : url('assets/images/thumbs/user-img.png') }}" 
                         alt="Profile Image" 
                         class="w-54 h-54 rounded-circle object-fit-cover">
                    <div class="">
                        <h4 class="mb-0">{{ Auth::user()->name ?? 'User' }}</h4>
                        <p class="fw-medium text-13 text-gray-200">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                        @if(Auth::user()->role)
                            <span class="badge bg-primary-50 text-primary-600 text-xs px-8 py-4 rounded-4">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        @endif
                    </div>
                </div>
                <ul class="max-h-270 overflow-y-auto scroll-sm pe-4">
                    <li class="mb-4">
                        <a href="{{ route('instructor.profile') }}" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15 {{ request()->routeIs('instructor.profile') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <span class="text-2xl {{ request()->routeIs('instructor.profile') ? 'text-primary-600' : 'text-primary-600' }} d-flex">
                                <i class="ph ph-user-circle"></i>
                            </span>
                            <span class="text">Profile</span>
                        </a>
                    </li>
               
                    <li class="mb-4">
                        <a href="{{ route('instructor.messages.index') }}" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15 {{ request()->routeIs('instructor.messages.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <span class="text-2xl {{ request()->routeIs('instructor.messages.*') ? 'text-primary-600' : 'text-primary-600' }} d-flex">
                                <i class="ph ph-chats-teardrop"></i>
                            </span>
                            <span class="text">Inbox</span>
                        </a>
                    </li>
                
                    <li class="pt-8 border-top border-gray-100">
                        <a href="#" id="logoutBtn" class="py-12 text-15 px-20 hover-bg-danger-50 text-gray-300 hover-text-danger-600 rounded-8 flex-align gap-8 fw-medium text-15">
                            <span class="text-2xl text-danger-600 d-flex">
                                <i class="ph ph-sign-out logout-icon"></i>
                                <span class="spinner-border spinner-border-sm logout-spinner d-none" role="status" aria-hidden="true"></span>
                            </span>
                            <span class="text logout-text">Log Out</span>
                            <span class="text logout-loading-text d-none">Logging out...</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
        <!-- User Profile Start -->

    </div>
</div>

        
        <div class="dashboard-body">
            @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-24" role="alert" id="dashboardSuccessAlert">
        <div class="d-flex align-items-center">
            <i class="ph ph-check-circle text-success me-12 text-xl"></i>
            <div class="flex-grow-1">
                <h6 class="mb-4 text-success fw-semibold">Welcome!</h6>
                <p class="mb-0 text-success-emphasis">{{ session('success') }}</p>
            </div>
            <button type="button" class="btn-close ms-12" data-dismiss="alert" aria-label="Close">
                <i class="ph ph-x text-success"></i>
            </button>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-24" role="alert" id="dashboardErrorAlert">
        <div class="d-flex align-items-center">
            <i class="ph ph-warning-circle text-danger me-12 text-xl"></i>
            <div class="flex-grow-1">
                <h6 class="mb-4 text-danger fw-semibold">Error!</h6>
                <p class="mb-0 text-danger-emphasis">{{ session('error') }}</p>
            </div>
            <button type="button" class="btn-close ms-12" data-dismiss="alert" aria-label="Close">
                <i class="ph ph-x text-danger"></i>
            </button>
        </div>
    </div>
@endif

     
            <div >
                {{ $slot }}
            </div>
           
        </div>
        <div class="dashboard-footer">
    <div class="flex-between flex-wrap gap-16">
        <p class="text-gray-300 text-13 fw-normal"> {{ date('Y') }} © Kagayaki, All Right Reserved</p>
        
    </div>
</div>
    </div>


    <script>
   document.addEventListener('DOMContentLoaded', function() {
        // Handle alert dismissal
        document.querySelectorAll('.alert .btn-close').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const alert = this.closest('.alert');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                }
            });
        });

        // Auto dismiss success alerts after 6 seconds
        const successAlert = document.getElementById('dashboardSuccessAlert');
        if (successAlert) {
            setTimeout(() => {
                if (successAlert && successAlert.parentNode) {
                    successAlert.classList.remove('show');
                    successAlert.classList.add('fade');
                    setTimeout(() => {
                        if (successAlert.parentNode) {
                            successAlert.remove();
                        }
                    }, 300);
                }
            }, 6000);
        }

        // AJAX Logout functionality
        const logoutBtn = document.getElementById('logoutBtn');
        const logoutIcon = document.querySelector('.logout-icon');
        const logoutSpinner = document.querySelector('.logout-spinner');
        const logoutText = document.querySelector('.logout-text');
        const logoutLoadingText = document.querySelector('.logout-loading-text');

        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Prevent double clicks
                if (logoutBtn.classList.contains('logout-loading')) {
                    return;
                }

                // Show loading state
                logoutBtn.classList.add('logout-loading');
                logoutIcon.classList.add('d-none');
                logoutSpinner.classList.remove('d-none');
                logoutText.classList.add('d-none');
                logoutLoadingText.classList.remove('d-none');

                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Make AJAX request
                fetch('{{ route("logout.ajax") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message briefly before redirect
                        console.log('Logout successful:', data.message);
                        
                        // Redirect to login page
                        window.location.href = data.redirect;
                    } else {
                        throw new Error(data.message || 'Logout failed');
                    }
                })
                .catch(error => {
                    console.error('Logout error:', error);
                    
                    // Reset loading state
                    logoutBtn.classList.remove('logout-loading');
                    logoutIcon.classList.remove('d-none');
                    logoutSpinner.classList.add('d-none');
                    logoutText.classList.remove('d-none');
                    logoutLoadingText.classList.add('d-none');
                    
                    // Show error message
                    alert('Logout failed. Please try again.');
                });
            });
        }
    });

document.addEventListener('DOMContentLoaded', function() {
    // Handle dropdown toggles
    const dropdownItems = document.querySelectorAll('.sidebar-menu__item.has-dropdown > .sidebar-menu__link');
    
    dropdownItems.forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            const parentItem = this.parentElement;
            const submenu = parentItem.querySelector('.sidebar-submenu');
            
            // Close other open dropdowns
            const otherDropdowns = document.querySelectorAll('.sidebar-menu__item.has-dropdown.open');
            otherDropdowns.forEach(function(dropdown) {
                if (dropdown !== parentItem) {
                    dropdown.classList.remove('open');
                    const otherSubmenu = dropdown.querySelector('.sidebar-submenu');
                    if (otherSubmenu) {
                        otherSubmenu.style.display = 'none';
                    }
                }
            });
            
            // Toggle current dropdown
            if (parentItem.classList.contains('open')) {
                parentItem.classList.remove('open');
                if (submenu) {
                    submenu.style.display = 'none';
                }
            } else {
                parentItem.classList.add('open');
                if (submenu) {
                    submenu.style.display = 'block';
                }
            }
        });
    });
    
    // Keep dropdown open if child is active
    const activeSubmenuItems = document.querySelectorAll('.sidebar-submenu__item.active');
    activeSubmenuItems.forEach(function(item) {
        const parentDropdown = item.closest('.sidebar-menu__item.has-dropdown');
        if (parentDropdown) {
            parentDropdown.classList.add('open');
            const submenu = parentDropdown.querySelector('.sidebar-submenu');
            if (submenu) {
                submenu.style.display = 'block';
            }
        }
    });
    
    // Handle active states for better UX
    const allSidebarLinks = document.querySelectorAll('.sidebar-menu__link, .sidebar-submenu__link');
    allSidebarLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            // Remove active class from all items first
            document.querySelectorAll('.sidebar-menu__item, .sidebar-submenu__item').forEach(function(item) {
                item.classList.remove('active');
            });
            document.querySelectorAll('.sidebar-menu__link, .sidebar-submenu__link').forEach(function(link) {
                link.classList.remove('active');
            });
            
            // Add active class to clicked item
            if (this.classList.contains('sidebar-menu__link')) {
                this.parentElement.classList.add('active');
                this.classList.add('active');
            } else if (this.classList.contains('sidebar-submenu__link')) {
                this.parentElement.classList.add('active');
                this.classList.add('active');
                // Also mark parent dropdown as active
                const parentDropdown = this.closest('.sidebar-menu__item.has-dropdown');
                if (parentDropdown) {
                    parentDropdown.classList.add('active');
                }
            }
        });
    });
});
    </script>
        <!-- Jquery js -->
    <script src="{{ url('assets/js/jquery-3.7.1.min.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="{{ url('assets/js/boostrap.bundle.min.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- Phosphor Js -->
    <script src="{{ url('assets/js/phosphor-icon.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- file upload -->
    <script src="{{ url('assets/js/file-upload.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- file upload -->
    <script src="{{ url('assets/js/plyr.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- dataTables -->
    <script src="{{ url('../../cdn.datatables.net/2.0.8/js/dataTables.min.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- full calendar -->
    <script src="{{ url('assets/js/full-calendar.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- jQuery UI -->
    <script src="{{ url('assets/js/jquery-ui.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- jQuery UI -->
    <script src="{{ url('assets/js/editor-quill.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- apex charts -->
    <script src="{{ url('assets/js/apexcharts.min.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- jvectormap Js -->
    <script src="{{ url('assets/js/jquery-jvectormap-2.0.5.min.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- jvectormap world Js -->
    <script src="{{ url('assets/js/jquery-jvectormap-world-mill-en.js?v=' .env("CACHE_VERSION")) }}"></script>
    
    <!-- main js -->
    <script src="{{ url('assets/js/main.js?v=' .env("CACHE_VERSION")) }}"></script>
    </body>
</html>