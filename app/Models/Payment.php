<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_fee_id', 'amount', 'payment_date', 'payment_method',
        'transaction_id', 'remarks', 'received_by'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the student fee for this payment
     */
    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }

    /**
     * Get the student for this payment through student fee
     */
    public function student()
    {
        return $this->hasOneThrough(Student::class, StudentFee::class, 'id', 'id', 'student_fee_id', 'student_id');
    }

    /**
     * Get the user who received this payment
     */
    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
