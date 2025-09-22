<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'submission_text', // Add this if you have it in your database
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

    // ==================== RELATIONSHIPS ====================

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // ==================== SCOPES ====================

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

    // ==================== FILE HANDLING METHODS ====================

    /**
     * Get file paths as array (handles both JSON and single path)
     */
    public function getFilePathsArray(): array
    {
        if (empty($this->file_path)) {
            return [];
        }

        // If it's already an array (shouldn't happen with current setup, but just in case)
        if (is_array($this->file_path)) {
            return $this->file_path;
        }

        // Try to decode as JSON first
        $decoded = json_decode($this->file_path, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // If not JSON, treat as single file path
        return [$this->file_path];
    }

    /**
     * Check if submission has files (plural - for multiple files)
     */
    public function hasFiles(): bool
    {
        $paths = $this->getFilePathsArray();
        return !empty($paths);
    }

    /**
     * Check if submission has file (singular - for backward compatibility)
     */
    public function hasFile(): bool
    {
        return $this->hasFiles();
    }

    /**
     * Get all files with metadata
     */
    public function getFiles(): array
    {
        $paths = $this->getFilePathsArray();
        
        return collect($paths)->map(function ($path) {
            return [
                'path' => $path,
                'name' => basename($path),
                'url' => Storage::url($path),
                'download_url' => route('student.submissions.download', $this->id),
                'exists' => Storage::disk('public')->exists($path),
                'size' => Storage::disk('public')->exists($path) ? Storage::disk('public')->size($path) : 0,
                'size_formatted' => Storage::disk('public')->exists($path) ? $this->formatFileSize(Storage::disk('public')->size($path)) : 'N/A',
                'extension' => strtolower(pathinfo($path, PATHINFO_EXTENSION)),
                'type' => $this->getFileType(strtolower(pathinfo($path, PATHINFO_EXTENSION))),
                'icon' => $this->getFileIcon(strtolower(pathinfo($path, PATHINFO_EXTENSION))),
            ];
        })->toArray();
    }

    /**
     * Get first file (for single file display)
     */
    public function getFirstFile(): ?array
    {
        $files = $this->getFiles();
        return !empty($files) ? $files[0] : null;
    }

    /**
     * Get file URL (for backward compatibility - returns first file)
     */
    public function getFileUrl(): ?string
    {
        $firstFile = $this->getFirstFile();
        return $firstFile ? $firstFile['url'] : null;
    }

    /**
     * Get file name (for backward compatibility - returns first file name)
     */
    public function getFileNameAttribute(): ?string
    {
        $firstFile = $this->getFirstFile();
        return $firstFile ? $firstFile['name'] : null;
    }

    /**
     * Get file size (for backward compatibility - returns first file size)
     */
    public function getFileSizeAttribute(): ?int
    {
        $firstFile = $this->getFirstFile();
        return $firstFile ? $firstFile['size'] : null;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $size = $this->file_size;
        
        if (!$size) {
            return 'N/A';
        }

        return $this->formatFileSize($size);
    }

    /**
     * Format file size in human readable format
     */
    private function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        $size = $bytes;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Get file type based on extension
     */
    private function getFileType(string $extension): string
    {
        $types = [
            'pdf' => 'PDF Document',
            'doc' => 'Word Document',
            'docx' => 'Word Document',
            'txt' => 'Text File',
            'rtf' => 'Rich Text File',
            'jpg' => 'Image',
            'jpeg' => 'Image',
            'png' => 'Image',
            'gif' => 'Image',
            'bmp' => 'Image',
            'svg' => 'Image',
            'mp3' => 'Audio File',
            'wav' => 'Audio File',
            'mp4' => 'Video File',
            'avi' => 'Video File',
            'mov' => 'Video File',
            'zip' => 'Archive',
            'rar' => 'Archive',
            '7z' => 'Archive',
            'tar' => 'Archive',
            'gz' => 'Archive',
        ];

        return $types[$extension] ?? 'File';
    }

    /**
     * Get file icon based on extension
     */
    private function getFileIcon(string $extension): string
    {
        $icons = [
            'pdf' => 'file-pdf',
            'doc' => 'file-word',
            'docx' => 'file-word',
            'txt' => 'file-text',
            'rtf' => 'file-text',
            'jpg' => 'file-image',
            'jpeg' => 'file-image',
            'png' => 'file-image',
            'gif' => 'file-image',
            'bmp' => 'file-image',
            'svg' => 'file-image',
            'mp3' => 'file-audio',
            'wav' => 'file-audio',
            'mp4' => 'file-video',
            'avi' => 'file-video',
            'mov' => 'file-video',
            'zip' => 'file-archive',
            'rar' => 'file-archive',
            '7z' => 'file-archive',
            'tar' => 'file-archive',
            'gz' => 'file-archive',
        ];

        return $icons[$extension] ?? 'file';
    }

    // ==================== CONTENT CHECKING METHODS ====================

    /**
     * Check if submission has code content
     */
    public function hasCode(): bool
    {
        return !empty(trim($this->code_content ?? ''));
    }

    /**
     * Check if submission has code content (alias for backward compatibility)
     */
    public function hasCodeSubmission(): bool
    {
        return $this->hasCode();
    }

    /**
     * Check if submission has text content
     */
    public function hasText(): bool
    {
        return !empty(trim($this->submission_text ?? ''));
    }

    /**
     * Check if submission has any content
     */
    public function hasContent(): bool
    {
        return $this->hasFiles() || $this->hasCode() || $this->hasText();
    }

    /**
     * Get submission types
     */
    public function getSubmissionTypesAttribute(): array
    {
        $types = [];
        
        if ($this->hasCode()) {
            $types[] = 'code';
        }
        
        if ($this->hasFiles()) {
            $types[] = 'file';
        }

        if ($this->hasText()) {
            $types[] = 'text';
        }
        
        return $types;
    }

    // ==================== STATUS AND GRADING METHODS ====================

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
        if (!$this->submitted_at || !$this->assignment || !$this->assignment->deadline) {
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
        
        if ($grade >= 70) return 'text-success';
        if ($grade >= 60) return 'text-info';
        if ($grade >= 50) return 'text-primary';
        if ($grade >= 46) return 'text-warning';
        if ($grade >= 41) return 'text-orange';
        return 'text-danger';
    }

    public function getGradeBadgeColorAttribute(): string
    {
        if (!$this->isGraded()) {
            return 'secondary';
        }

        $grade = $this->grade;
        
        if ($grade >= 70) return 'success';
        if ($grade >= 60) return 'info';
        if ($grade >= 50) return 'primary';
        if ($grade >= 46) return 'warning';
        if ($grade >= 41) return 'orange';
        return 'danger';
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

    // ==================== ACCESSOR ATTRIBUTES ====================

    /**
     * Get formatted submitted date
     */
    protected function formattedSubmittedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->submitted_at ? $this->submitted_at->format('M d, Y \a\t g:i A') : 'N/A'
        );
    }

    /**
     * Get formatted graded date
     */
    protected function formattedGradedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->graded_at ? $this->graded_at->format('M d, Y \a\t g:i A') : 'N/A'
        );
    }

    /**
     * Get time ago for submitted date
     */
    protected function timeAgo(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->submitted_at ? $this->submitted_at->diffForHumans() : 'N/A'
        );
    }
}