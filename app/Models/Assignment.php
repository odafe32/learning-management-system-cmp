<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Assignment extends Model
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
        'course_id',
        'title',
        'slug',
        'description',
        'code_sample',
        'deadline',
        'status',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    // Automatically generate slug when creating/updating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($assignment) {
            if (empty($assignment->slug)) {
                $assignment->slug = Str::slug($assignment->title);
            }
        });

        static::updating(function ($assignment) {
            if ($assignment->isDirty('title')) {
                $assignment->slug = Str::slug($assignment->title);
            }
        });
    }

    // Scopes
    public function scopeByInstructor($query, $instructorId)
    {
        return $query->where('user_id', $instructorId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('deadline', '>', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Helper Methods
    public static function getStatuses(): array
    {
        return [
            'active' => 'Active',
            'draft' => 'Draft',
            'archived' => 'Archived',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'active' => 'Active',
            'draft' => 'Draft',
            'archived' => 'Archived',
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'active' => 'success',
            'draft' => 'warning',
            'archived' => 'secondary',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">Active</span>',
            'draft' => '<span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4">Draft</span>',
            'archived' => '<span class="badge bg-secondary-50 text-secondary-600 px-8 py-4 rounded-4">Archived</span>',
            default => '<span class="badge bg-gray-50 text-gray-600 px-8 py-4 rounded-4">Unknown</span>',
        };
    }

    public function isOverdue(): bool
    {
        return $this->deadline < now() && $this->status === 'active';
    }

    public function getDaysUntilDeadline(): int
    {
        return now()->diffInDays($this->deadline, false);
    }

    public function getFormattedDeadline(): string
    {
        return $this->deadline->format('M d, Y \a\t g:i A');
    }

    public function getSubmissionsCount(): int
    {
        try {
            return $this->submissions()->count();
        } catch (\Exception $e) {
            // Handle case where submissions table doesn't exist yet
            return 0;
        }
    }

    public function getPendingSubmissionsCount(): int
    {
        try {
            return $this->submissions()->where('status', 'pending')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getGradedSubmissionsCount(): int
    {
        try {
            return $this->submissions()->where('status', 'graded')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getSubmittedSubmissionsCount(): int
    {
        try {
            return $this->submissions()->where('status', 'submitted')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getAverageGrade(): ?float
    {
        try {
            return $this->submissions()
                ->where('status', 'graded')
                ->whereNotNull('grade')
                ->avg('grade');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getFormattedAverageGrade(): string
    {
        $average = $this->getAverageGrade();
        
        if ($average === null) {
            return 'N/A';
        }

        return number_format($average, 1) . '%';
    }

    public function hasSubmissions(): bool
    {
        try {
            return $this->submissions()->exists();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getSubmissionStats(): array
    {
        try {
            $total = $this->getSubmissionsCount();
            $submitted = $this->getSubmittedSubmissionsCount();
            $pending = $this->getPendingSubmissionsCount();
            $graded = $this->getGradedSubmissionsCount();

            return [
                'total' => $total,
                'submitted' => $submitted,
                'pending' => $pending,
                'graded' => $graded,
                'draft' => $total - $submitted - $pending - $graded,
                'average_grade' => $this->getAverageGrade(),
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'submitted' => 0,
                'pending' => 0,
                'graded' => 0,
                'draft' => 0,
                'average_grade' => null,
            ];
        }
    }
}