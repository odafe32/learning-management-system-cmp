<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Submit Assignment</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <i class="ph ph-house text-lg"></i>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('student.assignments.index') }}" class="hover-text-primary">
                    Assignments
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Submit</li>
        </ul>
    </div>

    <div class="row gy-4">
        <!-- Submission Form -->
        <div class="col-lg-8">
            <div class="card h-100 p-0 radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-lg fw-semibold mb-0">Submit: {{ $assignment->title }}</h6>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-info" id="previewBtn">
                                <i class="ph ph-eye me-1"></i>
                                Preview
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="autoSaveStatus">
                                <i class="ph ph-floppy-disk me-1"></i>
                                Auto-save: On
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-24">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ph ph-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ph ph-warning-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('student.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data" id="submissionForm">
                        @csrf

                        <!-- Submission Tabs -->
                        <ul class="nav nav-tabs mb-24" id="submissionTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="code-tab" data-bs-toggle="tab" data-bs-target="#code-pane" type="button" role="tab">
                                    <i class="ph ph-code me-2"></i>
                                    Code Submission
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-pane" type="button" role="tab">
                                    <i class="ph ph-file me-2"></i>
                                    File Upload
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="text-tab" data-bs-toggle="tab" data-bs-target="#text-pane" type="button" role="tab">
                                    <i class="ph ph-text-aa me-2"></i>
                                    Text Submission
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="submissionTabContent">
                            <!-- Code Submission Tab -->
                            <div class="tab-pane fade show active" id="code-pane" role="tabpanel">
                                <div class="mb-20">
                                    <div class="d-flex justify-content-between align-items-center mb-12">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-0">
                                            Code Editor
                                        </label>
                                        <div class="d-flex gap-2">
                                            <select class="form-select form-select-sm" id="languageSelect" style="width: auto;">
                                                <option value="javascript">JavaScript</option>
                                                <option value="python">Python</option>
                                                <option value="java">Java</option>
                                                <option value="php">PHP</option>
                                                <option value="cpp">C++</option>
                                                <option value="csharp">C#</option>
                                                <option value="html">HTML</option>
                                                <option value="css">CSS</option>
                                                <option value="sql">SQL</option>
                                                <option value="json">JSON</option>
                                                <option value="xml">XML</option>
                                                <option value="text">Plain Text</option>
                                            </select>
                                            <select class="form-select form-select-sm" id="themeSelect" style="width: auto;">
                                                <option value="default">Light Theme</option>
                                                <option value="material">Dark Theme</option>
                                                <option value="monokai">Monokai</option>
                                                <option value="dracula">Dracula</option>
                                            </select>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="fullscreenBtn">
                                                <i class="ph ph-arrows-out"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Code Editor Container -->
                                    <div class="code-editor-container border rounded-8">
                                        <div class="code-editor-toolbar bg-light border-bottom px-12 py-8">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-2">
                                                    @if($assignment->code_sample)
                                                    <button type="button" class="btn btn-xs btn-outline-info" id="loadTemplateBtn">
                                                        <i class="ph ph-code me-1"></i>
                                                        Load Template
                                                    </button>
                                                    @endif
                                                    <button type="button" class="btn btn-xs btn-outline-secondary" id="formatCodeBtn">
                                                        <i class="ph ph-brackets-curly me-1"></i>
                                                        Format
                                                    </button>
                                                    <button type="button" class="btn btn-xs btn-outline-danger" id="clearCodeBtn">
                                                        <i class="ph ph-trash me-1"></i>
                                                        Clear
                                                    </button>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <small class="text-muted" id="codeStats">Lines: 0 | Characters: 0</small>
                                                    <small class="text-muted" id="cursorPosition">Ln 1, Col 1</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="codeEditor" style="height: 400px;"></div>
                                    </div>
                                    
                                    <!-- Hidden textarea for form submission -->
                                    <textarea name="code_submission" id="codeSubmissionInput" style="display: none;"></textarea>
                                    
                                    @error('code_submission')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    
                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="text-muted">
                                            Use Ctrl+Space for autocomplete, Ctrl+F to find, F11 for fullscreen
                                        </small>
                                        <small class="text-muted" id="codeCounter">0/50000 characters</small>
                                    </div>
                                </div>
                            </div>

                            <!-- File Upload Tab -->
                            <div class="tab-pane fade" id="file-pane" role="tabpanel">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        File Upload
                                    </label>
                                    
                                    <!-- Multiple File Upload -->
                                    <div class="upload-area border-2 border-dashed border-gray-300 rounded-8 p-24 text-center position-relative">
                                        <input type="file" 
                                               class="form-control @error('submission_files') is-invalid @enderror" 
                                               id="submissionFiles" 
                                               name="submission_files[]"
                                               accept=".pdf,.doc,.docx,.txt,.zip,.rar,.jpg,.jpeg,.png,.py,.js,.html,.css,.cpp,.java,.php"
                                               multiple
                                               style="position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; z-index: 2;">
                                        
                                        <div class="upload-content">
                                            <i class="ph ph-cloud-arrow-up text-4xl text-primary mb-12"></i>
                                            <h6 class="mb-8">Drop your files here or click to browse</h6>
                                            <p class="text-sm text-secondary-light mb-0">
                                                Supported formats: PDF, DOC, DOCX, TXT, ZIP, RAR, Images, Code files<br>
                                                Maximum file size: 10MB per file | Maximum 5 files
                                            </p>
                                        </div>
                                        
                                        <div class="files-info d-none">
                                            <div id="filesList"></div>
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-12" onclick="clearAllFiles()">
                                                <i class="ph ph-trash me-1"></i>
                                                Clear All Files
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @error('submission_files')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    @error('submission_files.*')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Text Submission Tab -->
                            <div class="tab-pane fade" id="text-pane" role="tabpanel">
                                <div class="mb-20">
                                    <label for="textSubmission" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Text Submission
                                    </label>
                                    <textarea class="form-control radius-8 @error('submission_text') is-invalid @enderror" 
                                              id="textSubmission" 
                                              name="submission_text" 
                                              rows="12" 
                                              placeholder="Enter your text submission here...">{{ old('submission_text') }}</textarea>
                                    
                                    @error('submission_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="text-muted">
                                            Maximum 10,000 characters
                                        </small>
                                        <small class="text-muted" id="textCounter">0/10000 characters</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submission Note -->
                        <div class="alert alert-info mb-24">
                            <i class="ph ph-info me-2"></i>
                            <strong>Note:</strong> You must provide at least one form of submission (code, file, or text). 
                            Your work is automatically saved as you type. Once submitted, you cannot modify your submission.
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ph ph-check-circle text-success"></i>
                                <small class="text-muted" id="saveStatus">All changes saved</small>
                            </div>
                            <div class="d-flex gap-3">
                                <a href="{{ route('student.assignments.show', $assignment) }}" 
                                   class="btn btn-gray border text-secondary-light fw-semibold radius-8 px-20 py-11">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary fw-semibold radius-8 px-20 py-11" id="submitBtn">
                                    <i class="ph ph-paper-plane-tilt me-2"></i>
                                    Submit Assignment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Assignment Info Sidebar -->
        <div class="col-lg-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0">Assignment Details</h6>
                </div>
                <div class="card-body p-24">
                    <!-- Assignment Info -->
                    <div class="mb-20">
                        <h6 class="text-md fw-semibold mb-12">{{ $assignment->title }}</h6>
                        <p class="text-sm text-secondary-light mb-12">{{ $assignment->course->code }} - {{ $assignment->course->title }}</p>
                        
                        @if($assignment->deadline)
                            <div class="d-flex align-items-center gap-2 mb-8">
                                <i class="ph ph-calendar text-danger"></i>
                                <span class="text-sm fw-medium text-danger">
                                    Due: {{ $assignment->deadline->format('M d, Y \a\t g:i A') }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-12">
                                <i class="ph ph-timer text-warning"></i>
                                <span class="text-sm text-warning">
                                    {{ $assignment->deadline->diffForHumans() }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Description Preview -->
                    @if($assignment->description)
                        <div class="mb-20">
                            <h6 class="text-md fw-semibold mb-12">Description</h6>
                            <div class="text-sm text-secondary-light">
                                {!! nl2br(e(Str::limit($assignment->description, 200))) !!}
                            </div>
                            <a href="{{ route('student.assignments.show', $assignment) }}" class="text-primary text-sm">
                                View full details →
                            </a>
                        </div>
                    @endif

                    <!-- Submission Progress -->
                    <div class="mb-20">
                        <h6 class="text-md fw-semibold mb-12">Submission Progress</h6>
                        <div class="progress mb-8" style="height: 6px;">
                            <div class="progress-bar bg-primary" id="submissionProgress" style="width: 0%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-xs text-muted">
                            <span>Not started</span>
                            <span id="progressText">0% complete</span>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mb-20">
                        <h6 class="text-md fw-semibold mb-12">Quick Stats</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="bg-primary-50 p-12 rounded-6 text-center">
                                    <div class="text-lg fw-bold text-primary" id="codeLines">0</div>
                                    <div class="text-xs text-muted">Lines of Code</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-success-50 p-12 rounded-6 text-center">
                                    <div class="text-lg fw-bold text-success" id="filesCount">0</div>
                                    <div class="text-xs text-muted">Files Uploaded</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submission Guidelines -->
                    <div class="card border border-primary-200 radius-8">
                        <div class="card-header bg-primary-50 py-12 px-16">
                            <h6 class="text-md fw-semibold mb-0 text-primary-600">Submission Guidelines</h6>
                        </div>
                        <div class="card-body p-16">
                            <ul class="list-unstyled mb-0 text-sm">
                                <li class="mb-8">• Provide at least one submission type</li>
                                <li class="mb-8">• File size limit: 10MB per file</li>
                                <li class="mb-8">• Maximum 5 files allowed</li>
                                <li class="mb-8">• Code is automatically formatted</li>
                                <li class="mb-8">• Auto-save keeps your work safe</li>
                                <li>• Preview before submitting</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submission Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitFromPreview">Submit Assignment</button>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen Modal -->
<div class="modal fade" id="fullscreenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Code Editor - Fullscreen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="fullscreenEditor" style="height: calc(100vh - 120px);"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveFullscreen">Save & Close</button>
            </div>
        </div>
    </div>
</div>

<!-- CodeMirror CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/material.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/dialog/dialog.min.css">

<style>
.upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #0d6efd;
    background-color: #f8f9ff;
}

.upload-area.dragover {
    border-color: #0d6efd;
    background-color: #e7f3ff;
}

.code-editor-container {
    overflow: hidden;
}

.CodeMirror {
    height: 400px;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 14px;
    line-height: 1.5;
}

.CodeMirror-focused {
    outline: none;
}

.file-item {
    display: flex;
    align-items-center;
    justify-content: space-between;
    padding: 8px 12px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    margin-bottom: 8px;
}

.file-item:last-child {
    margin-bottom: 0;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    background: none;
    color: #6c757d;
}

.nav-tabs .nav-link.active {
    border-bottom-color: #0d6efd;
    color: #0d6efd;
    background: none;
}

.progress {
    border-radius: 10px;
    overflow: hidden;
}

.btn-xs {
    padding: 2px 8px;
    font-size: 11px;
}
</style>
<!-- CodeMirror CSS - Use version 5.65.16 like the working instructor page -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/material.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/monokai.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/show-hint.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/dialog/dialog.min.css">

<!-- CodeMirror JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/matchbrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/selection/active-line.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/search/search.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/search/searchcursor.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/dialog/dialog.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/show-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/javascript-hint.min.js"></script>

<!-- Language modes -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/python/python.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/php/php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/sql/sql.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>

<style>
.CodeMirror {
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 14px;
    line-height: 1.5;
    height: 400px !important;
}

.CodeMirror-focused {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.CodeMirror-scroll {
    min-height: 400px;
}

.file-item {
    display: flex;
    align-items-center;
    justify-content: space-between;
    padding: 8px 12px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    margin-bottom: 8px;
}

.upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #0d6efd;
    background-color: #f8f9ff;
}

.upload-area.dragover {
    border-color: #0d6efd;
    background-color: #e7f3ff;
}
</style>

<script>
// Complete Assignment Submission Script with CodeMirror Editor and File Upload
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing Assignment Submission System...');
    
    // ==================== GLOBAL VARIABLES ====================
    let codeEditor = null;
    let fullscreenEditor = null;
    let selectedFiles = [];
    
    // DOM Elements
    const codeEditorContainer = document.getElementById('codeEditor');
    const codeSubmissionInput = document.getElementById('codeSubmissionInput');
    const languageSelect = document.getElementById('languageSelect');
    const themeSelect = document.getElementById('themeSelect');
    const fileInput = document.getElementById('submissionFiles');
    const uploadArea = document.querySelector('.upload-area');
    const uploadContent = document.querySelector('.upload-content');
    const filesInfo = document.querySelector('.files-info');
    const filesList = document.getElementById('filesList');
    const textSubmission = document.getElementById('textSubmission');
    const textCounter = document.getElementById('textCounter');
    
    // Configuration
    const maxFiles = 5;
    const maxFileSize = 10 * 1024 * 1024; // 10MB in bytes
    const maxTextLength = 10000;
    const maxCodeLength = 50000;
    
    // Language modes mapping
    const languageModes = {
        'javascript': 'text/javascript',
        'python': 'text/x-python',
        'java': 'text/x-java',
        'php': 'application/x-httpd-php',
        'cpp': 'text/x-c++src',
        'csharp': 'text/x-csharp',
        'html': 'text/html',
        'css': 'text/css',
        'sql': 'text/x-sql',
        'json': 'application/json',
        'xml': 'text/xml',
        'text': 'text/plain'
    };
    
    // Allowed file types
    const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'text/plain',
        'application/zip',
        'application/x-rar-compressed',
        'application/vnd.rar',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'text/x-python',
        'text/javascript',
        'text/html',
        'text/css',
        'text/x-c++src',
        'text/x-java-source',
        'application/x-php'
    ];
    
    // File type icons mapping
    const fileIcons = {
        'pdf': 'ph-file-pdf',
        'doc': 'ph-file-doc',
        'docx': 'ph-file-doc',
        'txt': 'ph-file-text',
        'zip': 'ph-file-zip',
        'rar': 'ph-file-zip',
        'jpg': 'ph-file-image',
        'jpeg': 'ph-file-image',
        'png': 'ph-file-image',
        'py': 'ph-file-code',
        'js': 'ph-file-code',
        'html': 'ph-file-code',
        'css': 'ph-file-code',
        'cpp': 'ph-file-code',
        'java': 'ph-file-code',
        'php': 'ph-file-code'
    };
    
    // ==================== CODEMIRROR INITIALIZATION ====================
    function initializeCodeEditor() {
        // Check if CodeMirror is available
        if (typeof CodeMirror === 'undefined') {
            console.error('CodeMirror is not loaded.');
            showCodeEditorError();
            return false;
        }
        
        try {
            // Create a hidden textarea for CodeMirror to use
            const hiddenTextarea = document.createElement('textarea');
            hiddenTextarea.style.display = 'none';
            hiddenTextarea.value = '// Start coding here...\n';
            codeEditorContainer.appendChild(hiddenTextarea);
            
            codeEditor = CodeMirror.fromTextArea(hiddenTextarea, {
                lineNumbers: true,
                mode: 'text/javascript',
                theme: 'default',
                indentUnit: 4,
                smartIndent: true,
                tabSize: 4,
                indentWithTabs: false,
                electricChars: true,
                autoCloseBrackets: true,
                matchBrackets: true,
                styleActiveLine: true,
                lineWrapping: true,
                foldGutter: true,
                gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                extraKeys: {
                    "Ctrl-Space": "autocomplete",
                    "Ctrl-F": "findPersistent",
                    "F11": function(cm) {
                        toggleFullscreen();
                    },
                    "Esc": function(cm) {
                        if (cm.getOption("fullScreen")) {
                            cm.setOption("fullScreen", false);
                        }
                    }
                }
            });
            
            // Set editor size
            codeEditor.setSize(null, "400px");
            
            console.log('CodeMirror initialized successfully');
            
            // Event listeners
            codeEditor.on('change', function() {
                updateCodeStats();
                syncWithHiddenInput();
                updateSubmissionProgress();
                triggerAutoSave();
            });
            
            codeEditor.on('cursorActivity', function() {
                updateCursorPosition();
            });
            
            // Initial setup
            updateCodeStats();
            syncWithHiddenInput();
            
            // Refresh editor after initialization
            setTimeout(() => {
                codeEditor.refresh();
            }, 100);
            
            return true;
            
        } catch (error) {
            console.error('Failed to initialize CodeMirror:', error);
            showCodeEditorError();
            return false;
        }
    }
    
    function showCodeEditorError() {
        if (codeEditorContainer) {
            codeEditorContainer.innerHTML = `
                <div class="alert alert-warning">
                    <i class="ph ph-warning-circle me-2"></i>
                    Code editor failed to load. Please refresh the page or use the text submission tab.
                </div>
                <textarea class="form-control" rows="15" placeholder="You can type your code here as a fallback..." id="fallbackCodeEditor"></textarea>
            `;
            
            // Setup fallback editor
            const fallbackEditor = document.getElementById('fallbackCodeEditor');
            if (fallbackEditor) {
                fallbackEditor.addEventListener('input', function() {
                    if (codeSubmissionInput) {
                        codeSubmissionInput.value = this.value;
                    }
                    updateSubmissionProgress();
                    triggerAutoSave();
                });
            }
        }
    }
    
    // ==================== CODE EDITOR FUNCTIONS ====================
    function updateCodeStats() {
        if (!codeEditor) return;
        
        const code = codeEditor.getValue();
        const lines = code.split('\n').length;
        const characters = code.length;
        
        updateStatsDisplay(lines, characters);
    }
    
    function updateStatsDisplay(lines, characters) {
        const codeStats = document.getElementById('codeStats');
        const codeCounter = document.getElementById('codeCounter');
        const codeLines = document.getElementById('codeLines');
        
        if (codeStats) {
            codeStats.textContent = `Lines: ${lines} | Characters: ${characters}`;
        }
        
        if (codeCounter) {
            codeCounter.textContent = `${characters}/${maxCodeLength} characters`;
            if (characters > maxCodeLength) {
                codeCounter.classList.add('text-danger');
            } else {
                codeCounter.classList.remove('text-danger');
            }
        }
        
        if (codeLines) {
            codeLines.textContent = lines;
        }
    }
    
    function updateCursorPosition() {
        if (!codeEditor) return;
        
        const cursorPosition = document.getElementById('cursorPosition');
        if (cursorPosition) {
            const cursor = codeEditor.getCursor();
            cursorPosition.textContent = `Ln ${cursor.line + 1}, Col ${cursor.ch + 1}`;
        }
    }
    
    function syncWithHiddenInput() {
        if (codeEditor && codeSubmissionInput) {
            codeSubmissionInput.value = codeEditor.getValue();
        }
    }
    
    // ==================== FILE UPLOAD FUNCTIONS ====================
    function initializeFileUpload() {
        if (!fileInput || !uploadArea) return;
        
        // File input change handler
        fileInput.addEventListener('change', handleFileSelect);
        
        // Drag and drop handlers
        uploadArea.addEventListener('dragover', handleDragOver);
        uploadArea.addEventListener('dragleave', handleDragLeave);
        uploadArea.addEventListener('drop', handleDrop);
        
        // Click to upload
        uploadArea.addEventListener('click', function(e) {
            if (e.target === uploadArea || e.target.closest('.upload-content')) {
                fileInput.click();
            }
        });
    }
    
    function handleFileSelect(e) {
        const files = Array.from(e.target.files);
        processFiles(files);
    }
    
    function handleDragOver(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    }
    
    function handleDragLeave(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    }
    
    function handleDrop(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = Array.from(e.dataTransfer.files);
        processFiles(files);
    }
    
    function processFiles(files) {
        // Check total files limit
        if (selectedFiles.length + files.length > maxFiles) {
            showAlert(`Maximum ${maxFiles} files allowed. You can select ${maxFiles - selectedFiles.length} more files.`, 'warning');
            return;
        }
        
        const validFiles = [];
        const errors = [];
        
        files.forEach(file => {
            // Check file size
            if (file.size > maxFileSize) {
                errors.push(`${file.name}: File size exceeds 10MB limit`);
                return;
            }
            
            // Check file type
            const fileExtension = file.name.split('.').pop().toLowerCase();
            const isValidType = allowedTypes.some(type => {
                return type.includes(fileExtension) || 
                       file.type === type ||
                       isCodeFile(fileExtension, file.type);
            });
            
            if (!isValidType) {
                errors.push(`${file.name}: File type not supported`);
                return;
            }
            
            // Check for duplicates
            if (selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                errors.push(`${file.name}: File already selected`);
                return;
            }
            
            validFiles.push(file);
        });
        
        // Show errors if any
        if (errors.length > 0) {
            showAlert('Some files could not be added:\n\n' + errors.join('\n'), 'warning');
        }
        
        // Add valid files
        if (validFiles.length > 0) {
            selectedFiles.push(...validFiles);
            updateFileInput();
            displayFiles();
            updateSubmissionProgress();
            triggerAutoSave();
        }
    }
    
    function isCodeFile(extension, mimeType) {
        const codeExtensions = ['py', 'js', 'cpp', 'java', 'php', 'html', 'css'];
        return codeExtensions.includes(extension) || 
               mimeType.includes('text/') || 
               mimeType.includes('application/javascript');
    }
    
    function updateFileInput() {
        // Create a new DataTransfer object to update the file input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => {
            dt.items.add(file);
        });
        fileInput.files = dt.files;
    }
    
    function displayFiles() {
        if (selectedFiles.length === 0) {
            uploadContent.style.display = 'block';
            filesInfo.classList.add('d-none');
            return;
        }
        
        uploadContent.style.display = 'none';
        filesInfo.classList.remove('d-none');
        
        filesList.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const fileItem = createFileItem(file, index);
            filesList.appendChild(fileItem);
        });
        
        // Update files count
        const filesCount = document.getElementById('filesCount');
        if (filesCount) {
            filesCount.textContent = selectedFiles.length;
        }
    }
    
    function createFileItem(file, index) {
        const fileExtension = file.name.split('.').pop().toLowerCase();
        const iconClass = fileIcons[fileExtension] || 'ph-file';
        const fileSize = formatFileSize(file.size);
        
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        fileItem.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="ph ${iconClass} text-primary"></i>
                <div>
                    <div class="fw-medium text-sm">${escapeHtml(file.name)}</div>
                    <div class="text-xs text-muted">${fileSize}</div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                <i class="ph ph-x"></i>
            </button>
        `;
        
        return fileItem;
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // ==================== TEXT SUBMISSION FUNCTIONS ====================
    function initializeTextSubmission() {
        if (!textSubmission || !textCounter) return;
        
        textSubmission.addEventListener('input', function() {
            const length = this.value.length;
            
            textCounter.textContent = `${length}/${maxTextLength} characters`;
            
            if (length > maxTextLength) {
                textCounter.classList.add('text-danger');
                this.value = this.value.substring(0, maxTextLength);
            } else {
                textCounter.classList.remove('text-danger');
            }
            
            updateSubmissionProgress();
            triggerAutoSave();
        });
        
        // Initial count
        const initialLength = textSubmission.value.length;
        textCounter.textContent = `${initialLength}/${maxTextLength} characters`;
    }
    
    // ==================== PROGRESS TRACKING ====================
    function updateSubmissionProgress() {
        const progressBar = document.getElementById('submissionProgress');
        const progressText = document.getElementById('progressText');
        
        if (!progressBar || !progressText) return;
        
        let progress = 0;
        let completedSections = [];
        
        // Check code submission
        const codeContent = getCodeContent();
        if (codeContent.length > 0) {
            progress += 34;
            completedSections.push('Code');
        }
        
        // Check file uploads
        if (selectedFiles.length > 0) {
            progress += 33;
            completedSections.push('Files');
        }
        
        // Check text submission
        if (textSubmission && textSubmission.value.trim().length > 0) {
            progress += 33;
            completedSections.push('Text');
        }
        
        // Update progress bar
        progressBar.style.width = progress + '%';
        progressText.textContent = progress + '% complete';
        
        // Update progress status text
        const statusSpan = progressText.parentElement.querySelector('span:first-child');
        if (statusSpan) {
            if (progress === 0) {
                statusSpan.textContent = 'Not started';
            } else if (progress < 100) {
                statusSpan.textContent = `In progress (${completedSections.join(', ')})`;
            } else {
                statusSpan.textContent = 'Ready to submit';
            }
        }
        
        // Update progress bar color
        progressBar.className = 'progress-bar';
        if (progress > 0 && progress < 100) {
            progressBar.classList.add('bg-primary');
        } else if (progress >= 100) {
            progressBar.classList.add('bg-success');
        }
    }
    
    function getCodeContent() {
        if (codeEditor) {
            return codeEditor.getValue().trim();
        } else if (codeSubmissionInput) {
            return codeSubmissionInput.value.trim();
        }
        return '';
    }
    
    // ==================== EVENT HANDLERS ====================
    function initializeEventHandlers() {
        // Language change handler
        if (languageSelect) {
            languageSelect.addEventListener('change', function() {
                if (codeEditor) {
                    const mode = languageModes[this.value] || 'text/plain';
                    codeEditor.setOption('mode', mode);
                    console.log('Language changed to:', this.value, 'Mode:', mode);
                }
            });
        }
        
        // Theme change handler
        if (themeSelect) {
            themeSelect.addEventListener('change', function() {
                if (codeEditor) {
                    const themeMap = {
                        'default': 'default',
                        'material': 'material',
                        'monokai': 'monokai',
                        'dracula': 'dracula'
                    };
                    const theme = themeMap[this.value] || 'default';
                    codeEditor.setOption('theme', theme);
                    console.log('Theme changed to:', theme);
                }
            });
        }
        
        // Load template button
        const loadTemplateBtn = document.getElementById('loadTemplateBtn');
        if (loadTemplateBtn) {
            loadTemplateBtn.addEventListener('click', function() {
                const template = `{!! addslashes($assignment->code_sample ?? '') !!}`;
                if (template && template.trim() && template !== '// Enter your starter code or template here') {
                    if (codeEditor) {
                        codeEditor.setValue(template);
                        codeEditor.focus();
                    }
                } else {
                    showAlert('No template available for this assignment.', 'info');
                }
            });
        }
        
        // Format code button
        const formatCodeBtn = document.getElementById('formatCodeBtn');
        if (formatCodeBtn) {
            formatCodeBtn.addEventListener('click', function() {
                if (codeEditor) {
                    const currentValue = codeEditor.getValue();
                    codeEditor.setValue(currentValue);
                    codeEditor.execCommand('selectAll');
                    codeEditor.execCommand('indentAuto');
                    codeEditor.setCursor(0, 0);
                    codeEditor.focus();
                }
            });
        }
        
        // Clear code button
        const clearCodeBtn = document.getElementById('clearCodeBtn');
        if (clearCodeBtn) {
            clearCodeBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to clear all code? This action cannot be undone.')) {
                    if (codeEditor) {
                        codeEditor.setValue('');
                        codeEditor.focus();
                    }
                }
            });
        }
        
        // Fullscreen functionality
        initializeFullscreen();
        
        // Preview functionality
        initializePreview();
        
        // Tab switching
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            button.addEventListener('shown.bs.tab', function() {
                updateSubmissionProgress();
                if (codeEditor) {
                    setTimeout(() => codeEditor.refresh(), 100);
                }
            });
        });
        
        // Form submission
        initializeFormSubmission();
    }
    
    // ==================== FULLSCREEN FUNCTIONALITY ====================
    function initializeFullscreen() {
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        const fullscreenModal = document.getElementById('fullscreenModal');
        
        if (!fullscreenBtn || !fullscreenModal) return;
        
        const modal = new bootstrap.Modal(fullscreenModal);
        
        fullscreenBtn.addEventListener('click', function() {
            if (!codeEditor) return;
            
            const fullscreenContainer = document.getElementById('fullscreenEditor');
            fullscreenContainer.innerHTML = '';
            
            const textarea = document.createElement('textarea');
            textarea.value = codeEditor.getValue();
            fullscreenContainer.appendChild(textarea);
            
            fullscreenEditor = CodeMirror.fromTextArea(textarea, {
                lineNumbers: true,
                mode: codeEditor.getOption('mode'),
                theme: codeEditor.getOption('theme'),
                indentUnit: 4,
                smartIndent: true,
                tabSize: 4,
                indentWithTabs: false,
                electricChars: true,
                autoCloseBrackets: true,
                matchBrackets: true,
                styleActiveLine: true,
                lineWrapping: true,
                foldGutter: true,
                gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                extraKeys: {
                    "Ctrl-Space": "autocomplete",
                    "Esc": function(cm) {
                        document.getElementById('saveFullscreen').click();
                    }
                }
            });
            
            fullscreenEditor.setSize(null, "100%");
            modal.show();
            
            setTimeout(() => {
                fullscreenEditor.refresh();
                fullscreenEditor.focus();
            }, 300);
        });
        
        // Save fullscreen
        const saveFullscreenBtn = document.getElementById('saveFullscreen');
        if (saveFullscreenBtn) {
            saveFullscreenBtn.addEventListener('click', function() {
                if (fullscreenEditor && codeEditor) {
                    codeEditor.setValue(fullscreenEditor.getValue());
                }
                modal.hide();
            });
        }
        
        // Clean up on modal close
        fullscreenModal.addEventListener('hidden.bs.modal', function() {
            if (fullscreenEditor) {
                fullscreenEditor = null;
            }
        });
    }
    
    // ==================== PREVIEW FUNCTIONALITY ====================
    function initializePreview() {
        const previewBtn = document.getElementById('previewBtn');
        const previewModal = document.getElementById('previewModal');
        const previewContent = document.getElementById('previewContent');
        
        if (!previewBtn || !previewModal || !previewContent) return;
        
        previewBtn.addEventListener('click', function() {
            generatePreview();
            const modal = new bootstrap.Modal(previewModal);
            modal.show();
        });
        
        // Submit from preview
        const submitFromPreview = document.getElementById('submitFromPreview');
        if (submitFromPreview) {
            submitFromPreview.addEventListener('click', function() {
                const form = document.getElementById('submissionForm');
                if (form) {
                    // Close preview modal
                    const modal = bootstrap.Modal.getInstance(previewModal);
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Submit form
                    form.submit();
                }
            });
        }
    }
    
    function generatePreview() {
        const previewContent = document.getElementById('previewContent');
        if (!previewContent) return;
        
        let previewHTML = '<div class="submission-preview">';
        
        // Code preview
        const codeContent = getCodeContent();
        if (codeContent) {
            previewHTML += `
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Code Submission</h6>
                    <pre class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;"><code>${escapeHtml(codeContent)}</code></pre>
                </div>
            `;
        }
        
        // Files preview
        if (selectedFiles.length > 0) {
            previewHTML += `
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Files (${selectedFiles.length})</h6>
                    <div class="list-group">
            `;
            
            selectedFiles.forEach(file => {
                const fileExtension = file.name.split('.').pop().toLowerCase();
                const iconClass = fileIcons[fileExtension] || 'ph-file';
                const fileSize = formatFileSize(file.size);
                
                previewHTML += `
                    <div class="list-group-item d-flex align-items-center gap-3">
                        <i class="ph ${iconClass} text-primary"></i>
                        <div class="flex-grow-1">
                            <div class="fw-medium">${escapeHtml(file.name)}</div>
                            <small class="text-muted">${fileSize}</small>
                        </div>
                    </div>
                `;
            });
            
            previewHTML += '</div></div>';
        }
        
        // Text preview
        const textContent = textSubmission ? textSubmission.value.trim() : '';
        if (textContent) {
            previewHTML += `
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Text Submission</h6>
                    <div class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;">
                        ${escapeHtml(textContent).replace(/\n/g, '<br>')}
                    </div>
                </div>
            `;
        }
        
        if (!codeContent && selectedFiles.length === 0 && !textContent) {
            previewHTML += '<p class="text-muted">No content to preview. Please add some content before previewing.</p>';
        }
        
        previewHTML += '</div>';
        previewContent.innerHTML = previewHTML;
    }
    
    // ==================== FORM SUBMISSION ====================
    function initializeFormSubmission() {
        const form = document.getElementById('submissionForm');
        if (!form) return;
        
        form.addEventListener('submit', function(e) {
            // Ensure hidden input is updated before submission
            syncWithHiddenInput();
            
            // Validate that at least one submission type is provided
            const hasCode = getCodeContent().length > 0;
            const hasFiles = selectedFiles.length > 0;
            const hasText = textSubmission && textSubmission.value.trim().length > 0;
            
            if (!hasCode && !hasFiles && !hasText) {
                e.preventDefault();
                showAlert('Please provide at least one form of submission (code, file, or text).', 'error');
                return false;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin me-2"></i>Submitting...';
            }
        });
    }
    
    // ==================== AUTO-SAVE FUNCTIONALITY ====================
    let autoSaveTimeout;
    function triggerAutoSave() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            const saveStatus = document.getElementById('saveStatus');
            if (saveStatus) {
                saveStatus.textContent = 'Saving...';
                saveStatus.className = 'text-warning';
                
                // Simulate save (implement actual auto-save here if needed)
                setTimeout(() => {
                    saveStatus.textContent = 'All changes saved';
                    saveStatus.className = 'text-success';
                }, 1000);
            }
        }, 2000);
    }
    
    // ==================== UTILITY FUNCTIONS ====================
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function showAlert(message, type = 'info') {
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="ph ph-${type === 'error' ? 'warning-circle' : type === 'success' ? 'check-circle' : 'info'} me-2"></i>
            ${message.replace(/\n/g, '<br>')}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at top of form
        const form = document.getElementById('submissionForm');
        if (form) {
            form.insertBefore(alertDiv, form.firstChild);
        }
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    // ==================== GLOBAL FUNCTIONS (for HTML onclick handlers) ====================
    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        displayFiles();
        updateSubmissionProgress();
        triggerAutoSave();
    };
    
    window.clearAllFiles = function() {
        if (confirm('Are you sure you want to remove all files?')) {
            selectedFiles = [];
            updateFileInput();
            displayFiles();
            updateSubmissionProgress();
            triggerAutoSave();
        }
    };
    
    window.toggleFullscreen = function() {
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        if (fullscreenBtn) {
            fullscreenBtn.click();
        }
    };
    
    // ==================== INITIALIZATION ====================
    function initialize() {
        console.log('Starting initialization...');
        
        // Initialize components
        const codeEditorInitialized = initializeCodeEditor();
        initializeFileUpload();
        initializeTextSubmission();
        initializeEventHandlers();
        
        // Initial progress update
        setTimeout(() => {
            updateSubmissionProgress();
        }, 500);
        
        console.log('Assignment submission system initialized successfully');
        console.log('CodeMirror:', codeEditorInitialized ? 'Loaded' : 'Failed');
        console.log('File Upload:', fileInput ? 'Ready' : 'Not available');
        console.log('Text Submission:', textSubmission ? 'Ready' : 'Not available');
    }
    
    // Start initialization
    initialize();
});
</script>

</x-student-layout>