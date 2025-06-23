<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'student_id',
        'class_id',
        'section_id',
        'date',
        'status',
        'remark'
    ];
    
    protected $casts = [
        'date' => 'date',
        'status' => 'string', // present, absent, late, etc.
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    
    // Scopes
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }
    
    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }
    
    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }
    
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }
    
    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }
    
    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }
    
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}