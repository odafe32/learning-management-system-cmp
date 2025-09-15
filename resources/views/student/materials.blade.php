<x-student-layout
    :metaTitle="$meta_title ?? 'Course Materials'"
    :metaDesc="$meta_desc ?? 'Access your course materials'"
    :metaImage="$meta_image ?? ''"
>
<!-- Add these to your <head> section in your layout file -->

<!-- Iconify Icon Sets -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>

<!-- Alternative: You can also use the newer version -->
<script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

<!-- Or use the CDN with specific icon sets -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconify/iconify@3.1.1/dist/iconify.min.css">
<div class="dashboard-main-body">
    <!-- Breadcrumb -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Course Materials</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Materials</li>
        </ul>
    </div>

    <!-- Search and Filter Section -->
    <div class="card mb-24">
        <div class="card-body">
            <form method="GET" action="{{ route('student.materials.index') }}" class="row g-3">
                <!-- Search Input -->
                <div class="col-md-4">
                    <label class="form-label">Search Materials</label>
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control" placeholder="Search by title or description..." 
                               value="{{ ($currentFilters['search'] ?? '') }}">
                        <iconify-icon icon="solar:magnifer-outline" class="position-absolute top-50 end-0 translate-middle-y me-12"></iconify-icon>
                    </div>
                </div>

                <!-- Course Filter -->
                <div class="col-md-3">
                    <label class="form-label">Filter by Course</label>
                    <select name="course" class="form-select">
                        <option value="">All Courses</option>
                        @if(isset($enrolledCourses) && $enrolledCourses->count() > 0)
                            @foreach($enrolledCourses as $course)
                                <option value="{{ $course->id }}" {{ (($currentFilters['course'] ?? '') == $course->id) ? 'selected' : '' }}>
                                    {{ $course->code }} - {{ $course->title }}
                                    @if(isset($course->materials_count) && $course->materials_count > 0)
                                        ({{ $course->materials_count }})
                                    @endif
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- File Type Filter -->
                <div class="col-md-2">
                    <label class="form-label">File Type</label>
                    <select name="file_type" class="form-select">
                        <option value="">All Types</option>
                        @if(isset($availableFileTypes) && $availableFileTypes->count() > 0)
                            @foreach($availableFileTypes as $fileType)
                                <option value="{{ $fileType }}" {{ (($currentFilters['file_type'] ?? '') == $fileType) ? 'selected' : '' }}>
                                    {{ strtoupper($fileType) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Sort Options -->
                <div class="col-md-2">
                    <label class="form-label">Sort By</label>
                    <select name="sort_by" class="form-select">
                        <option value="uploaded_at" {{ (($currentFilters['sort_by'] ?? 'uploaded_at') == 'uploaded_at') ? 'selected' : '' }}>Upload Date</option>
                        <option value="title" {{ (($currentFilters['sort_by'] ?? '') == 'title') ? 'selected' : '' }}>Title</option>
                        <option value="file_type" {{ (($currentFilters['sort_by'] ?? '') == 'file_type') ? 'selected' : '' }}>File Type</option>
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="col-md-1">
                    <label class="form-label">Order</label>
                    <select name="sort_order" class="form-select">
                        <option value="desc" {{ (($currentFilters['sort_order'] ?? 'desc') == 'desc') ? 'selected' : '' }}>↓</option>
                        <option value="asc" {{ (($currentFilters['sort_order'] ?? '') == 'asc') ? 'selected' : '' }}>↑</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <iconify-icon icon="solar:magnifer-outline" class="icon"></iconify-icon>
                            Search & Filter
                        </button>
                        <a href="{{ route('student.materials.index') }}" class="btn btn-secondary">
                            <iconify-icon icon="solar:refresh-outline" class="icon"></iconify-icon>
                            Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Materials Grid -->
    @if(isset($materials) && $materials->count() > 0)
        <div class="row">
            @foreach($materials as $material)
                <div class="col-lg-4 col-md-6 mb-24">
                    <div class="card h-100">
                        <div class="card-body">
                            <!-- File Icon and Type -->
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="w-48-px h-48-px bg-primary-50 text-primary-600 rounded-circle d-flex justify-content-center align-items-center text-xl">
                                    @if(($material->file_type ?? '') === 'pdf')
                                        <iconify-icon icon="solar:file-text-outline"></iconify-icon>
                                    @elseif(in_array(($material->file_type ?? ''), ['jpg', 'jpeg', 'png', 'gif']))
                                        <iconify-icon icon="solar:gallery-outline"></iconify-icon>
                                    @elseif(in_array(($material->file_type ?? ''), ['mp4', 'avi', 'mov']))
                                        <iconify-icon icon="solar:video-outline"></iconify-icon>
                                    @elseif(in_array(($material->file_type ?? ''), ['mp3', 'wav']))
                                        <iconify-icon icon="solar:music-note-outline"></iconify-icon>
                                    @else
                                        <iconify-icon icon="solar:folder-outline"></iconify-icon>
                                    @endif
                                </span>
                                <div class="flex-grow-1">
                                    <span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4 text-xs">
                                        {{ strtoupper($material->file_type ?? 'FILE') }}
                                    </span>
                                    <span class="badge bg-secondary-50 text-secondary-600 px-8 py-4 rounded-4 text-xs ms-1">
                                        {{ $material->file_size_formatted ?? 'Unknown' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Material Info -->
                            <h6 class="mb-2">{{ $material->title ?? 'Untitled' }}</h6>
                            <p class="text-secondary-light text-sm mb-3">
                                {{ Str::limit($material->description ?? '', 100) }}
                            </p>

                            <!-- Course Info -->
                            @if(isset($material->course))
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge bg-primary-50 text-primary-600 px-8 py-4 rounded-4 text-xs">
                                        {{ $material->course->code ?? 'N/A' }}
                                    </span>
                                    <span class="text-xs text-secondary-light">
                                        {{ $material->course->title ?? 'N/A' }}
                                    </span>
                                </div>
                            @endif

                            <!-- Upload Info -->
                            @if(isset($material->instructor))
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <img src="{{ $material->instructor->profile_image_url ?? asset('assets/images/default-avatar.png') }}" 
                                         alt="{{ $material->instructor->name ?? 'Instructor' }}" 
                                         class="w-24-px h-24-px rounded-circle object-fit-cover">
                                    <span class="text-xs text-secondary-light">
                                        {{ $material->instructor->name ?? 'Unknown' }}
                                    </span>
                                    <span class="text-xs text-secondary-light">•</span>
                                    <span class="text-xs text-secondary-light">
                                        {{ $material->uploaded_at ? $material->uploaded_at->format('M d, Y') : ($material->created_at ? $material->created_at->format('M d, Y') : 'N/A') }}
                                    </span>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 mt-auto">
                                <a href="{{ route('student.materials.show', $material->id) }}" 
                                   class="btn btn-primary btn-sm flex-grow-1">
                                    <iconify-icon icon="solar:eye-outline" class="icon"></iconify-icon>
                                    View
                                </a>
                                @if(($material->file_exists ?? false))
                                    <a href="{{ route('student.materials.download', $material->id) }}" 
                                       class="btn btn-outline-success btn-sm">
                                        <iconify-icon icon="solar:download-outline" class="icon"></iconify-icon>
                                    </a>
                                    @if(in_array(($material->file_type ?? ''), ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'mp3']))
                                        <a href="{{ route('student.materials.stream', $material->id) }}" 
                                           class="btn btn-outline-info btn-sm" target="_blank">
                                            <iconify-icon icon="solar:play-outline" class="icon"></iconify-icon>
                                        </a>
                                    @endif
                                @else
                                    <span class="btn btn-danger btn-sm disabled">
                                        <iconify-icon icon="solar:close-circle-outline" class="icon"></iconify-icon>
                                        Unavailable
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(isset($materials) && method_exists($materials, 'hasPages') && $materials->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $materials->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body text-center py-5">
                <iconify-icon icon="solar:folder-outline" class="icon text-6xl text-secondary-light mb-3"></iconify-icon>
                <h6 class="mb-2">No Materials Found</h6>
                @if(isset($currentFilters) && array_filter($currentFilters))
                    <p class="text-secondary-light mb-3">No materials match your current filters.</p>
                    <a href="{{ route('student.materials.index') }}" class="btn btn-outline-primary">
                        <iconify-icon icon="solar:refresh-outline" class="icon"></iconify-icon>
                        Clear Filters
                    </a>
                @else
                    <p class="text-secondary-light">Course materials will appear here when your instructors upload them.</p>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on select change
    const selectElements = document.querySelectorAll('select[name="course"], select[name="file_type"], select[name="sort_by"], select[name="sort_order"]');
    selectElements.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Search input with debounce
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }
});
</script>
</x-student-layout>