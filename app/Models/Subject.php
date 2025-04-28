<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'code', 'type', 'description', 'credit_hours', 'class_id'
    ];

    /**
     * Get the class for this subject
     */
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /**
     * Get the teachers for this subject
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subjects')
            ->withPivot('section_id', 'academic_year_id')
            ->withTimestamps();
    }

    /**
     * Get the exam results for this subject
     */
    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    /**
     * Get the timetables for this subject
     */
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
