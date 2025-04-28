<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'fee_structure_id', 'amount', 'status', 'due_date'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * The valid fee statuses
     */
    const STATUS_PAID = 'paid';
    const STATUS_UNPAID = 'unpaid';
    const STATUS_PARTIAL = 'partial';

    /**
     * Get all valid fee statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PAID,
            self::STATUS_UNPAID,
            self::STATUS_PARTIAL
        ];
    }

    /**
     * Get the student for this fee
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the fee structure for this fee
     */
    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    /**
     * Get the payments for this fee
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
