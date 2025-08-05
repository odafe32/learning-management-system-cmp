<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Instructor Dashboard</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('instructor.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
            </ul>
        </div>

        <!-- Success Message -->
        <x-success-message />

        <!-- Dashboard content -->
        <div class="row gy-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Welcome to Instructor Dashboard</h5>
                        <p class="card-text">Manage your courses, students, and assignments from here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-instructor-layout>