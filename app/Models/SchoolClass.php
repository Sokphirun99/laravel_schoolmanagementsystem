<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'class_code',
        'description',
        'teacher_id',
        'academic_year',
        'status'
    ];
    
    protected $casts = [
        'status' => 'boolean',
    ];
    
    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }
    
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }
    
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}