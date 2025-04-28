<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'class_id', 'capacity', 'teacher_id', 'room_number', 'academic_year_id'
    ];

    /**
     * Get the class that this section belongs to
     */
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /**
     * Get the teacher assigned to this section
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the academic year for this section
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the students in this section
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'current_section_id');
    }

    /**
     * Get the timetables for this section
     */
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
