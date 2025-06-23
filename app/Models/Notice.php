<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'message',
        'notice_type',
        'start_date',
        'end_date',
        'created_by',
        'target_audience', // all, students, teachers, parents
        'class_id', // optional, for class-specific notices
        'status'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function parent()
    {
        return $this->hasOne(ParentModel::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    
    public function scopeForAudience($query, $audience)
    {
        return $query->where('target_audience', $audience)
            ->orWhere('target_audience', 'all');
    }
    
    public function scopeByClass($query, $classId)
    {
        return $query->where(function ($query) use ($classId) {
            $query->where('class_id', $classId)
                ->orWhereNull('class_id');
        });
    }
    
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Display helpers for views
    public function typeClass()
    {
        $noticeType = $this->notice_type ?? 'general';
        
        switch ($noticeType) {
            case 'general':
                return 'info';
            case 'exam':
                return 'danger';
            case 'event':
                return 'success';
            case 'holiday':
                return 'warning';
            default:
                return 'secondary';
        }
    }
    
    public function statusBadgeClass()
    {
        // Using Null coalescing operator to avoid undefined property errors
        $status = $this->status ?? false;
        return $status ? 'bg-success' : 'bg-secondary';
    }
}