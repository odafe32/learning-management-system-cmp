<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Messages</h6>
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
                <li class="fw-medium text-primary">Messages</li>
            </ul>
        </div>

        <!-- Success Message -->
        <x-success-message />

        <!-- Statistics Cards -->
        <div class="row gy-4 mb-24">
            <div class="col-xxl-3 col-sm-6">
                <div class="card radius-8 border-0 overflow-hidden">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center gap-16">
                            <div class="w-64-px h-64-px bg-primary-50 text-primary rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                <i class="ph ph-envelope text-2xl"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Total Messages</span>
                                <h4 class="fw-bold text-primary-light mb-0">{{ number_format($stats['total_messages']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card radius-8 border-0 overflow-hidden">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center gap-16">
                            <div class="w-64-px h-64-px bg-warning-50 text-warning rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                <i class="ph ph-envelope-open text-2xl"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Unread</span>
                                <h4 class="fw-bold text-primary-light mb-0">{{ number_format($stats['unread_count']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card radius-8 border-0 overflow-hidden">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center gap-16">
                            <div class="w-64-px h-64-px bg-success-50 text-success rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                <i class="ph ph-paper-plane-tilt text-2xl"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Sent</span>
                                <h4 class="fw-bold text-primary-light mb-0">{{ number_format($stats['sent_count']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card radius-8 border-0 overflow-hidden">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center gap-16">
                            <div class="w-64-px h-64-px bg-info-50 text-info rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                <i class="ph ph-envelope-simple text-2xl"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Received</span>
                                <h4 class="fw-bold text-primary-light mb-0">{{ number_format($stats['received_count']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Interface -->
        <div class="row gy-4">
            <!-- Conversations Sidebar -->
            <div class="col-lg-4">
                <div class="card radius-8 border-0 overflow-hidden h-100">
                    <div class="card-header border-bottom border-gray-100 flex-between flex-wrap gap-8">
                        <h6 class="mb-0 fw-semibold">Conversations</h6>
                        <button type="button" class="btn btn-primary radius-8 px-16 py-8" data-bs-toggle="modal" data-bs-target="#composeModal">
                            <i class="ph ph-plus me-8"></i>
                            New Message
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <!-- Filter Tabs -->
                        <div class="border-bottom border-gray-100">
                            <nav class="nav nav-pills nav-fill">
                                <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" href="{{ route('instructor.messages.index', ['filter' => 'all']) }}">
                                    All
                                </a>
                                <a class="nav-link {{ $filter === 'unread' ? 'active' : '' }}" href="{{ route('instructor.messages.index', ['filter' => 'unread']) }}">
                                    Unread
                                    @if($stats['unread_count'] > 0)
                                        <span class="badge bg-warning text-white ms-2">{{ $stats['unread_count'] }}</span>
                                    @endif
                                </a>
                                <a class="nav-link {{ $filter === 'sent' ? 'active' : '' }}" href="{{ route('instructor.messages.index', ['filter' => 'sent']) }}">
                                    Sent
                                </a>
                            </nav>
                        </div>

                        <!-- Search -->
                        <div class="p-16">
                            <form method="GET" action="{{ route('instructor.messages.index') }}">
                                <input type="hidden" name="filter" value="{{ $filter }}">
                                <div class="position-relative">
                                    <input type="text" name="search" class="form-control radius-8 ps-44" 
                                           placeholder="Search messages..." value="{{ $search }}">
                                    <span class="position-absolute top-50 translate-middle-y ms-16 text-gray">
                                        <i class="ph ph-magnifying-glass"></i>
                                    </span>
                                </div>
                            </form>
                        </div>

                        <!-- Conversations List -->
                        <div class="conversations-list" style="max-height: 500px; overflow-y: auto;">
                            @forelse($conversations as $conversation)
                                @php
                                    $partner = $conversation->getConversationPartner(Auth::id());
                                    $hasUnread = !$conversation->is_read && $conversation->receiver_id === Auth::id();
                                @endphp
                                @if($partner)
                                <div class="conversation-item p-16 border-bottom border-gray-100 cursor-pointer hover-bg-gray-50 {{ $conversationWith == $partner->id ? 'bg-primary-50' : '' }} {{ $hasUnread ? 'unread-conversation' : '' }}" 
                                     data-user-id="{{ $partner->id }}" data-conversation-id="{{ $conversation->id }}">
                                    <div class="d-flex align-items-start gap-12">
                                        <div class="position-relative">
                                            <img src="{{ $partner->profile_image_url ?? '/assets/images/default-avatar.png' }}" alt="{{ $partner->name }}" 
                                                 class="w-44-px h-44-px rounded-circle object-fit-cover">
                                            @if($hasUnread)
                                                <span class="position-absolute top-0 end-0 w-12-px h-12-px bg-warning rounded-circle unread-indicator"></span>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex align-items-center justify-content-between mb-4">
                                                <h6 class="text-sm fw-semibold mb-0 text-truncate {{ $hasUnread ? 'fw-bold' : '' }}">{{ $partner->name }}</h6>
                                                <span class="text-xs text-secondary-light">{{ $conversation->time_ago }}</span>
                                            </div>
                                            <p class="text-xs text-secondary-light mb-0 text-truncate {{ $hasUnread ? 'fw-medium text-dark' : '' }}">{{ $conversation->preview }}</p>
                                            <div class="d-flex align-items-center justify-content-between mt-4">
                                                <span class="badge bg-{{ $partner->role === 'student' ? 'success' : ($partner->role === 'admin' ? 'danger' : 'primary') }}-50 text-{{ $partner->role === 'student' ? 'success' : ($partner->role === 'admin' ? 'danger' : 'primary') }} px-8 py-2 rounded-pill text-xs">
                                                    {{ ucfirst($partner->role) }}
                                                </span>
                                                <div class="d-flex align-items-center gap-4">
                                                    @if($conversation->hasAttachment())
                                                        <i class="ph ph-paperclip text-secondary-light"></i>
                                                    @endif
                                                    @if($hasUnread)
                                                        <span class="badge bg-warning text-white rounded-pill px-6 py-2 text-xs">New</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @empty
                                <div class="text-center py-5">
                                    <i class="ph ph-envelope text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600">No conversations found</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="col-lg-8">
                <div class="card radius-8 border-0 overflow-hidden h-100">
                    @if($conversationWith && $conversationPartner)
                        <!-- Conversation Header -->
                        <div class="card-header border-bottom border-gray-100 p-20">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-12">
                                    <img src="{{ $conversationPartner->profile_image_url ?? '/assets/images/default-avatar.png' }}" alt="{{ $conversationPartner->name }}" 
                                         class="w-44-px h-44-px rounded-circle object-fit-cover">
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $conversationPartner->name }}</h6>
                                        <span class="text-sm text-secondary-light">{{ ucfirst($conversationPartner->role) }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-8">
                                    <button type="button" class="btn btn-gray radius-8 px-16 py-8" onclick="markAllAsRead({{ $conversationPartner->id }})">
                                        <i class="ph ph-checks"></i>
                                        Mark All Read
                                    </button>
                                    <a href="{{ route('instructor.messages.index') }}" class="btn btn-gray radius-8 px-16 py-8">
                                        <i class="ph ph-arrow-left"></i>
                                        Back
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Messages Container -->
                        <div class="card-body p-0 d-flex flex-column" style="height: 600px;">
                            <div class="messages-container flex-grow-1 p-20" style="overflow-y: auto;" id="messagesContainer">
                                @forelse($messages as $message)
                                    <div class="message-item mb-16 {{ $message->sender_id === Auth::id() ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
                                        <div class="d-flex {{ $message->sender_id === Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                            <div class="message-bubble {{ $message->sender_id === Auth::id() ? 'bg-primary text-white' : 'bg-gray-100' }} {{ !$message->is_read && $message->receiver_id === Auth::id() ? 'unread-message' : '' }} p-12 radius-8" style="max-width: 70%;">
                                                <p class="mb-8">{{ $message->content }}</p>
                                                @if($message->hasAttachment())
                                                    <div class="attachment-item mt-8 p-8 bg-white bg-opacity-20 radius-4">
                                                        @if($message->isAttachmentImage())
                                                            <div class="mb-2">
                                                                <img src="{{ $message->attachment_url }}" alt="{{ $message->getAttachmentName() }}" 
                                                                     class="img-fluid rounded cursor-pointer attachment-image" 
                                                                     style="max-width: 200px; max-height: 150px;" 
                                                                     onclick="showImageModal('{{ $message->attachment_url }}', '{{ $message->getAttachmentName() }}')">
                                                            </div>
                                                        @endif
                                                        <a href="{{ $message->attachment_url }}" target="_blank" class="d-flex align-items-center gap-8 text-decoration-none {{ $message->sender_id === Auth::id() ? 'text-white' : 'text-primary' }}">
                                                            <i class="ph ph-{{ $message->getAttachmentIcon() }} text-lg"></i>
                                                            <div class="flex-grow-1">
                                                                <span class="text-sm fw-medium d-block">{{ $message->getAttachmentName() }}</span>
                                                                <small class="opacity-75">{{ $message->getAttachmentType() }}</small>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                                <div class="d-flex align-items-center justify-content-between mt-8">
                                                    <small class="{{ $message->sender_id === Auth::id() ? 'text-white-50' : 'text-secondary-light' }}">
                                                        {{ $message->time_ago }}
                                                    </small>
                                                    @if($message->sender_id === Auth::id())
                                                        <small class="text-white-50">
                                                            <i class="ph ph-check{{ $message->is_read ? '-double' : '' }}"></i>
                                                        </small>
                                                    @else
                                                        @if(!$message->is_read)
                                                            <span class="badge bg-warning text-white rounded-pill px-6 py-2 text-xs">Unread</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="ph ph-chat-circle text-4xl text-gray-400 mb-3"></i>
                                        <p class="text-gray-600">No messages in this conversation yet</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Message Input -->
                            <div class="border-top border-gray-100 p-20">
                                <form id="messageForm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="receiver_id" value="{{ $conversationPartner->id }}">
                                    <div class="d-flex align-items-end gap-12">
                                        <div class="flex-grow-1">
                                            <textarea name="content" class="form-control radius-8" rows="2" 
                                                      placeholder="Type your message..." required></textarea>
                                            <input type="file" name="attachment" id="messageAttachment" class="form-control mt-8" 
                                                   accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.mp3,.mp4,.wav,.avi,.mov,.zip,.rar">
                                            <div id="attachmentPreview" class="mt-2" style="display: none;"></div>
                                        </div>
                                        <button type="submit" class="btn btn-primary radius-8 px-20 py-11">
                                            <i class="ph ph-paper-plane-tilt"></i>
                                            Send
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- No Conversation Selected -->
                        <div class="card-body d-flex align-items-center justify-content-center" style="height: 600px;">
                            <div class="text-center">
                                <i class="ph ph-chat-circle text-6xl text-gray-400 mb-3"></i>
                                <h5 class="mb-2 fw-semibold">Select a conversation</h5>
                                <p class="text-gray-600 mb-4">Choose a conversation from the sidebar to start messaging</p>
                                <button type="button" class="btn btn-primary radius-8 px-20 py-11" data-bs-toggle="modal" data-bs-target="#composeModal">
                                    <i class="ph ph-plus me-8"></i>
                                    Start New Conversation
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Compose Message Modal -->
    <div class="modal fade" id="composeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content radius-16 border-0">
                <div class="modal-header border-bottom border-gray-100 pb-20 mb-20">
                    <h5 class="modal-title fw-semibold">Compose Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="composeForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-20">
                            <label for="recipient" class="form-label fw-semibold text-primary-light">To</label>
                            <select name="receiver_id" id="recipient" class="form-select radius-8" required>
                                <option value="">Select recipient...</option>
                                <optgroup label="Admins">
                                    @foreach($availableUsers->where('role', 'admin') as $user)
                                        <option value="{{ $user->id }}" data-role="admin" data-avatar="{{ $user->profile_image_url ?? '/assets/images/default-avatar.png' }}">
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Students">
                                    @foreach($availableUsers->where('role', 'student') as $user)
                                        <option value="{{ $user->id }}" data-role="student" data-avatar="{{ $user->profile_image_url ?? '/assets/images/default-avatar.png' }}">
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <div id="selectedRecipientPreview" class="mt-2" style="display: none;">
                                <div class="d-flex align-items-center gap-8 p-8 bg-primary-50 radius-4">
                                    <img id="selectedAvatar" src="" alt="" class="w-32-px h-32-px rounded-circle">
                                    <div class="flex-grow-1">
                                        <div class="fw-medium" id="selectedName"></div>
                                        <small class="text-secondary-light" id="selectedRole"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <label for="messageContent" class="form-label fw-semibold text-primary-light">Message</label>
                            <textarea name="content" id="messageContent" class="form-control radius-8" rows="5" 
                                      placeholder="Type your message..." required></textarea>
                        </div>
                        <div class="mb-20">
                            <label for="composeAttachment" class="form-label fw-semibold text-primary-light">Attachment (Optional)</label>
                            <input type="file" name="attachment" id="composeAttachment" class="form-control radius-8" 
                                   accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.mp3,.mp4,.wav,.avi,.mov,.zip,.rar">
                            <small class="text-secondary-light">Max file size: 10MB. Allowed types: PDF, DOC, DOCX, TXT, JPG, PNG, GIF, MP3, MP4, ZIP, etc.</small>
                            <div id="composeAttachmentPreview" class="mt-2" style="display: none;"></div>
                        </div>
                        
                        <!-- Move submit button inside the form -->
                        <div class="d-flex justify-content-end gap-8 mt-20 pt-20 border-top border-gray-100">
                            <button type="button" class="btn btn-gray radius-8 px-20 py-11" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary radius-8 px-20 py-11" id="sendMessageBtn">
                                <i class="ph ph-paper-plane-tilt me-8"></i>
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ph ph-check-circle me-2"></i>
                    <span id="toastMessage">Message sent successfully!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Messages page loaded');
            
            // Check if CSRF token exists
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found! Make sure you have <meta name="csrf-token" content="{{ csrf_token() }}"> in your layout');
            } else {
                console.log('CSRF token found:', csrfToken.getAttribute('content'));
            }

            // Auto-scroll to bottom of messages
            const messagesContainer = document.getElementById('messagesContainer');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Handle conversation item clicks with unread marking
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.addEventListener('click', function() {
                    const userId = this.dataset.userId;
                    const conversationId = this.dataset.conversationId;
                    
                    console.log('Conversation clicked:', userId);
                    
                    // Mark conversation as read visually
                    this.classList.remove('unread-conversation');
                    const unreadIndicator = this.querySelector('.unread-indicator');
                    if (unreadIndicator) {
                        unreadIndicator.remove();
                    }
                    const newBadge = this.querySelector('.badge');
                    if (newBadge && newBadge.textContent === 'New') {
                        newBadge.remove();
                    }
                    
                    // Navigate to conversation
                    window.location.href = `{{ route('instructor.messages.index') }}?conversation=${userId}`;
                });
            });

            // Handle message form submission
            const messageForm = document.getElementById('messageForm');
            if (messageForm) {
                console.log('Message form found');
                messageForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Message form submitted');
                    sendMessage(this);
                });
            } else {
                console.log('Message form not found');
            }

            // Handle compose form submission
            const composeForm = document.getElementById('composeForm');
            if (composeForm) {
                console.log('Compose form found');
                composeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Compose form submitted');
                    sendMessage(this, true);
                });
            } else {
                console.log('Compose form not found');
            }

            // Auto-resize textarea
            document.querySelectorAll('textarea').forEach(textarea => {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });
            });

            // Handle recipient selection in compose modal
            const recipientSelect = document.getElementById('recipient');
            if (recipientSelect) {
                recipientSelect.addEventListener('change', function() {
                    console.log('Recipient selected:', this.value);
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value) {
                        const preview = document.getElementById('selectedRecipientPreview');
                        const avatar = document.getElementById('selectedAvatar');
                        const name = document.getElementById('selectedName');
                        const role = document.getElementById('selectedRole');
                        
                        avatar.src = selectedOption.dataset.avatar;
                        name.textContent = selectedOption.text.split(' (')[0];
                        role.textContent = selectedOption.dataset.role.charAt(0).toUpperCase() + selectedOption.dataset.role.slice(1);
                        
                        preview.style.display = 'block';
                    } else {
                        document.getElementById('selectedRecipientPreview').style.display = 'none';
                    }
                });
            }

            // Handle file attachment preview
            const messageAttachment = document.getElementById('messageAttachment');
            if (messageAttachment) {
                messageAttachment.addEventListener('change', function() {
                    showFilePreview(this, 'attachmentPreview');
                });
            }

            const composeAttachment = document.getElementById('composeAttachment');
            if (composeAttachment) {
                composeAttachment.addEventListener('change', function() {
                    showFilePreview(this, 'composeAttachmentPreview');
                });
            }

            // Mark messages as read when viewing conversation
            if (messagesContainer) {
                const unreadMessages = messagesContainer.querySelectorAll('.unread-message');
                if (unreadMessages.length > 0) {
                    setTimeout(() => {
                        unreadMessages.forEach(message => {
                            message.classList.remove('unread-message');
                            const unreadBadge = message.querySelector('.badge');
                            if (unreadBadge && unreadBadge.textContent === 'Unread') {
                                unreadBadge.remove();
                            }
                        });
                    }, 2000); // Mark as read after 2 seconds of viewing
                }
            }
        });

        function showFilePreview(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            
            if (!file) {
                preview.style.display = 'none';
                return;
            }

            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
            const fileExtension = fileName.split('.').pop().toLowerCase();
            const fileIcon = getFileIconJS(fileExtension);
            const fileType = getFileTypeJS(fileExtension);
            
            let previewHtml = `
                <div class="d-flex align-items-center gap-8 p-8 bg-light rounded">
                    <i class="ph ph-${fileIcon} text-2xl text-primary"></i>
                    <div class="flex-grow-1">
                        <div class="fw-medium">${fileName}</div>
                        <small class="text-muted">${fileType} • ${fileSize} MB</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFilePreview('${input.id}', '${previewId}')">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
            `;

            // Add image preview for image files
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewHtml = `
                        <div class="p-8 bg-light rounded">
                            <div class="mb-2">
                                <img src="${e.target.result}" alt="${fileName}" class="img-fluid rounded" style="max-width: 200px; max-height: 150px;">
                            </div>
                            <div class="d-flex align-items-center gap-8">
                                <i class="ph ph-${fileIcon} text-lg text-primary"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">${fileName}</div>
                                    <small class="text-muted">${fileType} • ${fileSize} MB</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFilePreview('${input.id}', '${previewId}')">
                                    <i class="ph ph-x"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    preview.innerHTML = previewHtml;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = previewHtml;
            }
            
            preview.style.display = 'block';
        }

        function clearFilePreview(inputId, previewId) {
            document.getElementById(inputId).value = '';
            document.getElementById(previewId).style.display = 'none';
        }

        function getFileIconJS(extension) {
            const iconMap = {
                // Images
                'jpg': 'image', 'jpeg': 'image', 'png': 'image', 'gif': 'image', 'webp': 'image',
                // Documents
                'pdf': 'file-pdf', 'doc': 'file-doc', 'docx': 'file-doc', 'txt': 'file-text',
                'xls': 'file-xls', 'xlsx': 'file-xls', 'ppt': 'file-ppt', 'pptx': 'file-ppt',
                // Audio
                'mp3': 'music-note', 'wav': 'music-note', 'flac': 'music-note', 'aac': 'music-note',
                // Video
                'mp4': 'video', 'avi': 'video', 'mov': 'video', 'wmv': 'video', 'flv': 'video',
                // Archives
                'zip': 'file-zip', 'rar': 'file-zip', '7z': 'file-zip', 'tar': 'file-zip',
                // Code
                'html': 'file-code', 'css': 'file-code', 'js': 'file-code', 'php': 'file-code',
                'py': 'file-code', 'java': 'file-code', 'cpp': 'file-code', 'c': 'file-code'
            };
            return iconMap[extension] || 'file';
        }

        function getFileTypeJS(extension) {
            const typeMap = {
                // Images
                'jpg': 'Image', 'jpeg': 'Image', 'png': 'Image', 'gif': 'Image', 'webp': 'Image',
                // Documents
                'pdf': 'PDF Document', 'doc': 'Word Document', 'docx': 'Word Document', 'txt': 'Text File',
                'xls': 'Excel Spreadsheet', 'xlsx': 'Excel Spreadsheet', 'ppt': 'PowerPoint', 'pptx': 'PowerPoint',
                // Audio
                'mp3': 'Audio File', 'wav': 'Audio File', 'flac': 'Audio File', 'aac': 'Audio File',
                // Video
                'mp4': 'Video File', 'avi': 'Video File', 'mov': 'Video File', 'wmv': 'Video File', 'flv': 'Video File',
                // Archives
                'zip': 'Archive', 'rar': 'Archive', '7z': 'Archive', 'tar': 'Archive',
                // Code
                'html': 'HTML File', 'css': 'CSS File', 'js': 'JavaScript File', 'php': 'PHP File',
                'py': 'Python File', 'java': 'Java File', 'cpp': 'C++ File', 'c': 'C File'
            };
            return typeMap[extension] || 'File';
        }

        function showImageModal(src, title) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModalTitle').textContent = title;
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }

       function sendMessage(form, isCompose = false) {
    console.log('sendMessage function called', { isCompose });
    
    const formData = new FormData(form);
    
    // Find submit button - handle both cases
    let submitBtn;
    if (isCompose) {
        submitBtn = document.getElementById('sendMessageBtn');
    } else {
        submitBtn = form.querySelector('button[type="submit"]');
    }
    
    if (!submitBtn) {
        console.error('Submit button not found');
        showError('Submit button not found');
        return;
    }
    
    const originalText = submitBtn.innerHTML;

    // Log form data
    console.log('Form data:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    // Validate recipient for compose form
    if (isCompose && !formData.get('receiver_id')) {
        console.error('No recipient selected');
        showError('Please select a recipient');
        return;
    }

    // Validate content
    if (!formData.get('content') || formData.get('content').trim() === '') {
        console.error('No message content');
        showError('Please enter a message');
        return;
    }

    // Validate file size if attachment exists
    const attachment = formData.get('attachment');
    if (attachment && attachment.size > 0) {
        const maxSize = 10 * 1024 * 1024; // 10MB
        if (attachment.size > maxSize) {
            showError('File size cannot exceed 10MB');
            return;
        }
        
        // Check file type
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'audio/mpeg',
            'video/mp4',
            'audio/wav',
            'video/avi',
            'video/quicktime',
            'application/zip',
            'application/x-rar-compressed'
        ];
        
        if (!allowedTypes.includes(attachment.type)) {
            showError('Invalid file type. Please select a supported file format.');
            return;
        }
    }

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ph ph-spinner-gap ph-spin me-8"></i>Sending...';

    const url = '{{ route("instructor.messages.send") }}';
    console.log('Sending to URL:', url);

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        // Handle different response types
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // If not JSON, get text and try to parse
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Response is not valid JSON:', text);
                    throw new Error(`Server returned non-JSON response: ${response.status}`);
                }
            });
        }
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            if (isCompose) {
                // Close modal and redirect to conversation
                const modal = bootstrap.Modal.getInstance(document.getElementById('composeModal'));
                modal.hide();
                showSuccessToast(data.message);
                setTimeout(() => {
                    window.location.href = `{{ route('instructor.messages.index') }}?conversation=${formData.get('receiver_id')}`;
                }, 1000);
            } else {
                // Add message to conversation and clear form
                addMessageToConversation(data.data);
                form.reset();
                // Clear file preview
                const preview = document.getElementById('attachmentPreview');
                if (preview) {
                    preview.style.display = 'none';
                }
                showSuccessToast(data.message);
            }
        } else {
            console.error('Server returned error:', data.message);
            showError(data.message || 'Failed to send message');
            
            // Handle validation errors
            if (data.errors) {
                console.error('Validation errors:', data.errors);
                const firstError = Object.values(data.errors)[0];
                if (Array.isArray(firstError)) {
                    showError(firstError[0]);
                } else {
                    showError(firstError);
                }
            }
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showError('Failed to send message. Please check your connection and try again.');
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}
        function addMessageToConversation(messageData) {
            const messagesContainer = document.getElementById('messagesContainer');
            if (!messagesContainer) return;

            const attachmentHtml = messageData.has_attachment ? `
                <div class="attachment-item mt-8 p-8 bg-white bg-opacity-20 radius-4">
                    <div class="d-flex align-items-center gap-8 text-white">
                        <i class="ph ph-paperclip"></i>
                        <span class="text-sm">${messageData.attachment_name}</span>
                    </div>
                </div>
            ` : '';

            const messageHtml = `
                <div class="message-item mb-16 sent" data-message-id="${messageData.id}">
                    <div class="d-flex justify-content-end">
                        <div class="message-bubble bg-primary text-white p-12 radius-8" style="max-width: 70%;">
                            <p class="mb-8">${messageData.content}</p>
                            ${attachmentHtml}
                            <div class="d-flex align-items-center justify-content-between mt-8">
                                <small class="text-white-50">${messageData.time_ago}</small>
                                <small class="text-white-50">
                                    <i class="ph ph-check"></i>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function markAllAsRead(conversationWith = null) {
            const data = conversationWith ? { conversation_with: conversationWith } : {};
            
            fetch('{{ route("instructor.messages.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast(data.message);
                    
                    // Remove unread indicators from UI
                    document.querySelectorAll('.unread-indicator').forEach(indicator => {
                        indicator.remove();
                    });
                    document.querySelectorAll('.unread-message').forEach(message => {
                        message.classList.remove('unread-message');
                    });
                    document.querySelectorAll('.unread-conversation').forEach(conversation => {
                        conversation.classList.remove('unread-conversation');
                    });
                    
                    // Update unread count in stats
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Helper functions for showing messages
        function showSuccessToast(message) {
            const toast = document.getElementById('successToast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = message;
            
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 3000
            });
            bsToast.show();
        }

        function showError(message) {
            if (typeof toastr !== 'undefined') {
                toastr.error(message);
            } else {
                alert('Error: ' + message);
            }
        }

        // Reset compose modal when it's closed
        document.getElementById('composeModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('composeForm').reset();
            document.getElementById('selectedRecipientPreview').style.display = 'none';
            document.getElementById('composeAttachmentPreview').style.display = 'none';
        });

        // Add CSS for enhanced styling
        if (!document.getElementById('message-styles')) {
            const style = document.createElement('style');
            style.id = 'message-styles';
            style.textContent = `
                .ph-spin {
                    animation: spin 1s linear infinite;
                }
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                .cursor-pointer {
                    cursor: pointer;
                }
                .hover-bg-gray-50:hover {
                    background-color: #f8f9fa;
                }
                .unread-conversation {
                    background-color: #fff3cd !important;
                    border-left: 4px solid #ffc107;
                }
                .unread-message {
                    border-left: 4px solid #ffc107;
                    background-color: #fff3cd !important;
                }
                .unread-message.bg-gray-100 {
                    background-color: #fff3cd !important;
                }
                .message-bubble {
                    word-wrap: break-word;
                    word-break: break-word;
                }
                .unread-indicator {
                    animation: pulse 2s infinite;
                }
                @keyframes pulse {
                    0% { opacity: 1; }
                    50% { opacity: 0.5; }
                    100% { opacity: 1; }
                }
                .attachment-image:hover {
                    opacity: 0.8;
                    transform: scale(1.02);
                    transition: all 0.2s ease;
                }
                .toast-container {
                    z-index: 1060;
                }
            `;
            document.head.appendChild(style);
        }
    </script>
</x-instructor-layout>