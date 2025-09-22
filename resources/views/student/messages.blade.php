<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<style>
    .messages-container {
        height: calc(100vh - 200px);
        min-height: 500px;
    }
    
    .conversations-list {
        height: 100%;
        overflow-y: auto;
        border-right: 1px solid #e5e7eb;
    }
    
    .chat-area {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .messages-list {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        background-color: #f9fafb;
    }
    
    .message-bubble {
        max-width: 70%;
        margin-bottom: 1rem;
        word-wrap: break-word;
    }
    
    .message-bubble.sent {
        margin-left: auto;
    }
    
    .message-bubble.received {
        margin-right: auto;
    }
    
    .message-content {
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        position: relative;
    }
    
    .message-bubble.sent .message-content {
        background-color: #3b82f6;
        color: white;
        border-bottom-right-radius: 0.25rem;
    }
    
    .message-bubble.received .message-content {
        background-color: white;
        color: #374151;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 0.25rem;
    }
    
    .message-time {
        font-size: 0.75rem;
        opacity: 0.7;
        margin-top: 0.25rem;
    }
    
    .message-input-area {
        border-top: 1px solid #e5e7eb;
        padding: 1rem;
        background-color: white;
    }
    
    .conversation-item {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .conversation-item:hover {
        background-color: #f9fafb;
    }
    
    .conversation-item.active {
        background-color: #eff6ff;
        border-right: 3px solid #3b82f6;
    }
    
    .conversation-item.unread {
        background-color: #fef3c7;
    }
    
    .attachment-preview {
        margin-top: 0.5rem;
        padding: 0.5rem;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .message-bubble.received .attachment-preview {
        background-color: #f3f4f6;
    }
    
    .empty-chat {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #6b7280;
    }
    
    .role-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-weight: 500;
    }
    
    .role-badge.lecturer {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .role-badge.instructor {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .role-badge.student {
        background-color: #dcfce7;
        color: #166534;
    }

    /* Enhanced select styling */
    .user-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    .user-option-group {
        font-weight: bold;
        color: #374151;
        background-color: #f3f4f6;
    }

    .typing-indicator {
        display: none;
        padding: 0.5rem 1rem;
        font-style: italic;
        color: #6b7280;
        font-size: 0.875rem;
    }

    .message-status {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .message-status.read {
        color: #3b82f6;
    }

    .quick-reply-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
        flex-wrap: wrap;
    }

    .quick-reply-btn {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 1rem;
        background: white;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s;
    }

    .quick-reply-btn:hover {
        background-color: #f3f4f6;
        border-color: #9ca3af;
    }
</style>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Messages</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Messages</li>
        </ul>
    </div>

    <!-- Messages Stats -->
    <div class="row gy-4 mb-24">
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-64 h-64 radius-12 d-flex justify-content-center align-items-center bg-primary-50">
                            <iconify-icon icon="mage:email" class="text-primary-600 text-xxl"></iconify-icon>
                        </div>
                        <div>
                            <h6 class="mb-2 fw-semibold">{{ $stats['total_messages'] }}</h6>
                            <p class="text-sm text-secondary-light fw-medium">Total Messages</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-64 h-64 radius-12 d-flex justify-content-center align-items-center bg-warning-50">
                            <iconify-icon icon="material-symbols:mark-email-unread" class="text-warning-600 text-xxl"></iconify-icon>
                        </div>
                        <div>
                            <h6 class="mb-2 fw-semibold">{{ $stats['unread_count'] }}</h6>
                            <p class="text-sm text-secondary-light fw-medium">Unread Messages</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-64 h-64 radius-12 d-flex justify-content-center align-items-center bg-success-50">
                            <iconify-icon icon="material-symbols:send" class="text-success-600 text-xxl"></iconify-icon>
                        </div>
                        <div>
                            <h6 class="mb-2 fw-semibold">{{ $stats['sent_count'] }}</h6>
                            <p class="text-sm text-secondary-light fw-medium">Sent Messages</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-64 h-64 radius-12 d-flex justify-content-center align-items-center bg-info-50">
                            <iconify-icon icon="material-symbols:inbox" class="text-info-600 text-xxl"></iconify-icon>
                        </div>
                        <div>
                            <h6 class="mb-2 fw-semibold">{{ $stats['received_count'] }}</h6>
                            <p class="text-sm text-secondary-light fw-medium">Received Messages</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Interface -->
    <div class="card">
        <div class="card-body p-0">
            <div class="messages-container">
                <div class="row g-0 h-100">
                    <!-- Conversations List -->
                    <div class="col-md-4 col-lg-3">
                        <div class="conversations-list">
                            <!-- Search and Filters -->
                            <div class="p-3 border-bottom">
                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                                        <iconify-icon icon="material-symbols:add" class="me-1"></iconify-icon>
                                        New Message
                                    </button>
                                </div>
                                
                                <!-- Filter Buttons -->
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="messageFilter" id="filterAll" value="all" {{ $filter === 'all' ? 'checked' : '' }}>
                                    <label class="btn btn-primary btn-sm" for="filterAll">All</label>
                                    
                                    <input type="radio" class="btn-check" name="messageFilter" id="filterUnread" value="unread" {{ $filter === 'unread' ? 'checked' : '' }}>
                                    <label class="btn btn-warning btn-sm" for="filterUnread">Unread</label>
                                    
                                    <input type="radio" class="btn-check" name="messageFilter" id="filterSent" value="sent" {{ $filter === 'sent' ? 'checked' : '' }}>
                                    <label class="btn btn-success btn-sm" for="filterSent">Sent</label>
                                </div>
                            </div>

                            <!-- Conversations -->
                            <div class="conversations-scroll">
                                @forelse($conversations as $conversation)
                                    @php
                                        $partner = $conversation->sender_id === $user->id ? $conversation->receiver : $conversation->sender;
                                        $isActive = $conversationWith == $partner->id;
                                        $isUnread = !$conversation->is_read && $conversation->receiver_id === $user->id;
                                    @endphp
                                    <div class="conversation-item {{ $isActive ? 'active' : '' }} {{ $isUnread ? 'unread' : '' }}" 
                                         data-user-id="{{ $partner->id }}" 
                                         onclick="loadConversation('{{ $partner->id }}')">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="flex-shrink-0">
                                                <img src="{{ $partner->profile_image_url }}" 
                                                     alt="{{ $partner->name }}" 
                                                     class="w-40 h-40 rounded-circle object-fit-cover">
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <h6 class="text-sm fw-semibold mb-0 text-truncate">{{ $partner->name }}</h6>
                                                    <small class="text-xs text-secondary-light">{{ $conversation->time_ago }}</small>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="role-badge {{ strtolower($partner->role) }}">
                                                        {{ ucfirst($partner->role) }}
                                                        @if($partner->level)
                                                            - Level {{ $partner->level }}
                                                        @endif
                                                    </span>
                                                    @if($isUnread)
                                                        <span class="badge bg-warning text-dark">New</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-secondary-light mb-0 text-truncate mt-1">{{ $conversation->preview }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center">
                                        <iconify-icon icon="material-symbols:chat-bubble-outline" class="text-secondary-light text-4xl mb-2"></iconify-icon>
                                        <p class="text-secondary-light mb-0">No conversations yet</p>
                                        <small class="text-secondary-light">Start a conversation with your lecturers or classmates</small>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Chat Area -->
                    <div class="col-md-8 col-lg-9">
                        <div class="chat-area">
                            @if($conversationWith && $conversationPartner)
                                <!-- Chat Header -->
                                <div class="p-3 border-bottom bg-light">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $conversationPartner->profile_image_url }}" 
                                             alt="{{ $conversationPartner->name }}" 
                                             class="w-40 h-40 rounded-circle object-fit-cover">
                                        <div>
                                            <h6 class="mb-0 fw-semibold">{{ $conversationPartner->name }}</h6>
                                            <small class="text-secondary-light">
                                                {{ ucfirst($conversationPartner->role) }}
                                                @if($conversationPartner->level)
                                                    - Level {{ $conversationPartner->level }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="ms-auto">
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="markAllAsRead('{{ $conversationPartner->id }}')">
                                                <iconify-icon icon="material-symbols:mark-email-read" class="me-1"></iconify-icon>
                                                Mark as Read
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Messages List -->
                                <div class="messages-list" id="messagesList">
                                    @foreach($messages as $message)
                                        <div class="message-bubble {{ $message->sender_id === $user->id ? 'sent' : 'received' }}">
                                            <div class="message-content">
                                                <p class="mb-0">{{ $message->content }}</p>
                                                
                                                @if($message->hasAttachment())
                                                    <div class="attachment-preview">
                                                        <iconify-icon icon="material-symbols:attach-file" class="text-lg"></iconify-icon>
                                                        <a href="{{ $message->attachment_url }}" target="_blank" class="text-decoration-none">
                                                            {{ $message->getAttachmentName() }}
                                                        </a>
                                                    </div>
                                                @endif
                                                
                                                <div class="message-time text-end">
                                                    {{ $message->time_ago }}
                                                    @if($message->sender_id === $user->id)
                                                        <iconify-icon icon="material-symbols:{{ $message->is_read ? 'done-all' : 'done' }}" 
                                                                     class="ms-1 message-status {{ $message->is_read ? 'read' : '' }}"></iconify-icon>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Typing Indicator -->
                                <div class="typing-indicator" id="typingIndicator">
                                    <iconify-icon icon="eos-icons:three-dots-loading" class="me-1"></iconify-icon>
                                    <span id="typingText">Someone is typing...</span>
                                </div>

                                <!-- Quick Reply Buttons (Optional) -->
                                @if($conversationPartner->isInstructor())
                                <div class="px-3 py-2 border-top">
                                    <small class="text-secondary-light d-block mb-2">Quick replies:</small>
                                    <div class="quick-reply-buttons">
                                        <button class="quick-reply-btn" onclick="insertQuickReply('Thank you for your help!')">Thank you</button>
                                        <button class="quick-reply-btn" onclick="insertQuickReply('I understand.')">I understand</button>
                                        <button class="quick-reply-btn" onclick="insertQuickReply('Could you please clarify?')">Need clarification</button>
                                        <button class="quick-reply-btn" onclick="insertQuickReply('When is the deadline?')">Ask deadline</button>
                                    </div>
                                </div>
                                @endif

                                <!-- Message Input -->
                                <div class="message-input-area">
                                    <form id="messageForm" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="receiver_id" value="{{ $conversationPartner->id }}">
                                        
                                        <div class="d-flex gap-2 align-items-end">
                                            <div class="flex-grow-1">
                                                <textarea name="content" 
                                                         id="messageInput"
                                                         class="form-control" 
                                                         rows="2" 
                                                         placeholder="Type your message..." 
                                                         required></textarea>
                                            </div>
                                            <div class="d-flex flex-column gap-2">
                                                <label for="attachment" class="btn btn-secondary btn-sm" title="Attach file">
                                                    <iconify-icon icon="material-symbols:attach-file"></iconify-icon>
                                                </label>
                                                <input type="file" id="attachment" name="attachment" class="d-none" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.mp3,.mp4,.wav,.avi,.mov,.zip,.rar">
                                                
                                                <button type="submit" class="btn btn-primary btn-sm" title="Send message">
                                                    <iconify-icon icon="material-symbols:send"></iconify-icon>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div id="attachmentPreview" class="mt-2" style="display: none;">
                                            <small class="text-secondary-light">
                                                <iconify-icon icon="material-symbols:attach-file" class="me-1"></iconify-icon>
                                                <span id="attachmentName"></span>
                                                <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="removeAttachment()">Remove</button>
                                            </small>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <!-- Empty Chat State -->
                                <div class="empty-chat">
                                    <iconify-icon icon="material-symbols:chat-bubble-outline" class="text-6xl mb-3"></iconify-icon>
                                    <h5 class="mb-2">Select a conversation</h5>
                                    <p class="text-center">Choose a conversation from the list to start messaging<br>
                                    You can message your lecturers and fellow students in your level.</p>
                                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                                        <iconify-icon icon="material-symbols:add" class="me-1"></iconify-icon>
                                        Start New Conversation
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Message Modal -->
<div class="modal fade" id="newMessageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="newMessageForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">To</label>
                        <select name="receiver_id" id="userSelect" class="form-select user-select" required>
                            <option value="">Select a person to message...</option>
                            
                            @php
                                $lecturers = $availableUsers->where('role', 'instructor')->merge($availableUsers->where('role', 'lecturer'));
                                $students = $availableUsers->where('role', 'student');
                            @endphp
                            
                            @if($lecturers->count() > 0)
                                <optgroup label="📚 Lecturers & Instructors" class="user-option-group">
                                    @foreach($lecturers as $lecturer)
                                        <option value="{{ $lecturer->id }}" data-role="{{ $lecturer->role }}" data-level="{{ $lecturer->level ?? 'N/A' }}">
                                            {{ $lecturer->name }} ({{ ucfirst($lecturer->role) }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            
                            @if($students->count() > 0)
                                <optgroup label="👥 Classmates (Level {{ $user->level }})" class="user-option-group">
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" data-role="{{ $student->role }}" data-level="{{ $student->level }}">
                                            {{ $student->name }} ({{ $student->matric_or_staff_id ?? 'Student' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            
                            @if($lecturers->count() === 0 && $students->count() === 0)
                                <option value="" disabled>No users available to message</option>
                            @endif
                        </select>
                        <small class="text-secondary-light">You can message lecturers assigned to your level and fellow students in Level {{ $user->level }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="content" class="form-control" rows="4" placeholder="Type your message..." required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Attachment (Optional)</label>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.mp3,.mp4,.wav,.avi,.mov,.zip,.rar">
                        <small class="text-secondary-light">Max file size: 10MB. Supported formats: PDF, DOC, DOCX, TXT, Images, Audio, Video, ZIP, RAR</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <iconify-icon icon="material-symbols:send" class="me-1"></iconify-icon>
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-scroll to bottom of messages
    function scrollToBottom() {
        const messagesList = document.getElementById('messagesList');
        if (messagesList) {
            messagesList.scrollTop = messagesList.scrollHeight;
        }
    }
    
    // Initial scroll
    scrollToBottom();
    
    // Handle message form submission (Reply in existing conversation)
    $('#messageForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i>Sending...');
        
        $.ajax({
            url: '{{ route("student.messages.send") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Clear form
                    $('#messageForm')[0].reset();
                    removeAttachment();
                    
                    // Add message to chat
                    const messageHtml = `
                        <div class="message-bubble sent">
                            <div class="message-content">
                                <p class="mb-0">${response.data.content}</p>
                                ${response.data.has_attachment ? `
                                    <div class="attachment-preview">
                                        <iconify-icon icon="material-symbols:attach-file" class="text-lg"></iconify-icon>
                                        <span>${response.data.attachment_name}</span>
                                    </div>
                                ` : ''}
                                <div class="message-time text-end">
                                    ${response.data.time_ago}
                                    <iconify-icon icon="material-symbols:done" class="ms-1 message-status"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    $('#messagesList').append(messageHtml);
                    scrollToBottom();
                    
                    showToast('Message sent successfully!', 'success');
                } else {
                    showToast(response.message || 'Failed to send message', 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response?.message || 'Failed to send message', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Handle new message form submission (New conversation)
    $('#newMessageForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!$('#userSelect').val()) {
            showToast('Please select a recipient', 'error');
            return;
        }
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i>Sending...');
        
        $.ajax({
            url: '{{ route("student.messages.send") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#newMessageModal').modal('hide');
                    showToast('Message sent successfully!', 'success');
                    
                    // Redirect to conversation
                    const receiverId = $('#userSelect').val();
                    window.location.href = `{{ route("student.messages.index") }}?conversation=${receiverId}`;
                } else {
                    showToast(response.message || 'Failed to send message', 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response?.message || 'Failed to send message', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Handle attachment preview
    $('#attachment').on('change', function() {
        const file = this.files[0];
        if (file) {
            $('#attachmentName').text(file.name);
            $('#attachmentPreview').show();
        }
    });
    
    // Handle filter changes
    $('input[name="messageFilter"]').on('change', function() {
        const filter = $(this).val();
        window.location.href = `{{ route("student.messages.index") }}?filter=${filter}`;
    });
    
    // Clear modal form when closed
    $('#newMessageModal').on('hidden.bs.modal', function() {
        $('#newMessageForm')[0].reset();
        $('#userSelect').val('');
    });

    // Auto-resize textarea
    $('#messageInput').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Enter to send (Shift+Enter for new line)
    $('#messageInput').on('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            $('#messageForm').submit();
        }
    });

    // Show user info on select change
    $('#userSelect').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const role = selectedOption.data('role');
        const level = selectedOption.data('level');
        
        if (role && level) {
            console.log(`Selected: ${selectedOption.text()} - Role: ${role}, Level: ${level}`);
        }
    });
});

// Load conversation function
function loadConversation(userId) {
    window.location.href = `{{ route("student.messages.index") }}?conversation=${userId}`;
}

// Remove attachment function
function removeAttachment() {
    $('#attachment').val('');
    $('#attachmentPreview').hide();
}

// Insert quick reply
function insertQuickReply(text) {
    const messageInput = document.getElementById('messageInput');
    messageInput.value = text;
    messageInput.focus();
}

// Mark all messages as read
function markAllAsRead(conversationWith = null) {
    const data = conversationWith ? { conversation_with: conversationWith } : {};
    
    $.ajax({
        url: '{{ route("student.messages.mark-all-read") }}',
        method: 'POST',
        data: {
            ...data,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showToast(`Marked ${response.count} messages as read`, 'success');
                
                // Update UI
                $('.conversation-item.unread').removeClass('unread');
                $('.message-status').addClass('read');
                
                // Reload page to update counts
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        },
        error: function() {
            showToast('Failed to mark messages as read', 'error');
        }
    });
}

// Toast notification function
function showToast(message, type = 'info') {
    // Create a simple toast notification
    const toast = $(`
        <div class="toast-notification ${type}" style="
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 6px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            max-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            background-color: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        ">
            ${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'} ${message}
        </div>
    `);
    
    $('body').append(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.fadeOut(300, function() {
            $(this).remove();
        });
    }, 3000);
}
</script>

</x-student-layout>