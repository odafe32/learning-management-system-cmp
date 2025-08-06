<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Upload Material</h6>
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
                <li class="fw-medium text-primary-600">Upload Material</li>
            </ul>
        </div>

        <div class="row gy-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-gray-100 flex-between flex-wrap gap-8">
                        <h5 class="mb-0">Upload New Material</h5>
                        <a href="{{ route('instructor.materials.index') }}" class="btn btn-outline-primary-600 radius-8 px-20 py-11">
                            <i class="ph ph-arrow-left me-8"></i>
                            Back to Materials
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('instructor.materials.store') }}" method="POST" enctype="multipart/form-data" id="materialForm">
                            @csrf
                            
                            <div class="row gy-4">
                                <!-- File Upload -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-primary-light">Material File <span class="text-danger">*</span></label>
                                    <div class="upload-area border-2 border-dashed border-gray-200 rounded-12 p-24 text-center" id="uploadArea">
                                        <div class="upload-content">
                                            <i class="ph ph-cloud-arrow-up text-64 text-gray-400 mb-16"></i>
                                            <h6 class="mb-8">Drop files here or click to upload</h6>
                                            <p class="text-gray-600 text-sm mb-16">
                                                Supported formats: {{ implode(', ', $allowedTypes) }}<br>
                                                Maximum file size: {{ $maxFileSize / 1024 }}MB
                                            </p>
                                            <input type="file" id="file" name="file" accept=".{{ implode(',.',$allowedTypes) }}" class="d-none" required>
                                            <button type="button" class="btn btn-outline-primary-600 radius-8 px-20 py-11" onclick="document.getElementById('file').click()">
                                                <i class="ph ph-plus me-8"></i>
                                                Choose File
                                            </button>
                                        </div>
                                        <div class="file-preview d-none" id="filePreview">
                                            <div class="d-flex align-items-center gap-12 p-16 bg-gray-50 rounded-8">
                                                <i class="ph ph-file text-32 text-primary-600" id="fileIcon"></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-4" id="fileName"></h6>
                                                    <p class="text-sm text-gray-600 mb-0" id="fileSize"></p>
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
                                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
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
                                            <option value="{{ $key }}" {{ old('visibility', 'public') == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                                           id="title" name="title" value="{{ old('title') }}" required 
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
                                              placeholder="Provide a brief description of the material content...">{{ old('description') }}</textarea>
                                    <small class="text-gray-600">Maximum 1000 characters</small>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Form Actions -->
                                <div class="col-12">
                                    <div class="flex-align justify-content-end gap-8">
                                        <a href="{{ route('instructor.materials.index') }}" class="btn btn-outline-gray-400 radius-8 px-20 py-11">Cancel</a>
                                        <button type="submit" class="btn btn-primary radius-8 px-20 py-11">
                                            <i class="ph ph-cloud-arrow-up me-8"></i>
                                            Upload Material
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file');
        const uploadArea = document.getElementById('uploadArea');
        const filePreview = document.getElementById('filePreview');
        const uploadContent = uploadArea.querySelector('.upload-content');
        const materialForm = document.getElementById('materialForm');
        const titleInput = document.getElementById('title');
        const descriptionTextarea = document.getElementById('description');

        // File input change handler
        fileInput.addEventListener('change', function(e) {
            handleFileSelect(e.target.files[0]);
        });

        // Drag and drop handlers
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('border-primary-600', 'bg-primary-50');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-primary-600', 'bg-primary-50');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-primary-600', 'bg-primary-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });

        // Handle file selection
        function handleFileSelect(file) {
            if (!file) return;

            // Validate file size
            const maxSize = {{ $maxFileSize }} * 1024; // Convert KB to bytes
            if (file.size > maxSize) {
                alert(`File size must be less than {{ $maxFileSize / 1024 }}MB`);
                fileInput.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = {!! json_encode($allowedTypes) !!};
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedTypes.includes(fileExtension)) {
                alert('Please select a valid file type: ' + allowedTypes.join(', '));
                fileInput.value = '';
                return;
            }

            // Show file preview
            showFilePreview(file);

            // Auto-fill title if empty
            if (!titleInput.value.trim()) {
                const fileName = file.name.replace(/\.[^/.]+$/, ""); // Remove extension
                titleInput.value = fileName.replace(/[-_]/g, ' '); // Replace dashes and underscores with spaces
            }
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
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
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
    </script>
</x-instructor-layout>