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
        'birth_date',
        'address',
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

    /**
     * Relationship: Courses taught by this lecturer/instructor
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Relationship: Materials uploaded by this lecturer/instructor
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Check if user has a specific role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        // Handle both 'lecturer' and 'instructor' for compatibility
        if ($role === 'instructor' && $this->role === self::ROLE_LECTURER) {
            return true;
        }
        if ($role === 'lecturer' && $this->role === self::ROLE_LECTURER) {
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
        return $this->role === self::ROLE_LECTURER;
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

    /**
     * Get all available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_STUDENT,
            self::ROLE_LECTURER,
            self::ROLE_ADMIN,
        ];
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            self::ROLE_STUDENT => 'Student',
            self::ROLE_LECTURER => 'Lecturer',
            self::ROLE_ADMIN => 'Administrator',
            default => 'Unknown',
        };
    }

    /**
     * Get the appropriate dashboard route based on role
     */
    public function getDashboardRoute(): string
    {
        return match($this->role) {
            self::ROLE_STUDENT => 'student.dashboard',
            self::ROLE_LECTURER => 'instructor.dashboard', // Routes use 'instructor'
            self::ROLE_ADMIN => 'admin.dashboard',
            default => 'dashboard',
        };
    }

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
        return $query->where('role', self::ROLE_LECTURER);
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
}