<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Materials</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('instructor.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ph ph-house text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li class="fw-medium">
                    <span class="text-gray-300">/</span>
                </li>
                <li class="fw-medium text-primary-600">Materials</li>
            </ul>
        </div>



        <!-- Filters and Search -->
        <div class="card mb-24">
            <div class="card-body">
                <form method="GET" action="{{ route('instructor.materials.index') }}" class="row gy-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control radius-8" 
                               placeholder="Search materials..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <select name="course_id" class="form-select radius-8">
                            <option value="">All Courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ ($filters['course_id'] ?? '') == $course->id ? 'selected' : '' }}>
                                    {{ $course->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="visibility" class="form-select radius-8">
                            <option value="">All Visibility</option>
                            @if(isset($visibilityOptions))
                                @foreach($visibilityOptions as $key => $value)
                                    <option value="{{ $key }}" {{ ($filters['visibility'] ?? '') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="file_type" class="form-control radius-8" 
                               placeholder="File type..." value="{{ $filters['file_type'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary radius-8 flex-grow-1">
                                <i class="ph ph-magnifying-glass"></i>
                            </button>
                            <a href="{{ route('instructor.materials.index') }}" class="btn btn-gray radius-8">
                                <i class="ph ph-x"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Materials Grid -->
        <div class="row gy-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-gray-100 flex-between flex-wrap gap-8">
                        <h5 class="mb-0">Your Materials ({{ $materials->total() }})</h5>
                        <a href="{{ route('instructor.materials.upload') }}" class="btn btn-primary radius-8 px-20 py-11">
                            <i class="ph ph-plus me-8"></i>
                            Upload Material
                        </a>
                    </div>
                    <div class="card-body">
                        @if($materials->count() > 0)
                            <div class="row gy-4">
                                @foreach($materials as $materialItem)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card border border-gray-100 hover-shadow-lg transition-2" id="material-card-{{ $materialItem->id }}">
                                            <div class="card-body p-16">
                                                <div class="d-flex align-items-start justify-content-between mb-12">
                                                    <div class="d-flex align-items-center gap-8">
                                                        <i class="ph {{ $materialItem->file_icon }} text-32 text-primary"></i>
                                                        <div>
                                                            <span class="badge bg-{{ $materialItem->visibility == 'public' ? 'success' : ($materialItem->visibility == 'enrolled' ? 'warning' : 'secondary') }}-100 text-{{ $materialItem->visibility == 'public' ? 'success' : ($materialItem->visibility == 'enrolled' ? 'warning' : 'secondary') }}-600 text-xs px-8 py-4 radius-4">
                                                                {{ ucfirst($materialItem->visibility) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-gray dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ph ph-dots-three-outline"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <button type="button" class="dropdown-item" onclick="previewFile('{{ $materialItem->id }}', '{{ $materialItem->title }}', '{{ $materialItem->file_url }}', '{{ $materialItem->file_type }}')">
                                                                    <i class="ph ph-eye me-8"></i>
                                                                    Preview
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('instructor.materials.download', $materialItem) }}">
                                                                    <i class="ph ph-download me-8"></i>
                                                                    Download
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('instructor.materials.edit', $materialItem) }}">
                                                                    <i class="ph ph-pencil me-8"></i>
                                                                    Edit
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <button type="button" class="dropdown-item text-danger" onclick="showDeleteModal('{{ $materialItem->id }}', '{{ $materialItem->title }}')">
                                                                    <i class="ph ph-trash me-8"></i>
                                                                    Delete
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                
                                                <h6 class="mb-8 line-clamp-2" title="{{ $materialItem->title }}">{{ $materialItem->title }}</h6>
                                                
                                                <div class="d-flex align-items-center gap-8 mb-12">
                                                    <span class="text-sm text-gray-600">
                                                        <i class="ph ph-book me-4"></i>
                                                        {{ $materialItem->course->code ?? 'N/A' }}
                                                    </span>
                                                    <span class="text-sm text-gray-600">
                                                        <i class="ph ph-file me-4"></i>
                                                        {{ strtoupper($materialItem->file_type ?? 'Unknown') }}
                                                    </span>
                                                </div>
                                                
                                                @if($materialItem->description)
                                                    <p class="text-sm text-gray-600 mb-12 line-clamp-3" title="{{ $materialItem->description }}">{{ $materialItem->description }}</p>
                                                @endif
                                                
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="text-xs text-gray-500">{{ $materialItem->file_size_formatted ?? 'Unknown size' }}</span>
                                                    <span class="text-xs text-gray-500">{{ $materialItem->uploaded_at ? $materialItem->uploaded_at->format('M d, Y') : 'Unknown date' }}</span>
                                                </div>

                                                <!-- Quick Action Buttons -->
                                                <div class="d-flex gap-2 mt-12">
                                                    <button type="button" class="btn btn-outline-primary btn-sm flex-grow-1" onclick="previewFile('{{ $materialItem->id }}', '{{ $materialItem->title }}', '{{ $materialItem->file_url }}', '{{ $materialItem->file_type }}')">
                                                        <i class="ph ph-eye me-4"></i>
                                                        Preview
                                                    </button>
                                                    <a href="{{ route('instructor.materials.edit', $materialItem) }}" class="btn btn-outline-secondary btn-sm">
                                                        <i class="ph ph-pencil"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($materials->hasPages())
                                <div class="d-flex justify-content-center mt-24">
                                    {{ $materials->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="ph ph-folder-open text-64 text-gray-400 mb-16"></i>
                                <h6 class="mb-8">No Materials Found</h6>
                                <p class="text-gray-600 mb-16">
                                    @if(request()->hasAny(['search', 'course_id', 'visibility', 'file_type']))
                                        No materials match your current filters.
                                    @else
                                        You haven't uploaded any materials yet.
                                    @endif
                                </p>
                                <a href="{{ route('instructor.materials.upload') }}" class="btn btn-primary radius-8 px-20 py-11">
                                    <i class="ph ph-plus me-8"></i>
                                    Upload Your First Material
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom border-gray-100">
                    <h5 class="modal-title" id="previewModalLabel">
                        <i class="ph ph-eye me-8"></i>
                        <span id="previewTitle">File Preview</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="previewContent" class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading preview...</p>
                    </div>
                </div>
                <div class="modal-footer border-top border-gray-100">
                    <button type="button" class="btn btn-outline-gray-400 radius-8" data-bs-dismiss="modal">
                        <i class="ph ph-x me-8"></i>
                        Close
                    </button>
                    <a id="downloadBtn" href="#" class="btn btn-primary radius-8">
                        <i class="ph ph-download me-8"></i>
                        Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom border-gray-100">
                    <h5 class="modal-title text-danger" id="deleteModalLabel">
                        <i class="ph ph-warning-circle me-8"></i>
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-3">
                        <i class="ph ph-trash text-64 text-danger mb-16"></i>
                        <h6 class="mb-8">Are you sure you want to delete this material?</h6>
                        <p class="text-gray-600 mb-16">
                            <strong id="materialTitle"></strong><br>
                            This action cannot be undone. The file will be permanently deleted from the server.
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-top border-gray-100">
                    <button type="button" class="btn btn-outline-gray-400 radius-8 px-20 py-11" data-bs-dismiss="modal">
                        <i class="ph ph-x me-8"></i>
                        Cancel
                    </button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger radius-8 px-20 py-11" id="confirmDeleteBtn">
                            <i class="ph ph-trash me-8"></i>
                            <span class="btn-text">Delete Material</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay d-none">
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 mb-0">Processing...</p>
        </div>
    </div>

    <style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .hover-shadow-lg:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .transition-2 {
        transition: all 0.2s ease;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .loading-content {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-item.text-danger:hover {
        background-color: #f8d7da;
        color: #721c24 !important;
    }

    .modal-content {
        border: none;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .alert {
        border: none;
        border-radius: 8px;
    }

    .alert-success {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    #previewContent {
        min-height: 400px;
        max-height: 70vh;
        overflow: auto;
    }

    #previewContent img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    #previewContent iframe {
        width: 100%;
        height: 60vh;
        border: none;
        border-radius: 8px;
    }

    #previewContent video {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .file-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 2rem;
        margin: 1rem;
    }

    .file-info .file-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Handle delete form submission
        const deleteForm = document.getElementById('deleteForm');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const loadingOverlay = document.getElementById('loadingOverlay');

        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                confirmDeleteBtn.disabled = true;
                confirmDeleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
                loadingOverlay.classList.remove('d-none');
            });
        }

        // Handle edit button clicks with loading state
        const editButtons = document.querySelectorAll('a[href*="edit"]');
        editButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                loadingOverlay.classList.remove('d-none');
            });
        });

        // Handle download button clicks
        const downloadButtons = document.querySelectorAll('a[href*="download"]');
        downloadButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="ph ph-spinner me-8"></i>Downloading...';
                this.style.pointerEvents = 'none';
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.pointerEvents = 'auto';
                }, 2000);
            });
        });
    });

    // Show delete modal function
    function showDeleteModal(materialId, materialTitle) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const deleteForm = document.getElementById('deleteForm');
        const materialTitleElement = document.getElementById('materialTitle');
        
        materialTitleElement.textContent = materialTitle;
        deleteForm.action = `/instructor/materials/${materialId}`;
        
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        confirmDeleteBtn.disabled = false;
        confirmDeleteBtn.innerHTML = '<i class="ph ph-trash me-8"></i><span class="btn-text">Delete Material</span>';
        
        modal.show();
    }

    // Preview file function
    function previewFile(materialId, title, fileUrl, fileType) {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        const previewTitle = document.getElementById('previewTitle');
        const previewContent = document.getElementById('previewContent');
        const downloadBtn = document.getElementById('downloadBtn');
        
        // Set title and download link
        previewTitle.textContent = title;
        downloadBtn.href = `/instructor/materials/${materialId}/download`;
        
        // Show loading state
        previewContent.innerHTML = `
            <div class="text-center p-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Loading preview...</p>
            </div>
        `;
        
        modal.show();
        
        // Generate preview content based on file type
        setTimeout(() => {
            generatePreview(fileUrl, fileType, previewContent);
        }, 500);
    }

    function generatePreview(fileUrl, fileType, container) {
        const imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        const videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
        const audioTypes = ['mp3', 'wav', 'ogg', 'aac'];
        const documentTypes = ['pdf'];
        
        fileType = fileType.toLowerCase();
        
        if (imageTypes.includes(fileType)) {
            container.innerHTML = `
                <div class="p-3">
                    <img src="${fileUrl}" alt="Image preview" class="img-fluid" style="max-height: 60vh;">
                </div>
            `;
        } else if (videoTypes.includes(fileType)) {
            container.innerHTML = `
                <div class="p-3">
                    <video controls class="w-100" style="max-height: 60vh;">
                        <source src="${fileUrl}" type="video/${fileType}">
                        Your browser does not support the video tag.
                    </video>
                </div>
            `;
        } else if (audioTypes.includes(fileType)) {
            container.innerHTML = `
                <div class="p-4">
                    <div class="file-info text-center">
                        <i class="ph ph-music-note file-icon text-primary"></i>
                        <h5>Audio File</h5>
                        <audio controls class="w-100 mt-3">
                            <source src="${fileUrl}" type="audio/${fileType}">
                            Your browser does not support the audio tag.
                        </audio>
                    </div>
                </div>
            `;
        } else if (documentTypes.includes(fileType)) {
            container.innerHTML = `
                <div class="p-2">
                    <iframe src="${fileUrl}" style="width: 100%; height: 60vh; border: none;"></iframe>
                </div>
            `;
        } else {
            // For other file types, show file info
            const iconMap = {
                'doc': 'ph-file-doc',
                'docx': 'ph-file-doc',
                'xls': 'ph-file-xls',
                'xlsx': 'ph-file-xls',
                'ppt': 'ph-file-ppt',
                'pptx': 'ph-file-ppt',
                'txt': 'ph-file-text',
                'zip': 'ph-file-zip',
                'rar': 'ph-file-zip'
            };
            
            const icon = iconMap[fileType] || 'ph-file';
            
            container.innerHTML = `
                <div class="p-4">
                    <div class="file-info text-center">
                        <i class="ph ${icon} file-icon text-primary"></i>
                        <h5>File Preview Not Available</h5>
                        <p class="text-muted">This file type cannot be previewed in the browser.</p>
                        <p class="mb-3">File Type: <strong>${fileType.toUpperCase()}</strong></p>
                        <a href="${fileUrl}" target="_blank" class="btn btn-outline-primary">
                            <i class="ph ph-arrow-square-out me-2"></i>
                            Open in New Tab
                        </a>
                    </div>
                </div>
            `;
        }
    }

    // Handle successful operations
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                successAlert.style.animation = 'slideInDown 0.5s ease-out';
            }
        });
    @endif

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
    </script>
</x-instructor-layout>