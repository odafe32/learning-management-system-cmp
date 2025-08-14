<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, HasUuids;

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

    /**
     * Get the instructor who teaches this course
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all assignments for this course
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get all materials for this course
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Get all submissions through assignments
     */
    public function submissions()
    {
        return $this->hasManyThrough(Submission::class, Assignment::class);
    }

    // ==================== MODEL EVENTS ====================

    /**
     * Automatically generate slug when creating/updating
     */
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

    // ==================== QUERY SCOPES ====================

    /**
     * Scope to get active courses
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get courses by instructor
     */
    public function scopeByInstructor($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get courses by level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to get courses by semester
     */
    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Scope to get courses by department
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->whereHas('instructor', function($q) use ($department) {
            $q->where('department', $department);
        });
    }

    /**
     * Scope to get courses by faculty
     */
    public function scopeByFaculty($query, $faculty)
    {
        return $query->whereHas('instructor', function($q) use ($faculty) {
            $q->where('faculty', $faculty);
        });
    }

    /**
     * Scope to search courses by title or code
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // ==================== ACCESSORS ====================

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">Active</span>',
            'inactive' => '<span class="badge bg-danger-50 text-danger-600 px-8 py-4 rounded-4">Inactive</span>',
            'draft' => '<span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4">Draft</span>',
            default => '<span class="badge bg-gray-50 text-gray-600 px-8 py-4 rounded-4">Unknown</span>',
        };
    }

    /**
     * Get course image URL
     */
    public function getImageUrlAttribute(): string
    {
        return $this->image 
            ? asset('storage/' . $this->image) 
            : asset('assets/images/thumbs/course-default.png');
    }

    /**
     * Get course display name (code + title)
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->code} - {$this->title}";
    }

    /**
     * Get level display name
     */
    public function getLevelDisplayAttribute(): string
    {
        $levels = self::getLevels();
        return $levels[$this->level] ?? $this->level;
    }

    /**
     * Get semester display name
     */
    public function getSemesterDisplayAttribute(): string
    {
        $semesters = self::getSemesters();
        return $semesters[$this->semester] ?? $this->semester;
    }

    /**
     * Get course statistics
     */
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
            ];
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if course is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if course is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if course is inactive
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    /**
     * Get assignments count
     */
    public function getAssignmentsCount(): int
    {
        try {
            return $this->assignments()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get materials count
     */
    public function getMaterialsCount(): int
    {
        try {
            return $this->materials()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get active assignments count
     */
    public function getActiveAssignmentsCount(): int
    {
        try {
            return $this->assignments()->where('status', 'active')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get overdue assignments count
     */
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

    /**
     * Get upcoming assignments (due within next 7 days)
     */
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

    /**
     * Get recent materials (uploaded in last 30 days)
     */
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

    /**
     * Get course progress percentage based on assignments
     */
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

    /**
     * Get available levels
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

    /**
     * Get available semesters
     */
    public static function getSemesters(): array
    {
        return [
            'first' => 'First Semester',
            'second' => 'Second Semester',
        ];
    }

    /**
     * Get available statuses
     */
    public static function getStatuses(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'draft' => 'Draft',
        ];
    }

    /**
     * Get courses summary for dashboard
     */
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

    /**
     * Get popular courses (most assignments/materials)
     */
    public static function getPopularCourses($limit = 5)
    {
        return self::withCount(['assignments', 'materials'])
            ->orderByDesc('assignments_count')
            ->orderByDesc('materials_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Format course for select dropdown
     */
    public function getSelectOptionAttribute(): string
    {
        return "{$this->code} - {$this->title} ({$this->level_display})";
    }

    /**
     * Get course URL/route
     */
    public function getUrlAttribute(): string
    {
        return route('courses.show', $this->slug);
    }

    /**
     * Check if course has content (assignments or materials)
     */
    public function hasContent(): bool
    {
        return $this->getAssignmentsCount() > 0 || $this->getMaterialsCount() > 0;
    }

    /**
     * Get course activity status
     */
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