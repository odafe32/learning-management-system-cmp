<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Edit Material</h6>
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
                <li class="fw-medium">
                    <a href="{{ route('instructor.materials.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        Materials
                    </a>
                </li>
                <li class="fw-medium">
                    <span class="text-gray-300">/</span>
                </li>
                <li class="fw-medium text-primary-600">Edit Material</li>
            </ul>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph ph-check-circle me-8"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph ph-x-circle me-8"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row gy-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-gray-100 flex-between flex-wrap gap-8">
                        <h5 class="mb-0">Edit Material Information</h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-info radius-8 px-20 py-11" onclick="previewCurrentFile()">
                                <i class="ph ph-eye me-8"></i>
                                Preview Current File
                            </button>
                            <a href="{{ route('instructor.materials.index') }}" class="btn btn-outline-primary-600 radius-8 px-20 py-11">
                                <i class="ph ph-arrow-left me-8"></i>
                                Back to Materials
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('instructor.materials.update', $material) }}" method="POST" enctype="multipart/form-data" id="materialForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row gy-4">
                                <!-- Current File Display -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-primary-light">Current File</label>
                                    <div class="current-file-card border border-gray-200 rounded-12 p-20 mb-16">
                                        <div class="d-flex align-items-center gap-16">
                                            <div class="file-icon-wrapper">
                                                <i class="ph {{ $material->file_icon }} text-48 text-primary-600"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-8">{{ $material->title }}</h6>
                                                <div class="d-flex align-items-center gap-16 mb-8">
                                                    <span class="text-sm text-gray-600">
                                                        <i class="ph ph-file me-4"></i>
                                                        {{ strtoupper($material->file_type) }} File
                                                    </span>
                                                    <span class="text-sm text-gray-600">
                                                        <i class="ph ph-hard-drives me-4"></i>
                                                        {{ $material->file_size_formatted }}
                                                    </span>
                                                    <span class="text-sm text-gray-600">
                                                        <i class="ph ph-calendar me-4"></i>
                                                        {{ $material->uploaded_at->format('M d, Y') }}
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center gap-8">
                                                    <span class="badge bg-{{ $material->visibility == 'public' ? 'success' : ($material->visibility == 'enrolled' ? 'warning' : 'secondary') }}-100 text-{{ $material->visibility == 'public' ? 'success' : ($material->visibility == 'enrolled' ? 'warning' : 'secondary') }}-600 text-xs px-8 py-4 radius-4">
                                                        {{ ucfirst($material->visibility) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        Course: {{ $material->course->code ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column gap-2">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="previewCurrentFile()">
                                                    <i class="ph ph-eye me-4"></i>
                                                    Preview
                                                </button>
                                                <a href="{{ route('instructor.materials.download', $material) }}" class="btn btn-secondary btn-sm">
                                                    <i class="ph ph-download me-4"></i>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Replace File (Optional) -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-primary-light">Replace File (Optional)</label>
                                    <div class="upload-area border-2 border-dashed border-gray-200 rounded-12 p-24 text-center" id="uploadArea">
                                        <div class="upload-content">
                                            <i class="ph ph-cloud-arrow-up text-64 text-gray-400 mb-16"></i>
                                            <h6 class="mb-8">Drop new file here or click to upload</h6>
                                            <p class="text-gray-600 text-sm mb-16">
                                                Leave empty to keep current file<br>
                                                Supported formats: pdf, doc, docx, ppt, pptx, xls, xlsx, mp4, avi, mov, mp3, wav, jpg, jpeg, png, gif, zip, rar, txt<br>
                                                Maximum file size: 50MB
                                            </p>
                                            <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.avi,.mov,.mp3,.wav,.jpg,.jpeg,.png,.gif,.zip,.rar,.txt" class="d-none">
                                            <button type="button" class="btn btn-primary radius-8 px-20 py-11" onclick="document.getElementById('file').click()">
                                                <i class="ph ph-plus me-8"></i>
                                                Choose New File
                                            </button>
                                        </div>
                                        <div class="file-preview d-none" id="filePreview">
                                            <div class="d-flex align-items-center gap-12 p-16 bg-primary-50 rounded-8">
                                                <i class="ph ph-file text-32 text-primary-600" id="fileIcon"></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-4 text-primary-600" id="fileName"></h6>
                                                    <p class="text-sm text-primary-600 mb-0" id="fileSize"></p>
                                                </div>
                                                <button type="button" class="btn btn-outline-danger-600 btn-sm" onclick="removeFile()">
                                                    <i class="ph ph-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('file')
                                        <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Course Selection -->
                                <div class="col-md-6">
                                    <label for="course_id" class="form-label fw-semibold text-primary-light">Course <span class="text-danger">*</span></label>
                                    <select class="form-select radius-8 @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                                        <option value="">Select Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ (old('course_id', $material->course_id) == $course->id) ? 'selected' : '' }}>
                                                {{ $course->code }} - {{ $course->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Visibility -->
                                <div class="col-md-6">
                                    <label for="visibility" class="form-label fw-semibold text-primary-light">Visibility <span class="text-danger">*</span></label>
                                    <select class="form-select radius-8 @error('visibility') is-invalid @enderror" id="visibility" name="visibility" required>
                                        @foreach($visibilityOptions as $key => $value)
                                            <option value="{{ $key }}" {{ (old('visibility', $material->visibility) == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('visibility')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Material Title -->
                                <div class="col-12">
                                    <label for="title" class="form-label fw-semibold text-primary-light">Material Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control radius-8 @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $material->title) }}" required 
                                           placeholder="e.g., Introduction to Programming - Lecture 1">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold text-primary-light">Description</label>
                                    <textarea class="form-control radius-8 @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Provide a brief description of the material content...">{{ old('description', $material->description) }}</textarea>
                                    <small class="text-gray-600">Maximum 1000 characters</small>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Form Actions -->
                                <div class="col-12">
                                    <div class="flex-align justify-content-end gap-8">
                                        <a href="{{ route('instructor.materials.index') }}" class="btn btn-outline-gray-400 radius-8 px-20 py-11">
                                            <i class="ph ph-x me-8"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary radius-8 px-20 py-11">
                                            <i class="ph ph-floppy-disk me-8"></i>
                                            Update Material
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                        File Preview: {{ $material->title }}
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
                    <a href="{{ route('instructor.materials.download', $material) }}" class="btn btn-primary radius-8">
                        <i class="ph ph-download me-8"></i>
                        Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
    .current-file-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transition: all 0.3s ease;
    }

    .current-file-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .file-icon-wrapper {
        background: white;
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .upload-area {
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: #007bff;
        background-color: #f8f9ff;
    }

    .upload-area.dragover {
        border-color: #007bff;
        background-color: #e3f2fd;
        transform: scale(1.02);
    }

    .file-preview {
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        const fileInput = document.getElementById('file');
        const uploadArea = document.getElementById('uploadArea');
        const filePreview = document.getElementById('filePreview');
        const uploadContent = uploadArea.querySelector('.upload-content');
        const materialForm = document.getElementById('materialForm');
        const descriptionTextarea = document.getElementById('description');

        // File input change handler
        fileInput.addEventListener('change', function(e) {
            handleFileSelect(e.target.files[0]);
        });

        // Drag and drop handlers
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });

        // Handle file selection
        function handleFileSelect(file) {
            if (!file) return;

            // Validate file size (50MB)
            const maxSize = 50 * 1024 * 1024; // 50MB in bytes
            if (file.size > maxSize) {
                alert('File size must be less than 50MB');
                fileInput.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'mp4', 'avi', 'mov', 'mp3', 'wav', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar', 'txt'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedTypes.includes(fileExtension)) {
                alert('Please select a valid file type: ' + allowedTypes.join(', '));
                fileInput.value = '';
                return;
            }

            // Show file preview
            showFilePreview(file);
        }

        // Show file preview
        function showFilePreview(file) {
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const fileIcon = document.getElementById('fileIcon');

            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);

            // Set appropriate icon
            const extension = file.name.split('.').pop().toLowerCase();
            const iconClass = getFileIcon(extension);
            fileIcon.className = `ph ${iconClass} text-32 text-primary-600`;

            uploadContent.classList.add('d-none');
            filePreview.classList.remove('d-none');
        }

        // Remove file
        window.removeFile = function() {
            fileInput.value = '';
            uploadContent.classList.remove('d-none');
            filePreview.classList.add('d-none');
        };

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Get file icon based on extension
        function getFileIcon(extension) {
            const icons = {
                'pdf': 'ph-file-pdf',
                'doc': 'ph-file-doc',
                'docx': 'ph-file-doc',
                'ppt': 'ph-file-ppt',
                'pptx': 'ph-file-ppt',
                'xls': 'ph-file-xls',
                'xlsx': 'ph-file-xls',
                'mp4': 'ph-file-video',
                'avi': 'ph-file-video',
                'mov': 'ph-file-video',
                'mp3': 'ph-file-audio',
                'wav': 'ph-file-audio',
                'jpg': 'ph-file-image',
                'jpeg': 'ph-file-image',
                'png': 'ph-file-image',
                'gif': 'ph-file-image',
                'zip': 'ph-file-zip',
                'rar': 'ph-file-zip',
                'txt': 'ph-file-text'
            };
            return icons[extension] || 'ph-file';
        }

        // Form submission
        materialForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
            }
        });

        // Character count for description
        if (descriptionTextarea) {
            descriptionTextarea.addEventListener('input', function() {
                const maxLength = 1000;
                const currentLength = this.value.length;
                const remaining = maxLength - currentLength;
                
                let countElement = this.parentNode.querySelector('.char-count');
                if (!countElement) {
                    countElement = document.createElement('small');
                    countElement.className = 'char-count text-gray-600';
                    this.parentNode.appendChild(countElement);
                }
                
                countElement.textContent = `${remaining} characters remaining`;
                
                if (remaining < 0) {
                    countElement.className = 'char-count text-danger';
                    this.classList.add('is-invalid');
                } else {
                    countElement.className = 'char-count text-gray-600';
                    this.classList.remove('is-invalid');
                }
            });
        }
    });

    // Preview current file function
    function previewCurrentFile() {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        const previewContent = document.getElementById('previewContent');
        
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
        
        // Generate preview content
        setTimeout(() => {
            generatePreview('{{ $material->file_url }}', '{{ $material->file_type }}', previewContent);
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
    </script>
</x-instructor-layout>