<x-student-layout
    :metaTitle="$meta_title ?? 'Course Materials'"
    :metaDesc="$meta_desc ?? 'Access your course materials'"
    :metaImage="$meta_image ?? ''"
>
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
        <div class="card-header">
            <h6 class="card-title mb-0">Search & Filter Materials</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('student.materials.index') }}" class="row g-3" id="materialsFilterForm">
                <!-- Search Input -->
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-medium">Search Materials</label>
                    <div class="position-relative">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control ps-40" 
                            placeholder="Search by title or description..." 
                            value="{{ $currentFilters['search'] ?? '' }}"
                        >
                        <iconify-icon icon="solar:magnifer-outline" class="position-absolute top-50 start-0 translate-middle-y ms-12 text-secondary-light"></iconify-icon>
                    </div>
                </div>

                <!-- Course Filter -->
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-medium">Filter by Course</label>
                    <select name="course" class="form-select">
                        <option value="">All Courses</option>
                        @if(isset($enrolledCourses) && $enrolledCourses->count() > 0)
                            @foreach($enrolledCourses as $course)
                                <option value="{{ $course->id }}" {{ ($currentFilters['course'] ?? '') == $course->id ? 'selected' : '' }}>
                                    {{ $course->code }} - {{ Str::limit($course->title, 30) }}
                                    @if(isset($course->materials_count) && $course->materials_count > 0)
                                        ({{ $course->materials_count }})
                                    @endif
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- File Type Filter -->
                <div class="col-lg-2 col-md-4">
                    <label class="form-label fw-medium">File Type</label>
                    <select name="file_type" class="form-select">
                        <option value="">All Types</option>
                        @if(isset($availableFileTypes) && $availableFileTypes->count() > 0)
                            @foreach($availableFileTypes as $fileType)
                                <option value="{{ $fileType }}" {{ ($currentFilters['file_type'] ?? '') == $fileType ? 'selected' : '' }}>
                                    {{ strtoupper($fileType) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Sort Options -->
                <div class="col-lg-2 col-md-4">
                    <label class="form-label fw-medium">Sort By</label>
                    <select name="sort_by" class="form-select">
                        <option value="uploaded_at" {{ ($currentFilters['sort_by'] ?? 'uploaded_at') == 'uploaded_at' ? 'selected' : '' }}>Upload Date</option>
                        <option value="title" {{ ($currentFilters['sort_by'] ?? '') == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="file_type" {{ ($currentFilters['sort_by'] ?? '') == 'file_type' ? 'selected' : '' }}>File Type</option>
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="col-lg-1 col-md-4">
                    <label class="form-label fw-medium">Order</label>
                    <select name="sort_order" class="form-select">
                        <option value="desc" {{ ($currentFilters['sort_order'] ?? 'desc') == 'desc' ? 'selected' : '' }}>
                            <iconify-icon icon="solar:sort-vertical-outline"></iconify-icon> ↓
                        </option>
                        <option value="asc" {{ ($currentFilters['sort_order'] ?? '') == 'asc' ? 'selected' : '' }}>
                            <iconify-icon icon="solar:sort-vertical-outline"></iconify-icon> ↑
                        </option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <iconify-icon icon="solar:magnifer-outline" class="icon"></iconify-icon>
                            Search & Filter
                        </button>
                        <a href="{{ route('student.materials.index') }}" class="btn btn-outline-secondary">
                            <iconify-icon icon="solar:refresh-outline" class="icon"></iconify-icon>
                            Clear Filters
                        </a>
                        @if(isset($currentFilters) && array_filter($currentFilters))
                            <span class="badge bg-primary-50 text-primary-600 px-12 py-6 rounded-4 d-flex align-items-center">
                                {{ count(array_filter($currentFilters)) }} filter(s) active
                            </span>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    @if(isset($materials) && $materials->count() > 0)
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-secondary-light">
                Showing {{ $materials->firstItem() ?? 1 }}-{{ $materials->lastItem() ?? $materials->count() }} 
                of {{ $materials->total() ?? $materials->count() }} materials
            </span>
           
        </div>
    @endif

    <!-- Materials Grid -->
    @if(isset($materials) && $materials->count() > 0)
        <div class="row" id="materialsContainer">
            @foreach($materials as $material)
                <div class="col-lg-4 col-md-6 mb-24 material-item">
                    <div class="card h-100 material-card">
                        <div class="card-body d-flex flex-column">
                            <!-- File Icon and Type -->
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="flex-shrink-0">
                                    <span class="material-icon-container w-56-px h-56-px bg-primary-50 text-primary-600 rounded-12 d-flex justify-content-center align-items-center text-2xl">
                                        @php
                                            $fileType = strtolower($material->file_type ?? '');
                                            $icon = match($fileType) {
                                                'pdf' => 'solar:file-text-outline',
                                                'doc', 'docx' => 'solar:document-outline',
                                                'xls', 'xlsx' => 'solar:calculator-outline',
                                                'ppt', 'pptx' => 'solar:presentation-graph-outline',
                                                'jpg', 'jpeg', 'png', 'gif', 'webp' => 'solar:gallery-outline',
                                                'mp4', 'avi', 'mov', 'wmv' => 'solar:video-outline',
                                                'mp3', 'wav', 'ogg' => 'solar:music-note-outline',
                                                'zip', 'rar', '7z' => 'solar:archive-outline',
                                                default => 'solar:folder-outline'
                                            };
                                        @endphp
                                        <iconify-icon icon="{{ $icon }}"></iconify-icon>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4 text-xs fw-medium">
                                            {{ strtoupper($material->file_type ?? 'FILE') }}
                                        </span>
                                        <span class="badge bg-secondary-50 text-secondary-600 px-8 py-4 rounded-4 text-xs">
                                            {{ $material->file_size_formatted ?? 'Unknown' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Material Info -->
                            <div class="flex-grow-1">
                                <h6 class="mb-2 text-break">{{ $material->title ?? 'Untitled' }}</h6>
                                <p class="text-secondary-light text-sm mb-3 line-clamp-2">
                                    {{ $material->description ? Str::limit($material->description, 100) : 'No description available.' }}
                                </p>

                                <!-- Course Info -->
                                @if(isset($material->course))
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="badge bg-primary-50 text-primary-600 px-8 py-4 rounded-4 text-xs fw-medium">
                                            {{ $material->course->code ?? 'N/A' }}
                                        </span>
                                        <span class="text-xs text-secondary-light text-break">
                                            {{ Str::limit($material->course->title ?? 'N/A', 30) }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Upload Info -->
                                @if(isset($material->instructor))
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="flex-shrink-0">
                                            <img 
                                                src="{{ $material->instructor->profile_image_url ?? asset('assets/images/default-avatar.png') }}" 
                                                alt="{{ $material->instructor->name ?? 'Instructor' }}" 
                                                class="instructor-avatar rounded-circle object-fit-cover"
                                                style="width: 24px; height: 24px; min-width: 24px;"
                                                loading="lazy"
                                                onerror="this.src='{{ asset('assets/images/default-avatar.png') }}'"
                                            >
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <span class="text-xs text-secondary-light text-break">
                                                {{ $material->instructor->name ?? 'Unknown' }}
                                            </span>
                                            <span class="text-xs text-secondary-light mx-1">•</span>
                                            <span class="text-xs text-secondary-light">
                                                {{ $material->uploaded_at ? $material->uploaded_at->format('M d, Y') : ($material->created_at ? $material->created_at->format('M d, Y') : 'N/A') }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 mt-auto">
                                <a href="{{ route('student.materials.show', $material->id) }}" 
                                   class="btn btn-primary btn-sm flex-grow-1">
                                    <iconify-icon icon="solar:eye-outline" class="icon"></iconify-icon>
                                    View
                                </a>
                                
                                @if($material->file_exists ?? false)
                                    <a href="{{ route('student.materials.download', $material->id) }}" 
                                       class="btn btn-outline-success btn-sm" 
                                       title="Download">
                                        <iconify-icon icon="solar:download-outline" class="icon"></iconify-icon>
                                    </a>
                                    
                                    @if(in_array($fileType, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'mp3']))
                                        <a href="{{ route('student.materials.stream', $material->id) }}" 
                                           class="btn btn-outline-info btn-sm" 
                                           target="_blank"
                                           title="Preview">
                                            <iconify-icon icon="solar:play-outline" class="icon"></iconify-icon>
                                        </a>
                                    @endif
                                @else
                                    <span class="btn btn-outline-danger btn-sm disabled" title="File not available">
                                        <iconify-icon icon="solar:close-circle-outline" class="icon"></iconify-icon> Not Available
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($materials, 'hasPages') && $materials->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $materials->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="empty-state">
                    <iconify-icon icon="solar:folder-outline" class="icon text-6xl text-secondary-light mb-3"></iconify-icon>
                    <h6 class="mb-2">No Materials Found</h6>
                    @if(isset($currentFilters) && array_filter($currentFilters))
                        <p class="text-secondary-light mb-3">No materials match your current search criteria.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('student.materials.index') }}" class="btn btn-outline-primary">
                                <iconify-icon icon="solar:refresh-outline" class="icon"></iconify-icon>
                                Clear Filters
                            </a>
                            <button type="button" class="btn btn-primary" onclick="document.querySelector('input[name=search]').focus()">
                                <iconify-icon icon="solar:magnifer-outline" class="icon"></iconify-icon>
                                Try Different Search
                            </button>
                        </div>
                    @else
                        <p class="text-secondary-light mb-3">Course materials will appear here when your instructors upload them.</p>
                        <a href="{{ route('student.courses.index') }}" class="btn btn-primary">
                            <iconify-icon icon="solar:book-outline" class="icon"></iconify-icon>
                            Browse Courses
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Custom Styles -->
<style>
/* Fixed Image Dimensions */
.instructor-avatar {
    transition: transform 0.2s ease;
}

.instructor-avatar:hover {
    transform: scale(1.1);
}

/* Material Cards */
.material-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.material-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #3b82f6;
}

.material-icon-container {
    transition: all 0.2s ease;
}

.material-card:hover .material-icon-container {
    transform: scale(1.1);
    background-color: var(--bs-primary-100) !important;
}

/* Line Clamp for Descriptions */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Search Input Enhancement */
.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

/* Button Hover Effects */
.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Empty State */
.empty-state {
    max-width: 400px;
    margin: 0 auto;
}

/* View Toggle Buttons */
.btn-group .btn.active {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

/* Badge Improvements */
.badge {
    font-weight: 500;
    letter-spacing: 0.025em;
}

/* Responsive Design */
@media (max-width: 768px) {
    .instructor-avatar {
        width: 20px !important;
        height: 20px !important;
        min-width: 20px !important;
    }
    
    .material-icon-container {
        width: 48px !important;
        height: 48px !important;
        font-size: 1.5rem !important;
    }
    
    .material-card {
        margin-bottom: 16px;
    }
    
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
    }
    
    .card-body {
        padding: 16px;
    }
}

@media (max-width: 576px) {
    .instructor-avatar {
        width: 18px !important;
        height: 18px !important;
        min-width: 18px !important;
    }
    
    .material-icon-container {
        width: 40px !important;
        height: 40px !important;
        font-size: 1.25rem !important;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 8px !important;
    }
    
    .btn.flex-grow-1 {
        flex-grow: 0 !important;
    }
}

/* Loading States */
.material-item {
    opacity: 0;
    animation: fadeInUp 0.5s ease-out forwards;
}

.material-item:nth-child(1) { animation-delay: 0.1s; }
.material-item:nth-child(2) { animation-delay: 0.2s; }
.material-item:nth-child(3) { animation-delay: 0.3s; }
.material-item:nth-child(4) { animation-delay: 0.4s; }
.material-item:nth-child(5) { animation-delay: 0.5s; }
.material-item:nth-child(6) { animation-delay: 0.6s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Filter Form Enhancements */
.form-label {
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-select:focus,
.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

/* Card Header */
.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e5e7eb;
}

/* Text Break for Long Content */
.text-break {
    word-break: break-word;
    overflow-wrap: break-word;
}

.min-w-0 {
    min-width: 0;
}
</style>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on select change with loading state
    const selectElements = document.querySelectorAll('select[name="course"], select[name="file_type"], select[name="sort_by"], select[name="sort_order"]');
    const form = document.getElementById('materialsFilterForm');
    
    selectElements.forEach(select => {
        select.addEventListener('change', function() {
            // Add loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<iconify-icon icon="solar:loading-outline" class="icon animate-spin"></iconify-icon> Loading...';
            submitBtn.disabled = true;
            
            // Submit form
            form.submit();
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
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<iconify-icon icon="solar:loading-outline" class="icon animate-spin"></iconify-icon> Searching...';
                    submitBtn.disabled = true;
                    
                    form.submit();
                }
            }, 500);
        });
    }

    // View toggle functionality (placeholder for future implementation)
    const gridViewBtn = document.getElementById('gridView');
    const listViewBtn = document.getElementById('listView');
    const materialsContainer = document.getElementById('materialsContainer');

    if (gridViewBtn && listViewBtn) {
        gridViewBtn.addEventListener('click', function() {
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
            materialsContainer.className = 'row';
            // Update material items for grid view
            document.querySelectorAll('.material-item').forEach(item => {
                item.className = 'col-lg-4 col-md-6 mb-24 material-item';
            });
        });

        listViewBtn.addEventListener('click', function() {
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
            materialsContainer.className = 'row';
            // Update material items for list view
            document.querySelectorAll('.material-item').forEach(item => {
                item.className = 'col-12 mb-24 material-item';
            });
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add loading animation class
    const style = document.createElement('style');
    style.textContent = `
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});
</script>
</x-student-layout>