<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FileHelper;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'receiver_role',
        'content',
        'attachment',
        'is_read',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the sender of the message
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope to get unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get read messages
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope to get messages for a specific user (sent or received)
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
        });
    }

    /**
     * Scope to get conversation between two users
     */
    public function scopeConversation($query, $user1Id, $user2Id)
    {
        return $query->where(function($q) use ($user1Id, $user2Id) {
            $q->where(function($subQ) use ($user1Id, $user2Id) {
                $subQ->where('sender_id', $user1Id)
                     ->where('receiver_id', $user2Id);
            })->orWhere(function($subQ) use ($user1Id, $user2Id) {
                $subQ->where('sender_id', $user2Id)
                     ->where('receiver_id', $user1Id);
            });
        })->orderBy('created_at', 'asc');
    }

    /**
     * Scope to get messages by receiver role
     */
    public function scopeByReceiverRole($query, $role)
    {
        return $query->where('receiver_role', $role);
    }

    /**
     * Scope to get recent messages
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get attachment URL
     */
    protected function attachmentUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attachment 
                ? Storage::url($this->attachment)
                : null
        );
    }

    /**
     * Get formatted created date
     */
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->format('M d, Y \a\t g:i A')
        );
    }

    /**
     * Get time ago format
     */
    protected function timeAgo(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->diffForHumans()
        );
    }

    /**
     * Get message preview (first 50 characters)
     */
    protected function preview(): Attribute
    {
        return Attribute::make(
            get: fn () => \Str::limit($this->content, 50)
        );
    }

    // ==================== HELPER METHODS ====================

    /**
     * Mark message as read
     */
    public function markAsRead(): bool
    {
        if (!$this->is_read) {
            return $this->update(['is_read' => true]);
        }
        return true;
    }

    /**
     * Mark message as unread
     */
    public function markAsUnread(): bool
    {
        if ($this->is_read) {
            return $this->update(['is_read' => false]);
        }
        return true;
    }

    /**
     * Check if message has attachment
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment);
    }

    /**
     * Get attachment file name
     */
    public function getAttachmentName(): ?string
    {
        if (!$this->hasAttachment()) {
            return null;
        }
        
        return basename($this->attachment);
    }

    /**
     * Get attachment file extension
     */
    public function getAttachmentExtension(): ?string
    {
        if (!$this->hasAttachment()) {
            return null;
        }
        
        return strtolower(pathinfo($this->attachment, PATHINFO_EXTENSION));
    }

    /**
     * Get attachment file icon
     */
    public function getAttachmentIcon(): string
    {
        if (!$this->hasAttachment()) {
            return 'file';
        }
        
        return FileHelper::getFileIcon($this->getAttachmentExtension());
    }

    /**
     * Get attachment file type
     */
    public function getAttachmentType(): string
    {
        if (!$this->hasAttachment()) {
            return 'File';
        }
        
        return FileHelper::getFileType($this->getAttachmentExtension());
    }

    /**
     * Check if attachment is an image
     */
    public function isAttachmentImage(): bool
    {
        if (!$this->hasAttachment()) {
            return false;
        }
        
        return FileHelper::isImage($this->getAttachmentExtension());
    }

    /**
     * Get attachment file size
     */
    public function getAttachmentSize(): ?string
    {
        if (!$this->hasAttachment() || !Storage::exists($this->attachment)) {
            return null;
        }
        
        $bytes = Storage::size($this->attachment);
        return FileHelper::formatFileSize($bytes);
    }

    /**
     * Get attachment file category
     */
    public function getAttachmentCategory(): string
    {
        if (!$this->hasAttachment()) {
            return 'file';
        }
        
        return FileHelper::getFileCategory($this->getAttachmentExtension());
    }

    /**
     * Delete attachment file
     */
    public function deleteAttachment(): bool
    {
        if ($this->hasAttachment() && Storage::exists($this->attachment)) {
            Storage::delete($this->attachment);
            return $this->update(['attachment' => null]);
        }
        return true;
    }

    /**
     * Get conversation partner for a given user
     */
    public function getConversationPartner($userId): ?User
    {
        if ($this->sender_id === $userId) {
            return $this->receiver;
        } elseif ($this->receiver_id === $userId) {
            return $this->sender;
        }
        
        return null;
    }

    /**
     * Check if message belongs to user
     */
    public function belongsToUser($userId): bool
    {
        return $this->sender_id === $userId || $this->receiver_id === $userId;
    }

    /**
     * Get message status for a user
     */
    public function getStatusForUser($userId): string
    {
        if ($this->sender_id === $userId) {
            return 'sent';
        } elseif ($this->receiver_id === $userId) {
            return $this->is_read ? 'read' : 'unread';
        }
        
        return 'unknown';
    }

    // ==================== STATIC METHODS ====================

    /**
     * Get unread count for user
     */
    public static function getUnreadCountForUser($userId): int
    {
        return static::where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->count();
    }

    /**
     * Get conversation list for user
     */
    public static function getConversationsForUser($userId, $limit = 20)
    {
        return static::forUser($userId)
                    ->with(['sender', 'receiver'])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy(function($message) use ($userId) {
                        return $message->sender_id === $userId 
                            ? $message->receiver_id 
                            : $message->sender_id;
                    })
                    ->map(function($messages) {
                        return $messages->first();
                    })
                    ->take($limit);
    }

    /**
     * Mark all messages as read for user
     */
    public static function markAllAsReadForUser($userId): int
    {
        return static::where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
    }
    /**
 * Get courses taught by this instructor
 */
public function taughtCourses(): HasMany
{
    return $this->hasMany(Course::class, 'instructor_id');
}

/**
 * Get sent messages
 */
public function sentMessages(): HasMany
{
    return $this->hasMany(Message::class, 'sender_id');
}

/**
 * Get received messages
 */
public function receivedMessages(): HasMany
{
    return $this->hasMany(Message::class, 'receiver_id');
}

/**
 * Get profile image URL
 */
public function getProfileImageUrlAttribute()
{
    if ($this->avatar) {
        return Storage::url($this->avatar);
    }
    
    return '/assets/images/default-avatar.png';
}

/**
 * Get display name with role
 */
public function getDisplayNameAttribute()
{
    return $this->name . ' (' . ucfirst($this->role) . ')';
}

/**
 * Get role display name
 */
public function getRoleDisplayName()
{
    return ucfirst($this->role);
}
}