<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'student_id',
        'class_id',
        'fee_type',
        'amount',
        'due_date',
        'payment_date',
        'payment_method',
        'transaction_id',
        'status',
        'remarks'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
        'status' => 'string', // pending, paid, overdue, etc.
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->where('due_date', '<', now());
    }
    
    public function scopeByFeeType($query, $feeType)
    {
        return $query->where('fee_type', $feeType);
    }
    
    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }
    
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Additional Fee model methods can be added here
}