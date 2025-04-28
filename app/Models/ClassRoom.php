<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoom extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classes';

    protected $fillable = [
        'name', 'numeric_value', 'description', 'school_id'
    ];

    /**
     * Get all sections for this class
     */
    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    /**
     * Get the subjects taught in this class
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get the school this class belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all students in this class across all sections
     */
    public function studentsViaSection()
    {
        return $this->hasManyThrough(Student::class, Section::class, 'class_id', 'current_section_id');
    }
}
