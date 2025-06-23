<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'subject_code',
        'description',
        'credit_hours',
        'status'
    ];
    
    protected $casts = [
        'credit_hours' => 'integer',
        'status' => 'boolean',
    ];
    
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }
    
    public function schoolClasses()
    {
        return $this->belongsToMany(SchoolClass::class);
    }
    
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
    
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
    
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}