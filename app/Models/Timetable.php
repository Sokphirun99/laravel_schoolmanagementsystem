<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id', 'day_of_week', 'period_number', 'start_time', 'end_time',
        'subject_id', 'teacher_id', 'room_number', 'academic_year_id'
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'period_number' => 'integer',
    ];

    /**
     * Get the day name attribute
     */
    public function getDayNameAttribute()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return $days[$this->day_of_week - 1] ?? 'Unknown';
    }

    /**
     * Get the section for this timetable
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the subject for this timetable
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher for this timetable
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the academic year for this timetable
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
