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
            // Delete the file when material is deleted
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
        });
    }

    // Relationships
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

    // Scopes
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

    // Accessors & Mutators
    public function getFileUrlAttribute()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            // Use the serve route instead of direct storage URL
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

    // Static methods
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