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

  /* Notification Badge */
.notification-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    font-size: 10px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
    border: 2px solid white;
    animation: pulse 2s infinite;
}

/* Shaking Animation for Notification Bell */
@keyframes shake {
    0%, 100% { transform: rotate(0deg); }
    10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
    20%, 40%, 60%, 80% { transform: rotate(10deg); }
}

.shaking-animation {
    animation: shake 0.5s ease-in-out infinite;
}

/* Pulse Animation for Badge */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
    }
}

/* Notification Dropdown */
.dropdown-menu--lg {
    min-width: 380px;
    max-width: 420px;
}

@media (max-width: 768px) {
    .dropdown-menu--lg {
        min-width: 320px;
        max-width: 350px;
        margin-right: 10px;
    }
}

/* Notification Items */
.notification-item {
    transition: all 0.2s ease;
    cursor: pointer;
    border-radius: 8px;
    margin: 4px 8px;
}

.notification-item:hover {
    background-color: var(--gray-50, #f9fafb) !important;
    transform: translateX(2px);
}

.notification-item:last-child {
    border-bottom: none !important;
}

/* Unread Notification Styling */
.bg-primary-25 {
    background-color: rgba(99, 102, 241, 0.05) !important;
    border-left: 3px solid var(--main-600, #6366f1);
}

/* Notification Action Buttons */
.mark-read-btn,
.delete-notification-btn {
    transition: all 0.2s ease;
    text-decoration: none !important;
    font-weight: 500;
}

.mark-read-btn:hover {
    background-color: rgba(99, 102, 241, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
}

.delete-notification-btn:hover {
    background-color: rgba(239, 68, 68, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
}

/* Notification Loading States */
.notification-loading {
    color: var(--gray-500, #6b7280);
    font-size: 14px;
}

.notification-loading .spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* Notification Header Actions */
#refreshNotifications,
#clearAllNotifications {
    transition: all 0.2s ease;
    font-weight: 500;
}

#refreshNotifications:hover,
#clearAllNotifications:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

#refreshNotifications:disabled,
#clearAllNotifications:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Notification Icon Containers */
.notification-item .w-40.h-40 {
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.notification-item:hover .w-40.h-40 {
    transform: scale(1.05);
}

/* Notification Content */
.notification-item h6 {
    line-height: 1.3;
    margin-bottom: 4px;
}

.notification-item p {
    line-height: 1.4;
    margin-bottom: 8px;
}

/* Notification Time */
.notification-item small {
    font-size: 11px;
    opacity: 0.8;
}

/* Empty State */
.notification-item .text-center {
    padding: 2rem 1rem;
}

.notification-item .text-center i {
    opacity: 0.5;
    margin-bottom: 0.75rem;
}

/* Notification Dropdown Animation */
.dropdown-menu {
    animation: slideDown 0.2s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Close Dropdown Button */
.close-dropdown {
    transition: all 0.2s ease;
}

.close-dropdown:hover {
    transform: rotate(90deg);
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
}

/* Notification Scroll */
.max-h-400 {
    max-height: 400px;
}

.scroll-sm::-webkit-scrollbar {
    width: 4px;
}

.scroll-sm::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 2px;
}

.scroll-sm::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
}

.scroll-sm::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Notification Type Colors */
.bg-primary-50 { background-color: rgba(99, 102, 241, 0.1) !important; }
.bg-success-50 { background-color: rgba(34, 197, 94, 0.1) !important; }
.bg-warning-50 { background-color: rgba(245, 158, 11, 0.1) !important; }
.bg-danger-50 { background-color: rgba(239, 68, 68, 0.1) !important; }
.bg-info-50 { background-color: rgba(59, 130, 246, 0.1) !important; }
.bg-secondary-50 { background-color: rgba(107, 114, 128, 0.1) !important; }

.text-primary-600 { color: #6366f1 !important; }
.text-success-600 { color: #22c55e !important; }
.text-warning-600 { color: #f59e0b !important; }
.text-danger-600 { color: #ef4444 !important; }
.text-info-600 { color: #3b82f6 !important; }
.text-secondary-600 { color: #6b7280 !important; }

/* Responsive Adjustments */
@media (max-width: 576px) {
    .notification-badge {
        font-size: 9px;
        padding: 1px 4px;
        min-width: 16px;
        height: 16px;
    }
    
    .dropdown-menu--lg {
        min-width: 280px;
        max-width: 300px;
        margin-left: -100px;
    }
    
    .notification-item {
        margin: 2px 4px;
        padding: 12px !important;
    }
    
    .notification-item .w-40.h-40 {
        width: 32px !important;
        height: 32px !important;
    }
    
    .notification-item h6 {
        font-size: 13px;
    }
    
    .notification-item p {
        font-size: 11px;
    }
}

/* Loading Spinner Animations */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spinner-border {
    animation: spin 1s linear infinite;
}

/* Notification Button States */
.refresh-loading,
.clear-loading {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Hover Effects for Notification Bell */
.dropdown-btn:hover {
    transform: scale(1.05);
    background-color: var(--main-100, #e0e7ff) !important;
}

.dropdown-btn:active {
    transform: scale(0.95);
}

/* Focus States for Accessibility */
.dropdown-btn:focus,
.mark-read-btn:focus,
.delete-notification-btn:focus,
#refreshNotifications:focus,
#clearAllNotifications:focus {
    outline: 2px solid var(--main-600, #6366f1);
    outline-offset: 2px;
}

/* Notification Footer */
.notification-footer {
    background-color: var(--gray-25, #fafafa);
    border-top: 1px solid var(--gray-100, #f3f4f6);
}

/* Error State Styling */
.notification-error {
    color: var(--danger-600, #ef4444);
}

.notification-error i {
    color: var(--danger-600, #ef4444);
}

.notification-error .btn {
    font-size: 12px;
    padding: 4px 12px;
}

/* Success State for Actions */
.notification-success {
    background-color: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.2);
    color: #166534;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    margin: 8px;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Dark Mode Support (if needed) */
@media (prefers-color-scheme: dark) {
    .notification-item {
        background-color: var(--dark-bg, #1f2937);
        color: var(--dark-text, #f9fafb);
    }
    
    .notification-item:hover {
        background-color: var(--dark-hover, #374151) !important;
    }
    
    .bg-primary-25 {
        background-color: rgba(99, 102, 241, 0.15) !important;
    }
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
    
    <a href="{{ route('student.dashboard') }}" class="sidebar__logo text-center p-20 position-sticky inset-block-start-0 bg-white w-100 z-1 pb-10">
        <img src="{{ url('assets/images/logo/logo.png') }}" alt="Logo">
    </a>

    <div class="sidebar-menu-wrapper overflow-y-auto scroll-sm">
    <div class="p-20 pt-10">
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="sidebar-menu__item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <a href="{{ route('student.dashboard') }}" class="sidebar-menu__link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <span class="icon"><i class="ph ph-squares-four"></i></span>
                    <span class="text">Dashboard</span>
                </a>
            </li>

            <!-- Courses -->
            <li class="sidebar-menu__item has-dropdown {{ request()->routeIs('student.courses.*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-menu__link {{ request()->routeIs('student.courses.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ph ph-bookmarks"></i></span>
                    <span class="text">My Courses</span>
                </a>
                <!-- Submenu start -->
                <ul class="sidebar-submenu" style="{{ request()->routeIs('student.courses.*') ? 'display: block;' : '' }}">
                    <li class="sidebar-submenu__item {{ request()->routeIs('student.courses.enroll-courses') ? 'active' : '' }}">
                        <a href="{{ route('student.courses.enroll-courses') }}" class="sidebar-submenu__link {{ request()->routeIs('student.courses.enroll-courses') ? 'active' : '' }}">Enroll Courses</a>
                    </li>
                    <li class="sidebar-submenu__item {{ request()->routeIs('student.courses.index') ? 'active' : '' }}">
                        <a href="{{ route('student.courses.index') }}" class="sidebar-submenu__link {{ request()->routeIs('student.courses.index') ? 'active' : '' }}">Courses</a>
                    </li>
                </ul>
                <!-- Submenu End -->
            </li>

            <!-- Assignments -->
            <li class="sidebar-menu__item has-dropdown {{ request()->routeIs('student.assignments.*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-menu__link {{ request()->routeIs('student.assignments.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ph ph-clipboard-text"></i></span>
                    <span class="text">Assignments</span>
                </a>
                <!-- Submenu start -->
                <ul class="sidebar-submenu" style="{{ request()->routeIs('student.assignments.*') ? 'display: block;' : '' }}">
                    <li class="sidebar-submenu__item {{ request()->routeIs('student.assignments.index') ? 'active' : '' }}">
                        <a href="{{ route('student.assignments.index') }}" class="sidebar-submenu__link {{ request()->routeIs('student.assignments.index') ? 'active' : '' }}">Assignment</a>
                    </li>
                    <li class="sidebar-submenu__item {{ request()->routeIs('student.assignments.submit-assignments') ? 'active' : '' }}">
                        <a href="{{ route('student.assignments.submit-assignments') }}" class="sidebar-submenu__link {{ request()->routeIs('student.assignments.submit-assignments') ? 'active' : '' }}">Submit Assignment</a>
                    </li>
                </ul>
                <!-- Submenu End -->
            </li>

            <!-- Lecture Materials -->
            <li class="sidebar-menu__item has-dropdown {{ request()->routeIs('student.materials.*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-menu__link {{ request()->routeIs('student.materials.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ph ph-book"></i></span>
                    <span class="text">Lecture Materials</span>
                </a>
                <!-- Submenu start -->
                <ul class="sidebar-submenu" style="{{ request()->routeIs('student.materials.*') ? 'display: block;' : '' }}">
                    <li class="sidebar-submenu__item {{ request()->routeIs('student.materials.index') ? 'active' : '' }}">
                        <a href="{{ route('student.materials.index') }}" class="sidebar-submenu__link {{ request()->routeIs('student.materials.index') ? 'active' : '' }}">View Materials</a>
                    </li>
                </ul>
                <!-- Submenu End -->
            </li>

            <!-- Submissions -->
            <li class="sidebar-menu__item has-dropdown {{ request()->routeIs('student.submissions.*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-menu__link {{ request()->routeIs('student.submissions.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ph ph-file-arrow-up"></i></span>
                    <span class="text">Submissions</span>
                </a>
                <!-- Submenu start -->
                <ul class="sidebar-submenu" style="{{ request()->routeIs('student.submissions.*') ? 'display: block;' : '' }}">
                    <li class="sidebar-submenu__item {{ request()->routeIs('student.submissions.index') ? 'active' : '' }}">
                        <a href="{{ route('student.submissions.index') }}" class="sidebar-submenu__link {{ request()->routeIs('student.submissions.index') ? 'active' : '' }}">View Submissions</a>
                    </li>
                </ul>
                <!-- Submenu End -->
            </li>

            <!-- Grades -->
            <li class="sidebar-menu__item has-dropdown {{ request()->routeIs('student.grades.*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-menu__link {{ request()->routeIs('student.grades.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ph ph-chart-line"></i></span>
                    <span class="text">Grades</span>
                </a>
                <!-- Submenu start -->
                <ul class="sidebar-submenu" style="{{ request()->routeIs('student.grades.*') ? 'display: block;' : '' }}">
                    <li class="sidebar-submenu__item {{ request()->routeIs('student.grades.index') ? 'active' : '' }}">
                        <a href="{{ route('student.grades.index') }}" class="sidebar-submenu__link {{ request()->routeIs('student.grades.index') ? 'active' : '' }}">View Grades</a>
                    </li>
                </ul>
                <!-- Submenu End -->
            </li>

            <!-- Feedbacks -->
            <li class="sidebar-menu__item has-dropdown {{ request()->routeIs('student.feedbacks.*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-menu__link {{ request()->routeIs('student.feedbacks.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ph ph-chat-text"></i></span>
                    <span class="text">Feedbacks</span>
                </a>
                <!-- Submenu start -->
                <ul class="sidebar-submenu" style="{{ request()->routeIs('student.feedbacks.*') ? 'display: block;' : '' }}">
                    <li class="sidebar-submenu__item {{ request()->routeIs('student.feedbacks.index') ? 'active' : '' }}">
                        <a href="{{ route('student.feedbacks.index') }}" class="sidebar-submenu__link {{ request()->routeIs('student.feedbacks.index') ? 'active' : '' }}">View Feedbacks</a>
                    </li>
                </ul>
                <!-- Submenu End -->
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
                <button id="notificationBtn" class="dropdown-btn shaking-animation text-gray-500 w-40 h-40 bg-main-50 hover-bg-main-100 transition-2 rounded-circle text-xl flex-center position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="position-relative">
                        <i class="ph ph-bell"></i>
                        <span id="notificationBadge" class="notification-badge d-none">0</span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                    <div class="card border border-gray-100 rounded-12 box-shadow-custom p-0 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="py-8 px-24 bg-main-600">
                                <div class="flex-between">
                                    <h5 class="text-xl fw-semibold text-white mb-0">Notifications</h5>
                                    <div class="flex-align gap-12">
                                        <button type="button" id="refreshNotifications" class="bg-white rounded-6 text-sm px-8 py-2 hover-text-primary-600 border-0">
                                            <i class="ph ph-arrow-clockwise me-1"></i> 
                                            <span class="refresh-text">Refresh</span>
                                            <span class="refresh-loading d-none">
                                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                                Refreshing...
                                            </span>
                                        </button>
                                        <button type="button" id="clearAllNotifications" class="bg-white rounded-6 text-sm px-8 py-2 hover-text-primary-600 border-0">
                                            <i class="ph ph-check-circle me-1"></i> 
                                            <span class="clear-text">Clear All</span>
                                            <span class="clear-loading d-none">
                                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                                Clearing...
                                            </span>
                                        </button>
                                        <button type="button" class="close-dropdown hover-scale-1 text-xl text-white border-0 bg-transparent">
                                            <i class="ph ph-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="notificationsList" class="p-24 max-h-400 overflow-y-auto scroll-sm">
                                <div class="notification-loading">
                                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Loading notifications...
                                </div>
                            </div>
                            <div class="border-top border-gray-100 p-12 text-center">
                                <small class="text-gray-400">
                                    <i class="ph ph-info me-1"></i>
                                    Notifications refresh every 30 seconds
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Notification End -->
            
          
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
                        <a href="{{ route('student.profile') }}" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15 {{ request()->routeIs('student.profile') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <span class="text-2xl {{ request()->routeIs('student.profile') ? 'text-primary-600' : 'text-primary-600' }} d-flex">
                                <i class="ph ph-user-circle"></i>
                            </span>
                            <span class="text">Profile</span>
                        </a>
                    </li>
               
                    <li class="mb-4">
                        <a href="{{ route('student.feedbacks.index') }}" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15 {{ request()->routeIs('student.feedbacks.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <span class="text-2xl {{ request()->routeIs('student.feedbacks.*') ? 'text-primary-600' : 'text-primary-600' }} d-flex">
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
    // Notification system variables
    let notificationInterval;
    let isNotificationDropdownOpen = false;
    
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationsList = document.getElementById('notificationsList');
    const refreshNotificationsBtn = document.getElementById('refreshNotifications');
    const clearAllNotificationsBtn = document.getElementById('clearAllNotifications');
    
    // Initialize notifications
    loadNotifications();
    startNotificationPolling();
    
    // Handle notification dropdown toggle
    notificationBtn.addEventListener('click', function() {
        isNotificationDropdownOpen = !isNotificationDropdownOpen;
        if (isNotificationDropdownOpen) {
            loadNotifications();
        }
    });
    
    // Handle dropdown close
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            isNotificationDropdownOpen = false;
        }
    });
    
    // Handle refresh notifications
    refreshNotificationsBtn.addEventListener('click', function(e) {
        e.preventDefault();
        refreshNotifications();
    });
    
    // Handle clear all notifications
    clearAllNotificationsBtn.addEventListener('click', function(e) {
        e.preventDefault();
        clearAllNotifications();
    });
    
    // Load notifications function
    function loadNotifications() {
        showNotificationLoading();
        
        fetch('/student/notifications', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayNotifications(data.notifications);
                updateNotificationBadge(data.unread_count);
            } else {
                showNotificationError('Failed to load notifications');
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            showNotificationError('Error loading notifications');
        });
    }
    
    // Display notifications in the dropdown
    function displayNotifications(notifications) {
        if (notifications.length === 0) {
            notificationsList.innerHTML = `
                <div class="text-center py-32">
                    <i class="ph ph-bell-slash text-4xl text-gray-400 mb-12"></i>
                    <p class="text-gray-500 mb-0">No notifications yet</p>
                    <small class="text-gray-400">You're all caught up!</small>
                </div>
            `;
            return;
        }
        
        let notificationsHtml = '';
        notifications.forEach(notification => {
            const isUnread = !notification.is_read;
            const timeAgo = formatTimeAgo(notification.created_at);
            const icon = getNotificationIcon(notification.type);
            const bgClass = isUnread ? 'bg-primary-25' : '';
            
            notificationsHtml += `
                <div class="notification-item ${bgClass} p-16 border-bottom border-gray-100 position-relative" data-notification-id="${notification.id}">
                    ${isUnread ? '<span class="position-absolute top-16 end-16 w-8 h-8 bg-primary-600 rounded-circle"></span>' : ''}
                    <div class="flex-align gap-12">
                        <div class="w-40 h-40 bg-${getNotificationColor(notification.type)}-50 text-${getNotificationColor(notification.type)}-600 rounded-circle flex-center">
                            <i class="${icon}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-sm fw-semibold mb-4 ${isUnread ? 'text-gray-900' : 'text-gray-600'}">${notification.title}</h6>
                            <p class="text-xs text-gray-500 mb-8">${notification.message}</p>
                            <div class="flex-between">
                                <small class="text-gray-400">${timeAgo}</small>
                                <div class="flex-align gap-8">
                                    ${isUnread ? `<button class="mark-read-btn text-xs text-primary-600 hover-text-primary-700 border-0 bg-transparent p-0" data-notification-id="${notification.id}">Mark as read</button>` : ''}
                                    <button class="delete-notification-btn text-xs text-danger-600 hover-text-danger-700 border-0 bg-transparent p-0" data-notification-id="${notification.id}">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        notificationsList.innerHTML = notificationsHtml;
        
        // Add event listeners to notification actions
        addNotificationEventListeners();
    }
    
    // Add event listeners to notification buttons
    function addNotificationEventListeners() {
        // Mark as read buttons
        document.querySelectorAll('.mark-read-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const notificationId = this.dataset.notificationId;
                markNotificationAsRead(notificationId);
            });
        });
        
        // Delete notification buttons
        document.querySelectorAll('.delete-notification-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const notificationId = this.dataset.notificationId;
                deleteNotification(notificationId);
            });
        });
    }
    
    // Mark notification as read
    function markNotificationAsRead(notificationId) {
        fetch('/student/notifications/mark-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                notification_id: notificationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove unread styling
                const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                if (notificationItem) {
                    notificationItem.classList.remove('bg-primary-25');
                    notificationItem.querySelector('.position-absolute.w-8.h-8')?.remove();
                    notificationItem.querySelector('.mark-read-btn')?.remove();
                    notificationItem.querySelector('h6').classList.remove('text-gray-900');
                    notificationItem.querySelector('h6').classList.add('text-gray-600');
                }
                
                // Update badge count
                const currentCount = parseInt(notificationBadge.textContent) || 0;
                updateNotificationBadge(Math.max(0, currentCount - 1));
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
        });
    }
    
    // Delete notification
    function deleteNotification(notificationId) {
        if (!confirm('Are you sure you want to delete this notification?')) {
            return;
        }
        
        fetch(`/student/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove notification from DOM
                const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                if (notificationItem) {
                    notificationItem.remove();
                }
                
                // Update badge if it was unread
                if (notificationItem && notificationItem.classList.contains('bg-primary-25')) {
                    const currentCount = parseInt(notificationBadge.textContent) || 0;
                    updateNotificationBadge(Math.max(0, currentCount - 1));
                }
                
                // Check if no notifications left
                if (document.querySelectorAll('.notification-item').length === 0) {
                    displayNotifications([]);
                }
            }
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
        });
    }
    
    // Refresh notifications
    function refreshNotifications() {
        const refreshText = refreshNotificationsBtn.querySelector('.refresh-text');
        const refreshLoading = refreshNotificationsBtn.querySelector('.refresh-loading');
        
        refreshText.classList.add('d-none');
        refreshLoading.classList.remove('d-none');
        refreshNotificationsBtn.disabled = true;
        
        loadNotifications();
        
        setTimeout(() => {
            refreshText.classList.remove('d-none');
            refreshLoading.classList.add('d-none');
            refreshNotificationsBtn.disabled = false;
        }, 1000);
    }
    
    // Clear all notifications
    function clearAllNotifications() {
        if (!confirm('Are you sure you want to clear all notifications?')) {
            return;
        }
        
        const clearText = clearAllNotificationsBtn.querySelector('.clear-text');
        const clearLoading = clearAllNotificationsBtn.querySelector('.clear-loading');
        
        clearText.classList.add('d-none');
        clearLoading.classList.remove('d-none');
        clearAllNotificationsBtn.disabled = true;
        
        fetch('/student/notifications/clear-all', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayNotifications([]);
                updateNotificationBadge(0);
            }
        })
        .catch(error => {
            console.error('Error clearing notifications:', error);
        })
        .finally(() => {
            clearText.classList.remove('d-none');
            clearLoading.classList.add('d-none');
            clearAllNotificationsBtn.disabled = false;
        });
    }
    
    // Update notification badge
    function updateNotificationBadge(count) {
        if (count > 0) {
            notificationBadge.textContent = count > 99 ? '99+' : count;
            notificationBadge.classList.remove('d-none');
            notificationBtn.classList.add('shaking-animation');
        } else {
            notificationBadge.classList.add('d-none');
            notificationBtn.classList.remove('shaking-animation');
        }
    }
    
    // Show loading state
    function showNotificationLoading() {
        notificationsList.innerHTML = `
            <div class="notification-loading text-center py-24">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Loading notifications...
            </div>
        `;
    }
    
    // Show error state
    function showNotificationError(message) {
        notificationsList.innerHTML = `
            <div class="text-center py-32">
                <i class="ph ph-warning-circle text-4xl text-danger mb-12"></i>
                <p class="text-danger mb-0">${message}</p>
                <button class="btn btn-sm btn-outline-primary mt-12" onclick="loadNotifications()">Try Again</button>
            </div>
        `;
    }
    
    // Start polling for new notifications
    function startNotificationPolling() {
        notificationInterval = setInterval(() => {
            if (!isNotificationDropdownOpen) {
                // Only update badge count when dropdown is closed
                fetch('/student/notifications/count', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateNotificationBadge(data.unread_count);
                    }
                })
                .catch(error => {
                    console.error('Error polling notifications:', error);
                });
            }
        }, 30000); // Poll every 30 seconds
    }
    
    // Helper functions
    function getNotificationIcon(type) {
        const icons = {
            'user_created': 'ph-user-plus',
            'course_created': 'ph-book',
            'assignment_created': 'ph-clipboard-text',
            'assignment_submitted': 'ph-file-arrow-up',
            'material_uploaded': 'ph-file-plus',
            'system': 'ph-gear',
            'warning': 'ph-warning',
            'info': 'ph-info',
            'success': 'ph-check-circle',
            'error': 'ph-x-circle'
        };
        return icons[type] || 'ph-bell';
    }
    
    function getNotificationColor(type) {
        const colors = {
            'user_created': 'primary',
            'course_created': 'success',
            'assignment_created': 'warning',
            'assignment_submitted': 'info',
            'material_uploaded': 'secondary',
            'system': 'primary',
            'warning': 'warning',
            'info': 'info',
            'success': 'success',
            'error': 'danger'
        };
        return colors[type] || 'primary';
    }
    
    function formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) {
            return 'Just now';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return `${hours} hour${hours > 1 ? 's' : ''} ago`;
        } else if (diffInSeconds < 604800) {
            const days = Math.floor(diffInSeconds / 86400);
            return `${days} day${days > 1 ? 's' : ''} ago`;
        } else {
            return date.toLocaleDateString();
        }
    }
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (notificationInterval) {
            clearInterval(notificationInterval);
        }
    });

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
    <script src="{{ url('assets/js/jquery-jvectormap-world-mill-en.js?v=' .env("CACHE_VERSION")) }}a"></script>
    
    <!-- main js -->
    <script src="{{ url('assets/js/main.js?v=' .env("CACHE_VERSION")) }}"></script>
    </body>
</html>