<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicYear extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'start_date', 'end_date', 'is_current', 'school_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get the sections for this academic year
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    /**
     * Get the exams for this academic year
     */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the fee structures for this academic year
     */
    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class);
    }

    /**
     * Get the school this academic year belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
