<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_type_id', 'class_id', 'amount', 'academic_year_id', 'due_date'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Get the fee type for this fee structure
     */
    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }

    /**
     * Get the class for this fee structure
     */
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /**
     * Get the academic year for this fee structure
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the student fees for this fee structure
     */
    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }
}
