<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AcademicYear;
// Ensure the ExamSchedule class exists in the specified namespace
use App\Models\ExamSchedule;
use App\Models\ExamResult;

class Exam extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'description', 'start_date', 'end_date', 'term', 'academic_year_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the academic year for this exam
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the exam schedules for this exam
     */
    public function examSchedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }
}
