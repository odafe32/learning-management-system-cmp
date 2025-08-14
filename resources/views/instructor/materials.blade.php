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
                <li class="fw-medium text-primary">Materials</li>
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
                                                                <button type="button" class="dropdown-item" onclick="previewFile('{{ $materialItem->id }}', '{{ addslashes($materialItem->title) }}', '{{ $materialItem->file_url }}', '{{ $materialItem->file_type }}')">
                                                                    <i class="ph ph-eye me-8"></i>
                                                                    Preview
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ $materialItem->file_url }}" target="_blank">
                                                                    <i class="ph ph-arrow-square-out me-8"></i>
                                                                    Open in New Tab
                                                                </a>
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
                                                                <button type="button" class="dropdown-item text-danger" onclick="showDeleteModal('{{ $materialItem->id }}', '{{ addslashes($materialItem->title) }}')">
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
                                                    <button type="button" class="btn btn-primary btn-sm flex-grow-1" onclick="previewFile('{{ $materialItem->id }}', '{{ addslashes($materialItem->title) }}', '{{ $materialItem->file_url }}', '{{ $materialItem->file_type }}')">
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
                    <a id="openNewTabBtn" href="#" target="_blank" class="btn btn-primary radius-8">
                        <i class="ph ph-arrow-square-out me-8"></i>
                        Open in New Tab
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
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    #previewContent iframe {
        width: 100%;
        height: 60vh;
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    #previewContent video {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .file-info {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 2rem;
        margin: 1rem;
        border: 1px solid #dee2e6;
    }

    .file-info .file-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .preview-options {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .preview-option {
        flex: 1;
        min-width: 200px;
        max-width: 300px;
    }

    .document-preview-error {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 1rem;
        margin: 1rem;
        color: #856404;
    }
    </style>

 <script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Materials page loaded');
    
    // Debug: Check if materials are loaded
    const materialCards = document.querySelectorAll('[id^="material-card-"]');
    console.log('Found material cards:', materialCards.length);
    
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
    console.log('Delete modal called for:', materialId, materialTitle);
    
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

// Enhanced preview file function with debugging
function previewFile(materialId, title, fileUrl, fileType) {
    console.log('Preview file called:', {
        materialId: materialId,
        title: title,
        fileUrl: fileUrl,
        fileType: fileType
    });
    
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    const previewTitle = document.getElementById('previewTitle');
    const previewContent = document.getElementById('previewContent');
    const downloadBtn = document.getElementById('downloadBtn');
    const openNewTabBtn = document.getElementById('openNewTabBtn');
    
    // Set title and links
    previewTitle.textContent = title;
    downloadBtn.href = `/instructor/materials/${materialId}/download`;
    openNewTabBtn.href = fileUrl;
    
    // Show loading state
    previewContent.innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading preview...</p>
            <small class="text-muted">Material ID: ${materialId}</small>
        </div>
    `;
    
    modal.show();
    
    // Debug: Test file URL accessibility
    console.log('Testing file URL accessibility...');
    
    // First, try to fetch the debug endpoint if in development
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        fetch(`/instructor/materials/${materialId}/debug`)
            .then(response => response.json())
            .then(debugData => {
                console.log('Debug data:', debugData);
                
                // Check if file exists and is accessible
                if (!debugData.file_info.file_exists_in_storage) {
                    console.error('File does not exist in storage');
                    showPreviewError('File not found in storage', debugData);
                    return;
                }
                
                if (!debugData.file_info.file_exists_on_disk) {
                    console.error('File does not exist on disk');
                    showPreviewError('File not found on disk', debugData);
                    return;
                }
                
                if (!debugData.auth.can_access) {
                    console.error('User cannot access this file');
                    showPreviewError('Access denied', debugData);
                    return;
                }
                
                // If all checks pass, generate preview
                setTimeout(() => {
                    generatePreview(fileUrl, fileType, previewContent, title, materialId);
                }, 500);
            })
            .catch(error => {
                console.error('Debug fetch failed:', error);
                // Fallback to normal preview
                setTimeout(() => {
                    generatePreview(fileUrl, fileType, previewContent, title, materialId);
                }, 500);
            });
    } else {
        // Production: Generate preview directly
        setTimeout(() => {
            generatePreview(fileUrl, fileType, previewContent, title, materialId);
        }, 500);
    }
}

function showPreviewError(message, debugData = null) {
    const previewContent = document.getElementById('previewContent');
    
    let debugInfo = '';
    if (debugData && (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1')) {
        debugInfo = `
            <details class="mt-3">
                <summary class="btn btn-sm btn-outline-secondary">Debug Info</summary>
                <pre class="mt-2 p-2 bg-light text-start" style="font-size: 12px; max-height: 200px; overflow-y: auto;">${JSON.stringify(debugData, null, 2)}</pre>
            </details>
        `;
    }
    
    previewContent.innerHTML = `
        <div class="text-center p-4">
            <i class="ph ph-warning-circle text-64 text-danger mb-3"></i>
            <h5 class="text-danger">Preview Error</h5>
            <p class="text-muted">${message}</p>
            ${debugInfo}
            <div class="mt-3">
                <button onclick="retryPreview()" class="btn btn-primary me-2">
                    <i class="ph ph-arrow-clockwise me-2"></i>
                    Retry
                </button>
                <a href="${downloadBtn.href}" class="btn btn-primary">
                    <i class="ph ph-download me-2"></i>
                    Download Instead
                </a>
            </div>
        </div>
    `;
}

function retryPreview() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('previewModal'));
    const materialId = downloadBtn.href.split('/').slice(-2, -1)[0];
    const title = previewTitle.textContent;
    const fileUrl = openNewTabBtn.href;
    const fileType = 'unknown'; // We'll detect this in generatePreview
    
    modal.hide();
    setTimeout(() => {
        previewFile(materialId, title, fileUrl, fileType);
    }, 300);
}

function generatePreview(fileUrl, fileType, container, title, materialId) {
    console.log('Generating preview for:', {
        fileUrl: fileUrl,
        fileType: fileType,
        title: title,
        materialId: materialId
    });
    
    const imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    const videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
    const audioTypes = ['mp3', 'wav', 'ogg', 'aac'];
    const documentTypes = ['pdf'];
    const officeTypes = ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];
    
    fileType = fileType.toLowerCase();
    
    // Test file accessibility first
    testFileAccessibility(fileUrl)
        .then(isAccessible => {
            if (!isAccessible) {
                throw new Error('File is not accessible');
            }
            
            if (imageTypes.includes(fileType)) {
                generateImagePreview(fileUrl, container, title);
            } else if (videoTypes.includes(fileType)) {
                generateVideoPreview(fileUrl, container, title, fileType);
            } else if (audioTypes.includes(fileType)) {
                generateAudioPreview(fileUrl, container, title, fileType);
            } else if (documentTypes.includes(fileType)) {
                generatePdfPreview(fileUrl, container, title);
            } else if (officeTypes.includes(fileType)) {
                generateOfficePreview(fileUrl, container, title, fileType);
            } else {
                generateGenericPreview(fileUrl, container, title, fileType);
            }
        })
        .catch(error => {
            console.error('Preview generation failed:', error);
            showPreviewError(`Failed to load file: ${error.message}`);
        });
}

function testFileAccessibility(fileUrl) {
    return new Promise((resolve) => {
        const img = new Image();
        const timeout = setTimeout(() => {
            resolve(false);
        }, 5000);
        
        img.onload = () => {
            clearTimeout(timeout);
            resolve(true);
        };
        
        img.onerror = () => {
            clearTimeout(timeout);
            // Try with fetch as fallback
            fetch(fileUrl, { method: 'HEAD' })
                .then(response => resolve(response.ok))
                .catch(() => resolve(false));
        };
        
        img.src = fileUrl;
    });
}

function generateImagePreview(fileUrl, container, title) {
    container.innerHTML = `
        <div class="p-3">
            <img src="${fileUrl}" alt="Image preview" class="img-fluid" style="max-height: 60vh;" 
                 onload="console.log('Image loaded successfully')"
                 onerror="console.error('Image failed to load'); this.style.display='none'; this.nextElementSibling.style.display='block';">
            <div class="file-info text-center" style="display: none;">
                <i class="ph ph-image file-icon text-primary"></i>
                <h5>Image Preview Failed</h5>
                <p class="text-muted">Unable to load image preview.</p>
                <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                    <i class="ph ph-arrow-square-out me-2"></i>
                    Open in New Tab
                </a>
            </div>
        </div>
    `;
}

function generateVideoPreview(fileUrl, container, title, fileType) {
    container.innerHTML = `
        <div class="p-3">
            <video controls class="w-100" style="max-height: 60vh;" preload="metadata">
                <source src="${fileUrl}" type="video/${fileType}">
                <div class="file-info text-center">
                    <i class="ph ph-video file-icon text-primary"></i>
                    <h5>Video Preview Not Supported</h5>
                    <p class="text-muted">Your browser doesn't support this video format.</p>
                    <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                        <i class="ph ph-arrow-square-out me-2"></i>
                        Open in New Tab
                    </a>
                </div>
            </video>
        </div>
    `;
}

function generateAudioPreview(fileUrl, container, title, fileType) {
    container.innerHTML = `
        <div class="p-4">
            <div class="file-info text-center">
                <i class="ph ph-music-note file-icon text-primary"></i>
                <h5>${title}</h5>
                <p class="text-muted mb-3">Audio File - ${fileType.toUpperCase()}</p>
                <audio controls class="w-100 mt-3" style="max-width: 500px;" preload="metadata">
                    <source src="${fileUrl}" type="audio/${fileType}">
                    Your browser does not support the audio tag.
                </audio>
            </div>
        </div>
    `;
}

function generatePdfPreview(fileUrl, container, title) {
    container.innerHTML = `
        <div class="p-2">
            <iframe src="${fileUrl}#toolbar=0&navpanes=0&scrollbar=0" 
                    style="width: 100%; height: 60vh; border: none; border-radius: 8px;"
                    onload="console.log('PDF loaded successfully')"
                    onerror="console.error('PDF failed to load'); showPdfError()">
            </iframe>
            <div id="pdfError" class="document-preview-error text-center" style="display: none;">
                <i class="ph ph-warning-circle text-24 mb-2"></i>
                <h6>PDF Preview Not Available</h6>
                <p class="mb-3">Your browser settings may be blocking PDF preview.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                        <i class="ph ph-arrow-square-out me-2"></i>
                        Open PDF in New Tab
                    </a>
                    <a href="https://docs.google.com/viewer?url=${encodeURIComponent(fileUrl)}" target="_blank" class="btn btn-primary">
                        <i class="ph ph-google-logo me-2"></i>
                        View with Google
                    </a>
                </div>
            </div>
        </div>
    `;
}

function generateOfficePreview(fileUrl, container, title, fileType) {
    const googleViewerUrl = `https://docs.google.com/viewer?url=${encodeURIComponent(fileUrl)}&embedded=true`;
    
    container.innerHTML = `
        <div class="p-3">
            <div class="file-info text-center">
                <i class="ph ${getOfficeIcon(fileType)} file-icon text-primary"></i>
                <h5>${title}</h5>
                <p class="text-muted mb-3">${fileType.toUpperCase()} Document</p>
                <iframe src="${googleViewerUrl}" 
                        style="width: 100%; height: 60vh; border: none; border-radius: 8px;"
                        onload="console.log('Office document loaded')"
                        onerror="console.error('Office document failed to load'); this.style.display='none'; this.nextElementSibling.style.display='block';">
                </iframe>
                <div class="document-preview-error" style="display: none;">
                    <p>Document preview failed to load</p>
                    <div class="mt-3">
                        <a href="${fileUrl}" target="_blank" class="btn btn-primary me-2">
                            <i class="ph ph-arrow-square-out me-2"></i>
                            Open in New Tab
                        </a>
                        <a href="${googleViewerUrl}" target="_blank" class="btn btn-primary">
                            <i class="ph ph-google-logo me-2"></i>
                            Google Viewer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function generateGenericPreview(fileUrl, container, title, fileType) {
    const iconMap = {
        'txt': 'ph-file-text',
        'zip': 'ph-file-zip',
        'rar': 'ph-file-zip',
        '7z': 'ph-file-zip'
    };
    
    const icon = iconMap[fileType] || 'ph-file';
    
    container.innerHTML = `
        <div class="p-4">
            <div class="file-info text-center">
                <i class="ph ${icon} file-icon text-primary"></i>
                <h5>${title}</h5>
                <p class="text-muted">File Type: <strong>${fileType.toUpperCase()}</strong></p>
                <p class="mb-3">This file type cannot be previewed in the browser.</p>
                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                        <i class="ph ph-arrow-square-out me-2"></i>
                        Open in New Tab
                    </a>
                    <a href="${fileUrl}" download class="btn btn-primary">
                        <i class="ph ph-download me-2"></i>
                        Download File
                    </a>
                </div>
            </div>
        </div>
    `;
}

function getOfficeIcon(fileType) {
    const icons = {
        'doc': 'ph-file-doc',
        'docx': 'ph-file-doc',
        'xls': 'ph-file-xls',
        'xlsx': 'ph-file-xls',
        'ppt': 'ph-file-ppt',
        'pptx': 'ph-file-ppt'
    };
    return icons[fileType] || 'ph-file-doc';
}

function showPdfError() {
    const pdfError = document.getElementById('pdfError');
    if (pdfError) {
        pdfError.style.display = 'block';
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
</script>

</x-instructor-layout>