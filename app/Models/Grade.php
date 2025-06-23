<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'student_id',
        'class_id',
        'section_id',
        'subject_id',
        'exam_id',
        'marks_obtained',
        'max_marks',
        'grade_letter',
        'remarks'
    ];
    
    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'max_marks' => 'decimal:2',
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
    
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    
    // Accessor for percentage
    public function getPercentageAttribute()
    {
        return $this->max_marks > 0 
            ? round(($this->marks_obtained / $this->max_marks) * 100, 2) 
            : null;
    }
    
    // Scopes
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
    
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }
    
    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }
}