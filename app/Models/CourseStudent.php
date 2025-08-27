<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseStudent extends Model
{
    use HasFactory;

    protected $table = 'course_student';

    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at',
        'status',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the student (user) for this enrollment
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the course for this enrollment
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // ==================== QUERY SCOPES ====================

    /**
     * Scope to get active enrollments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get enrollments by course
     */
    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope to get enrollments by student
     */
    public function scopeByStudent($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if enrollment is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get available statuses
     */
    public static function getStatuses(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'completed' => 'Completed',
            'dropped' => 'Dropped',
        ];
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">Active</span>',
            'inactive' => '<span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4">Inactive</span>',
            'completed' => '<span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4">Completed</span>',
            'dropped' => '<span class="badge bg-danger-50 text-danger-600 px-8 py-4 rounded-4">Dropped</span>',
            default => '<span class="badge bg-gray-50 text-gray-600 px-8 py-4 rounded-4">Unknown</span>',
        };
    }
}