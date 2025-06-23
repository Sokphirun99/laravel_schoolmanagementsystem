<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'blood_group',
        'class_id',
        'section_id',
        'parent_id',
        'admission_date',
        'status',
        'photo',
        'user_id'
    ];
    
    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'status' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Backward compatibility
    public function class()
    {
        return $this->schoolClass();
    }
    
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }
    
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
    
    public function fees()
    {
        return $this->hasMany(Fee::class);
    }
    
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }
    
    // Accessor for full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    // Accessor for class name
    public function getClassNameAttribute()
    {
        return $this->schoolClass ? $this->schoolClass->name : null;
    }
    
    // Accessor for section name
    public function getSectionNameAttribute()
    {
        return $this->section ? $this->section->name : null;
    }
    
    // Accessor for parent name
    public function getParentNameAttribute()
    {
        return $this->parent ? $this->parent->full_name : null;
    }
    
    // Accessor for age
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
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
    
    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }
    
    // Helper methods
    public function isActive()
    {
        return (bool) $this->status;
    }
    
    public function classId()
    {
        return $this->class_id;
    }
    
    public function sectionId()
    {
        return $this->section_id;
    }
    
    public function parentId()
    {
        return $this->parent_id;
    }
}