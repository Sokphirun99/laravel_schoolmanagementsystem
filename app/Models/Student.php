<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id', 'admission_number', 'roll_number', 'first_name', 'last_name',
        'date_of_birth', 'gender', 'blood_group', 'address', 'phone',
        'current_section_id', 'section_id', 'admission_date', 'parent_id', 'status',
        'emergency_contact', 'medical_condition', 'photo', 'school_id'
    ];

    protected $dates = [
        'date_of_birth',
        'admission_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date'
    ];

    /**
     * Get the full name attribute
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'current_section_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function fees()
    {
        return $this->hasMany(StudentFee::class);
    }
}
