<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'code',
        'description',
        'level',
        'semester',
        'status',
        'credit_units',
        'image',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function submissions()
    {
        return $this->hasManyThrough(Submission::class, Assignment::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'user_id')
                    ->withPivot(['enrolled_at', 'status'])
                    ->withTimestamps()
                    ->wherePivot('status', 'active');
    }

    public function allStudents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'user_id')
                    ->withPivot(['enrolled_at', 'status'])
                    ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseStudent::class, 'course_id');
    }

    public function activeEnrollments(): HasMany
    {
        return $this->hasMany(CourseStudent::class, 'course_id')->where('status', 'active');
    }

    // ==================== MODEL EVENTS ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });

        static::updating(function ($course) {
            if ($course->isDirty('title') && empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    // ==================== ENHANCED QUERY SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByInstructor($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->whereHas('instructor', function($q) use ($department) {
            $q->where('department', $department);
        });
    }

    public function scopeByFaculty($query, $faculty)
    {
        return $query->whereHas('instructor', function($q) use ($faculty) {
            $q->where('faculty', $faculty);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to get courses available for a student's level
     */
    public function scopeAvailableForLevel($query, $level)
    {
        return $query->where('level', $level)
                    ->where('status', 'active');
    }

    /**
     * Scope to get courses that a student is not enrolled in
     */
    public function scopeNotEnrolledByStudent($query, $studentId)
    {
        return $query->whereDoesntHave('students', function($q) use ($studentId) {
            $q->where('user_id', $studentId)
              ->where('status', 'active');
        });
    }

    /**
     * Scope to get courses available for enrollment by a specific student
     */
    public function scopeAvailableForEnrollment($query, $studentId, $studentLevel)
    {
        return $query->where('level', $studentLevel)
                    ->where('status', 'active')
                    ->whereDoesntHave('students', function($q) use ($studentId) {
                        $q->where('user_id', $studentId)
                          ->where('status', 'active');
                    });
    }

    /**
     * Scope to get courses enrolled by a specific student
     */
    public function scopeEnrolledByStudent($query, $studentId)
    {
        return $query->whereHas('students', function($q) use ($studentId) {
            $q->where('user_id', $studentId)
              ->where('status', 'active');
        });
    }

    /**
     * Scope to get courses for student's level and enrollment status
     */
    public function scopeForStudentAccess($query, $studentId, $studentLevel, $enrolledOnly = true)
    {
        $query = $query->where('level', $studentLevel)
                      ->where('status', 'active');

        if ($enrolledOnly) {
            $query->whereHas('students', function($q) use ($studentId) {
                $q->where('user_id', $studentId)
                  ->where('status', 'active');
            });
        }

        return $query;
    }

    // ==================== LEVEL-BASED ACCESS METHODS ====================

    /**
     * Check if course is accessible to a student based on level and enrollment
     */
    public function isAccessibleToStudent($studentId, $studentLevel): bool
    {
        // Check if course level matches student level
        if ($this->level !== $studentLevel) {
            return false;
        }

        // Check if student is enrolled
        return $this->students()
            ->where('user_id', $studentId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if course is available for enrollment by a student
     */
    public function isAvailableForEnrollment($studentId, $studentLevel): bool
    {
        // Check if course is active
        if ($this->status !== 'active') {
            return false;
        }

        // Check if course level matches student level
        if ($this->level !== $studentLevel) {
            return false;
        }

        // Check if student is not already enrolled
        return !$this->students()
            ->where('user_id', $studentId)
            ->where('status', 'active')
            ->exists();
    }

    // ==================== EXISTING METHODS (UNCHANGED) ====================

    public function getEnrolledStudentsCount(): int
    {
        try {
            return $this->students()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function hasStudent($userId): bool
    {
        return $this->students()->where('user_id', $userId)->exists();
    }

    public function enrollStudent($userId, $status = 'active'): bool
    {
        try {
            if (!$this->hasStudent($userId)) {
                $this->students()->attach($userId, [
                    'enrolled_at' => now(),
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function unenrollStudent($userId): bool
    {
        try {
            return $this->students()->detach($userId) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateStudentStatus($userId, $status): bool
    {
        try {
            $this->students()->updateExistingPivot($userId, [
                'status' => $status,
                'updated_at' => now(),
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // ==================== ACCESSORS ====================

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">Active</span>',
            'inactive' => '<span class="badge bg-danger-50 text-danger-600 px-8 py-4 rounded-4">Inactive</span>',
            'draft' => '<span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4">Draft</span>',
            default => '<span class="badge bg-gray-50 text-gray-600 px-8 py-4 rounded-4">Unknown</span>',
        };
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image 
            ? asset('storage/' . $this->image) 
            : asset('assets/images/thumbs/course-default.png');
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->code} - {$this->title}";
    }

    public function getLevelDisplayAttribute(): string
    {
        $levels = self::getLevels();
        return $levels[$this->level] ?? $this->level;
    }

    public function getSemesterDisplayAttribute(): string
    {
        $semesters = self::getSemesters();
        return $semesters[$this->semester] ?? $this->semester;
    }

    public function getStatsAttribute(): array
    {
        try {
            return [
                'assignments_count' => $this->assignments()->count(),
                'materials_count' => $this->materials()->count(),
                'active_assignments' => $this->assignments()->where('status', 'active')->count(),
                'total_submissions' => $this->submissions()->count(),
                'pending_submissions' => $this->submissions()->where('status', 'pending')->count(),
                'graded_submissions' => $this->submissions()->where('status', 'graded')->count(),
                'average_grade' => $this->submissions()->where('status', 'graded')->avg('grade'),
                'enrolled_students' => $this->getEnrolledStudentsCount(),
            ];
        } catch (\Exception $e) {
            return [
                'assignments_count' => 0,
                'materials_count' => 0,
                'active_assignments' => 0,
                'total_submissions' => 0,
                'pending_submissions' => 0,
                'graded_submissions' => 0,
                'average_grade' => null,
                'enrolled_students' => 0,
            ];
        }
    }

    // ==================== HELPER METHODS ====================

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    public function getAssignmentsCount(): int
    {
        try {
            return $this->assignments()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getMaterialsCount(): int
    {
        try {
            return $this->materials()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getActiveAssignmentsCount(): int
    {
        try {
            return $this->assignments()->where('status', 'active')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getOverdueAssignmentsCount(): int
    {
        try {
            return $this->assignments()
                ->where('status', 'active')
                ->where('deadline', '<', now())
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getUpcomingAssignmentsCount(): int
    {
        try {
            return $this->assignments()
                ->where('status', 'active')
                ->whereBetween('deadline', [now(), now()->addDays(7)])
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getRecentMaterialsCount(): int
    {
        try {
            return $this->materials()
                ->where('uploaded_at', '>=', now()->subDays(30))
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getProgressPercentage(): float
    {
        try {
            $totalAssignments = $this->assignments()->count();
            if ($totalAssignments === 0) {
                return 0;
            }

            $completedAssignments = $this->assignments()
                ->where('deadline', '<', now())
                ->count();

            return round(($completedAssignments / $totalAssignments) * 100, 2);
        } catch (\Exception $e) {
            return 0;
        }
    }

    // ==================== STATIC HELPER METHODS ====================

    public static function getLevels(): array
    {
        return [
            '100' => '100 Level',
            '200' => '200 Level',
            '300' => '300 Level',
            '400' => '400 Level',
        ];
    }

    public static function getSemesters(): array
    {
        return [
            'first' => 'First Semester',
            'second' => 'Second Semester',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'draft' => 'Draft',
        ];
    }

    public static function getDashboardSummary($instructorId = null): array
    {
        $query = self::query();
        
        if ($instructorId) {
            $query->where('user_id', $instructorId);
        }

        return [
            'total' => $query->count(),
            'active' => $query->where('status', 'active')->count(),
            'draft' => $query->where('status', 'draft')->count(),
            'inactive' => $query->where('status', 'inactive')->count(),
        ];
    }

    public static function getPopularCourses($limit = 5)
    {
        return self::withCount(['assignments', 'materials'])
            ->orderByDesc('assignments_count')
            ->orderByDesc('materials_count')
            ->limit($limit)
            ->get();
    }

    public function getSelectOptionAttribute(): string
    {
        return "{$this->code} - {$this->title} ({$this->level_display})";
    }

    public function getUrlAttribute(): string
    {
        return route('courses.show', $this->slug);
    }

    public function hasContent(): bool
    {
        return $this->getAssignmentsCount() > 0 || $this->getMaterialsCount() > 0;
    }

    public function getActivityStatusAttribute(): string
    {
        if (!$this->isActive()) {
            return 'inactive';
        }

        $recentActivity = $this->assignments()
            ->where('created_at', '>=', now()->subDays(30))
            ->exists() || 
            $this->materials()
            ->where('uploaded_at', '>=', now()->subDays(30))
            ->exists();

        return $recentActivity ? 'active' : 'dormant';
    }
}