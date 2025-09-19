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
// Fixed CodeMirror Editor Implementation for Student Assignment Submission
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing CodeMirror editor...');
    
    let codeEditor = null;
    let fullscreenEditor = null;
    const codeEditorContainer = document.getElementById('codeEditor');
    const fallbackEditor = document.getElementById('fallbackEditor');
    const codeSubmissionInput = document.getElementById('codeSubmissionInput');
    const languageSelect = document.getElementById('languageSelect');
    const themeSelect = document.getElementById('themeSelect');
    
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
    
    // Check if CodeMirror is available
    if (typeof CodeMirror === 'undefined') {
        console.error('CodeMirror is not loaded. Using fallback textarea.');
        showFallbackEditor();
        return;
    }
    
    // Initialize CodeMirror using the working method from instructor page
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
        });
        
        // Language change handler
        if (languageSelect) {
            languageSelect.addEventListener('change', function() {
                const mode = languageModes[this.value] || 'text/plain';
                codeEditor.setOption('mode', mode);
                console.log('Language changed to:', this.value, 'Mode:', mode);
            });
        }
        
        // Theme change handler
        if (themeSelect) {
            themeSelect.addEventListener('change', function() {
                const themeMap = {
                    'default': 'default',
                    'material': 'material',
                    'monokai': 'monokai',
                    'dracula': 'dracula'
                };
                const theme = themeMap[this.value] || 'default';
                codeEditor.setOption('theme', theme);
                console.log('Theme changed to:', theme);
            });
        }
        
        // Load template button
        const loadTemplateBtn = document.getElementById('loadTemplateBtn');
        if (loadTemplateBtn) {
            loadTemplateBtn.addEventListener('click', function() {
                const template = `{!! addslashes($assignment->code_sample ?? '') !!}`;
                if (template && template.trim()) {
                    codeEditor.setValue(template);
                    codeEditor.focus();
                } else {
                    alert('No template available for this assignment.');
                }
            });
        }
        
        // Format code button
        const formatCodeBtn = document.getElementById('formatCodeBtn');
        if (formatCodeBtn) {
            formatCodeBtn.addEventListener('click', function() {
                const currentValue = codeEditor.getValue();
                // Simple formatting - just refresh and re-indent
                codeEditor.setValue(currentValue);
                codeEditor.execCommand('selectAll');
                codeEditor.execCommand('indentAuto');
                codeEditor.setCursor(0, 0);
                codeEditor.focus();
            });
        }
        
        // Clear code button
        const clearCodeBtn = document.getElementById('clearCodeBtn');
        if (clearCodeBtn) {
            clearCodeBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to clear all code? This action cannot be undone.')) {
                    codeEditor.setValue('');
                    codeEditor.focus();
                }
            });
        }
        
        // Fullscreen functionality
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        const fullscreenModal = document.getElementById('fullscreenModal');
        
        if (fullscreenBtn && fullscreenModal) {
            const modal = new bootstrap.Modal(fullscreenModal);
            
            fullscreenBtn.addEventListener('click', function() {
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
                    if (fullscreenEditor) {
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
        
        // Initial setup
        updateCodeStats();
        syncWithHiddenInput();
        updateSubmissionProgress();
        
        // Refresh editor after initialization
        setTimeout(() => {
            codeEditor.refresh();
        }, 100);
        
    } catch (error) {
        console.error('Failed to initialize CodeMirror:', error);
        showFallbackEditor();
    }
    
    function showFallbackEditor() {
        if (codeEditorContainer) {
            codeEditorContainer.innerHTML = '<p class="text-danger">Code editor failed to load. Using fallback editor.</p>';
        }
        if (fallbackEditor) {
            fallbackEditor.classList.remove('d-none');
            fallbackEditor.style.display = 'block';
            
            // Sync fallback editor with hidden input
            fallbackEditor.addEventListener('input', function() {
                if (codeSubmissionInput) {
                    codeSubmissionInput.value = this.value;
                }
                updateCodeStatsFromTextarea();
                updateSubmissionProgress();
            });
        }
    }
    
    function updateCodeStats() {
        if (!codeEditor) return;
        
        const code = codeEditor.getValue();
        const lines = code.split('\n').length;
        const characters = code.length;
        
        updateStatsDisplay(lines, characters);
    }
    
    function updateCodeStatsFromTextarea() {
        if (!fallbackEditor) return;
        
        const code = fallbackEditor.value;
        const lines = code.split('\n').length;
        const characters = code.length;
        
        updateStatsDisplay(lines, characters);
    }
    
    function updateStatsDisplay(lines, characters) {
        const codeStats = document.getElementById('codeStats');
        const codeCounter = document.getElementById('codeCounter');
        const codeLines = document.getElementById('codeLines');
        const cursorPosition = document.getElementById('cursorPosition');
        
        if (codeStats) {
            codeStats.textContent = `Lines: ${lines} | Characters: ${characters}`;
        }
        
        if (codeCounter) {
            codeCounter.textContent = `${characters}/50000 characters`;
            if (characters > 50000) {
                codeCounter.classList.add('text-danger');
            } else {
                codeCounter.classList.remove('text-danger');
            }
        }
        
        if (codeLines) {
            codeLines.textContent = lines;
        }
        
        if (cursorPosition && codeEditor) {
            const cursor = codeEditor.getCursor();
            cursorPosition.textContent = `Ln ${cursor.line + 1}, Col ${cursor.ch + 1}`;
        }
    }
    
    function syncWithHiddenInput() {
        if (codeEditor && codeSubmissionInput) {
            codeSubmissionInput.value = codeEditor.getValue();
        }
    }
    
    function updateSubmissionProgress() {
        const progressBar = document.getElementById('submissionProgress');
        const progressText = document.getElementById('progressText');
        
        if (!progressBar || !progressText) return;
        
        let progress = 0;
        
        // Check code submission
        const codeContent = codeEditor ? codeEditor.getValue().trim() : (fallbackEditor ? fallbackEditor.value.trim() : '');
        if (codeContent.length > 0) progress += 50;
        
        // Check file uploads
        const fileInput = document.getElementById('submissionFiles');
        if (fileInput && fileInput.files.length > 0) progress += 25;
        
        // Check text submission
        const textSubmission = document.getElementById('textSubmission');
        if (textSubmission && textSubmission.value.trim().length > 0) progress += 25;
        
        progressBar.style.width = progress + '%';
        progressText.textContent = progress + '% complete';
        
        if (progress > 0) {
            progressBar.classList.add('bg-primary');
            progressText.parentElement.querySelector('span:first-child').textContent = 'In progress';
        }
    }
    
    // Form submission handler
    const form = document.getElementById('submissionForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Ensure hidden input is updated before submission
            if (codeEditor && codeSubmissionInput) {
                codeSubmissionInput.value = codeEditor.getValue();
            } else if (fallbackEditor && codeSubmissionInput) {
                codeSubmissionInput.value = fallbackEditor.value;
            }
            
            // Validate that at least one submission type is provided
            const hasCode = codeSubmissionInput.value.trim().length > 0;
            const hasFiles = document.getElementById('submissionFiles').files.length > 0;
            const hasText = document.getElementById('textSubmission').value.trim().length > 0;
            
            if (!hasCode && !hasFiles && !hasText) {
                e.preventDefault();
                alert('Please provide at least one form of submission (code, file, or text).');
                return false;
            }
        });
    }
    
    // Auto-save functionality
    let autoSaveTimeout;
    function autoSave() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            const saveStatus = document.getElementById('saveStatus');
            if (saveStatus) {
                saveStatus.textContent = 'Saving...';
                saveStatus.className = 'text-warning';
                
                // Simulate save (you can implement actual auto-save here)
                setTimeout(() => {
                    saveStatus.textContent = 'All changes saved';
                    saveStatus.className = 'text-success';
                }, 1000);
            }
        }, 2000);
    }
    
    // Trigger auto-save on changes
    if (codeEditor) {
        codeEditor.on('change', autoSave);
    }
    
    // Handle tab switching to update progress
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function() {
            updateSubmissionProgress();
            if (codeEditor) {
                setTimeout(() => codeEditor.refresh(), 100);
            }
        });
    });
});
</script>

</x-student-layout>