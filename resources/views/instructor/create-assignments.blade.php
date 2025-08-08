<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create Assignment</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('instructor.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
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
            <li class="fw-medium">Create Assignment</li>
        </ul>
    </div>

    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h6 class="text-lg fw-semibold mb-0">Create New Assignment</h6>
        </div>
        <div class="card-body p-24">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <iconify-icon icon="solar:check-circle-outline" class="icon text-lg"></iconify-icon>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <iconify-icon icon="solar:danger-circle-outline" class="icon text-lg"></iconify-icon>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('instructor.assignments.store') }}" method="POST" id="assignmentForm">
                @csrf
                
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
                                   value="{{ old('title') }}" 
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
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
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
                                      placeholder="Enter assignment description">{{ old('description') }}</textarea>
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
                                            <iconify-icon icon="solar:full-screen-outline"></iconify-icon>
                                            Fullscreen
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-code-btn">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-outline"></iconify-icon>
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
                                <textarea name="code_sample" id="code_sample" style="display: none;">{{ old('code_sample', '// Enter your starter code or template here
// This will be provided to students as a starting point

function example() {
    // Your code here
    console.log("Hello, World!");
}') }}</textarea>
                                
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
                                    <option value="{{ $key }}" {{ old('status', 'draft') == $key ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                   value="{{ old('deadline') }}" 
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   required>
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Assignment Info Card -->
                        <div class="card border border-gray-200 radius-8 mb-20">
                            <div class="card-header bg-primary-50 py-12 px-16">
                                <h6 class="text-md fw-semibold mb-0 text-primary-600">Assignment Guidelines</h6>
                            </div>
                            <div class="card-body p-16">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-start gap-2 mb-8">
                                        <iconify-icon icon="solar:check-circle-outline" class="icon text-success-600 mt-1"></iconify-icon>
                                        <span class="text-sm">Choose an appropriate deadline</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-8">
                                        <iconify-icon icon="solar:check-circle-outline" class="icon text-success-600 mt-1"></iconify-icon>
                                        <span class="text-sm">Provide clear instructions</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-8">
                                        <iconify-icon icon="solar:check-circle-outline" class="icon text-success-600 mt-1"></iconify-icon>
                                        <span class="text-sm">Include starter code if needed</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2">
                                        <iconify-icon icon="solar:check-circle-outline" class="icon text-success-600 mt-1"></iconify-icon>
                                        <span class="text-sm">Save as draft to review later</span>
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
                <div class="d-flex align-items-center justify-content-end gap-3 mt-24">
                    <a href="{{ route('instructor.assignments.manage') }}" 
                       class="btn btn-outline-gray border text-secondary-light fw-semibold radius-8 px-20 py-11">
                        Cancel
                    </a>
                    <button type="submit" name="action" value="draft" 
                            class="btn btn-outline-warning fw-semibold radius-8 px-20 py-11">
                        <iconify-icon icon="solar:document-add-outline" class="icon text-lg"></iconify-icon>
                        Save as Draft
                    </button>
                    <button type="submit" name="action" value="active" 
                            class="btn btn-primary-600 fw-semibold radius-8 px-20 py-11">
                        <iconify-icon icon="solar:check-circle-outline" class="icon text-lg"></iconify-icon>
                        Create Assignment
                    </button>
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

.code-editor-toolbar {
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-bottom: none;
    border-radius: 8px 8px 0 0;
    padding: 8px 12px;
    font-size: 12px;
}

.editor-with-toolbar .CodeMirror {
    border-top: none;
    border-radius: 0 0 8px 8px;
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
        const initialContent = textarea.value || `// Enter your starter code or template here
// This will be provided to students as a starting point

function example() {
    // Your code here
    console.log("Hello, World!");
}`;
        
        editor.setValue(initialContent);
        
        // Auto-resize
        editor.setSize(null, "300px");
        
        return editor;
    }

    // Initialize editor
    editor = initializeEditor();

    // Language change handler
    document.getElementById('code_language').addEventListener('change', function() {
        const language = this.value;
        const mode = languageModes[language] || 'text/plain';
        
        editor.setOption('mode', mode);
        if (fullscreenEditor) {
            fullscreenEditor.setOption('mode', mode);
        }
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
    const form = document.getElementById('assignmentForm');
    const submitButtons = form.querySelectorAll('button[type="submit"]');
    
    submitButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Update the hidden textarea with editor content
            editor.save();
            
            // Set the status based on which button was clicked
            const action = this.getAttribute('name') === 'action' ? this.value : 'active';
            document.getElementById('status').value = action === 'draft' ? 'draft' : 'active';
        });
    });

    // Auto-save to localStorage
    let autoSaveTimeout;
    editor.on('change', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            const formData = {
                title: document.getElementById('title').value,
                course_id: document.getElementById('course_id').value,
                description: document.getElementById('description').value,
                code_sample: editor.getValue(),
                deadline: document.getElementById('deadline').value,
                status: document.getElementById('status').value,
                language: document.getElementById('code_language').value,
                theme: document.getElementById('editor_theme').value
            };
            localStorage.setItem('assignment_draft_' + Date.now(), JSON.stringify(formData));
        }, 2000);
    });

    // Load draft on page load (optional)
    const savedDrafts = Object.keys(localStorage).filter(key => key.startsWith('assignment_draft_'));
    if (savedDrafts.length > 0 && !document.getElementById('title').value) {
        const latestDraft = savedDrafts.sort().pop();
        const draft = JSON.parse(localStorage.getItem(latestDraft));
        
        if (confirm('A draft assignment was found. Would you like to restore it?')) {
            document.getElementById('title').value = draft.title || '';
            document.getElementById('course_id').value = draft.course_id || '';
            document.getElementById('description').value = draft.description || '';
            document.getElementById('deadline').value = draft.deadline || '';
            document.getElementById('status').value = draft.status || 'draft';
            document.getElementById('code_language').value = draft.language || 'javascript';
            document.getElementById('editor_theme').value = draft.theme || 'light';
            
            // Update editor
            editor.setValue(draft.code_sample || '');
            
            // Trigger change events
            document.getElementById('code_language').dispatchEvent(new Event('change'));
            document.getElementById('editor_theme').dispatchEvent(new Event('change'));
        }
        
        // Clean up old drafts
        localStorage.removeItem(latestDraft);
    }

    // Prevent accidental page leave
    let hasUnsavedChanges = false;
    
    // Track changes
    ['input', 'textarea', 'select'].forEach(selector => {
        document.querySelectorAll(selector).forEach(element => {
            element.addEventListener('change', () => {
                hasUnsavedChanges = true;
            });
        });
    });

    editor.on('change', () => {
        hasUnsavedChanges = true;
    });

    // Warn before leaving
    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });

    // Clear flag on form submit
    form.addEventListener('submit', () => {
        hasUnsavedChanges = false;
    });

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
});
</script>
</x-instructor-layout>