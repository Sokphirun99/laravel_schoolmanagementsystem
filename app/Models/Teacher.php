<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'employee_id', 'first_name', 'last_name', 'gender',
        'date_of_birth', 'phone', 'address', 'qualification', 'experience',
        'join_date', 'designation', 'department', 'department_id', 'salary', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department that the teacher belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'teacher_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects')
            ->withPivot('section_id', 'academic_year_id')
            ->withTimestamps();
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
