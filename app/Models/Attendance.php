<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'section_id', 'date', 'status', 'remarks', 'taken_by'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * The valid attendance statuses
     */
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LATE = 'late';
    const STATUS_EXCUSED = 'excused';

    /**
     * Get all valid attendance statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PRESENT,
            self::STATUS_ABSENT,
            self::STATUS_LATE,
            self::STATUS_EXCUSED
        ];
    }

    /**
     * Get the student for this attendance record
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the section for this attendance record
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the user who took the attendance
     */
    public function takenByUser()
    {
        return $this->belongsTo(User::class, 'taken_by');
    }
}
