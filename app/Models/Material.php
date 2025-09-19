<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Material extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'visibility',
        'uploaded_at',
    ];

    protected $casts = [
        'id' => 'string',
        'uploaded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
            if (empty($model->uploaded_at)) {
                $model->uploaded_at = now();
            }
        });

        static::deleting(function ($material) {
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ==================== ENHANCED SCOPES WITH LEVEL FILTERING ====================

    public function scopeByInstructor($query, $instructorId)
    {
        return $query->where('user_id', $instructorId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeVisible($query, $visibility = null)
    {
        if ($visibility) {
            return $query->where('visibility', $visibility);
        }
        return $query;
    }

    /**
     * Scope to filter materials by course level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->whereHas('course', function($q) use ($level) {
            $q->where('level', $level);
        });
    }

    /**
     * Enhanced scope for student access with level filtering
     */
    public function scopeAccessibleToStudent($query, $studentId, $studentLevel = null)
    {
        return $query->where(function($q) use ($studentId, $studentLevel) {
            // Public materials for student's level
            $q->where('visibility', 'public')
              ->whereHas('course', function($courseQ) use ($studentLevel) {
                  if ($studentLevel) {
                      $courseQ->where('level', $studentLevel);
                  }
              })
              // OR enrolled materials for student's enrolled courses
              ->orWhere(function($subQ) use ($studentId, $studentLevel) {
                  $subQ->where('visibility', 'enrolled')
                       ->whereHas('course', function($courseQ) use ($studentId, $studentLevel) {
                           $courseQ->whereHas('students', function($studentQ) use ($studentId) {
                               $studentQ->where('user_id', $studentId)
                                        ->where('status', 'active');
                           });
                           
                           if ($studentLevel) {
                               $courseQ->where('level', $studentLevel);
                           }
                       });
              });
        });
    }

    /**
     * Scope to get materials for student's enrolled courses only
     */
    public function scopeForEnrolledStudent($query, $studentId)
    {
        return $query->whereHas('course.students', function($q) use ($studentId) {
            $q->where('user_id', $studentId)
              ->where('status', 'active');
        });
    }

    /**
     * Scope to get materials for student's level and enrolled courses
     */
    public function scopeForStudentLevelAndEnrollment($query, $studentId, $studentLevel)
    {
        return $query->whereHas('course', function($q) use ($studentId, $studentLevel) {
            $q->where('level', $studentLevel)
              ->whereHas('students', function($studentQ) use ($studentId) {
                  $studentQ->where('user_id', $studentId)
                           ->where('status', 'active');
              });
        });
    }

    // ==================== ENHANCED ACCESS CONTROL METHODS ====================

    /**
     * Enhanced method to check if material is accessible to student
     */
    public function isAccessibleToStudent($studentId, $studentLevel = null)
    {
        // Check course level match if provided
        if ($studentLevel && $this->course->level !== $studentLevel) {
            return false;
        }

        if ($this->visibility === 'public') {
            return true;
        }
        
        if ($this->visibility === 'enrolled') {
            return $this->course->students()
                ->where('user_id', $studentId)
                ->where('status', 'active')
                ->exists();
        }
        
        return false;
    }

    /**
     * Enhanced method to check if material can be viewed by user
     */
    public function canBeViewedBy($user)
    {
        // Instructor can always view their own materials
        if ($this->user_id === $user->id) {
            return true;
        }
        
        // Admin can view all materials
        if ($user->role === 'admin') {
            return true;
        }
        
        // Students can view based on visibility, enrollment, and level
        if ($user->role === 'student') {
            return $this->isAccessibleToStudent($user->id, $user->level);
        }
        
        return false;
    }

    // ==================== EXISTING ACCESSORS (UNCHANGED) ====================

    public function getFileUrlAttribute()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return route('instructor.materials.serve', $this->id);
        }
        return null;
    }

    public function getDirectFileUrlAttribute()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->url($this->file_path);
        }
        return null;
    }

    public function getStreamUrlAttribute()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return route('instructor.materials.stream', $this->id);
        }
        return null;
    }

    public function getDownloadUrlAttribute()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return route('instructor.materials.download', $this->id);
        }
        return null;
    }

    public function getStudentFileUrlAttribute()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return route('student.materials.show', $this->id);
        }
        return null;
    }

    public function getStudentStreamUrlAttribute()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return route('student.materials.stream', $this->id);
        }
        return null;
    }

    public function getStudentDownloadUrlAttribute()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return route('student.materials.download', $this->id);
        }
        return null;
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return 'Unknown';
        
        $bytes = $this->file_size * 1024; 
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileIconAttribute()
    {
        $extension = strtolower($this->file_type ?? '');
        
        $icons = [
            'pdf' => 'ph-file-pdf',
            'doc' => 'ph-file-doc',
            'docx' => 'ph-file-doc',
            'ppt' => 'ph-file-ppt',
            'pptx' => 'ph-file-ppt',
            'xls' => 'ph-file-xls',
            'xlsx' => 'ph-file-xls',
            'mp4' => 'ph-file-video',
            'avi' => 'ph-file-video',
            'mov' => 'ph-file-video',
            'mp3' => 'ph-file-audio',
            'wav' => 'ph-file-audio',
            'jpg' => 'ph-file-image',
            'jpeg' => 'ph-file-image',
            'png' => 'ph-file-image',
            'gif' => 'ph-file-image',
            'zip' => 'ph-file-zip',
            'rar' => 'ph-file-zip',
            'txt' => 'ph-file-text',
        ];

        return $icons[$extension] ?? 'ph-file';
    }

    public function getFileExistsAttribute()
    {
        return $this->file_path && Storage::disk('public')->exists($this->file_path);
    }

    public function getIsPreviewableAttribute()
    {
        return in_array($this->file_type, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'mp3', 'wav']);
    }

    public function getIsImageAttribute()
    {
        return in_array($this->file_type, ['jpg', 'jpeg', 'png', 'gif']);
    }

    public function getIsVideoAttribute()
    {
        return in_array($this->file_type, ['mp4', 'avi', 'mov']);
    }

    public function getIsAudioAttribute()
    {
        return in_array($this->file_type, ['mp3', 'wav']);
    }

    public function getIsPdfAttribute()
    {
        return $this->file_type === 'pdf';
    }

    // ==================== STATIC METHODS ====================

    public static function getVisibilityOptions()
    {
        return [
            'public' => 'Public (Everyone can access)',
            'enrolled' => 'Enrolled Students Only',
            'private' => 'Private (Only me)',
        ];
    }

    public static function getAllowedFileTypes()
    {
        return [
            'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx',
            'mp4', 'avi', 'mov', 'mp3', 'wav',
            'jpg', 'jpeg', 'png', 'gif',
            'zip', 'rar', 'txt'
        ];
    }

    public static function getMaxFileSize()
    {
        return 50 * 1024; // 50MB in KB
    }
}

