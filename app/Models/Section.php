<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'class_id',
        'capacity',
        'status'
    ];
    
    protected $casts = [
        'capacity' => 'integer',
        'status' => 'boolean',
    ];
    
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
    
    // Accessor for full name (Grade + Section)
    public function getFullNameAttribute()
    {
        return $this->schoolClass ? "{$this->schoolClass->name} - {$this->name}" : $this->name;
    }
    
    // Accessor for current student count
    public function getStudentCountAttribute()
    {
        return $this->students()->count();
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
}