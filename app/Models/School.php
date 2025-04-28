<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'code', 'address', 'phone', 'email', 'website',
        'principal_name', 'established_date', 'logo', 'status'
    ];

    protected $casts = [
        'established_date' => 'date',
    ];

    /**
     * Get the academic years for this school
     */
    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    /**
     * Get the classes for this school
     */
    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }

    /**
     * Get the current academic year for this school
     */
    public function currentAcademicYear()
    {
        return $this->hasOne(AcademicYear::class)->where('is_current', true);
    }
}
