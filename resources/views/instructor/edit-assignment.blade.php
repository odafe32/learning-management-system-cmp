<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Edit Assignment</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('instructor.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <i class="ph ph-house text-lg"></i>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('instructor.assignments.manage') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    Assignments
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Edit Assignment</li>
        </ul>
    </div>

    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="text-lg fw-semibold mb-0">Edit Assignment: {{ $assignment->title }}</h6>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-{{ $assignment->status_color }}-50 text-{{ $assignment->status_color }}-600 px-12 py-6 rounded-4">
                        {{ $assignment->status_label }}
                    </span>
                    <span class="text-sm text-secondary-light">
                        Created {{ $assignment->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-24">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ph ph-check-circle text-lg"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ph ph-warning-circle text-lg"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('instructor.assignments.update', $assignment) }}" method="POST" id="editAssignmentForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Assignment Title -->
                        <div class="mb-20">
                            <label for="title" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Assignment Title <span class="text-danger-600">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control radius-8 @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $assignment->title) }}" 
                                   placeholder="Enter assignment title"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Course Selection -->
                        <div class="mb-20">
                            <label for="course_id" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Course <span class="text-danger-600">*</span>
                            </label>
                            <select class="form-select radius-8 @error('course_id') is-invalid @enderror" 
                                    id="course_id" 
                                    name="course_id" 
                                    required>
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" 
                                            {{ old('course_id', $assignment->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->code }} - {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-20">
                            <label for="description" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Description
                            </label>
                            <textarea class="form-control radius-8 @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Enter assignment description">{{ old('description', $assignment->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Code Sample with CodeMirror -->
                        <div class="mb-20">
                            <label for="code_sample" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Code Sample/Template
                            </label>
                            
                            <!-- Editor Controls -->
                            <div class="mb-12">
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <div class="d-flex align-items-center gap-2">
                                        <label for="code_language" class="form-label fw-medium text-sm mb-0">Language:</label>
                                        <select class="form-select form-select-sm" id="code_language" style="width: auto;">
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
                                    </div>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        <label for="editor_theme" class="form-label fw-medium text-sm mb-0">Theme:</label>
                                        <select class="form-select form-select-sm" id="editor_theme" style="width: auto;">
                                            <option value="light">Light</option>
                                            <option value="dark">Dark</option>
                                        </select>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 ms-auto">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="fullscreen-btn">
                                            <i class="ph ph-arrows-out"></i>
                                            Fullscreen
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-code-btn">
                                            <i class="ph ph-trash"></i>
                                            Clear
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- CodeMirror Editor Container -->
                            <div class="position-relative">
                                <div id="code-editor" 
                                     class="border radius-8 @error('code_sample') border-danger @enderror" 
                                     style="min-height: 300px;">
                                </div>
                                
                                <!-- Hidden textarea to store the code for form submission -->
                                <textarea name="code_sample" id="code_sample" style="display: none;">{{ old('code_sample', $assignment->code_sample) }}</textarea>
                                
                                @error('code_sample')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mt-12">
                                <small class="text-muted">
                                    Provide starter code or template for students (optional). 
                                    Use <kbd>Ctrl+Space</kbd> for autocomplete, <kbd>Ctrl+F</kbd> to search.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Assignment Info Card -->
                        <div class="card border border-info-200 radius-8 mb-20">
                            <div class="card-header bg-info-50 py-12 px-16">
                                <h6 class="text-md fw-semibold mb-0 text-info-600">Assignment Info</h6>
                            </div>
                            <div class="card-body p-16">
                                <div class="mb-12">
                                    <label class="text-sm fw-medium text-secondary-light">Assignment ID</label>
                                    <div class="text-sm font-monospace">{{ $assignment->id }}</div>
                                </div>
                                <div class="mb-12">
                                    <label class="text-sm fw-medium text-secondary-light">Current Course</label>
                                    <div class="text-sm fw-semibold">{{ $assignment->course->code }} - {{ $assignment->course->title }}</div>
                                </div>
                                <div class="mb-12">
                                    <label class="text-sm fw-medium text-secondary-light">Submissions</label>
                                    <div class="text-sm">
                                        <span class="fw-semibold">{{ $assignment->getSubmissionsCount() }}</span> total,
                                        <span class="text-warning">{{ $assignment->getPendingSubmissionsCount() }}</span> pending
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <label class="text-sm fw-medium text-secondary-light">Last Updated</label>
                                    <div class="text-sm">{{ $assignment->updated_at->format('M d, Y \a\t g:i A') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-20">
                            <label for="status" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Status <span class="text-danger-600">*</span>
                            </label>
                            <select class="form-select radius-8 @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" 
                                            {{ old('status', $assignment->status) == $key ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1">
                                @if($assignment->getSubmissionsCount() > 0)
                                    <i class="ph ph-warning text-warning"></i>
                                    This assignment has {{ $assignment->getSubmissionsCount() }} submission(s). 
                                    Changing status may affect student access.
                                @endif
                            </small>
                        </div>

                        <!-- Deadline -->
                        <div class="mb-20">
                            <label for="deadline" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Deadline <span class="text-danger-600">*</span>
                            </label>
                            <input type="datetime-local" 
                                   class="form-control radius-8 @error('deadline') is-invalid @enderror" 
                                   id="deadline" 
                                   name="deadline" 
                                   value="{{ old('deadline', $assignment->deadline->format('Y-m-d\TH:i')) }}" 
                                   required>
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1">
                                Current deadline: {{ $assignment->getFormattedDeadline() }}
                                @if($assignment->isOverdue())
                                    <span class="text-danger">(Overdue)</span>
                                @endif
                            </small>
                        </div>

                        <!-- Assignment Guidelines -->
                        <div class="card border border-gray-200 radius-8 mb-20">
                            <div class="card-header bg-primary-50 py-12 px-16">
                                <h6 class="text-md fw-semibold mb-0 text-primary-600">Update Guidelines</h6>
                            </div>
                            <div class="card-body p-16">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-start gap-2 mb-8">
                                        <i class="ph ph-check-circle text-success-600 mt-1"></i>
                                        <span class="text-sm">Review all changes carefully</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-8">
                                        <i class="ph ph-check-circle text-success-600 mt-1"></i>
                                        <span class="text-sm">Consider existing submissions</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-8">
                                        <i class="ph ph-check-circle text-success-600 mt-1"></i>
                                        <span class="text-sm">Notify students of major changes</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2">
                                        <i class="ph ph-check-circle text-success-600 mt-1"></i>
                                        <span class="text-sm">Test code samples before saving</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Code Editor Tips -->
                        <div class="card border border-info-200 radius-8 mb-20">
                            <div class="card-header bg-info-50 py-12 px-16">
                                <h6 class="text-md fw-semibold mb-0 text-info-600">Code Editor Shortcuts</h6>
                            </div>
                            <div class="card-body p-16">
                                <ul class="list-unstyled mb-0 text-sm">
                                    <li class="mb-8">• <strong>Ctrl+Space:</strong> Autocomplete</li>
                                    <li class="mb-8">• <strong>Ctrl+F:</strong> Find text</li>
                                    <li class="mb-8">• <strong>Ctrl+H:</strong> Find & replace</li>
                                    <li class="mb-8">• <strong>Ctrl+/:</strong> Toggle comment</li>
                                    <li class="mb-8">• <strong>Tab:</strong> Indent selection</li>
                                    <li>• <strong>F11:</strong> Toggle fullscreen</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex align-items-center justify-content-between mt-24">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('instructor.assignments.manage') }}" 
                           class="btn btn-gray border text-secondary-light fw-semibold radius-8 px-20 py-11">
                            <i class="ph ph-arrow-left text-lg"></i>
                            Back to Assignments
                        </a>
                      
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" name="action" value="draft" 
                                class="btn btn-warning fw-semibold radius-8 px-20 py-11">
                            <i class="ph ph-floppy-disk text-lg"></i>
                            Save as Draft
                        </button>
                        <button type="submit" name="action" value="update" 
                                class="btn btn-primary fw-semibold radius-8 px-20 py-11">
                            <i class="ph ph-check-circle text-lg"></i>
                            Update Assignment
                        </button>
                    </div>
                </div>
            </form>
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
                <div id="fullscreen-editor" style="height: calc(100vh - 120px);"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-fullscreen">Save & Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Unsaved Changes Warning Modal -->
<div class="modal fade" id="unsavedChangesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Unsaved Changes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="ph ph-warning-circle text-6xl text-warning-600 mb-3"></i>
                    <h6 class="text-lg fw-semibold mb-2">You have unsaved changes</h6>
                    <p class="text-secondary-light mb-0">
                        Are you sure you want to leave this page? Your changes will be lost.
                    </p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-gray radius-8" data-bs-dismiss="modal">
                    Stay on Page
                </button>
                <button type="button" class="btn btn-danger radius-8" id="confirmLeave">
                    Leave Without Saving
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CodeMirror CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/material.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/eclipse.min.css">

<style>
.CodeMirror {
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 14px;
    line-height: 1.5;
    height: auto;
    min-height: 300px;
}

.CodeMirror-focused {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.CodeMirror-scroll {
    min-height: 300px;
}

.fullscreen-editor .CodeMirror {
    height: 100%;
    border: none;
    border-radius: 0;
}

.fullscreen-editor .CodeMirror-scroll {
    min-height: 100%;
}

.form-floating-custom {
    position: relative;
}

.form-floating-custom .form-control {
    padding-top: 1.625rem;
    padding-bottom: 0.625rem;
}

.form-floating-custom .form-label {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    padding: 1rem 0.75rem;
    pointer-events: none;
    border: 1px solid transparent;
    transform-origin: 0 0;
    transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
}

.has-changes {
    border-left: 4px solid #ffc107;
    background-color: #fff8e1;
}

.change-indicator {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 8px;
    height: 8px;
    background-color: #ffc107;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.submission-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 16px;
}

.deadline-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;
}

.deadline-status.overdue {
    background-color: #ffebee;
    color: #c62828;
}

.deadline-status.due-soon {
    background-color: #fff3e0;
    color: #ef6c00;
}

.deadline-status.normal {
    background-color: #e8f5e8;
    color: #2e7d32;
}
</style>

<!-- CodeMirror JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/edit/closebrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/edit/matchbrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/selection/active-line.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/search/search.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/search/searchcursor.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/dialog/dialog.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/hint/show-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/hint/javascript-hint.min.js"></script>

<!-- Language modes -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/python/python.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/php/php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/sql/sql.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/xml/xml.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let editor = null;
    let fullscreenEditor = null;
    let hasUnsavedChanges = false;
    let originalFormData = {};
    
    // Language mode mapping
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

    // Initialize CodeMirror
    function initializeEditor() {
        const textarea = document.getElementById('code_sample');
        
        editor = CodeMirror.fromTextArea(textarea, {
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

        // Set initial content
        const initialContent = textarea.value || '';
        editor.setValue(initialContent);
        
        // Auto-resize
        editor.setSize(null, "300px");
        
        return editor;
    }

    // Initialize editor
    editor = initializeEditor();

    // Store original form data
    function storeOriginalData() {
        const form = document.getElementById('editAssignmentForm');
        const formData = new FormData(form);
        originalFormData = {
            title: formData.get('title'),
            course_id: formData.get('course_id'),
            description: formData.get('description'),
            code_sample: editor.getValue(),
            deadline: formData.get('deadline'),
            status: formData.get('status')
        };
    }

    // Check if form has changes
    function checkForChanges() {
        const form = document.getElementById('editAssignmentForm');
        const formData = new FormData(form);
        const currentData = {
            title: formData.get('title'),
            course_id: formData.get('course_id'),
            description: formData.get('description'),
            code_sample: editor.getValue(),
            deadline: formData.get('deadline'),
            status: formData.get('status')
        };

        hasUnsavedChanges = JSON.stringify(originalFormData) !== JSON.stringify(currentData);
        
        // Update UI to show changes
        const card = document.querySelector('.card');
        if (hasUnsavedChanges) {
            card.classList.add('has-changes');
            if (!document.querySelector('.change-indicator')) {
                const indicator = document.createElement('div');
                indicator.className = 'change-indicator';
                indicator.title = 'You have unsaved changes';
                card.querySelector('.card-header').appendChild(indicator);
            }
        } else {
            card.classList.remove('has-changes');
            const indicator = document.querySelector('.change-indicator');
            if (indicator) {
                indicator.remove();
            }
        }
    }

    // Store original data after page load
    setTimeout(() => {
        storeOriginalData();
    }, 1000);

    // Language change handler
    document.getElementById('code_language').addEventListener('change', function() {
        const language = this.value;
        const mode = languageModes[language] || 'text/plain';
        
        editor.setOption('mode', mode);
        if (fullscreenEditor) {
            fullscreenEditor.setOption('mode', mode);
        }
        checkForChanges();
    });

    // Theme change handler
    document.getElementById('editor_theme').addEventListener('change', function() {
        const theme = this.value === 'dark' ? 'material' : 'eclipse';
        
        editor.setOption('theme', theme);
        if (fullscreenEditor) {
            fullscreenEditor.setOption('theme', theme);
        }
    });

    // Clear code button
    document.getElementById('clear-code-btn').addEventListener('click', function() {
        if (confirm('Are you sure you want to clear all code? This action cannot be undone.')) {
            editor.setValue('');
            editor.focus();
            checkForChanges();
        }
    });

    // Fullscreen functionality
    const fullscreenModal = new bootstrap.Modal(document.getElementById('fullscreenModal'));
    
    function toggleFullscreen() {
        document.getElementById('fullscreen-btn').click();
    }

    document.getElementById('fullscreen-btn').addEventListener('click', function() {
        // Create fullscreen editor
        const fullscreenContainer = document.getElementById('fullscreen-editor');
        fullscreenContainer.innerHTML = '';
        
        const textarea = document.createElement('textarea');
        textarea.value = editor.getValue();
        fullscreenContainer.appendChild(textarea);
        
        fullscreenEditor = CodeMirror.fromTextArea(textarea, {
            lineNumbers: true,
            mode: editor.getOption('mode'),
            theme: editor.getOption('theme'),
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
                    document.getElementById('save-fullscreen').click();
                }
            }
        });
        
        fullscreenEditor.setSize(null, "100%");
        
        fullscreenModal.show();
        
        // Focus editor after modal is shown
        setTimeout(() => {
            fullscreenEditor.refresh();
            fullscreenEditor.focus();
        }, 300);
    });

    // Save and close fullscreen
    document.getElementById('save-fullscreen').addEventListener('click', function() {
        if (fullscreenEditor) {
            editor.setValue(fullscreenEditor.getValue());
            checkForChanges();
        }
        fullscreenModal.hide();
    });

    // Handle modal close
    document.getElementById('fullscreenModal').addEventListener('hidden.bs.modal', function() {
        if (fullscreenEditor) {
            fullscreenEditor = null;
        }
    });

    // Form submission handling
    const form = document.getElementById('editAssignmentForm');
    const submitButtons = form.querySelectorAll('button[type="submit"]');
    
    submitButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Update the hidden textarea with editor content
            editor.save();
            
            // Set the status based on which button was clicked
            const action = this.getAttribute('name') === 'action' ? this.value : 'update';
            if (action === 'draft') {
                document.getElementById('status').value = 'draft';
            }
            
            // Clear unsaved changes flag
            hasUnsavedChanges = false;
        });
    });

    // Track changes on form elements
    const formElements = form.querySelectorAll('input, textarea, select');
    formElements.forEach(element => {
        element.addEventListener('change', checkForChanges);
        element.addEventListener('input', checkForChanges);
    });

    // Track changes in CodeMirror
    editor.on('change', function() {
        checkForChanges();
    });

    // Prevent accidental page leave
    const unsavedChangesModal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
    let pendingNavigation = null;

    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });

    // Handle navigation links
    document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (hasUnsavedChanges && !this.href.includes('#')) {
                e.preventDefault();
                pendingNavigation = this.href;
                unsavedChangesModal.show();
            }
        });
    });

    // Confirm leave button
    document.getElementById('confirmLeave').addEventListener('click', function() {
        hasUnsavedChanges = false;
        unsavedChangesModal.hide();
        if (pendingNavigation) {
            window.location.href = pendingNavigation;
        }
    });

    // Clear flag on form submit
    form.addEventListener('submit', () => {
        hasUnsavedChanges = false;
    });

    // Auto-save functionality (optional)
    let autoSaveTimeout;
    function autoSave() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            if (hasUnsavedChanges) {
                const formData = {
                    title: document.getElementById('title').value,
                    course_id: document.getElementById('course_id').value,
                    description: document.getElementById('description').value,
                    code_sample: editor.getValue(),
                    deadline: document.getElementById('deadline').value,
                    status: document.getElementById('status').value,
                    timestamp: Date.now()
                };
                localStorage.setItem('assignment_edit_{{ $assignment->id }}', JSON.stringify(formData));
                console.log('Auto-saved assignment draft');
            }
        }, 5000); // Auto-save after 5 seconds of inactivity
    }

    // Trigger auto-save on changes
    formElements.forEach(element => {
        element.addEventListener('input', autoSave);
    });
    editor.on('change', autoSave);

    // Load auto-saved data on page load (optional)
    const autoSavedData = localStorage.getItem('assignment_edit_{{ $assignment->id }}');
    if (autoSavedData) {
        const data = JSON.parse(autoSavedData);
        const timeDiff = Date.now() - data.timestamp;
        
        // Only restore if auto-save is less than 1 hour old
        if (timeDiff < 3600000 && confirm('An auto-saved version of this assignment was found. Would you like to restore it?')) {
            document.getElementById('title').value = data.title || '';
            document.getElementById('course_id').value = data.course_id || '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('deadline').value = data.deadline || '';
            document.getElementById('status').value = data.status || '';
            editor.setValue(data.code_sample || '');
            
            checkForChanges();
        }
        
        // Clean up auto-saved data
        localStorage.removeItem('assignment_edit_{{ $assignment->id }}');
    }

    // Refresh editor on window resize
    window.addEventListener('resize', () => {
        if (editor) {
            setTimeout(() => editor.refresh(), 100);
        }
    });

    // Initialize editor properly
    setTimeout(() => {
        editor.refresh();
    }, 100);

    // Show submission warning if there are submissions
    @if($assignment->getSubmissionsCount() > 0)
        const submissionWarning = document.createElement('div');
        submissionWarning.className = 'submission-warning';
        submissionWarning.innerHTML = `
            <div class="d-flex align-items-start gap-2">
                <i class="ph ph-warning text-warning text-lg mt-1"></i>
                <div>
                    <strong>Important:</strong> This assignment has {{ $assignment->getSubmissionsCount() }} submission(s). 
                    Major changes may affect existing submissions and student work.
                    <div class="mt-2">
                        <small>Consider notifying students about significant updates.</small>
                    </div>
                </div>
            </div>
        `;
        
        const cardBody = document.querySelector('.card-body');
        cardBody.insertBefore(submissionWarning, cardBody.firstChild.nextSibling);
    @endif

    // Add deadline status indicator
    const deadlineInput = document.getElementById('deadline');
    const currentDeadline = new Date('{{ $assignment->deadline->toISOString() }}');
    const now = new Date();
    
    function updateDeadlineStatus() {
        const newDeadline = new Date(deadlineInput.value);
        const diffTime = newDeadline - now;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        let statusClass = 'normal';
        let statusText = 'Normal';
        
        if (diffTime < 0) {
            statusClass = 'overdue';
            statusText = 'Overdue';
        } else if (diffDays <= 3) {
            statusClass = 'due-soon';
            statusText = 'Due Soon';
        }
        
        // Update or create status indicator
        let statusIndicator = deadlineInput.parentNode.querySelector('.deadline-status');
        if (!statusIndicator) {
            statusIndicator = document.createElement('div');
            statusIndicator.className = 'deadline-status';
            deadlineInput.parentNode.appendChild(statusIndicator);
        }
        
        statusIndicator.className = `deadline-status ${statusClass}`;
        statusIndicator.innerHTML = `<i class="ph ph-clock"></i> ${statusText}`;
    }
    
    deadlineInput.addEventListener('change', updateDeadlineStatus);
    updateDeadlineStatus(); // Initial call
});
</script>
</x-instructor-layout>