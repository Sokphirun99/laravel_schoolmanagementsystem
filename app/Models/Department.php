<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'head_id',
        'school_id',
    ];

    /**
     * Get the school that the department belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the department head (teacher).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function head()
    {
        return $this->belongsTo(Teacher::class, 'head_id');
    }

    /**
     * Get the teachers belonging to this department.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'department_id');
    }

    /**
     * Get the subjects taught in this department.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'department_id');
    }
}
