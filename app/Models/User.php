<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    // Role constants
    const ROLE_STUDENT = 'student';
    const ROLE_LECTURER = 'lecturer';
    const ROLE_INSTRUCTOR = 'instructor'; 
    const ROLE_ADMIN = 'admin';

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
        'profile_image', // Added for compatibility
        'birth_date',
        'address',
        'email_verified_at', // Added for seeding
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

    // ==================== RELATIONSHIPS ====================

    /**
     * Relationship: Courses taught by this lecturer/instructor
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'user_id');
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
        if ($role === 'instructor' && ($this->role === self::ROLE_LECTURER || $this->role === self::ROLE_INSTRUCTOR)) {
            return true;
        }
        if ($role === 'lecturer' && ($this->role === self::ROLE_LECTURER || $this->role === self::ROLE_INSTRUCTOR)) {
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
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
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
        return $this->role === self::ROLE_LECTURER || $this->role === self::ROLE_INSTRUCTOR;
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

    // ==================== ACCESSOR METHODS ====================

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
    public function getRoleBadgeAttribute(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => '<span class="badge bg-danger-50 text-danger-600 px-8 py-4 rounded-4">Admin</span>',
            self::ROLE_INSTRUCTOR => '<span class="badge bg-primary-50 text-primary-600 px-8 py-4 rounded-4">Instructor</span>',
            self::ROLE_LECTURER => '<span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4">Lecturer</span>',
            self::ROLE_STUDENT => '<span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">Student</span>',
            default => '<span class="badge bg-gray-50 text-gray-600 px-8 py-4 rounded-4">Unknown</span>',
        };
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get profile image URL
     */
    public function getProfileImageUrlAttribute(): string
    {
        // Check both avatar and profile_image fields for compatibility
        $image = $this->avatar ?? $this->profile_image;
        
        return $image 
            ? asset('storage/' . $image)
            : asset('assets/images/default-avatar.png');
    }

    /**
     * Get the appropriate dashboard route based on role
     */
    public function getDashboardRoute(): string
    {
        return match($this->role) {
            self::ROLE_STUDENT => 'student.dashboard',
            self::ROLE_LECTURER => 'instructor.dashboard', // Routes use 'instructor'
            self::ROLE_INSTRUCTOR => 'instructor.dashboard', // Both lecturer and instructor use same dashboard
            self::ROLE_ADMIN => 'admin.dashboard',
            default => 'dashboard',
        };
    }

    // ==================== QUERY SCOPES ====================

    /**
     * Scope to filter users by role
     */
    public function scopeByRole($query, $role)
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
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope to filter by faculty
     */
    public function scopeByFaculty($query, $faculty)
    {
        return $query->where('faculty', $faculty);
    }

    /**
     * Scope to filter by level (for students)
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to filter by gender
     */
    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get user's initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return substr($initials, 0, 2); // Return max 2 initials
    }

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
     * Get user statistics based on role
     */
    public function getStatsAttribute(): array
    {
        if ($this->isInstructor()) {
            return [
                'courses_count' => $this->courses()->count(),
                'assignments_count' => $this->assignments()->count(),
                'total_submissions' => $this->assignments()->withCount('submissions')->get()->sum('submissions_count'),
            ];
        }
        
        if ($this->isStudent()) {
            return [
                'submissions_count' => $this->submissions()->count(),
                'graded_submissions' => $this->submissions()->where('status', 'graded')->count(),
                'average_grade' => $this->submissions()->where('status', 'graded')->avg('grade'),
            ];
        }
        
        return [];
    }

    /**
     * Format user for display in dropdowns/selects
     */
    public function getDisplayNameAttribute(): string
    {
        $role = $this->getRoleDisplayName();
        $id = $this->matric_or_staff_id ?? 'N/A';
        
        return "{$this->name} ({$role} - {$id})";
    }
}