<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    // Role constants - using class constants for better maintainability
    public const ROLE_STUDENT = 'student';
    public const ROLE_LECTURER = 'lecturer';
    public const ROLE_INSTRUCTOR = 'instructor'; 
    public const ROLE_ADMIN = 'admin';

    // Cache keys
    private const CACHE_USER_STATS = 'user_stats_';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'gender',
        'matric_or_staff_id',
        'department',
        'faculty',
        'level',
        'avatar',
        'birth_date',
        'address',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    // ==================== MODEL EVENTS ====================

    protected static function boot()
    {
        parent::boot();

        // Clear cache when user is updated
        static::updated(function ($user) {
            Cache::forget(self::CACHE_USER_STATS . $user->id);
        });

        static::deleted(function ($user) {
            Cache::forget(self::CACHE_USER_STATS . $user->id);
        });
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Relationship: Courses taught by this lecturer/instructor
     */
    public function taughtCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'user_id');
    }

    /**
     * Alias for taughtCourses for backward compatibility
     */
    public function courses(): HasMany
    {
        return $this->taughtCourses();
    }

    /**
     * Relationship: Courses enrolled by this student
     */
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_student', 'user_id', 'course_id')
                    ->withPivot(['enrolled_at', 'status'])
                    ->withTimestamps()
                    ->wherePivot('status', 'active');
    }

    /**
     * Relationship: All courses (including inactive enrollments)
     */
    public function allEnrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_student', 'user_id', 'course_id')
                    ->withPivot(['enrolled_at', 'status'])
                    ->withTimestamps();
    }

    /**
     * Relationship: Course enrollments
     */
    public function courseEnrollments(): HasMany
    {
        return $this->hasMany(CourseStudent::class, 'user_id');
    }

    /**
     * Relationship: Assignments created by this instructor
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'user_id');
    }

    /**
     * Relationship: Submissions made by this student
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    /**
     * Relationship: Materials uploaded by this lecturer/instructor
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'user_id');
    }

    // ==================== MESSAGE RELATIONSHIPS ====================

    /**
     * Messages sent by this user
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Messages received by this user
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * All messages (sent and received)
     */
    public function allMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id')
                    ->union($this->hasMany(Message::class, 'receiver_id'));
    }

    /**
     * Unread messages received by this user
     */
    public function unreadMessages(): HasMany
    {
        return $this->receivedMessages()->where('is_read', false);
    }

    // ==================== ROLE CHECKING METHODS ====================

    /**
     * Check if user has a specific role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        // Handle both 'lecturer' and 'instructor' for compatibility
        if (in_array($role, ['instructor', 'lecturer']) && 
            in_array($this->role, [self::ROLE_LECTURER, self::ROLE_INSTRUCTOR])) {
            return true;
        }
        
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return collect($roles)->contains(fn($role) => $this->hasRole($role));
    }

    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    /**
     * Check if user is a lecturer
     */
    public function isLecturer(): bool
    {
        return in_array($this->role, [self::ROLE_LECTURER, self::ROLE_INSTRUCTOR]);
    }

    /**
     * Check if user is an instructor (alias for lecturer)
     */
    public function isInstructor(): bool
    {
        return $this->isLecturer();
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // ==================== ENROLLMENT METHODS ====================

    /**
     * Check if student is enrolled in a course
     */
    public function isEnrolledIn(string $courseId): bool
    {
        return $this->enrolledCourses()->where('course_id', $courseId)->exists();
    }

    /**
     * Enroll in a course
     */
    public function enrollInCourse(string $courseId, string $status = 'active'): bool
    {
        try {
            if (!$this->isStudent()) {
                return false;
            }

            if (!$this->isEnrolledIn($courseId)) {
                $this->enrolledCourses()->attach($courseId, [
                    'enrolled_at' => now(),
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Clear cache
                Cache::forget(self::CACHE_USER_STATS . $this->id);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('Failed to enroll user in course', [
                'user_id' => $this->id,
                'course_id' => $courseId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Unenroll from a course
     */
    public function unenrollFromCourse(string $courseId): bool
    {
        try {
            $result = $this->enrolledCourses()->detach($courseId) > 0;
            if ($result) {
                Cache::forget(self::CACHE_USER_STATS . $this->id);
            }
            return $result;
        } catch (\Exception $e) {
            \Log::error('Failed to unenroll user from course', [
                'user_id' => $this->id,
                'course_id' => $courseId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    // ==================== MESSAGE METHODS ====================

    /**
     * Send a message to another user
     */
    public function sendMessage(string $receiverId, string $content, ?string $attachment = null, ?string $receiverRole = null): Message
    {
        return Message::create([
            'sender_id' => $this->id,
            'receiver_id' => $receiverId,
            'receiver_role' => $receiverRole,
            'content' => $content,
            'attachment' => $attachment,
        ]);
    }

    /**
     * Get unread message count
     */
    public function getUnreadMessageCount(): int
    {
        return $this->unreadMessages()->count();
    }

    /**
     * Get conversation with another user
     */
    public function getConversationWith(string $userId, int $limit = 50)
    {
        return Message::conversation($this->id, $userId)
                     ->with(['sender', 'receiver'])
                     ->limit($limit)
                     ->get();
    }

    /**
     * Mark all messages from a user as read
     */
    public function markMessagesAsReadFrom(string $senderId): int
    {
        return $this->receivedMessages()
                   ->where('sender_id', $senderId)
                   ->where('is_read', false)
                   ->update(['is_read' => true]);
    }

    /**
     * Get recent conversations
     */
    public function getRecentConversations(int $limit = 10)
    {
        return Message::getConversationsForUser($this->id, $limit);
    }

    /**
     * Check if user can message another user
     */
    public function canMessageUser(User $user): bool
    {
        // Admin can message anyone
        if ($this->isAdmin()) {
            return true;
        }

        // Students can message instructors and admins
        if ($this->isStudent()) {
            return $user->isInstructor() || $user->isAdmin();
        }

        // Instructors can message students and admins
        if ($this->isInstructor()) {
            return $user->isStudent() || $user->isAdmin();
        }

        return false;
    }

    // ==================== STATIC HELPER METHODS ====================

    /**
     * Get all available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_INSTRUCTOR => 'Instructor',
            self::ROLE_LECTURER => 'Lecturer',
            self::ROLE_STUDENT => 'Student',
        ];
    }

    /**
     * Get available genders
     */
    public static function getGenders(): array
    {
        return [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
        ];
    }

    /**
     * Get available levels for students
     */
    public static function getLevels(): array
    {
        return [
            '100' => '100 Level',
            '200' => '200 Level',
            '300' => '300 Level',
            '400' => '400 Level',
        ];
    }

    // ==================== ACCESSOR METHODS (Using Laravel 9+ Attribute class) ====================

    /**
     * Get role display name
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            self::ROLE_STUDENT => 'Student',
            self::ROLE_LECTURER => 'Lecturer',
            self::ROLE_INSTRUCTOR => 'Instructor',
            self::ROLE_ADMIN => 'Administrator',
            default => 'Unknown',
        };
    }

    /**
     * Get role badge HTML
     */
    protected function roleBadge(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->role) {
                self::ROLE_ADMIN => '<span class="badge bg-danger-50 text-danger-600 px-8 py-4 rounded-4">Admin</span>',
                self::ROLE_INSTRUCTOR => '<span class="badge bg-primary-50 text-primary-600 px-8 py-4 rounded-4">Instructor</span>',
                self::ROLE_LECTURER => '<span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4">Lecturer</span>',
                self::ROLE_STUDENT => '<span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">Student</span>',
                default => '<span class="badge bg-gray-50 text-gray-600 px-8 py-4 rounded-4">Unknown</span>',
            }
        );
    }

    /**
     * Get full name attribute
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name,
        );
    }

    /**
     * Get profile image URL
     */
    protected function profileImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->avatar 
                ? asset('storage/' . $this->avatar)
                : asset('assets/images/default-avatar.png')
        );
    }

    /**
     * Get user's initials for avatar
     */
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: function () {
                $names = explode(' ', $this->name);
                $initials = '';
                
                foreach ($names as $name) {
                    $initials .= strtoupper(substr($name, 0, 1));
                }
                
                return substr($initials, 0, 2); // Return max 2 initials
            }
        );
    }

    /**
     * Format user for display in dropdowns/selects
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: function () {
                $role = $this->getRoleDisplayName();
                $id = $this->matric_or_staff_id ?? 'N/A';
                
                return "{$this->name} ({$role} - {$id})";
            }
        );
    }

    /**
     * Get user statistics based on role (cached)
     */
    protected function stats(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Cache::remember(
                    self::CACHE_USER_STATS . $this->id,
                    self::CACHE_TTL,
                    function () {
                        try {
                            if ($this->isInstructor()) {
                                return [
                                    'courses_count' => $this->taughtCourses()->count(),
                                    'assignments_count' => $this->assignments()->count(),
                                    'materials_count' => $this->materials()->count(),
                                    'total_submissions' => $this->assignments()->withCount('submissions')->get()->sum('submissions_count'),
                                    'active_courses' => $this->taughtCourses()->where('status', 'active')->count(),
                                    'unread_messages' => $this->getUnreadMessageCount(),
                                ];
                            }
                            
                            if ($this->isStudent()) {
                                return [
                                    'enrolled_courses' => $this->enrolledCourses()->count(),
                                    'submissions_count' => $this->submissions()->count(),
                                    'graded_submissions' => $this->submissions()->where('status', 'graded')->count(),
                                    'pending_submissions' => $this->submissions()->where('status', 'pending')->count(),
                                    'average_grade' => round($this->submissions()->where('status', 'graded')->avg('grade') ?? 0, 2),
                                    'unread_messages' => $this->getUnreadMessageCount(),
                                ];
                            }
                            
                            return [];
                        } catch (\Exception $e) {
                            \Log::error('Failed to get user stats', [
                                'user_id' => $this->id,
                                'error' => $e->getMessage()
                            ]);
                            return [];
                        }
                    }
                );
            }
        );
    }

    /**
     * Get the appropriate dashboard route based on role
     */
    public function getDashboardRoute(): string
    {
        return match($this->role) {
            self::ROLE_STUDENT => 'student.dashboard',
            self::ROLE_LECTURER, self::ROLE_INSTRUCTOR => 'instructor.dashboard',
            self::ROLE_ADMIN => 'admin.dashboard',
            default => 'dashboard',
        };
    }

    // ==================== QUERY SCOPES ====================

    /**
     * Scope to filter users by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope to get active users (if you have a status field)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get lecturers/instructors
     */
    public function scopeLecturers($query)
    {
        return $query->whereIn('role', [self::ROLE_LECTURER, self::ROLE_INSTRUCTOR]);
    }

    /**
     * Scope to get instructors (alias for lecturers)
     */
    public function scopeInstructors($query)
    {
        return $query->whereIn('role', [self::ROLE_LECTURER, self::ROLE_INSTRUCTOR]);
    }

    /**
     * Scope to get students
     */
    public function scopeStudents($query)
    {
        return $query->where('role', self::ROLE_STUDENT);
    }

    /**
     * Scope to get admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    /**
     * Scope to filter by department
     */
    public function scopeByDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope to filter by faculty
     */
    public function scopeByFaculty($query, string $faculty)
    {
        return $query->where('faculty', $faculty);
    }

    /**
     * Scope to filter by level (for students)
     */
    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to filter by gender
     */
    public function scopeByGender($query, string $gender)
    {
        return $query->where('gender', $gender);
    }

    /**
     * Scope to search users by name, email, or matric/staff ID
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('matric_or_staff_id', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to get users with complete profiles
     */
    public function scopeWithCompleteProfile($query)
    {
        return $query->whereNotNull('name')
                    ->whereNotNull('email')
                    ->whereNotNull('phone')
                    ->whereNotNull('department')
                    ->whereNotNull('faculty');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if user has completed profile
     */
    public function hasCompleteProfile(): bool
    {
        $requiredFields = ['name', 'email', 'phone', 'department', 'faculty'];
        
        if ($this->isStudent()) {
            $requiredFields[] = 'level';
            $requiredFields[] = 'matric_or_staff_id';
        }
        
        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get missing profile fields
     */
    public function getMissingProfileFields(): array
    {
        $requiredFields = ['name', 'email', 'phone', 'department', 'faculty'];
        
        if ($this->isStudent()) {
            $requiredFields[] = 'level';
            $requiredFields[] = 'matric_or_staff_id';
        }
        
        $missing = [];
        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                $missing[] = $field;
            }
        }
        
        return $missing;
    }

    /**
     * Clear user cache
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_USER_STATS . $this->id);
    }

    /**
     * Check if user can access course
     */
    public function canAccessCourse(Course $course): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        if ($this->isInstructor()) {
            return $course->user_id === $this->id;
        }
        
        if ($this->isStudent()) {
            return $this->isEnrolledIn($course->id);
        }
        
        return false;
    }

    /**
     * Get user's courses based on role
     */
    public function getCoursesForRole()
    {
        if ($this->isInstructor()) {
            return $this->taughtCourses();
        }
        
        if ($this->isStudent()) {
            return $this->enrolledCourses();
        }
        
        return collect();
    }

    /**
     * Format user for JSON API responses
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'role_display' => $this->getRoleDisplayName(),
            'department' => $this->department,
            'faculty' => $this->faculty,
            'level' => $this->level,
            'matric_or_staff_id' => $this->matric_or_staff_id,
            'avatar_url' => $this->profile_image_url,
            'initials' => $this->initials,
            'has_complete_profile' => $this->hasCompleteProfile(),
            'unread_messages' => $this->getUnreadMessageCount(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}