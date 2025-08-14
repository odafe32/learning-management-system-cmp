<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Submission extends Model
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
        'assignment_id',
        'student_id',
        'code_content',
        'file_path',
        'status',
        'grade',
        'feedback',
        'submitted_at',
        'graded_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'grade' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Scopes
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByAssignment($query, $assignmentId)
    {
        return $query->where('assignment_id', $assignmentId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeGraded($query)
    {
        return $query->where('status', 'graded');
    }

    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }

    public function scopeOnTime($query)
    {
        return $query->whereHas('assignment', function($q) {
            $q->whereColumn('submissions.submitted_at', '<=', 'assignments.deadline');
        });
    }

    public function scopeLate($query)
    {
        return $query->whereHas('assignment', function($q) {
            $q->whereColumn('submissions.submitted_at', '>', 'assignments.deadline');
        });
    }

    // Helper Methods
    public static function getStatuses(): array
    {
        return [
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'pending' => 'Pending Review',
            'graded' => 'Graded',
            'returned' => 'Returned for Revision',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'draft' => 'secondary',
            'submitted' => 'info',
            'pending' => 'warning',
            'graded' => 'success',
            'returned' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => '<span class="badge bg-secondary-50 text-secondary-600 px-8 py-4 rounded-4">Draft</span>',
            'submitted' => '<span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4">Submitted</span>',
            'pending' => '<span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4">Pending Review</span>',
            'graded' => '<span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">Graded</span>',
            'returned' => '<span class="badge bg-danger-50 text-danger-600 px-8 py-4 rounded-4">Returned</span>',
            default => '<span class="badge bg-gray-50 text-gray-600 px-8 py-4 rounded-4">Unknown</span>',
        };
    }

    public function isLate(): bool
    {
        if (!$this->submitted_at || !$this->assignment) {
            return false;
        }

        return $this->submitted_at > $this->assignment->deadline;
    }

    public function isGraded(): bool
    {
        return $this->status === 'graded' && !is_null($this->grade);
    }

    public function getFormattedGrade(): string
    {
        if (!$this->isGraded()) {
            return 'Not graded';
        }

        return number_format($this->grade, 1) . '%';
    }

    public function getGradeLetterAttribute(): string
    {
        if (!$this->isGraded()) {
            return 'N/A';
        }

        $grade = $this->grade;
        
        // Updated grading scale
        if ($grade >= 70) return 'A';
        if ($grade >= 60) return 'B';
        if ($grade >= 50) return 'C';
        if ($grade >= 46) return 'D';
        if ($grade >= 41) return 'E';
        return 'F';
    }

    public function getGradeColorAttribute(): string
    {
        if (!$this->isGraded()) {
            return 'text-muted';
        }

        $grade = $this->grade;
        
        // Updated color scheme based on new grading scale
        if ($grade >= 70) return 'text-success';      // A - Green
        if ($grade >= 60) return 'text-info';         // B - Blue
        if ($grade >= 50) return 'text-primary';      // C - Primary
        if ($grade >= 46) return 'text-warning';      // D - Yellow
        if ($grade >= 41) return 'text-orange';       // E - Orange
        return 'text-danger';                          // F - Red
    }

    public function getGradeBadgeColorAttribute(): string
    {
        if (!$this->isGraded()) {
            return 'secondary';
        }

        $grade = $this->grade;
        
        // Updated badge colors based on new grading scale
        if ($grade >= 70) return 'success';      // A - Green
        if ($grade >= 60) return 'info';         // B - Blue
        if ($grade >= 50) return 'primary';      // C - Primary
        if ($grade >= 46) return 'warning';      // D - Yellow
        if ($grade >= 41) return 'orange';       // E - Orange (you may need to add orange CSS)
        return 'danger';                         // F - Red
    }

    public function getSubmissionTimeStatus(): string
    {
        if (!$this->submitted_at) {
            return 'Not submitted';
        }

        if ($this->isLate()) {
            $lateDuration = $this->submitted_at->diffForHumans($this->assignment->deadline);
            return "Late ({$lateDuration})";
        }

        return 'On time';
    }

    public function getFileUrl(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        return Storage::disk('public')->url($this->file_path);
    }

    public function hasFile(): bool
    {
        return !empty($this->file_path) && Storage::disk('public')->exists($this->file_path);
    }

    public function getFileSizeAttribute(): ?int
    {
        if (!$this->hasFile()) {
            return null;
        }

        return Storage::disk('public')->size($this->file_path);
    }

    public function getFormattedFileSizeAttribute(): string
    {
        $size = $this->file_size;
        
        if (!$size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    public function getTimeUntilDeadline(): ?string
    {
        if (!$this->assignment || !$this->assignment->deadline) {
            return null;
        }

        if ($this->submitted_at) {
            return 'Submitted';
        }

        $deadline = $this->assignment->deadline;
        $now = now();

        if ($deadline < $now) {
            return 'Overdue';
        }

        return $deadline->diffForHumans($now);
    }

    /**
     * Get grading scale information
     */
    public static function getGradingScale(): array
    {
        return [
            'A' => ['min' => 70, 'max' => 100, 'color' => 'success'],
            'B' => ['min' => 60, 'max' => 69, 'color' => 'info'],
            'C' => ['min' => 50, 'max' => 59, 'color' => 'primary'],
            'D' => ['min' => 46, 'max' => 49, 'color' => 'warning'],
            'E' => ['min' => 41, 'max' => 45, 'color' => 'orange'],
            'F' => ['min' => 0, 'max' => 40, 'color' => 'danger'],
        ];
    }
}