<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'blood_group',
        'joining_date',
        'qualification',
        'experience',
        'salary',
        'status',
        'photo',
        'user_id'
    ];
    
    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'status' => 'boolean',
        'salary' => 'decimal:2',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }
    
    public function classTeacher()
    {
        return $this->hasMany(SchoolClass::class, 'teacher_id');
    }
    
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
    
    // Accessor for full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    // Accessor for age
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }
    
    // Accessor for experience in years
    public function getExperienceYearsAttribute()
    {
        return $this->joining_date ? Carbon::parse($this->joining_date)->diffInYears(now()) : null;
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}