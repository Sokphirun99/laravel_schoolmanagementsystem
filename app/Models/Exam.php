<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'exam_type',
        'class_id',
        'subject_id',
        'date',
        'start_time',
        'end_time',
        'max_marks',
        'passing_marks',
        'instructions',
        'status'
    ];
    
    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'max_marks' => 'decimal:2',
        'passing_marks' => 'decimal:2',
        'status' => 'boolean',
    ];
    
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
    
    // Accessor for duration in minutes
    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->diffInMinutes($this->end_time);
        }
        return null;
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    
    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }
    
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }
    
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->format('Y-m-d'));
    }
    
    public function scopePast($query)
    {
        return $query->where('date', '<', now()->format('Y-m-d'));
    }
}