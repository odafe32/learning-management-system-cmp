<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    // Relationships
    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Automatically generate slug when creating/updating
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

    // Scopes
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

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => '<span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">Active</span>',
            'inactive' => '<span class="badge bg-danger-50 text-danger-600 px-8 py-4 rounded-4">Inactive</span>',
            'draft' => '<span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4">Draft</span>',
            default => '<span class="badge bg-gray-50 text-gray-600 px-8 py-4 rounded-4">Unknown</span>',
        };
    }

    public function getImageUrlAttribute()
    {
        return $this->image 
            ? asset('storage/' . $this->image) 
            : asset('assets/images/thumbs/course-default.png');
    }

    // Helper methods
    public static function getLevels()
    {
        return [
            '100' => '100 Level',
            '200' => '200 Level',
            '300' => '300 Level',
            '400' => '400 Level',
  
        ];
    }

    public static function getSemesters()
    {
        return [
            'first' => 'First Semester',
            'second' => 'Second Semester',
        ];
    }

    public static function getStatuses()
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'draft' => 'Draft',
        ];
    }
}